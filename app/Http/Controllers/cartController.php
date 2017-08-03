<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Order;
use App\OrderProds;
use App\Product;

class cartController extends Controller
{

    private $order;

    public function __construct(){
        //  Проверим, есть ли в базе активный заказ, если нет, то создадим такой (пустой естественно)  
        $this->order = Order::firstOrCreate(['status'=>'active']);
        
    }
    
    public function index()
    {
        $products = $this->order->products->all();        
        
        return view('parts.cart', ['products' => $products]);
    }

    public function count()
    {
        return count($this->order->products->all());
    }

    public function add(Request $request)
    {

        $id = $request->input('id');
        dump($this->order->products->find($id));
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
        //dump($orderProd);

        return 'true';
    }

    public function remove(Request $request)
    {
        $id = $request->input('id');
        $this->order->products->find($id)->delete();
        return 'true';
    }    
    public function cancel(Request $request)
    {   

        $reason = 'TimeOut';
        if ($request->input('reason') != '') {
            $reason = $request->input('reason');
        }
        $this->order->update(['status'=>'cancel', 'whyCanceled' => $reason]);
    }   
}
