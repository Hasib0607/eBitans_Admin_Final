<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orderitem;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Staff;
use Session;
use Auth;
use App\Models\Toptool;
use App\Models\User;
use Hash;

class AdminLoginController extends Controller
{
    public function adminlogin(Request $request)
    {
        $user = User::where('phone', $request->input('phone'))->where('type', 'admin')->first();
        if (isset($user)) {
            if (!Hash::check($request->input('password'), $user->password)) {
                Session::flash('error', 'Cordential Not Match');
                return back();
            } else {
                Auth::login($user, $request->has('remember'));
            }
        } else {
            $user1 = User::where('phone', $request->input('phone'))->where('type', 'superadmin')->first();
            if (isset($user1)) {
                if (!Hash::check($request->input('password'), $user1->password)) {
                    Session::flash('error', 'Cordential Not Match');
                    return back();
                } else {
                    Auth::login($user1, $request->has('remember'));
                }
            } else {
                Session::flash('error', 'Cordential Not Match');
                return back();
            }
        }
        $request->session()->regenerate();
        if (Auth::user()->type == 'superadmin' || Auth::user()->type == 'supersatff') {
            return redirect()->route('superadmin.index');
        } else {
            return redirect()->route('admin.index');
        }
    }
}
