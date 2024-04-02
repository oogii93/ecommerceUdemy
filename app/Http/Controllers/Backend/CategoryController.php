<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{

    public function  AllCategory()
    {
        $categories = Category::latest()->get();

        return view("backend.category.category_all", compact("categories"));

    }

    public function AddCategory()
    {
        return view('backend.category.category_add');
    }

    public function StoreCategory(Request $request)
    {


        $manager = new ImageManager(new Driver());
    $name_gen = hexdec(uniqid()).'.'.$request->file('category_image')->getClientOriginalExtension();  // 3434343443.jpg
    $img=$manager->read($request->file('category_image'));

    $img=$img->resize(120,120);

    $img->toJpeg(80)->save(base_path('public/upload/category/'.$name_gen));

    $save_url='upload/category/'.$name_gen; //save in database
    //




    Category::insert([
        'category_name' => $request->category_name,
        'category_slug' => strtolower(str_replace(' ','-', $request->category_name)),

        'category_image' => $save_url,

    ]);
    $notification = array(
    'message' => 'Category inserted Successfully',
    'alert-type' => 'success'
);

    return redirect()->route('all.category')->with($notification);
    }

    public function EditCategory($id)
    {
        $category = Category::findOrFail($id);
        return view('backend.category.category_edit', compact('category'));
    }

    public function UpdateCategory(Request $request)
    {

        $category_id= $request->id;
        $old_image= $request->old_image;

        if($request->file('category_image'))
        {
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$request->file('category_image')->getClientOriginalExtension();  // 3434343443.jpg
            $img=$manager->read($request->file('category_image'));

            $img=$img->resize(300,300);

            $img->toJpeg(80)->save(base_path('public/upload/category/'.$name_gen));

            $save_url='upload/category/'.$name_gen; //save in database

            if(file_exists($old_image))
            {
                unlink($old_image);
            }




            Category::findOrFail($category_id)->update([
                'category_name' => $request->category_name,
                'categpru_slug' => strtolower(str_replace(' ','-', $request->category_name)),

                'category_image' => $save_url,

            ]);
            $notification = array(
            'message' => 'Category updated Successfully',
            'alert-type' => 'success'
        );

            return redirect()->route('all.category')->with($notification);
        }

        else
        {

            Category::findOrFail($category_id)->update([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ','-', $request->category_name)),


            ]);
            $notification = array(
            'message' => 'Category updated without image Successfully',
            'alert-type' => 'success'
        );

            return redirect()->route('all.category')->with($notification);
        }

    }

    public function DeleteCategory($id){

        $category = Category::findOrFail($id);
        $img = $category->category_image;
        unlink($img );

        Category::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Category Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }// End Method




}
