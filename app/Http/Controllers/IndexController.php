<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
   //     $productsAll = Product::get(); //bydefault assending order
   //     $productsAll = Product::OrderBy('id', 'DESC')->get(); //Desending order
        $productsAll = Product::inRandomOrder()->get(); // Randon products are shown
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();
        // dd($categories);
     //    $categoryDetails = Category::where(['url'=>$url])->first();
        // dd($categoryDetails->url);
      //   $productsAll = Product::where(['category_id'=> $categoryDetails->id])->get();
        return view('index', compact('categories', 'categoryDetails','productsAll'));
    }
}
