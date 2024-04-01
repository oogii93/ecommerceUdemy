<?php

namespace App\Http\Controllers\Backend;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BrandController extends Controller
{

    //eroosoool databasees ene code oor datagaa duudaad bvgdiin awaad brand_all viewrvv compactaar butsaaj baina
    public function AllBrand()
    {
        $brands = Brand::latest()->get();

        return view("backend.brand.brand_all", compact("brands"));

    }

    public function AddBrand()
    {
        return view('backend.brand.brand_add');
    }



    public function StoreBrand(Request $request)
    {


        $manager = new ImageManager(new Driver());
    $name_gen = hexdec(uniqid()).'.'.$request->file('brand_image')->getClientOriginalExtension();  // 3434343443.jpg
    $img=$manager->read($request->file('brand_image'));

    $img=$img->resize(300,300);

    $img->toJpeg(80)->save(base_path('public/upload/brand/'.$name_gen));

    $save_url='upload/brand/'.$name_gen; //save in database
    //




    Brand::insert([
        'brand_name' => $request->brand_name,
        'brand_slug' => strtolower(str_replace(' ','-', $request->brand_name)),

        'brand_image' => $save_url,

    ]);
    $notification = array(
    'message' => 'Brand inserted Successfully',
    'alert-type' => 'success'
);

    return redirect()->route('all.brand')->with($notification);
    }

    public function EditBrand($id)
    {
        $brand = Brand::findOrFail($id);
        return view('backend.brand.brand_edit', compact('brand'));
    }

    public function UpdateBrand(Request $request)
    {

        $brand_id= $request->id;
        $old_image= $request->old_image;

        if($request->file('brand_image'))
        {
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$request->file('brand_image')->getClientOriginalExtension();  // 3434343443.jpg
            $img=$manager->read($request->file('brand_image'));

            $img=$img->resize(300,300);

            $img->toJpeg(80)->save(base_path('public/upload/brand/'.$name_gen));

            $save_url='upload/brand/'.$name_gen; //save in database

            if(file_exists($old_image))
            {
                unlink($old_image);
            }




            Brand::findOrFail($brand_id)->update([
                'brand_name' => $request->brand_name,
                'brand_slug' => strtolower(str_replace(' ','-', $request->brand_name)),

                'brand_image' => $save_url,

            ]);
            $notification = array(
            'message' => 'Brand updated Successfully',
            'alert-type' => 'success'
        );

            return redirect()->route('all.brand')->with($notification);
        }

        else
        {

            Brand::findOrFail($brand_id)->update([
                'brand_name' => $request->brand_name,
                'brand_slug' => strtolower(str_replace(' ','-', $request->brand_name)),


            ]);
            $notification = array(
            'message' => 'Brand updated without image Successfully',
            'alert-type' => 'success'
        );

            return redirect()->route('all.brand')->with($notification);
        }

    }
    public function DeleteBrand($id)
    {
        $brand=Brand::findOrFail($id);
        $img=$brand->brand_image;
        unlink($img);

        Brand::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Brand deleted Successfully',
            'alert-type' => 'success'
        );

            return redirect()->route('all.brand')->with($notification);




    }



}
