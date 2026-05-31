<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Staff;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Validator;
use Session;
use Auth;
use App\Models\Role;
use App\Models\Store;
use App\Models\Testimonial;
use App\Models\Toptool;
use Carbon\Carbon;
use App\Models\Activitylog;
use App\Http\Traits\ActivityLogTraits;

class TestimonialsController extends Controller
{
    use ActivityLogTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (canAccess('testimonials')) {
            $urls = 'design';

            /*extract user_id, user_type, store_id,customer, customer_id*/
            extract(getUserData());

            /*increment tools count*/
            topToolsCount(' Testimonial', "testimonial.png", "design/testimonials");
            $testimonials = Testimonial::where('store_id', $store_id)->orderBy('position', 'ASC')->get();
            $activity = " Access Testimonial List Page ";
            $this->saveactivity($activity);
            // dd($testimonials);
            return view('admin.design.testimonials.index')->with('urls', $urls)->with('testimonials',
                $testimonials)->with('store_id', $store_id);
        }
    }

    public function changetestimonialsstatus(Request $request)
    {
        $id = $request->id;
        $value = $request->value;
        $product = Testimonial::find($id);
        if (isset($product) && $product->status == 'active') {
            $product->status = 'inactive';
        } else {
            $product->status = "active";
        }
        $product->save();
        $data = $product;
        $activity = " Change Testimonial Status " . $product->id;
        $this->saveactivity($activity);
        return response()->json($data);
    }

    public function create()
    {
        if (canAccess('testimonials')) {
            $urls = 'design';

            /*extract user_id, user_type, store_id,customer, customer_id*/
            extract(getUserData());

            /*increment tools count*/
            topToolsCount(' Testimonial', "testimonial.png", "design/testimonials");

            $activity = " Access Testimonial Create Page ";
            $this->saveactivity($activity);
            return view('admin.design.testimonials.create')->with('urls', $urls);
        }
    }

    public function save(Request $request)
    {
        $rules = array(
            'image' => 'required',
            'name' => 'required',
            'position' => 'required',
            'status' => 'required'
        );
        $message = array(
            'image.required' => 'Image is required.',
            'name.required' => 'Name is required.',
            'position.required' => 'Position is required.',
            'status.required' => 'Status is required.'
        );
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            /*extract user_id, user_type, store_id,customer, customer_id*/
            extract(getUserData());

            $slider = new Testimonial;
            $slider->name = $request->name;
            $slider->occupation = $request->occupation;
            $slider->feedback = $request->feedback;
            
            if ($request->input('image')) {
//                $image = $request->file('image');
//                $validation = imageValidation($image, $store_id);
//                if ($validation) {
//                    return back()->with('error', $validation);
//                }
//
//                $imageUploadPath = 'assets/images/testimonials/';
//                $imageName = uploadFile($image, $imageUploadPath);
                $slider->image = getLibraryImagePath($request->image);
            }

            $slider->position = $request->position;
            if ($request->status == 'on') {
                $slider->status = 'active';
            } else {
                $slider->status = 'inactive';
            }

            $slider->uid = $user_id;
            $slider->customer_id = $customer_id;
            $slider->store_id = $store_id;
            $slider->creator = $user_id;
            $slider->editor = $user_id;
            $slider->save();

            $activity = " Save Testimonial " . $slider->id;
            $this->saveactivity($activity);
            Session::flash('success_message', 'Testimonials Save Successfully !');
            return redirect()->route('admin.testimonials');
        }
    }

    public function edit($id)
    {
        if (canAccess('testimonials')) {
            $urls = 'design';

            /*extract user_id, user_type, store_id,customer, customer_id*/
            extract(getUserData());

            /*increment tools count*/
            topToolsCount(' Testimonial', "testimonial.png", "design/testimonials");

            $testimonials = Testimonial::where('id', $id)->first();
            $activity = " Edit Testimonial " . $testimonials->id;
            $this->saveactivity($activity);
            return view('admin.design.testimonials.edit')->with('urls', $urls)->with('data', $testimonials);
        }
    }

    public function update(Request $request, $id)
    {
        $rules = array(
            'name' => 'required',
            'position' => 'required',
            'status' => 'required'
        );
        $message = array(
            'image.required' => 'Image is required.',
            'name.required' => 'Name is required.',
            'position.required' => 'Position is required.',
            'status.required' => 'Status is required.'
        );
        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        } else {
            $userData = getUserData();
            $store_id = $userData['store_id'];

            $slider = Testimonial::find($id);
            $slider->name = $request->name;
            $slider->occupation = $request->occupation;
            $slider->feedback = $request->feedback;

            if ($request->input('image')) {
//                $image = $request->file('image');
//                $validation = imageValidation($image, $store_id);
//                if ($validation) {
//                    return back()->with('error', $validation);
//                }
//
//                $imageUploadPath = 'assets/images/testimonials/';
//                $imageName = updateFile($image, $imageUploadPath, $slider->image);
                $slider->image = getLibraryImagePath($request->image);
            }

            $slider->position = $request->position;
            if ($request->status == 'on') {
                $slider->status = 'active';
            } else {
                $slider->status = 'inactive';
            }
            $user = Auth::user()->id;
            $slider->editor = $user;
            $slider->save();
            $activity = " Update Testimonial " . $slider->id;
            $this->saveactivity($activity);
            Session::flash('success_message', 'Testimonials Updated Successfully !');
            return redirect()->route('admin.testimonials');
        }
    }

    public function delete($id)
    {
        if (canAccess('testimonials')) {
            $testimonial = Testimonial::find($id);
            if ($testimonial) {
                $activity = " Delete Testimonial " . $testimonial->id;
                $this->saveactivity($activity);
                $testimonial->delete();
                Session::flash('success', 'Testimonials Deleted Successfully !');
                return redirect()->route('admin.testimonials');
            }

            Session::flash('error', 'Record not found !');
            return redirect()->back();
        }
    }

    public function deleteImage($id)
    {
        if (canAccess('testimonials')) {
            $testimonials = Testimonial::where('id', $id)->first();
            if (isset($testimonials)) {
                $testimonials->image = null;
                $testimonials->update();

                return sendResponse("Testimonial Image Deleteed Successfully");
            }
        }
        return sendError("Testimonial not found");
    }

    public function updateposition(Request $request)
    {
        $value = $request->value;
        $id = $request->id;
        $test = Testimonial::where('id', $id)->first();
        $test->position = $value;
        $test->save();
        // $data=$test;
        $data = $test;
        $activity = " Update Testimonial Position";
        $this->saveactivity($activity);
        return response()->json($data);
    }

    public function changetestimonialssstatus(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('message', 'Please Select at least one item');
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
                    $product = Testimonial::find($ids);
                    if ($product) {
                        $product->status = 'active';
                        $product->save();
                    }
                }
            }
            $activity = " Update Testimonial Status";
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully Active Testimonial');
            return back();
        }
        if ($request->action == 'deactive') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Testimonial::find($ids);
                    if ($product) {
                        $product->status = 'deactive';
                        $product->save();
                    }
                }
            }
            $activity = " Update Testimonial Status";
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully Deactive Testimonial');
            return back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $product = Testimonial::find($ids);
                    if ($product) {
                        $product->delete();
                    }
                }
            }
            $activity = " Delete Testimonial";
            $this->saveactivity($activity);
            Session::flash('message', 'Successfully Deleted Testimonial');
            return back();
        }
    }
}
