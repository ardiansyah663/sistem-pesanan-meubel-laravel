<?php

     namespace App\Http\Controllers;

     use App\Models\Category;
     use App\Models\Product;
     use Illuminate\Http\Request;

     class ShopController extends Controller
     {
         public function index()
         {
             $categories = Category::with('products')->get();
             return view('shop.index', compact('categories'));
         }

         public function show(Product $product)
         {
             return view('shop.show', compact('product'));
         }
     }