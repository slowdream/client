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
    // TODO: исключить из выборки путые категории
    $categorys = Category::where('parent_id', $id)->get()->toArray();

    foreach ($categorys as $id => $category) {
      $image = public_path() . '/categorys_images/' . $category['guid'] . '.jpg';

      if (!file_exists($image)) {
        $image = '/static/images/category.jpg';
      } else {
        $image = 'http://client.my/categorys_images/' . $category['guid'] . '.jpg';
      }

      $categorys[$id]['image'] = $image;
    }

    return response()->json($categorys);
  }
}
