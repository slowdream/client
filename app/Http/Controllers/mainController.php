<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Product;
use App\Category;

use Curl;
use Pdf;

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
		$this->getDataFrom1C();
	}

	public function getContent($id = '')
	{
		$category = Category::where('parent_id', $id)->take(9)->get();		

		if (count($category)) {
			return view('parts.categorys', ['categorys' => $category]);			
		} else {
			$products = Category::where('guid', $id)->first()->products()->take(9)->get();			
			return view('parts.items', ['products' => $products]);
		}
	}
	public function search(Request $request)
	{		
		$error = ['error' => 'No results found, please try with different keywords.'];
		$query = '%'.$request->get('q') .'%';
		$posts = Product::where('name', 'like', $query)->get();
		dump($posts);
		//return $posts->count() ? $posts : $error;
	}
	public function pdf()
	{



		$pdf = new Pdf([
			'name' => 'name',
			'order_num' => 'order_num',
			'barcode' => 'barcode',
		]);
		$pdf = $pdf->process();
		file_put_contents(resource_path('reciepts/reciepts2.pdf'), $pdf);
	}
}
