<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Order;
use App\OrderProds;
use App\Product;
use App\Cash;

use Carbon\Carbon;
use PDF;
use App\Jobs\SendOrdersToServer;
use App\Jobs\SendSms;


class cartController extends Controller
{
  private $order;

  public function __construct(){
    $this->order = Order::firstOrCreate(['status'=>'active']);
  }

  /**
   * Отклик на запрос GET /api/cart
   */
  public function getCart()
  {
    $products = $this->order->products->all();
    $cartProducts = [];
    foreach ($products as $item) {;
      $itemProduct = $item->product->toArray();
      $itemProduct['stock'] = $itemProduct['count'];
      $itemProduct['count'] = $item->count;
      $cartProducts[] = $itemProduct;
    }
    return response()->json($cartProducts);
  }

  /**
   * Отклик на запрос POST /api/cart/add
   */
  public function addToCart(Request $request)
  {
    $data = $request->input('data');
    $id = $data['id'];
    $count = $data['count'];
    $product = Product::where('guid', $id)->first();
    $orderProd = OrderProds::firstOrNew([
      'product_id' => $product->id,
      'guid' => $id,
      'price' => $product->price,
      'order_id' => $this->order->id
    ]);

    $orderProd->count = $count;
    $orderProd->save();

    return $this->getCart();
  }

  /**
   * Отклик на запрос POST /api/cart/remove
   */
  public function remove(Request $request)
  {
    $id  = $request->input('id');
    OrderProds::where('guid', $id)->delete();

    return $this->getCart();
  }

  /**
   * Отклик на запрос POST /api/cart/add_contacts
   */
  public function addContacts(Request $request)
  {
    $contacts = $request->input('contacts');
    $this->order->contacts = $contacts;
    $this->order->save();
  }

  /**
   * Отклик на запрос POST /api/cart/complete
   */
  public function complete(Request $request)
  {
    $reason = $request->input('reason');
    // TODO добавить проверку что такая причина существует.
    // Пока доступны только две причины - отменено и оплачено

    $reason = ($reason == 'complete') ? 'payed' : 'canceled';

    if ($reason == 'canceled') {
      $this->order->status = 'canceled';
      $this->order->save();

    } else {
      $this->order->status = 'complete';
      $this->order->reason = $reason;
      $this->order->save();
      Cash::where('status', 'wait')->delete();
      $cash = Cash::where('status', 'injected')->get();
      foreach ($cash as $banknote) {
        $banknote->status = 'inbox';
        $banknote->save();
      }

      //dispatch(new SendOrdersToServer);
      $this->printCheck($reason);
    }

    $this->order = Order::firstOrCreate(['status'=>'active']);
    return response()->json([]);
    //return $this->getCart();
  }

  /*
    Печать чека
    TODO: вынести это безобразие в job ?
  */
  public function printCheck($reason = 'payed')
  {
    //$ord = New Order();
    //dd($ord->getActive());

    $contacts = json_decode($this->order->contacts, true);
    $cartProducts = $this->order->products;
    $products = [];
    $summ = 0;
    foreach ($cartProducts as $product) {
      $prod = $product->product;
      $prod['count'] = $product->count;
      $products[] = $prod;
      $summ += $product->price*$product->count;
    }
    $cash = Cash::where('status', 'injected')->get();
    $cashSumm = 0;
    foreach ($cash as $item) {
      $cashSumm += $item->value;
    }
    $data = [
      'products' => $products,
      'summ' => $summ,
      'cash' => $cashSumm,
      'tel' => $contacts['tel'],
      'address' => $contacts['address'],
      'id' => $this->order->id,
      'date' => Carbon::now('Europe/Moscow')->toDateTimeString(),
      'reason' => $reason
    ];

    $sms_text = view('sms', $data);
    dispatch(new SendSms($data['tel'], $sms_text));

    $pdfHeight = 220;
    $pdfHeight += count($products) * 90;

    if ($reason == 'canceled') {
      $pdfHeight += 40;
    }

    //Четвертое число в размере бумаги это высота чека и его нужно вычислять заранее
    $pdf = PDF::loadView('receipt', $data)->setPaper([0, 0, 218, $pdfHeight], 'portrait');
    //return $pdf->stream();
    $pdf->save(resource_path('reciepts/reciept.pdf'));
    $file = resource_path('reciepts/reciept.pdf');
    $print = `lp {$file}`;
  }

}
