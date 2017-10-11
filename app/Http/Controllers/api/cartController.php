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


class cartController extends Controller
{
  private $order;

  public function __construct(){
    $this->order = Order::firstOrCreate(['status'=>'active']);
  }

  /**
   * Отклик на запрос GET /cart
   */
  public function getCart()
  {
    $products = $this->order->products->all();
    $cartProducts = [];
    $product = new Product;
    foreach ($products as $item) {
      //$itemProduct = $product::find($item->id)->toArray();
      $itemProduct = $item->product->toArray();

      $itemProduct['stock'] = $itemProduct['count'];
      $itemProduct['count'] = $item->count;
      $cartProducts[] = $itemProduct;
    }
    return response()->json($cartProducts);
  }

  /**
   * Отклик на запрос POST /cart/add
   */
  public function addToCart(Request $request)
  {
    $data = $request->input('data');
    $id = $data['id'];
    $count = $data['count'];
    $order_id = $this->order->id;
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

  public function remove(Request $request)
  {
    $id  = $request->input('id');
    OrderProds::where('guid', $id)->delete();

    return $this->getCart();
  }

  public function addContacts(Request $request)
  {
    $contacts = $request->input('contacts');
    $this->order->contacts = $contacts;
    $this->order->save();
  }

  public function complete(Request $request)
  {
    $reason = $request->input('reason');
    if ($reason == 'cancel'){
      $reason = 'canceled';
    } else {
      $reason = 'payed';
    }
    $this->order->status = $reason;
    $this->order->save();
    Cash::where('status', 'wait')->delete();
    $cash = Cash::where('status', 'injected')->get();
    foreach ($cash as $banknote) {
      $banknote->status = 'inbox';
      $banknote->save();
    }
    /*
      TODO: тут отправляем заказ или в очередь или сразу на сервер 1С
    */
    dispatch(new SendOrdersToServer);
    $this->printCheck($reason);
  }

  /*
    Печать чека
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
      $products[] = $product->product;
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

    $pdfHeight = 220;
    $pdfHeight += count($products) * 90;

    if ($reason == 'canceled') {
      $pdfHeight += 40;
    }


    //return view('receipt', $data);
    //Четвертое число в размере бумаги это высота чека и его нужно вычислять заранее
    $pdf = PDF::loadView('receipt', $data)->setPaper([0, 0, 218, $pdfHeight], 'portrait');
    //return $pdf->stream();
    $pdf->save(resource_path('reciepts/reciept.pdf'));
    $file = resource_path('reciepts/reciept.pdf');
    $print = `lp {$file}`;
  }

}
