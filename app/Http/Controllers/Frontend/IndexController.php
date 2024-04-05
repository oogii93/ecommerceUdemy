<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\MultiImg;
use App\Models\SubCategory;
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
        //ene codeiig tailbarlawal ooroo $hot_deals gej huwisagch zarlaad Product model-oos hot_deals,1 buyu baiwal awaad bas product dotroosoo discount_price 0 bish bol id gaar ni daraaluul get-eer bvgdiin duudan awch baina
        $hot_deals=Product::where('hot_deals',1)->where('discount_price','!=',NULL)->orderBy('id','DESC')->limit(3)->get();


        $special_offer=Product::where('special_offer',1)->orderBy('id','DESC')->limit(3)->get();


        $new=Product::where('status',1)->orderBy('id','DESC')->limit(3)->get();



        $special_deals=Product::where('special_deals',1)->orderBy('id','DESC')->limit(3)->get();









        return view('frontend.index',compact('skip_category_0','skip_product_0','skip_category_2','skip_product_2','skip_category_8','skip_product_8','hot_deals','special_offer'
    ,'new','special_deals'));




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

    public function VendorDetails($id)

    {
        $vendor=User::findOrFail($id);//ehleed $vendor geser variable neegeed User modeloor $id ni olno
        $vproduct=Product::where('vendor_id',$id)->get();//$vproduct gesen variable neegeed product modeloos vendor id gaar bvh datand handaj baina

        return view('frontend.vendor.vendor_details',compact('vendor','vproduct'));
    }

    public function VendorAll()
    {
$vendors=User::where('status','active')->where('role','vendor')->orderBy('id','DESC')->get();

return view('frontend.vendor.vendor_all',compact('vendors'));

    }

    public function CatWiseProduct(Request $request,$id,$slug)
    {
        $products=Product::where('status',1)->where('category_id',$id)->orderBy('id','DESC')->get();

        $categories=Category::orderBy('category_name','ASC')->get();

        $breadcat=Category::where('id',$id)->first();

        $newproduct=Product::orderBy('id','DESC')->limit(3)->get();

        return view('frontend.product.category_view',compact('products','categories','breadcat','newproduct'));

    }


    public function SubCatWiseProduct(Request $request,$id,$slug)
    {
        $products=Product::where('status',1)->where('subcategory_id',$id)->orderBy('id','DESC')->get();

        $categories=Category::orderBy('category_name','ASC')->get();

        $breadsubcat=SubCategory::where('id',$id)->first();

        $newproduct=Product::orderBy('id','DESC')->limit(3)->get();

        return view('frontend.product.subcategory_view',compact('products','categories','breadsubcat','newproduct'));

    }
    public function ProductViewAjax($id)
    {
        //with eer relationshippee duudaj baina
        $product=Product::with('category','brand')->findOrFail($id);

        $color=$product->product_color;

        $product_color=explode(',',$color);

        $size=$product->product_size;

        $product_size=explode(',',$size);

        return response()->json(array(
            'product'=>$product,
            'color'=>$product_color,
            'size'=>$product_size,
        ));

    }
}
