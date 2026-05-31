<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\BlogCoverImage;
use App\Models\BuyModulus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminBlogKeyword;
use App\Models\AdminBlogType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\TempImage;
use App\Models\AdminBlog;
use App\Models\Customer;
use App\Models\Staff;
use Carbon\Carbon;

class AdminBlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $userData = getUserData();
        $store_id = $userData["store_id"] ?? NULL;

        if (Auth::check() && Auth::user()->type == "superadmin") {
            $blogs = AdminBlog::where("store_id", NULL)->orderBy('id', 'desc')->paginate(20);
        } elseif (Auth::check() && Auth::user()->type == "superstaff") {
            $blogs = AdminBlog::where("user_id", Auth::id())->where("store_id", NULL)->orderBy('id', 'desc')->paginate(20);
        } elseif (Auth::check() && (Auth::user()->type == "admin" || Auth::user()->type == "staff" || Auth::user()->type == "dropshipper")) {
            $blogs = AdminBlog::where("store_id", $store_id)->orderBy('id', 'desc')->paginate(20);
        }

        $coverImage = BlogCoverImage::where("store_id", $store_id)->first();

        return view('superadmin.blogs.index', [
            "blogs" => $blogs,
            "coverImage" => $coverImage
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        if (Auth::check() && Auth::user()->type == "superadmin") {
            $blogTypes = AdminBlogType::where("store_id", NULL)->where("status", 1)->get();
        } elseif (Auth::check() && Auth::user()->type == "superstaff") {
            $blogTypes = AdminBlogType::where("user_id", Auth::id())->where("store_id", NULL)->where("status", 1)->orderBy('id', 'desc')->get();
        } elseif (Auth::check() && (Auth::user()->type == "admin" || Auth::user()->type == "staff" || Auth::user()->type == "dropshipper")) {
            $userData = getUserData();
            $store_id = $userData["store_id"] ?? "";
            $blogTypes = AdminBlogType::where("store_id", $store_id)->where("status", 1)->get();
        }

        return view('superadmin.blogs.create', compact('blogTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response| \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = array(
            'title' => 'required',
        );

        $message = array(
            'title.required' => 'Title is required',
        );

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $blog = new AdminBlog;
            $blog->title = $request->title;
            $blog->sub_title = $request->sub_title;

            $keyWordIds = [];

            // Extract keywords
            $keywords = is_array($request->seo) ? $request->seo : explode(',', $request->seo);

            foreach ($keywords as $keyword) {
                // Convert the keyword to lowercase
                $keyword = strtolower($keyword);

                // Check if the keyword exists in the database
                $existingKeyword = AdminBlogKeyword::where('name', $keyword)->first();

                if (!$existingKeyword) {
                    // If the keyword doesn't exist, insert it
                    $newKeyword = new AdminBlogKeyword();
                    $newKeyword->name = $keyword;
                    $newKeyword->save();

                    // Collect the ID of the newly inserted keyword
                    $keyWordIds[] = $newKeyword->id;
                } else {
                    // If the keyword already exists, collect its ID
                    $keyWordIds[] = $existingKeyword->id;
                }
            }

            $slug = $this->generatePermalink($request->title, "slug");

            // Assign unique slug
            $blog->slug = $slug;


            if (isset($request->permalink) && !empty($request->permalink)) {
                $permalink = $this->generatePermalink($request->permalink, "permalink");
            }

            // Assign other attributes
            $blog->description = $request->details;
            $blog->type = $request->type;
            $blog->position = $request->position;
            $blog->permalink = $permalink ?? $slug ?? null;
            $blog->canonical_url = $request->canonical_url ?? null;
            $blog->custom_script = $request->custom_script ?? null;
            $blog->website = $request->website ?? null;
            $blog->popular = $request->popular_status == 'on' ? 1 : 0;
            $blog->user_id = Auth::user()->id ?? null;
            if (Auth::user()->type == "admin" || Auth::user()->type == "staff" || Auth::user()->type == "dropshipper") {
                $userData = getUserData();
                $store_id = $userData["store_id"] ?? "";
                $blog->store_id = $store_id;
            }
            $blog->status = $request->status == 'on' ? 1 : 0;
            $keyWordIds = array_map('strval', $keyWordIds);
            $blog->key_word = json_encode($keyWordIds);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Process image upload
                $originName = $request->file('image')->getClientOriginalName();
                $fileName = pathinfo($originName, PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $fileName = Auth::user()->id . '_blog_' . Carbon::now()->timestamp . '.' . $extension;
                $request->file('image')->move(public_path('BlogImages'), $fileName);
                $blog->image = $fileName;
            }

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                // Process thumbnail upload
                $originName = $request->file('thumbnail')->getClientOriginalName();
                $fileName = pathinfo($originName, PATHINFO_FILENAME);
                $extension = $request->file('thumbnail')->getClientOriginalExtension();
                $fileName = Auth::user()->id . '_blog_' . Carbon::now()->timestamp . '.' . $extension;
                $request->file('thumbnail')->move(public_path('BlogImages'), $fileName);
                $blog->thumbnail = $fileName;
            }

            // Save  the blog
            $blog->save();

            // Redirect to named route with success message
            return redirect()->route('superadmin.blog.index')->with('success', 'Blog saved');
        }
    }

    /**
     *  Generate permalink
     *
     * @param $permalink
     * @return string
     *
     */
    public function generatePermalink($permalink, $column)
    {
        // Generate initial slug
        $slug = generateSlug($permalink, '-');

        // Check if the slug already exists
        $originalSlug = $slug;
        $counter = 1;

        while (AdminBlog::where($column, $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $editBlog = AdminBlog::find($id);

        // Check if $editBlog is null
        if ($editBlog) {
            if (Auth::check() && Auth::user()->type == "superadmin") {
                $blogTypes = AdminBlogType::where("store_id", NULL)->where("status", 1)->get();
            } elseif (Auth::check() && Auth::user()->type == "superstaff") {
                $blogTypes = AdminBlogType::where("user_id", Auth::id())->where("store_id", NULL)->where("status", 1)->orderBy('id', 'desc')->get();
            } elseif (Auth::check() && (Auth::user()->type == "admin" || Auth::user()->type == "staff")) {
                $userData = getUserData();
                $store_id = $userData["store_id"] ?? "";
                $blogTypes = AdminBlogType::where("store_id", $store_id)->where("status", 1)->get();
            }

            $allKeyWord = AdminBlogKeyword::all();

            // Convert JSON string to array if $editBlog->key_word is a string
            $keywordIds = is_array($editBlog->key_word) ? $editBlog->key_word : json_decode($editBlog->key_word, true);

            // Initialize an empty array to store keyword names
            $keywordNames = [];
            if (!is_null($keywordIds)) {
                // Iterate through each keyword ID associated with the blog
                foreach ($keywordIds as $keywordId) {
                    // Search for the keyword in $allKeyWord collection
                    $keyword = $allKeyWord->where('id', $keywordId)->first();

                    // If keyword found, add its name to the array
                    if ($keyword) {
                        $keywordNames[] = $keyword->name;
                    }
                }
            }

            return view('superadmin.blogs.edit', compact('editBlog', 'blogTypes', 'keywordNames'));
        } else {
            // Handle case where $editBlog is not found
            // For example, you can return a 404 response or redirect to another page.
            return redirect()->back();
        }
    }

    /**
     * Update a blog post.
     *
     * This function handles the update process for a blog post based on the provided request and blog ID.
     * It checks if the blog exists, regenerates the slug if necessary, updates attributes such as title, subtitle,
     * description, type, position, status, keywords, and handles image and thumbnail uploads.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object containing blog update data
     * @param int $id The ID of the blog post to update
     * @return \Illuminate\Http\RedirectResponse Redirects back to the blog index page with a success message upon successful update
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'title' => 'required',
        );

        $message = array(
            'title.required' => 'Title is required',
        );

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {

            $blog = AdminBlog::find($id);

            if (is_null($blog)) {
                return redirect()->back();
            }

            // Regenerate slug if title is changed or if slug is not unique
            if ($request->title != $blog->title) {
                $slug = $this->generatePermalink($request->title, "slug");

                // Assign unique slug
                $blog->slug = $slug;
            }

            // Regenerate slug if title is changed or if slug is not unique
            if (isset($request->permalink) && !empty($request->permalink)) {
                if ($request->permalink != $blog->permalink) {
                    $blog->permalink = $this->generatePermalink($request->permalink, "permalink");
                }
            }

            $blog->title = $request->title;
            $blog->sub_title = $request->sub_title;

            $keyWordIds = [];

            // Extract keywords
            $keywords = is_array($request->seo) ? $request->seo : explode(',', $request->seo);

            foreach ($keywords as $keyword) {
                // Convert the keyword to lowercase
                $keyword = strtolower($keyword);

                // Check if the keyword exists in the database
                $existingKeyword = AdminBlogKeyword::where('name', $keyword)->first();

                if (!$existingKeyword) {
                    // If the keyword doesn't exist, insert it
                    $newKeyword = new AdminBlogKeyword();
                    $newKeyword->name = $keyword;
                    $newKeyword->save();

                    // Collect the ID of the newly inserted keyword
                    $keyWordIds[] = $newKeyword->id;
                } else {
                    // If the keyword already exists, collect its ID
                    $keyWordIds[] = $existingKeyword->id;
                }
            }

            // Assign other attributes
            $blog->description = $request->details;
            $blog->type = $request->type;
            $blog->position = $request->position;
            $blog->canonical_url = $request->canonical_url ?? null;
            $blog->custom_script = $request->custom_script ?? null;
            $blog->website = $request->website ?? null;
            $blog->popular = $request->popular_status == 'on' ? 1 : 0;
            $blog->status = $request->status == 'on' ? 1 : 0;
            $keyWordIds = array_map('strval', $keyWordIds);
            $blog->key_word = json_encode($keyWordIds);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($blog->image) {
                    $oldImagePath = public_path('BlogImages') . '/' . $blog->image;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Process image upload
                $originName = $request->file('image')->getClientOriginalName();
                $fileName = pathinfo($originName, PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $fileName = Auth::user()->id . '_blog_' . Carbon::now()->timestamp . '.' . $extension;
                $request->file('image')->move(public_path('BlogImages'), $fileName);
                $blog->image = $fileName;
            }

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                // Delete the old thumbnail if it exists
                if ($blog->thumbnail) {
                    $oldThumbnailPath = public_path('BlogImages') . '/' . $blog->thumbnail;
                    if (file_exists($oldThumbnailPath)) {
                        unlink($oldThumbnailPath);
                    }
                }

                // Process thumbnail upload
                $originName = $request->file('thumbnail')->getClientOriginalName();
                $fileName = pathinfo($originName, PATHINFO_FILENAME);
                $extension = $request->file('thumbnail')->getClientOriginalExtension();
                $fileName = Auth::user()->id . '_blog_' . Carbon::now()->timestamp . '.' . $extension;
                $request->file('thumbnail')->move(public_path('BlogImages'), $fileName);
                $blog->thumbnail = $fileName;
            }

            // Save  the blog
            $blog->save();

            // Redirect to named route with success message
            return redirect()->route('superadmin.blog.index')->with('success', 'Blog Updated');
        }
    }

    /**
     * Delete the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function blogDelete($id)
    {
        // Find the category by ID
        $blog = AdminBlog::find($id);

        // If category does not exist, redirect back
        if (empty($blog)) {
            Session::flash('error', 'Data Not Found!');
            return redirect()->back();
        }

        // Check if the user is a super admin, if not, redirect to 404 page
        if (Auth::user()->isSuperAdmin() || Auth::user()->id == $blog->user_id) {
            $blog->delete();

            // Set a flash message to indicate successful category delete
            Session::flash('message', 'Blog Delete Successfully !');

            // Redirect back
            return redirect()->back();
        }

        Session::flash('error', 'You can not delete this blog!');
        return redirect()->back();
    }

    /**
     * Handle image uploads for CKEditor.
     *
     * This method is responsible for processing image uploads initiated from CKEditor.
     * It receives a file upload request from CKEditor, saves the uploaded image to the server,
     * and returns a JSON response containing the URL of the uploaded image.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \InvalidArgumentException
     */
    public function ckEditor(Request $request)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];

        if ($request->hasFile('upload')) {
            // Check input image mimeType validation
            $validated = imageValidation($request->file('upload'), $store_id);
            if ($validated) {
                return response()->json(['error' => ['message' => $validated,]], 400);
            }

            // Upload image
            $imageUploadPath = 'BlogImages/';
            $fileName = uploadFile($request->file('upload'), $imageUploadPath);

            $tmp = new TempImage();
            $tmp->user_id = Auth::user()->id;
            $tmp->store_id = $store_id ?? 0;
            $tmp->image = $fileName;
            $tmp->status = 0;
            $tmp->save();

            $url = asset('BlogImages/' . $fileName);

            return response()->json(['fileName' => $fileName, 'uploaded' => 1, 'url' => $url]);
        }

        // If no file upload is found, throw an exception or return an error response
        throw new \InvalidArgumentException('No file upload found.');
    }

    /**
     *
     * Check input image mimetype validation
     *
     * @param $requestImage
     * @param $store_id
     * @return string|void
     */
    public function inputImageValidation($image, $store_id)
    {
        // Check image covert modules is active or not
        $imageModuleID = '107';
        $storeModulu = BuyModulus::where('modulus_id', $imageModuleID)->where('store_id', $store_id)->first();
        if (isset($storeModulu->status) && $storeModulu->status == 1) {
            $imageConvert = true;
        } else {
            $imageConvert = false;
        }

        $imgSize = $image->getSize();
        $imgSize = $imgSize / 1024;  // convert image size to kb

        // Check image converter module is active or not if active then check image size
        if ($imageConvert) {
            // Check image size if the size is greater than 600kb than throw an error.
            if ($imgSize > 6144) {
                return "Media must be lower than or equal to 6MB!";
            }
        } else {
            // Check image size if the size is greater than 200kb than throw an error.
            if ($imgSize > 200) {
                return "Media must be lower than or equal to 200kb.";
            }
        }


        // Check mimeType
        $mimeType = getMimeTypes();

        $imgExt = strtolower($image->getClientOriginalExtension());

        // Check input image mimeType
        if (!in_array($imgExt, $mimeType)) {
            return getMimeTypesValidationMessage();
        }

        return null;
    }


    /**
     * Update the position of a blogs.
     *
     * This method receives a request containing the new position value and the ID
     * of the blog to be updated. It validates the input data and updates the
     * position of the category if it exists. If the category or input data is
     * invalid, appropriate error responses are returned.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pseBlogPosition(Request $request)
    {
        // Extract value and ID from the request
        $value = $request->value;
        $id = $request->id;

        // Validate the input data
        if (is_null($value)) {
            // If value is missing, return error response
            return response()->json([
                'data' => $value,
                'status' => 'Value is missing'
            ]);
        }

        if (empty($id)) {
            // If ID is missing, return error response
            return response()->json([
                'data' => $id,
                'status' => 'Blog ID is missing'
            ]);
        }

        // Find the category by ID
        $category = AdminBlog::find($id);

        if (!$category) {
            // If category not found, return error response
            return response()->json([
                'data' => $id,
                'status' => 'Blog not found'
            ]);
        }

        // Update the category position and save
        $category->position = $value;
        $category->save();

        // Return success response
        return response()->json([
            'data' => $category,
            'status' => 'Blog Position Updated Successfully'
        ]);
    }

    /**
     * Updates the status of a Blog Post.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function blogStatus(Request $request)
    {
        $value = $request->value;
        $id = $request->id;

        if (empty($id)) {
            return response()->json([
                'data' => $id,
                'status' => 'ID not found'
            ]);
        }

        if ($value != 'on') {
            return response()->json([
                'data' => $value,
                'status' => "Value Not Found."
            ]);
        }

        $findBlogOrNot = AdminBlog::where('id', $id)->first();

        if (empty($findBlogOrNot)) {
            return response()->json([
                'data' => $findBlogOrNot,
                'status' => 'Blog not found.'
            ]);
        }

        if ($findBlogOrNot->status == 1) {
            $findBlogOrNot->status = false;
            $findBlogOrNot->save();

            return response()->json([
                'data' => $findBlogOrNot,
                'status' => 'Blog Inactive Updated Successfully.'
            ]);
        }

        if ($findBlogOrNot->status == 0) {
            $findBlogOrNot->status = true;
            $findBlogOrNot->save();

            return response()->json([
                'data' => $findBlogOrNot,
                'status' => 'Blog Active Updated Successfully.'
            ]);
        }

        return response()->json([
            'data' => 404,
            'status' => 'Something wants wrong.'
        ]);
    }


    public function blogActionChange(Request $request)
    {
        if ($request->text2 == '') {
            Session::flash('message', 'Please Select Blog');
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
                    $blog = AdminBlog::find($ids);
                    $blog->status = 1;
                    $blog->save();
                }
            }

            Session::flash('message', 'Successfully Active Blog');
            return back();
        }
        if ($request->action == 'deactive') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $blog = AdminBlog::find($ids);
                    $blog->status = 0;
                    $blog->save();
                }
            }

            Session::flash('message', 'Successfully Deactive Blog');
            return back();
        }
        if ($request->action == 'delete') {
            $id = explode(',', $request->text2);
            if (isset($id) && count($id) > 0) {
                foreach ($id as $ids) {
                    $blog = AdminBlog::find($ids);
                    $blog->delete();
                }
            }

            Session::flash('message', 'Successfully Deleted Blog');
            return back();
        }
    }


    public function updateCoverImage(Request $request)
    {
        $rules = array(
            'image' => 'required',
        );

        $message = array(
            'image.required' => 'image is required.',
        );
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return redirect()->back()->withInput()
                ->withErrors($validator);
        } else {
            $userData = getUserData();
            $store_id = $userData['store_id'] ?? NULL;

            $imageUploadPath = 'BlogImages/'; // Upload image

            $blogImage = BlogCoverImage::where("store_id", $store_id)->first();
            if (isset($blogImage)) {
                if ($request->hasFile('image')) {
                    $image = $request->file('image');

                    if (!is_null($store_id)) {
                        $validation = imageValidation($image, $store_id);
                        if ($validation) {
                            return back()->with('warning', $validation);
                        }
                    }

                    if ($blogImage->image) {
                        $oldImagePath = public_path('BlogImages') . '/' . $blogImage->image;
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }


                    $imageName = uploadFile($image, $imageUploadPath);
                    $blogImage->image = $imageName;
                }

                $blogImage->save();
            } else {
                $blogImage = new BlogCoverImage();
                $blogImage->store_id = $store_id;

                if ($request->hasFile('image')) {
                    $image = $request->file('image');

                    if (!is_null($store_id)) {
                        $validation = imageValidation($image, $store_id);
                        if ($validation) {
                            return back()->with('warning', $validation);
                        }
                    }

                    $imageName = uploadFile($image, $imageUploadPath);
                    $blogImage->image = $imageName;
                }

                $blogImage->save();
            }


            Session::flash('message', 'Cover Image Save Successfully !');
            return redirect()->back();
        }
    }


}
