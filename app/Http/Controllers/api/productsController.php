<?php

namespace App\Http\Controllers\api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Product;
use App\Category;



class productsController extends Controller
{

	

	public function index()
	{
	}	


	public function getProducts($id, Request $request)
	{
		
		$id = Category::where('guid', $id)->first()->id;
		$product = Product::where('category_id', $id)->get();
		return response()->json($product);

	}
	public function search(Request $request)
	{		
		$query = '%'.$request->get('q') .'%';
		$products = Product::where('name', 'like', $query)->get();

		return view('parts.items', ['products' => $products]);
	}
}
