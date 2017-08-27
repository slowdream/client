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


	public function getCategory(Request $request)
	{

		$id = ($request->input('id')) ? $request->input('id') : '' ;
		$category = Category::where('parent_id', $id)->get();	
		
		if (count($category)) {
			//return view('parts.categorys', ['categorys' => $category]);			
			return response()->json($request->input('www'));
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
