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


		$categorys = $curl->request('/crm/hs/info?action=group');
		$data = json_decode($categorys['html'], true);

		$category = new Category;
		foreach ($data as $val) {	
			$category::firstOrCreate([
				'guid' => $val['groupid'],
				'name' => $val['group'],
				'parent_id' => $val['parentid']
			]);			
		}


		$items = $curl->request('crm/hs/info?action=Goods');
		$data = json_decode($items['html'], true);

		foreach ($data as $val) {
			$category = new Category;
			//$category = new Category;
			//$category = 
			$category = $category::firstOrCreate([
				'guid' => $val['groupid'],
				'name' => $val['group'],
			]);
			$category->update([
				'items_parent' => true
			]);
			// $category = $category::firstOrNew(['guid' => $val['groupid']]);
			// $category->name = $val['group'];
			// $category->guid = $val['groupid'];
			// $category->items_parent = true;
			// $category->image = 'test image.jpg';
			// $category->save();
		
			
			$product = new Product([
				'name' => $val['name'],
				'guid' => $val['id'],
				'image' => 'test image.jpg',
				'description' => 'test description',
				'price' => $val['price'],
				'count' => $val['mount'],
				'unit' => 'шт'
			]);
			//$category = Category::find($category->id);

			$category->products()->save($product);
		}

		return redirect()->route('1с');	
	}
	// public function getDataFrom1C()
	// {
	// 	$username = 'admin';
	// 	$password = 1252351;
	// 	$curl = new Curl('http://95.213.156.3:8888/');
	// 	$curl->config_load('trip.cfg');
	// 	$curl->set(CURLOPT_USERPWD, $username . ":" . $password);
	// 	$items = $curl->request('crm/hs/info?action=Goods');
	// 	$data = json_decode($items['html'], true);

	// 	foreach ($data as $val) {

	// 		$category = new Category;
	// 		$cat = $category::where('guid', $val['groupid'])->first();
	// 		if (count($cat) > 0){
	// 			$category = $cat;
	// 		} else {
	// 			$category->name = $val['group'];
	// 			$category->guid = $val['groupid'];
	// 			$category->items_parent = true;
	// 			$category->image = 'test image.jpg';
	// 			$category->save();
	// 		}
			
	// 		$product = new Product([
	// 			'name' => $val['name'],
	// 			'guid' => $val['id'],
	// 			'image' => 'test image.jpg',
	// 			'description' => 'test description',
	// 			'price' => $val['price'],
	// 			'count' => $val['mount'],
	// 			'unit' => 'шт'
	// 		]);

	// 		$category->products()->save($product);
	// 	}

	// 	$categorys = $curl->request('/crm/hs/info?action=group');
	// 	$data = json_decode($categorys['html'], true);

	// 	foreach ($data as $val) {

	// 		$category = new Category;
	// 		$cat = $category::where('guid', $val['groupid'])->first();
	// 		if (count($cat) == 0){
	// 			$category->name = $val['group'];
	// 			$category->guid = $val['groupid'];
	// 			$category->parent_id = $val['parentid'];
	// 			$category->image = 'test image.jpg';
	// 			$category->save();
	// 		}
	// 	}

	// 	return redirect()->route('home');	
	// }

	public function index()
	{
		$this->getDataFrom1C();
	}

	public function getContent($id = '')
	{
		// if ($id == '') {
		// 	$categorys = Category::where('parent_id', '')->take(10)->get();
		// 	return view('parts.categorys', ['categorys' => $categorys]);
		// }


		$category = Category::where('parent_id', $id)->take(9)->get();
		//dd($category);

		if (count($category)) {
			return view('parts.categorys', ['categorys' => $category]);
			//$products = Product::where('category_id', $id)->take(10)->get();
			//return view('parts.items', ['products' => $products]);
		} else {
			$products = Product::where('category_id', $id)->take(9)->get();
			return view('parts.items', ['products' => $products]);
			//return view('parts.categorys', ['category' => $categorys]);
		}
	}

	// public function categorys($id = '')
	// {
	// 	if ($id == '') {
	// 		$categorys = Category::find(1)->take(20)->get();
	// 	}else {
	// 		$categorys = Category::find($id)->take(20)->get();
	// 	}
	// 	return view('parts.categorys', ['categorys' => $categorys]);
	// }

	// public function items($id)
	// {
	// 	//$products = Category::find($id)->products()->take(20)->get();
	// 	$products = Product::where('category_id', $id)->take(20)->get();

	// 	return view('parts.items', ['products' => $products]);
	// }
}
