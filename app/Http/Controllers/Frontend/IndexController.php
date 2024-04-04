<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Product;
use App\Models\Category;
use App\Models\MultiImg;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class IndexController extends Controller
{


    public function index()
    {
        $skip_category_0=Category::skip(0)->first();
        // dd($skip_category_0);
        $skip_product_0=Product::where('status',1)->where('category_id', $skip_category_0->id)->orderBy('id','DESC')->limit(5)->get();



        $skip_category_2=Category::skip(2)->first();
        // dd($skip_category_0);
        $skip_product_2=Product::where('status',1)->where('category_id', $skip_category_2->id)->orderBy('id','DESC')->limit(5)->get();





        $skip_category_8=Category::skip(2)->first();
        // dd($skip_category_0);
        $skip_product_8=Product::where('status',1)->where('category_id', $skip_category_8->id)->orderBy('id','DESC')->limit(5)->get();






        return view('frontend.index',compact('skip_category_0','skip_product_0','skip_category_2','skip_product_2','skip_category_8','skip_product_8'));




    }
    public function ProductDetails($id,$slug)
    {
        $product=Product::findOrFail($id);

        $color=$product->product_color;

        $product_color=explode(',',$color);

        $size=$product->product_size;
        $multiImage=MultiImg::where('product_id',$id)->get();

        $product_size=explode(',',$size);

        $cat_id=$product->category_id;
        $relatedProduct=Product::where('category_id',$cat_id)->where('id','!=',$id)->orderBy('id','DESC')->limit(4)->get();

        return view('frontend.product.product_details',compact('product','product_color','product_size','multiImage','relatedProduct'));

    }
}