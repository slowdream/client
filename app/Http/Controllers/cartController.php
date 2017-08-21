<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function complete(Request $request)
    {
        $pdf = new Pdf([
            'name' => $request->input('name'),
            'order_num' => $request->input('nomer'),
            'summ' => $request->input('summ')
        ]);
        $pdf = $pdf->process();
        file_put_contents(resource_path('reciepts/reciept.pdf'), $pdf);
        $file = resource_path('reciepts/reciept.pdf');
        $print = `lp {$file}`;
        return $print;
    }
    
    private function sendTo1C(Request $request)
    
        $curl = new Server1C();
        $arr = [
            'idterm' => 1313
            'prods' => $this->order->products()->all(),
            'id' => $this->order->id(),
            'summ' => $this->order->summ,
            'cash' => 1346,
            'tel' => $request->input('tel'),
            'comment' => $request->input('comment'),
            'date' => date('YmdHis')
        ];
        $curl->post($arr);
        $response = $curl->request('crm/hs/Terminal/?action=group');

        $data = json_decode($response['html'], true);
    }
}
