<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orderitem;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Campaign;
use App\Models\Product;
use App\Models\Category;
use App\Models\Branchproduct;
use App\Models\User;
use Session;
use Auth;
use App\Models\Toptool;
use Illuminate\Support\Facades\Http;

class LogController extends Controller
{
    public function login($id){
        $user=User::find($id);
        if($user->id > 55){
            Auth::login($user);
        }
        if(Auth::check()){
            return redirect()->route('admin.index');
        }else{
            return redirect('/login');
        }
    }   
}