<?php

namespace App\Http\Controllers\ChatSystem;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!canSuperStaffAccess('message')) {
            return redirect()->back();
        }

        return view('chatSystem.index');
    }

    public function getUser(Request $request)
    {
        $search = $request->search ?? ''; // Search query

        $user_id = Auth::user()->id;
        $user_type = Auth::user()->type;
        $store_id = null;

        if ($user_type == 'admin' || $user_type == 'dropshipper') {
            $customer = Customer::where('uid', $user_id)->first();
            $store_id = $customer->active_store;
        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', $user_id)->first();
            $store_id = $staff->store_id;
        }

        $query = User::where('type', 'customer')->where('store_id', $store_id)->with('store');

        if ($user_type == 'superadmin' || $user_type == 'superstaff') {
            $query = User::where(function ($q) {
                $q->where('type', 'admin')->orWhere('type', 'dropshipper');
            });
        }

        // Apply search filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%')
                    ->orWhere('phone', 'LIKE', '%' . $search . '%');
            });
        }


        // Apply pagination
        $users = $query->paginate(15);
        $usersCollection = UserResource::collection($users);

        // Constructing the paginated response with custom structure
        $response = [
            'current_page' => $users->currentPage(),
            'data' => $usersCollection->items(), // Extract the items from the collection
            'first_page_url' => $users->url(1),
            'from' => $users->firstItem(),
            'last_page' => $users->lastPage(),
            'last_page_url' => $users->url($users->lastPage()),
            'next_page_url' => $users->nextPageUrl(),
            'path' => $users->url(1),
            'per_page' => $users->perPage(),
            'prev_page_url' => $users->previousPageUrl(),
            'to' => $users->lastItem(),
            'total' => $users->total(),
        ];

        return response()->json($response);
    }


}
