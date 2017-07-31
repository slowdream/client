<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Order;
use App\Product;
use App\Category;

//use App\Helpers\Curl;
use Curl;

class mainController extends Controller
{

	private function getDataFrom1C()
	{
		$username = 'admin';
		$password = 1252351;
		$curl = new Curl('http://95.213.156.3:8888/');
		$curl->config_load('trip.cfg');
		$curl->set(CURLOPT_USERPWD, $username . ":" . $password);
		$curls = $curl->request('crm/hs/info?action=Goods');
		$data = json_decode($curls['html'], true);

		foreach ($data as $val) {

			$category = new Category;

			$cat = $category::where('name', $val['group'])->first();
			if (count($cat) > 0){
				$category = $cat;
			} else {
				$category->name = $val['group'];
				$category->guid = 000;
				$category->image = 'test image.jpg';
				$category->description = 'Описание на русском';
				$category->save();
			}
			
			$product = new Product([
				'name' => $val['name'],
				'guid' => $val['id'],
				'image' => 'test image',
				'description' => 'test description',
				'unit' => 'test шт',
				'warehouse' => 0
			]);

			$category->products()->save($product);
		}	
	}
	public function index()
	{
		
	}

	public function categorys($id = '')
	{
		if ($id == '') {
			$categorys = Category::find(1)->take(20)->get();
		}else {
			$categorys = Category::find($id)->take(20)->get();
		}

		
		return view('parts.categorys', ['categorys' => $categorys]);
	}

	public function items($id)
	{
		// if ($id == '') {
		// 	$products = Product::take(20)->get();
		// }else {
			$products = Product::where('category_id', $id)->take(20)->get();
		//}

		
		return view('parts.items', ['products' => $products]);
	}
}
