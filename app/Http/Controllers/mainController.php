<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Product;
use App\Category;

use Pdf;
use Server1C;

class mainController extends Controller
{
	public function getDataFrom1C()
	{

		$curl = new Server1C();

		$categorys = $curl->request('crm/hs/Terminal/?action=group');
		$data = json_decode($categorys['html'], true);

		$category = new Category;
		foreach ($data as $val) {	
			$category::firstOrCreate([
				'guid' => $val['groupid'],
				'name' => $val['group'],
				'parent_id' => $val['parentid']
			]);			
		}


		$items = $curl->request('crm/hs/Terminal/?action=Goods');
		$data = json_decode($items['html'], true);

		foreach ($data as $val) {

			$category = $category::firstOrCreate([
				'guid' => $val['groupid'],
				'name' => $val['group'],
			]);
			
			$category->update([
				'items_parent' => true
			]);
			
			$product = Product::firstOrNew([	
				'guid' => $val['id'],				
			]);
			$product->fill([
				'name' => $val['name'],
				'image' => 'test image.jpg',
				'description' => 'test description',
				'price' => $val['price'],
				'count' => $val['mount'],
				'unit' => 'шт'
			]);

			$category->products()->save($product);
		}

		return redirect()->route('home');	
	}
	

	public function index()
	{
		return view('welcome');
	}	
	public function api(Request $request)
	{
		$arr = $request->input('params');
		$arr = json_decode($arr, true);

		$arr = json_encode($arr);

		$curl = new Server1C();
		$curl->post($arr);
		$response = $curl->request('crm/hs/Terminal/zakaz');
		
		if ($request->input('dump')) {
			dump($response['html']);
		} else {
			echo $response['html'];
		}
		die();
	}

	public function getContent($id ='',Request $request)
	{
		$category = Category::where('parent_id', $id)->take(9)->get();		
		if ($request->input('id')) {
			$id = $request->input('id');
		}
		if (count($category)) {
			return view('parts.categorys', ['categorys' => $category]);			
		} else {
			$products = Category::where('guid', $id)->first()->products()->take(9)->get();			
			return view('parts.items', ['products' => $products]);
		}
	}
	public function search(Request $request)
	{		
		$query = '%'.$request->get('q') .'%';
		$products = Product::where('name', 'like', $query)->get();

		return view('parts.items', ['products' => $products]);
	}
}
