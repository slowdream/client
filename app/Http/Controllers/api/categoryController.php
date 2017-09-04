<?php

namespace App\Http\Controllers\api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Product;
use App\Category;



class categoryController extends Controller
{

	

	public function index()
	{
	}	


	public function getCategory($id = '', Request $request)
	{
		
		
		$category = Category::where('parent_id', $id)->get();
		return response()->json($category);

	}
	public function search(Request $request)
	{		
		$query = '%'.$request->get('q') .'%';
		$products = Product::where('name', 'like', $query)->get();

		return view('parts.items', ['products' => $products]);
	}
}
