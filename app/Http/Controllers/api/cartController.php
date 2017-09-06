<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Order;
use App\OrderProds;
use App\Product;

use Pdf;
use Server1C;

class cartController extends Controller
{
  private $order;

  public function __construct(){
      //  Проверим, есть ли в базе активный заказ, если нет, то создадим такой (пустой естественно)
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
    	$itemProduct = $product::find($item->id)->toArray();
    	$itemProduct['count'] = $item->count;
    	$cartProducts[] = $itemProduct;
    }
    return response()->json($cartProducts);
  }

  public function addToCart(Request $request)
  {
    $id = $request->input('id');
    $count = $request->input('count');
    $order_id = $this->order->id;
    $product = Product::find($id);

    $orderProd = OrderProds::firstOrNew([
      'product_id' => $product->id,
      'price' => $product->price,
      'order_id' => $this->order->id
    ]);

    $orderProd->count = $count;
    $orderProd->save();

    return 'true';
  }
  public function complete(Request $request)
  {

  	$contacts = $request->input('contacts');

  	$this->order->contacts = $contacts;
  	$this->order->status = 'Sendind';
  	$this->order->save();
  	/*
			TODO: тут отправляем заказ или в очередь или сразу на сервер 1С
  	*/
		$this->sendTo1C();

  }
  private function sendTo1C()
  {

    $curl = new Server1C();
    $contacts = json_decode($this->order->contacts, true);
    $arr = [];
    $arr[0] = [
    	"type" => "0",
      "idterm" => "13131",
      "data" => date('YmdHis'),
      "telnumber" => $contacts['tel']
    ];
    $orderProd = $this->order->products;


    foreach ($orderProd as $product) {
    	$arr[] = [
        "type" => "1",
        "name" => intval($product->product->guid),
        "count" => $product->count,
        "price" => $pr oduct->price,
        "sum" => $product->count * $product->price
    	];
    }

    $curl->post($arr);

    /*
    	Пока отключим отправку на сервер
    */
    //$response = $curl->request('crm/hs/Terminal/zakaz');

    $data = json_decode($response['html'], true);

    /*
    	Пока отключим закрытие заказа
    */
  	//$this->order->status = 'Complete';
		//$this->order->save();
  }

}
