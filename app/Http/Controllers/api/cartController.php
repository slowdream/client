<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Order;
use App\OrderProds;
use App\Product;

use PDF;
use Server1C;

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
    //dd($product);
    $orderProd = OrderProds::firstOrNew([
      'product_id' => $product->id,
      'guid' => $id,
      'price' => $product->price,
      'order_id' => $this->order->id
    ]);

    $orderProd->count = $count;
    // dd($orderProd);
    $orderProd->save();

    return $this->getCart();
  }

  public function remove(Request $request)
  {
    $id  = $request->input('id');
    OrderProds::where('guid', $id)->delete();

    return $this->getCart();
  }

  public function complete(Request $request)
  {

  	$contacts = $request->input('contacts');

  	$this->order->contacts = $contacts;
    $this->order->save();
    $this->printCheck();
  	$this->order->status = 'Sendind';
  	$this->order->save();
  	/*
			TODO: тут отправляем заказ или в очередь или сразу на сервер 1С
  	*/
		$this->sendTo1C();

  }

  /*
    Печать чека
  */
  public function printCheck()
  {
    $contacts = json_decode($this->order->contacts, true);
    $orderProds = $this->order->products->toArray();

    $data = [
      'products' => $this->order->products->toArray(),
      'tel' => $contacts['tel'],
      'id' => $this->order->id,
    ];
    $pdf = PDF::loadView('receipt', $data)->setPaper([0, 0, 80, 200], 'portrait');
    $pdf->save(resource_path('reciepts/reciept.pdf'));
    $file = resource_path('reciepts/reciept.pdf');
    $print = `lp {$file}`;
  }

  /*
    Отправка заказ в 1С
  */
  private function sendTo1C()
  {

    $curl = new Server1C();
    $contacts = json_decode($this->order->contacts, true);
    $arr = [];
    $arr[0] = [
    	"type" => "0",
      "idterm" => env('ID_TERM', "test"),
      "data" => date('YmdHis'),
      "telnumber" => $contacts['tel']
    ];

    $orderProd = $this->order->products;

    foreach ($orderProd as $product) {
    	$arr[] = [
        "type" => "1",
        "name" => intval($product->product->guid),
        "count" => $product->count,
        "price" => $product->price,
        "sum" => $product->count * $product->price
    	];
    }



    /*
    	Пока отключим отправку на сервер
    */
    //$curl->post($arr);
    //$response = $curl->request('crm/hs/Terminal/zakaz');
    //$data = json_decode($response['html'], true);

    /*
    	Пока отключим закрытие заказа
    */
  	//$this->order->status = 'Complete';
		//$this->order->save();
  }

}
