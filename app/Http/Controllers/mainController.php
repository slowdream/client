<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Product;
use App\Category;

use Curl;

class mainController extends Controller
{

	public function getDataFrom1C()
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
			$cat = $category::where('guid', $val['groupid'])->first();
			if (count($cat) > 0){
				$category = $cat;
			} else {
				$category->name = $val['group'];
				$category->guid = $val['groupid'];
				$category->image = 'test image.jpg';
				$category->save();
			}
			
			$product = new Product([
				'name' => $val['name'],
				'guid' => $val['id'],
				'image' => 'test image.jpg',
				'description' => 'test description',
				'price' => $val['price'],
				'count' => $val['mount'],
				'unit' => 'шт'
			]);

			$category->products()->save($product);
		}	
	}
	public function index()
	{
		$this->getDataFrom1C();
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
		//$products = Category::find($id)->products()->take(20)->get();
		$products = Product::where('category_id', $id)->take(20)->get();

		return view('parts.items', ['products' => $products]);
	}
}
