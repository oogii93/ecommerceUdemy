<?php

namespace App\Http\Controllers\Backend;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BannerController extends Controller
{
    public function  AllBanner()
    {
        $banner = Banner::latest()->get();

        return view("backend.banner.banner_all", compact("banner"));

    }

    public function AddBanner()
    {
        return view('backend.banner.banner_add');

    }

    public function StoreBanner(Request $request)
    {


    $manager = new ImageManager(new Driver());
    $name_gen = hexdec(uniqid()).'.'.$request->file('banner_image')->getClientOriginalExtension();  // 3434343443.jpg

    $img=$manager->read($request->file('banner_image'));

    $img=$img->resize(768,450);

    $img->toJpeg(80)->save(base_path('public/upload/banner/'.$name_gen));

    $save_url='upload/banner/'.$name_gen; //save in database
    //







    Banner::insert([
        'banner_title' => $request->banner_title,
        'banner_url' => $request->banner_url,

        'banner_image' => $save_url,

    ]);
    $notification = array(
    'message' => 'Banner inserted Successfully',
    'alert-type' => 'success'
);

    return redirect()->route('all.banner')->with($notification);
    }

    public Function EditBanner($id)
    {
        $banner=Banner::findOrFail($id);
        return view('backend.banner.banner_edit', compact('banner'));
    }

    public function UpdateBanner(Request $request)
    {

        $banner_id= $request->id;
        $old_img= $request->old_image;

        if($request->file('banner_image'))
        {

            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$request->file('banner_image')->getClientOriginalExtension();  // 3434343443.jpg

            $img=$manager->read($request->file('banner_image'));

            $img=$img->resize(768,450);

            $img->toJpeg(80)->save(base_path('public/upload/banner/'.$name_gen));

            $save_url='upload/banner/'.$name_gen; //save in database


            if(file_exists($old_img))
            {
                unlink($old_img);
            }




            Banner::findOrFail($banner_id)->update([
                'banner_title' => $request->banner_title,
                'banner_url' => $request->banner_url,

                'banner_image' => $save_url,

            ]);
            $notification = array(
            'message' => 'Banner updated with image Successfully',
            'alert-type' => 'success'
        );

            return redirect()->route('all.banner')->with($notification);
        }

        else
        {
            Banner::findOrFail($banner_id)->update([
                'banner_title' => $request->banner_title,
                'banner_url' => $request->banner_url,



            ]);
            $notification = array(
            'message' => 'Banner updated without image Successfully',
            'alert-type' => 'success'
        );

            return redirect()->route('all.banner')->with($notification);
        }

    }

    public function DeleteBanner($id){

        $banner = Banner::findOrFail($id);
        $img = $banner->banner_image;
        unlink($img );

        Banner::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Banner Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }// End Method


}
