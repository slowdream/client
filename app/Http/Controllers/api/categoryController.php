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
        $categorys = Category::where('parent_id', $id)->get()->toArray();

        foreach ($categorys as $id => $category) {
            // Уберем из массива пустые категории
            if (!!$category['items_parent'] && !Product::where('category_id', $category['id'])->get()->count()) {
                unset($categorys[$id]);
                continue;
            }

            $image = public_path() . '/categorys_images/' . $category['guid'] . '.jpg';

            if (!file_exists($image)) {
                $image = '/static/images/category.jpg';
            } else {
                $image = route('home') . '/categorys_images/' . $category['guid'] . '.jpg';
            }

            $categorys[$id]['image'] = $image;
        }
        sort($categorys);
        return response()->json($categorys);
    }
}
