<?php

namespace App\Http\Controllers\Backend;

use Image;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\MultiImg;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class ProductController extends Controller
{
    public function AllProduct()
    {
        $products=Product::latest()->get();

        return view("backend.product.product_all",compact("products"));
    }
    public function AddProduct()

    {
        $activeVendor=User::where('status','active')->where('role','vendor')->latest()->get();
        $brands=Brand::latest()->get();
        $categories=Category::latest()->get();


        return view('backend.product.product_add',compact('brands','categories','activeVendor'));
    }

    public function StoreProduct(Request $request)

    {
        $manager = new ImageManager(new Driver());
        $name_gen = hexdec(uniqid()).'.'.$request->file('product_thambnail')->getClientOriginalExtension();  // 3434343443.jpg
        $img=$manager->read($request->file('product_thambnail'));

        $img=$img->resize(800,800);

        $img->toJpeg(80)->save(base_path('public/upload/products/thambnail/'.$name_gen));

        $save_url='upload/products/thambnail/'.$name_gen; //save in database
        //

        $product_id=Product::insertGetId([
            'brand_id'=>$request->brand_id,
            'category_id'=>$request->category_id,
            'subcategory_id'=>$request->subcategory_id,
            'product_name'=>$request->product_name,
            'product_slug'=>strtolower(str_replace('','-',$request->product_name)),

            'product_code'=>$request->product_code,
            'product_qty'=>$request->product_qty,
            'product_tags'=>$request->product_tags,
            'product_size'=>$request->product_size,
            'product_color'=>$request->product_color,

            'selling_price'=>$request->selling_price,

            'discount_price'=>$request->discount_price,
            'short_descp'=>$request->short_descp,
            'long_descp'=>$request->long_descp,


            'hot_deals'=>$request->hot_deals,
            'featured'=>$request->featured,
            'special_offer'=>$request->special_offer,
            'special_deals'=>$request->special_deals,


            'product_thambnail'=>$save_url,
            'vendor_id'=>$request->vendor_id,
            'status'=>1,
            'created_at'=>Carbon::now(),


        ]);


        //Multi img

        $images = $request->file('multi_img');
        foreach ($images as $img) {
            $make_name = hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();
            $manager = new ImageManager(new Driver());
            $image = $manager->read($img);
            $image = $image->resize(800, 800);
            $image->toJpeg(80)->save(base_path('public/upload/products/multi-image/' . $make_name));
            $uploadPath = 'upload/products/multi-image/' . $make_name;

            MultiImg::insert([
                'product_id' => $product_id,
                'photo_name' => $uploadPath,
                'created_at' => Carbon::now(),
            ]);
        }

        $notification = array(
            'message' => 'Product inserted Successfully',
            'alert-type' => 'success'
        );

            return redirect()->route('all.product')->with($notification);



}   //end method

                    public function EditProduct($id)
                    {

                        $multiImgs=MultiImg::where('product_id',$id)->get();
                        $activeVendor=User::where('status','active')->where('role','vendor')->latest()->get();
                        $brands=Brand::latest()->get();
                        $categories=Category::latest()->get();
                        $subcategories=SubCategory::latest()->get();
                        $products=Product::findOrFail($id);


                        return view('backend.product.product_edit',compact('brands','categories','activeVendor','products','subcategories','multiImgs'));
                    }

                    public function UpdateProduct(Request $request)
                    {
                        $product_id=$request->id;

                      Product::findOrFail($product_id)->update([
                            'brand_id'=>$request->brand_id,
                            'category_id'=>$request->category_id,
                            'subcategory_id'=>$request->subcategory_id,
                            'product_name'=>$request->product_name,
                            'product_slug'=>strtolower(str_replace('','-',$request->product_name)),

                            'product_code'=>$request->product_code,
                            'product_qty'=>$request->product_qty,
                            'product_tags'=>$request->product_tags,
                            'product_size'=>$request->product_size,
                            'product_color'=>$request->product_color,

                            'selling_price'=>$request->selling_price,

                            'discount_price'=>$request->discount_price,
                            'short_descp'=>$request->short_descp,
                            'long_descp'=>$request->long_descp,


                            'hot_deals'=>$request->hot_deals,
                            'featured'=>$request->featured,
                            'special_offer'=>$request->special_offer,
                            'special_deals'=>$request->special_deals,


                            'vendor_id'=>$request->vendor_id,
                            'status'=>1,
                            'created_at'=>Carbon::now(),


                        ]);
                        $notification = array(
                            'message' => 'Product updated without image Successfully',
                            'alert-type' => 'success'
                        );

                            return redirect()->route('all.product')->with($notification);




                    }
//update -vvd deer aldaa ih bgaa ajilahgui daraa ni zasah
                    public function UpdateProductThambnail(Request $request)
                {

                    $pro_id= $request->id;
                    $oldImage=$request->old_img;

                    $manager = new ImageManager(new Driver());
                    $name_gen = hexdec(uniqid()).'.'.$request->file('product_thambnail')->getClientOriginalExtension();  // 3434343443.jpg
                    $img=$manager->read($request->file('product_thambnail'));

                    $img=$img->resize(800,800);

                    $img->toJpeg(80)->save(base_path('public/upload/products/thambnail/'.$name_gen));

                    $save_url='upload/products/thambnail/'.$name_gen; //save in database





                    if(file_exists($oldImage))
                    {
                        unlink($oldImage);
                    }

                    Product::findOrFail($pro_id)->update([
                        'product_thambnail'=>$save_url,
                        'updated_at'=>Carbon::now(),
                    ]);

                    $notification = array(
                        'message' => 'Product image thambnail updated Successfully',
                        'alert-type' => 'success'
                    );

                        return redirect()->back()->with($notification);




                }

                public function UpdateProductMultiimage(Request $request)
                {

                    $imgs= $request->multi_img;

                    foreach($imgs as $id=> $img)
                    {
                        $imgDel=MultiImg::findOrFail($id);

                        // unlink($imgDel->photo_name);

                        $make_name = hexdec(uniqid()) . '.' . $img->getClientOriginalExtension();
                        $manager = new ImageManager(new Driver());
                        $image = $manager->read($img);
                        $image = $image->resize(800, 800);
                        $image->toJpeg(80)->save(base_path('public/upload/products/multi-image/' . $make_name));
                        $uploadPath = 'upload/products/multi-image/' . $make_name;

                        MultiImg::where('id', $id)->update([
                            'photo_name'=>$uploadPath,
                            'updated_at'=>Carbon::now(),
                        ]);



                    }//end foreach
                    $notification = array(
                        'message' => 'Product Multi image  updated Successfully',
                        'alert-type' => 'success'
                    );

                        return redirect()->back()->with($notification);





                }// end method

                public function MultiImageDelete($id)
                {
                    $oldImg=MultiImg::findOrFail($id);
                    unlink($oldImg->photo_name);

                    MultiImg::findOrFail($id)->delete();

                    $notification = array(
                        'message' => 'Product Multi image  deleted Successfully',
                        'alert-type' => 'success'
                    );

                        return redirect()->back()->with($notification);
                }

                public function ProductInactive($id)
                {
                    Product::findOrFail($id)->update(['status'=>0]);

                    $notification = array(
                        'message' => 'Product inactive Successfully',
                        'alert-type' => 'success'
                    );

                        return redirect()->back()->with($notification);

                }
                public function ProductActive($id)
                {


                    Product::findOrFail($id)->update(['status'=>1]);

                    $notification = array(
                        'message' => 'Product Active',
                        'alert-type' => 'success'
                    );

                        return redirect()->back()->with($notification);

                }

                public function ProductDelete($id)
                {

                    $product=Product::findOrFail($id);
                    unlink($product->product_thambnail);
                    Product::find($id)->delete();
                    $imges=MultiImg::where('product_id',$id)->get();

                    foreach ($imges as $img) {

                    unlink($img->photo_name);
                    MultiImg::where('product_id',$id)->delete();
                    }

                    $notification = array(
                        'message' => 'Product Deleted successfully',
                        'alert-type' => 'success'
                    );

                        return redirect()->back()->with($notification);




                }









}
