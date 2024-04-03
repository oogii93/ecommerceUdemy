<?php

namespace App\Http\Controllers\Backend;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Symfony\Component\CssSelector\Node\FunctionNode;

class SliderController extends Controller
{
    public function  AllSlider()
    {
        $sliders = Slider::latest()->get();

        return view("backend.slider.slider_all", compact("sliders"));

    }

    public function AddSlider()
    {
        return view('backend.slider.slider_add');

    }


    public function StoreSlider(Request $request)
    {


        $manager = new ImageManager(new Driver());
    $name_gen = hexdec(uniqid()).'.'.$request->file('slider_image')->getClientOriginalExtension();  // 3434343443.jpg
    $img=$manager->read($request->file('slider_image'));

    $img=$img->resize(2376,807);

    $img->toJpeg(80)->save(base_path('public/upload/slider/'.$name_gen));

    $save_url='upload/slider/'.$name_gen; //save in database
    //




    Slider::insert([
        'slider_title' => $request->slider_title,
        'short_title' => $request->short_title,

        'slider_image' => $save_url,

    ]);
    $notification = array(
    'message' => 'Slider inserted Successfully',
    'alert-type' => 'success'
);

    return redirect()->route('all.slider')->with($notification);
    }

    public Function EditSlider($id)
    {
        $sliders=Slider::findOrFail($id);
        return view('backend.slider.slider_edit', compact('sliders'));
    }


    public function UpdateSlider(Request $request)
    {

        $slider_id= $request->id;
        $old_img= $request->old_image;

        if($request->file('slider_image'))
        {

        $manager = new ImageManager(new Driver());
        $name_gen = hexdec(uniqid()).'.'.$request->file('slider_image')->getClientOriginalExtension();  // 3434343443.jpg
        $img=$manager->read($request->file('slider_image'));

        $img=$img->resize(2376,807);

        $img->toJpeg(80)->save(base_path('public/upload/slider/'.$name_gen));

        $save_url='upload/slider/'.$name_gen; //save in database

            if(file_exists($old_img))
            {
                unlink($old_img);
            }




            Slider::findOrFail($slider_id)->update([
                'slider_title' => $request->slider_title,
                 'short_title' => $request->short_title,

                'slider_image' => $save_url,

            ]);
            $notification = array(
            'message' => 'Slider updated with image Successfully',
            'alert-type' => 'success'
        );

            return redirect()->route('all.slider')->with($notification);
        }

        else
        {

            Slider::findOrFail($slider_id)->update([
                'slider_title' => $request->slider_title,
                'short_title' => $request->short_title,



            ]);
            $notification = array(
            'message' => 'Slider updated without image Successfully',
            'alert-type' => 'success'
        );

            return redirect()->route('all.slider')->with($notification);
        }

    }

    public function DeleteSlider($id){

        $slider = Slider::findOrFail($id);
        $img = $slider->slider_image;
        unlink($img );

        Slider::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Slider Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }// End Method



}
