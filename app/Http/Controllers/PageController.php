<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Str;
use App\Models\Staff;
use App\Models\Role;
use App\Models\Customer;
use App\Models\Toptool;
use Carbon\Carbon;
use App\Models\Activitylog;
use App\Http\Traits\ActivityLogTraits;
use App\Models\temp;
use App\Models\TempImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    use ActivityLogTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (canAccess('pages')) {
            $urls = "design";

            /*extract user_id, user_type, store_id,customer, customer_id*/
            extract(getUserData());

            /*increment tools count*/
            topToolsCount(' Page', "team.png", "design/pages");

            $list = Page::where('store_id', $store_id)->orderBy('id', 'DESC')->paginate(50);
            $activity = " Access Page List Page";
            $this->saveactivity($activity);
            return view('admin.design.page.index')
                ->with('data', $list)->with('urls', $urls);
        }
    }

    public function create()
    {
        if (canAccess('pages')) {
            $urls = "design";

            /*extract user_id, user_type, store_id,customer, customer_id*/
            extract(getUserData());

            /*increment tools count*/
            topToolsCount(' Page', "team.png", "design/pages");

            $activity = " Access Page Create Page";
            $this->saveactivity($activity);
            return view('admin.design.page.create')->with('urls', $urls);
        }
    }

    public function pageexport(Request $request)
    {
        $date = Carbon::now();

        /*extract user_id, user_type, store_id,customer, customer_id*/
        extract(getUserData());

        $fileName = 'page(' . $date . ').csv';
        $coupon = Page::where('store_id', $store_id)->get();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('Name', 'Slug', 'Details', 'Link', 'Created_at');

        $callback = function () use ($coupon, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($coupon as $cat) {
                $row['Name'] = $cat->name;
                $row['Slug'] = $cat->slug;
                $row['Details'] = $cat->details;
                $row['Link'] = $cat->link;
                $row['Create Date'] = $cat->created_at;

                fputcsv($file, array($row['Name'], $row['Slug'], $row['Details'], $row['Link'], $row['Create Date']));
            }

            fclose($file);
        };
        $activity = " Export Page List";
        $this->saveactivity($activity);
        return response()->stream($callback, 200, $headers);
    }


    public function ckEditor(Request $request)
    {
        if ($request->hasFile('upload')) {

            $date = Carbon::now();

            /*extract user_id, user_type, store_id,customer, customer_id*/
            extract(getUserData());

            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = Auth::user()->id . '_' . $store_id . '_' . Carbon::now()->timestamp . '.' . $extension;

            $tmp = new TempImage();
            $tmp->user_id = Auth::user()->id;
            $tmp->store_id = $store_id;
            $tmp->image = $fileName;
            $tmp->status = 0;
            $tmp->save();

            $request->file('upload')->move(public_path('pageImages'), $fileName);

            $url = asset('pageImages/' . $fileName);
            return response()->json(['fileName' => $fileName, 'uploaded' => 1, 'url' => $url]);
        }
    }

    public function store(Request $request)
    {
        if ($request->link == 'none') {
            Session()->flash('error', 'Link Must Be Given');
            return back()->withInput();
        }
        $rules = array(
            'name' => 'required',
        );
        $message = array(
            'name.required' => 'Name is required!',
        );
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            /*extract user_id, user_type, store_id,customer, customer_id*/
            extract(getUserData());

            $pageEx = Page::where('store_id', $store_id)->where('name', $request->name)->first();
            if ($pageEx) {
                Session::flash('error', 'Name already exist!');
                return redirect()->back()->withInput();
            }

            $pages = new Page();
            $pages->name = $request->name;
            $slug = strtolower(str_replace(' ', '_', $request->name));
            $exist = Page::find($slug);
            if (isset($exist)) {
                $random = Str::random(10);
                $slug = $slug . $random;
            }
            $pages->slug = $slug;
            $pages->details = $request->details;

            if (isset($request->feature_image) && !empty($request->feature_image)) {
                $pages->feature_image = getLibraryImagePath($request->feature_image);
            }

            $pages->link = $request->link;
            if ($request->status == "on") {
                $pages->status = "active";
            } else {
                $pages->status = "inactive";
            }

            $pages->uid = $user_id;
            $pages->store_id = $store_id;
            $pages->customer_id = $customer_id;
            $pages->creator = $user_id;
            $pages->editor = $user_id;
            $pages->save();
            $activity = " Save Page " . $pages->name;
            $this->saveactivity($activity);


            $tt = TempImage::where('store_id', $store_id)->get();
            foreach ($tt as $key => $val) {
                if (stripos($request->details, $val->image) !== false) {
                    $r = TempImage::where('image', $val->image)->first();
                    $r->status = 1;
                    $r->update();
                }
            }
            $tt = TempImage::where('store_id', $store_id)->where('status', 0)->get();
            foreach ($tt as $key => $val) {
                $image_path = asset('pageImages/' . $val->image);
                // dd($image_path);
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
                TempImage::where('image', $val->image)->delete();
            }


            Session::flash('message', 'Successfully created!');
            return redirect()->route('admin.pages');
        }
    }

    public function updateposition(Request $request)
    {
        $value = $request->value;
        $id = $request->id;
        $test = Page::where('id', $id)->first();
        $test->position = $value;
        $test->save();
        // $data=$test;
        $data = $test;
        $activity = " Update Page Position";
        $this->saveactivity($activity);
        return response()->json($data);
    }

    public function deleteImage($id)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $page = Page::where('store_id', $store_id)->where('id', $id)->first();
        if (empty($page)) {
            return sendError("Page not found");
        }
        $page->feature_image = null;
        $page->update();

        $activity = " Delete Page Feature Image Successfully";
        $this->saveactivity($activity);
        return sendResponse("Page Feature Image Deleted Successfully");
    }

    public function changepagestatus(Request $request)
    {
        $id = $request->id;
        $page = Page::find($id);
        if (isset($page) && $page->status == 'active') {
            $page->status = 'inactive';
        } else {
            $page->status = "active";
        }
        $page->save();
        $data = $page;
        $activity = " Update Page Status";
        $this->saveactivity($activity);
        return response()->json($data);
    }

    public function edit($id)
    {
        if (canAccess('pages')) {
            $urls = "design";

            /*extract user_id, user_type, store_id,customer, customer_id*/
            extract(getUserData());

            /*increment tools count*/
            topToolsCount(' Page', "team.png", "design/pages");

            $singleData = Page::find($id);
            $activity = " Edit Page Information " . $singleData->name;
            $this->saveactivity($activity);
            return view('admin.design.page.edit')
                ->with('singleData', $singleData)->with('urls', $urls);
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $rules = array(
            'name' => 'required',
        );
        $message = array(
            'name.required' => 'Name is required!',
        );
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            /*extract user_id, user_type, store_id,customer, customer_id*/
            extract(getUserData());

            $pages = Page::find($id);

            if ($pages->name != $request->name) {
                $pageEx = Page::where('store_id', $store_id)->where('name', $request->name)->where('id', '!=', $id)->first();

                if ($pageEx) {
                    Session::flash('error', 'Name already exist!');
                    return redirect()->back()->withInput();
                }
            }

            $pages->name = $request->name;
            $pages->details = $request->details;
            if (isset($request->feature_image) && !empty($request->feature_image)) {
                $pages->feature_image = getLibraryImagePath($request->feature_image);
            }
            $pages->link = $request->link;
            if ($request->status == "on") {
                $pages->status = "active";
            } else {
                $pages->status = "inactive";
            }
            $pages->editor = Auth::user()->id;
            $pages->save();

            $activity = " Update Page " . $pages->name;
            $this->saveactivity($activity);


            $tt = TempImage::where('store_id', $store_id)->get();
            foreach ($tt as $key => $val) {
                if (stripos($request->details, $val->image) !== false) {
                    $r = TempImage::where('image', $val->image)->first();
                    $r->status = 1;
                    $r->update();
                }
            }
            $tt = TempImage::where('store_id', $store_id)->where('status', 0)->get();
            foreach ($tt as $key => $val) {
                $image_path = asset('pageImages/' . $val->image);
                // dd($image_path);
                if (File::exists($image_path)) {

                    File::delete($image_path);
                }
                // TempImage::where('image', $val->image)->delete();
            }

            Session::flash('message', 'Successfully Updated!');
            return redirect()->route('admin.pages');
        }
    }

    public function destroy($id)
    {
        if (canAccess('pages')) {
            Page::find($id)->delete();
            $activity = " Delete Page " . $id;
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully Deleted!');
            return redirect()->back();
        }
    }

    public function changepagessstatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('message', 'Please Select Page');
            return back();
        }
        if ($request->action == 'select') {
            Session::flash('message', 'Please Select a Option');
            return back();
        }

        if ($request->action == 'active') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Page::find($ids);
                    $product->status = 'active';
                    $product->save();
                }
            }
            $activity = " Change Page Status";
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully Active Page');
            return back();
        }
        if ($request->action == 'deactive') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Page::find($ids);
                    $product->status = 'deactive';
                    $product->save();
                }
            }
            $activity = " Change Page Status";
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully Deactive Page');
            return back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Page::find($ids);
                    $product->delete();
                }
            }
            $activity = " Delete Page ";
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully Deleted Page');
            return back();
        }
    }
}
