<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        $request->authenticate();

        $request->session()->regenerate();



        $notification =array(
            'message'=>'Login successfully',
            'alert-type'=>'success',

        );

        /// end herwee nohtsol shalgaad admin bol admin dashboardruu/ vendor bol vendor/dashboardruu user bol user/dashboardruu orohiig zaaj baina
        //ene role deer mash chuhal sain oilgoh!!!!!!!
        $url='';
        if($request->user()->role =='admin')
        {
            $url= 'admin/dashboard';
        }
        elseif($request->user()->role == 'vendor')
        {
            $url='vendor/dashboard';
        }
        elseif($request->user()->role == 'user')
        {
            $url='/dashboard';
        }

        return redirect()->intended($url)->with($notification);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
