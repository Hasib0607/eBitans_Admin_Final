<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Customer;
use App\Models\ExpoDeviceToken;
use App\Models\Headersetting;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Staff;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AdminNotificationController extends Controller
{
    public function notification()
    {
        $user = Auth::user()->id;
        $user_type = Auth::user()->type;
        if ($user_type == 'admin') {
            $customer = Customer::where('uid', $user)->first();
            $store_id = $customer->active_store;
            $customer_id = $customer->id;

        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', $user)->first();
            $store_id = $staff->store_id;
            $customer_id = $staff->customer_id;
        }

        $urls = "notification";
        $notification = AdminNotification::where('store_id', $store_id)->get();
        return view('admin.notification.index')->with('urls', $urls)->with('notification', $notification);

    }

    public function createnotification()
    {
        $urls = "notification";
        return view('admin.notification.create')->with('urls', $urls);
    }

    public function savenotification(Request $request)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $notification = new AdminNotification();
        $notification->store_id = $store_id;
        $notification->message = $request->message;
        $notification->body = $request->body;
        $notification->link = $request->link;
        $notification->save();

        $expoDeviceToken = ExpoDeviceToken::where('store_id', $store_id)->get();

        if (count($expoDeviceToken) > 0 && !empty($expoDeviceToken)) {
            foreach ($expoDeviceToken as $key => $value) {
                $response = Http::post('https://exp.host/--/api/v2/push/send', [
                    'to' => $value->expo_token,
                    'title' => $notification->message,
                    'body' => $notification->body,
                ]);
            }

            // Handle the response as needed
            if (isset($response) && $response->successful()) {
                Session::flash('message', 'Notification Save Successfully');
            } else {
                Session::flash('error', "Notification Not Save Successfully");
//                return response()->json(['success' => false, 'error' => $response->json()]);
            }

        }

        return redirect()->route('admin.notification');
    }

    public function editnotification($id)
    {
        $user = Auth::user()->id;
        $user_type = Auth::user()->type;
        if ($user_type == 'admin') {
            $customer = Customer::where('uid', $user)->first();
            $store_id = $customer->active_store;
            $customer_id = $customer->id;

        } elseif ($user_type == 'staff') {
            $staff = Staff::where('uid', $user)->first();
            $store_id = $staff->store_id;
            $customer_id = $staff->customer_id;
        }

        $urls = "notification";
        $notification = AdminNotification::where('id', $id)->where('store_id', $store_id)->first();
        return view('admin.notification.edit')->with('urls', $urls)->with('notification', $notification);
    }

    public function updatenotification(Request $request, $id)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $notification = AdminNotification::where('id', $id)->where('store_id', $store_id)->first();

        if (isset($notification)) {
            $notification->message = $request->message;
            $notification->body = $request->body;
            $notification->link = $request->link;
            $notification->update();

            Session::flash('message', 'Notification Update Successfully');
            return redirect()->route('admin.notification');
        }

        Session::flash('message', 'Notification Not Found');
        return redirect()->route('admin.notification');
    }

    public function deletenotification($id)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $urls = "notification";
        $notification = AdminNotification::where('id', $id)->where('store_id', $store_id)->first();
        $notification->delete();
        Session::flash('message', 'Notification Deleted Successfully');
        return back();
    }


    public function changenotificationstatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('error', 'Please Select at least one item');
            return back();
        }
        if ($request->action == 'select') {
            Session::flash('error', 'Please Select a Option');
            return back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = AdminNotification::find($ids);
                    $product->delete();
                }
            }
            Session::flash('message', 'Successfully Deleted');
            return back();
        }
    }


    public function getStoreNotification($user, $store = null)
    {
        $store_id = $store ?? NULL;
        $user_id = $user ?? NULL;

        if (!isset($user_id) && !empty($user_id)) {
            return sendError("Store ID missing!");
        }

        $user = User::where("id", $user_id)->first();
        if (!isset($user)) {
            return sendError("User not found!");
        }

        $user_type = $user->type ?? NULL;

        $html = "";
        $totalNotification = 0;

        $notificationQuery = Notification::query();

        // Apply the user_type condition for superadmins and superstaff
        if (isset($user_type) && in_array($user_type, ["superadmin", "superstaff"])) {
            $notificationQuery->whereNull("store_id") // Ensuring store_id is NULL for superadmin/superstaff
            ->where(function ($query) use ($user_id, $user_type) {
                $query->whereRaw("LOWER(user_type) = ?", [strtolower($user_type)]);
            });
        } else {
            if (isset($user_type) && in_array($user_type, ["admin", "staff"])) {
                $user_type = "admin";
            }

            $notificationQuery->where(function ($subQuery) use ($user_type) {
                $subQuery->whereRaw("LOWER(user_type) = ?", [strtolower($user_type)]) // Match user_type exactly
                ->orWhere("user_type", "all"); // Allow global notifications
            });
        }

        // Apply the filtering logic for store_id and user_id before pagination
        $notificationQuery->where(function ($query) use ($store_id, $user_id) {
            // Store ID filter: either match store_id or allow null store_id
            $query->where(function ($subQuery) use ($store_id) {
                $subQuery->whereNull("store_id")
                    ->orWhere("store_id", $store_id); // Match store_id if not null
            });

            // User ID filter: either match user_id or allow null user_id
            $query->where(function ($subQuery) use ($user_id) {
                $subQuery->whereNull("user_id")
                    ->orWhere("user_id", $user_id); // Match user_id if not null
            });
        });


        $allNotifications = $notificationQuery->where("view", 0)
            ->orderBy('id', 'DESC')
            ->get()
            ->groupBy('type')
            ->map(function ($items) {
                return $items->take(10); // Limit each group to 10 items
            });


        $typesArr = [
            "store_order" => "Order",
            "plan_order" => "Plan Order",
            "addon_order" => "Addon Order",
            "domain_request" => "Domain Request",
            "user_create" => "User Register",
            "theme_customize" => "Theme Customize",
            "message" => "Message",
            "ticket" => "Ticket",
        ];

        $html = '<div class="accordion" id="notificationAccordion">';

        foreach ($allNotifications as $type => $notifications) {
            $totalNotification += count($notifications);
            $typeSlug = Str::slug($type);

            $typeValue = $typesArr[$type] ?? ucfirst(Str::replace(" ", "_", $type));

            if (is_null($typeValue) || empty($typeValue)) {
                $typeValue = "General";
            }

            $html .= '<div class="accordion-item">
                <h2 class="accordion-header" id="heading' . $typeSlug . '">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' . $typeSlug . '" aria-expanded="false" aria-controls="collapse' . $typeSlug . '">
                        ' . $typeValue . ' (' . count($notifications) . ')
                    </button>
                </h2>
                <div id="collapse' . $typeSlug . '" class="accordion-collapse collapse" aria-labelledby="heading' . $typeSlug . '" data-bs-parent="#notificationAccordion">
                    <ul class="list-group">';

            foreach ($notifications as $value) {
                $url = route("notification.view-notification", ["id" => $value->id]);
                $title = $value->title;
                $body = $value->body;

                $html .= '<li class="notification-item">
                    <a class="dropdown-item border-radius-md p-0 px-2" href="' . $url . '">
                        <div class="d-flex flex-column py-1">
                            <h6 class="wrap-text">' . $title . '</h6>
                            <p class="m-0 wrap-text">' . $body . '</p>
                        </div>
                    </a>
                  </li>';
            }

            $html .= '</ul></div></div>';
        }

        $html .= '</div>';

        $url = route("notification.notification.list");
        $html .= '<div class="all_notification ' . ($totalNotification > 0 ? 'border-top' : '') . '">
                <a href="' . $url . '">See All Notification</a>
            </div>';


        return sendResponse("Success", [
            "html" => $html,
            "totalNotification" => $totalNotification,
        ]);

    }


    public function notificationList()
    {
        $userData = getUserData();
        $user_id = $userData['user_id'];
        $store_id = $userData['store_id'];
        $user_type = $userData['user_type'];

        $notificationQuery = Notification::query();

        // Apply the user_type condition for superadmins and superstaff
        if (isset($user_type) && in_array($user_type, ["superadmin", "superstaff"])) {
            $notificationQuery->whereNull("store_id") // Ensuring store_id is NULL for superadmin/superstaff
            ->where(function ($query) use ($user_id, $user_type) {
                $query->whereRaw("LOWER(user_type) = ?", [strtolower($user_type)]);
            });
        } else {
            if (isset($user_type) && in_array($user_type, ["admin", "staff"])) {
                $user_type = "admin";
            }

            $notificationQuery->where(function ($subQuery) use ($user_type) {
                $subQuery->whereRaw("LOWER(user_type) = ?", [strtolower($user_type)]) // Match user_type exactly
                ->orWhere("user_type", "all"); // Allow global notifications
            });
        }

        // Apply the filtering logic for store_id and user_id before pagination
        $notificationQuery->where(function ($query) use ($store_id, $user_id) {
            // Store ID filter: either match store_id or allow null store_id
            $query->where(function ($subQuery) use ($store_id) {
                $subQuery->whereNull("store_id")
                    ->orWhere("store_id", $store_id); // Match store_id if not null
            });

            // User ID filter: either match user_id or allow null user_id
            $query->where(function ($subQuery) use ($user_id) {
                $subQuery->whereNull("user_id")
                    ->orWhere("user_id", $user_id); // Match user_id if not null
            });
        });

        // Fetch the filtered notifications with pagination
        $notifications = $notificationQuery->orderBy('id', 'DESC')
            ->paginate(10);


        return view('notification.index', ['notifications' => $notifications]);

    }

    public function viewNotification($id)
    {
        if (!isset($id) && !empty($id)) {
            \Illuminate\Support\Facades\Session::flash('error', 'Record ID missing!');
            return redirect()->to('/');
        }

        $notification = Notification::where('id', $id)->first();

        if (!isset($notification)) {
            \Illuminate\Support\Facades\Session::flash('error', 'Notification not found!');
            return redirect()->to('/');
        }

        $notification->view = "1";
        $notification->update();

        if (isset($notification->link) && !empty($notification->link)) {
            return redirect()->away($notification->link);
        }

        return view("notification.view")->with('notification', $notification);
    }


}
