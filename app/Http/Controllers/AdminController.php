<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function AdminDashboard()
    {
        return view("admin.index");

    }//end method

    public function AdminLogin()
    {
        return view("admin.admin_login");
    }

    public function AdminDestroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    } //end method

    public function AdminProfile()
    {
        //
        $id=Auth::user()->id;
        $adminData=User::find($id);
        return view('admin.admin_profile_view',compact('adminData'));
    }//end method

    public function AdminProfileStore(Request $request)
    {
        $id=Auth::user()->id;
        $data=User::find($id);
        $data->name= $request->name;
        $data->email= $request->email;
        $data->phone= $request->phone;
        $data->address= $request->address;


        if($request->file('photo'))
        {
            $file=$request->file('photo');
            @unlink(public_path('upload/admin_images/' .$data->photo));
            $filename=date('YmdHi') .$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'), $filename);

            $data['photo']=$filename;

        }
        $data->save();

        $notification =array(
            'message'=>'Admin Profile updated successfully',
            'alert-type'=>'success',

        );
        return redirect()->back()->with($notification);



    }
    public function AdminChangePassword()
    {
        return view('admin.admin_change_password');
    }

    public function AdminUpdatePassword(Request $request)
    {

        //validation
        $request->validate([
            //validation hiih
            'old_password'=>'required',
            'new_password'=>'required|confirmed',

        ]);

        //match the old password

        if(!Hash::check($request->old_password, auth::user()->password))
        {
            return back()->with('error','Old password doesnt match');
        }

        //update new password

        User::whereId(auth()->user()->id)->update([
            'password'=>Hash::make($request->new_password)
        ]);
        return back()->with('status','password changed successfully');

    }


    public function InactiveVendor()
    {

        //ene controlliiig sain har sain oilgo
        $inActiveVendor = User::where('status','inactive')->where('role','vendor')
        ->latest()->get();

        return view('backend.vendor.inactive_vendor', compact('inActiveVendor'));
    }
    public function ActiveVendor()
    {

        //ene controlliiig sain har sain oilgo
        $ActiveVendor = User::where('status','active')->where('role','vendor')
        ->latest()->get();

        return view('backend.vendor.active_vendor', compact('ActiveVendor'));
    }
    public function InactiveVendorDetails($id)
    {
        $inactiveVendorDetails=User::findOrFail($id);
        return view('backend.vendor.inactive_vendor_details', compact('inactiveVendorDetails'));
    }


    public function ActiveVendorApprove(Request $request)
    {

        //form dotroo hidden id-g blade-ees ywuulaad teriigee end id gaar ni duudaad  olj bna

        $vendor_id=$request->id;
        $user=User::findOrFail($vendor_id)->update([
            'status'=>'active',
        ]);
        $notification =array(
            'message'=>'Vendor Active  successfully',
            'alert-type'=>'success',

        );
        return redirect()->route('active.vendor')->with($notification);
    }


    public function ActiveVendorDetails($id)
    {
        $activeVendorDetails=User::findOrFail($id);
        return view('backend.vendor.active_vendor_details', compact('activeVendorDetails'));
    }


    public function InActiveVendorApprove(Request $request)
    {

        //form dotroo hidden id-g blade-ees ywuulaad teriigee end id gaar ni duudaad  olj bna

        $vendor_id=$request->id;
        $user=User::findOrFail($vendor_id)->update([
            'status'=>'inactive',
        ]);
        $notification =array(
            'message'=>'Vendor InActive  successfully',
            'alert-type'=>'success',

        );
        return redirect()->route('inactive.vendor')->with($notification);
    }








}

