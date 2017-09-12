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

	public function getSingleProduct($id, Request $request)
	{

		$product = Product::where('guid', $id)->first()->toArray();
		$parent = Category::where('id', $product["category_id"])->first()->name;
		$product["parent"] = $parent;
		$img_folder = '/123/11/';

		$path = public_path().'/prods_images/'.$product['guid'].'/';
		$files = scandir($path);

		$images = array_slice($files, 2);
		$image = [];
		foreach ($images as $img) {
			$image[] = $img;
		}
		if (!$image) {
			$image = ['nothing/nothing.jpg'];
		}
		$product['image'] = $image;
		return response()->json($product);
	}


	public function search(Request $request)
	{

		$query = '%'.$request->get('q') .'%';
		$products = Product::where('name', 'like', $query)->get();

		return response()->json($products);
	}
}
