<?php

namespace App\Http\Controllers\api;

use App\Cash;
use App\Http\Controllers\Controller;
use App\Jobs\SendOrdersToServer;
use App\Jobs\SendSms;
use App\Order;
use App\OrderProds;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF;

class cartController extends Controller
{
    private $order;

    public function __construct()
    {
        $this->order = Order::firstOrCreate(['status' => 'active']);
    }

    /**
     * Отклик на запрос GET /api/cart.
     */
    public function getCart()
    {
        $products = $this->order->products->all();
        $cartProducts = [];
        foreach ($products as $item) {
            $itemProduct = $item->product->toArray();
            $itemProduct['stock'] = $itemProduct['count'];
            $itemProduct['count'] = $item->count;
            $cartProducts[] = $itemProduct;
        }

        return response()->json($cartProducts);
    }

    /**
     * Отклик на запрос POST /api/cart/add.
     */
    public function addToCart(Request $request)
    {
        $data = $request->input('data');
        $id = $data['id'];
        $count = $data['count'];
        $product = Product::where('guid', $id)->first();
        $orderProd = OrderProds::firstOrNew([
          'product_id' => $product->id,
          'guid'       => $id,
          'price'      => $product->price,
          'order_id'   => $this->order->id,
        ]);

        $orderProd->count = $count;
        $orderProd->save();

        return $this->getCart();
    }

    /**
     * Отклик на запрос POST /api/cart/remove.
     */
    public function remove(Request $request)
    {
        $id = $request->input('id');
        OrderProds::where('guid', $id)->delete();

        return $this->getCart();
    }

    /**
     * Отклик на запрос POST /api/cart/add_contacts.
     */
    public function addContacts(Request $request)
    {
        $contacts = $request->input('contacts');
        $this->order->contacts = $contacts;
        $this->order->save();
    }

    /**
     * Отклик на запрос POST /api/cart/complete.
     */
    public function complete(Request $request)
    {
        Cash::where('status', 'wait')->delete();
        $reason = $request->input('reason');
        $cashin = $request->input('cashin');
        // TODO добавить проверку что такая причина существует.

        if ($cashin > 0 || $reason == 'withoutPay') {
            $reason = ($reason == 'withoutPay') ? 'withoutPay' : 'payed';
            $this->order->status = 'complete';
            $this->order->reason = $reason;
            $this->order->save();
            $summ = 0;

            if ($reason != 'withoutPay') {
                $cash = Cash::where('status', 'injected')->get();

                foreach ($cash as $banknote) {
                    $summ += $banknote->value;
                    $banknote->status = 'inbox';
                    $banknote->save();
                }
            }

            dispatch(new SendOrdersToServer($this->order->id));
            $this->printCheck($reason, $summ);
        } else {
            $this->order->status = 'canceled';
            $this->order->reason = $reason;
            $this->order->save();
        }

        $this->order = Order::firstOrCreate(['status' => 'active']);

        return response()->json([]);
        //return $this->getCart();
    }

    /*
      Печать чека
      TODO: вынести это безобразие в job ?
    */
    public function printCheck($reason = 'payed', $cashSumm = 0)
    {
        $contacts = json_decode($this->order->contacts, true);
        $cartProducts = $this->order->products;
        $products = [];
        $summ = 0;
        foreach ($cartProducts as $product) {
            $prod = $product->product;
            $prod['count'] = $product->count;
            $products[] = $prod;
            $summ += $product->price * $product->count;
        }

        $data = [
          'products'  => $products,
          'summ'      => $summ,
          'cash'      => $cashSumm,
          'tel'       => $contacts['tel'],
          'address'   => $contacts['address'],
          'id'        => $this->order->id,
          'date'      => Carbon::now('Europe/Moscow')->toDateTimeString(),
          'orderDate' => $contacts['date'],
          'timeRange' => $contacts['timeRange']['text'],
          'reason'    => $reason,
          'delivery'  => ($summ > 2000) ? 0 : 300,
        ];
        $data['timeRange'] = mb_strimwidth($contacts['timeRange']['text'], 0, 2)
          .'-'.
          mb_strimwidth($contacts['timeRange']['text'], -5, 2);

        $data['orderDate'] = mb_strimwidth($contacts['date'], 5, 5);
        $sms_text = view('other_massages.sms', $data)->render();
        dispatch(new SendSms($data['tel'], $sms_text));

        $pdfHeight = 330;
        $pdfHeight += count($products) * 100;
        // Чуток добавим высоте к чеку, для дополнительной информации
        if ($reason == 'canceled' || $cashSumm > $summ) {
            $pdfHeight += 40;
        }

        //Четвертое число в размере бумаги это высота чека и его нужно вычислять заранее
        $pdf = PDF::loadView('check/receipt', $data)
          ->setPaper([0, 0, 218, $pdfHeight], 'portrait');
        //return $pdf->stream();
        $pdf->save(resource_path('reciepts/reciept.pdf'));
        $file = resource_path('reciepts/reciept.pdf');
        $print = `lp {$file}`;
    }
}
