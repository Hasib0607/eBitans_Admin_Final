<?php

namespace App\Http\Controllers\SuperAdmin;


use Illuminate\Support\Facades\Validator;
use App\Models\AcceptedPseProductRequest;
use Illuminate\Support\Facades\Session;
use function PHPUnit\Framework\isNull;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;
use Carbon\Carbon;


class PseCategoryController extends Controller
{
    /**
     * Validate the category data from the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateCategory(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required',
            'icon' => 'required',
            'banner' => 'required|max:2048',
            'position' => 'required'
        ]);
    }

    /**
     * Set a flash message in the session.
     *
     * @param string $key
     * @param string $message
     * @return void
     */
    private function setFlashMessage($key, $message)
    {
        Session::flash($key, $message);
    }

    /**
     * Calculate the total number of products for each category.
     *
     * This method iterates through the given categories and counts the total number of products
     * associated with each category using the AcceptedPseProductRequest model. The count is then
     * assigned to the 'totalProducts' property of each category object.
     *
     * @param \Illuminate\Database\Eloquent\Collection $categories The collection of categories to count products for.
     * @return void
     */
    protected function productCount($catagories)
    {
        foreach ($catagories as $cat) {
            $cat->totalProduct = AcceptedPseProductRequest::where('category_id', 'LIKE', '%"' . $cat->id . '"%')
                ->count();
        }
    }

    /**
     * Display the category index page.
     *
     * This method checks if the authenticated user is a super admin.
     * If the user is a super admin, it returns the view for managing categories.
     * If the user is not a super admin, it returns the 404 error page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        // Check if the user is a super admin, if not, redirect to 404 page
        if (!canSuperStaffAccess('pse')) {
            return redirect("/");
        }

        // get all pse category
        $catagories = Category::select('categories.*')
            ->whereNull('categories.store_id')
            ->whereNull('categories.customer_id')
            ->where('categories.status', '!=', 'RecycleBin')
            ->orderBy('categories.position', 'asc')
            ->groupBy('categories.id', 'categories.name')
            ->get();

        $this->productCount($catagories);

        // If user is a super admin, return the view for managing categories
        return view('superadmin.category.index', compact('catagories'));
    }

    /**
     * Store a newly created category.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
     */
    public function storeCategory(Request $request)
    {
        // Check if the user is a super admin, if not, redirect to 404 page
        if (!canSuperStaffAccess('pse')) {
            return redirect()->route('404');
        }

        // Validate the incoming category data
        $validator = $this->validateCategory($request);

        // If validation fails, redirect back with error messages
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // Create and store the category
        $this->createCategory($request);

        // Set a flash message to indicate successful category creation
        $this->setFlashMessage('message', 'PSE Category Save Successfully !');

        // Redirect back to the form page
        return redirect()->back();
    }

    /**
     * Create a new category instance and store it in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\Category
     */
    private function createCategory(Request $request)
    {
        // Create a new category instance
        $category = new Category;

        // Assign values from the request to the category instance
        $category->name = $request->name;
        $category->parent = "0";
        $category->icon = $request->has('icon') && $request->icon != 'null' ? $request->icon : null;

        // Generate initial slug
        $slug = generateSlug($request->name, '-');

        // Ensure slug uniqueness
        $count = 1;
        while (Category::where('slug', $slug)->exists()) {
            // If slug exists, append count to make it unique
            $slug = generateSlug($request->name, '-') . '-' . $count;
            $count++;
        }
        // Assign unique slug
        $category->slug = $slug;

        // Store the banner image if provided
        if ($request->hasFile('banner')) {
            $imageName = "b" . Carbon::now()->timestamp . '.' . $request->banner->extension();
            $request->banner->storeAs('category', $imageName);
            $category->banner = $imageName;
        }

        // Set category status based on the request data
        $category->status = $request->has('status') ? 'active' : 'inactive';

        // Set the category position
        $category->position = $request->position;

        // Save the category instance to the database
        $category->save();

        // Return the created category instance
        return $category;
    }

    /**
     * Show the form for editing the specified category.
     *
     * This method checks if the authenticated user is a super admin.
     * If the user is a super admin, it returns the view for managing categories.
     * If the user is not a super admin, it returns the 404 error page.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function categoryEdit($id)
    {
        // Check if the user is a super admin, if not, redirect to 404 page
        if (!canSuperStaffAccess('pse')) {
            return redirect()->route('404');
        }

        // Find the category by ID
        $category = Category::find($id);

        // If category does not exist, redirect back
        if (empty($category)) {
            return redirect()->back();
        }

        // Return the view for editing the category
        return view('superadmin.category.edit', compact('category', 'id'));
    }

    public function categoryUpdate(Request $request, $id)
    {
        // Check if the user is a super admin, if not, redirect to 404 page
        if (!canSuperStaffAccess('pse')) {
            return redirect()->route('404');
        }

        // Define validation rules
        $rules = [
            'name' => 'required',
            'position' => 'required',
            'banner' => 'max:2048'
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // If validation fails, redirect back with error messages
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            // Find the category by ID
            $category = Category::find($id);

            // Update category fields with new values from request
            $category->name = $request->name;
            $category->parent = "0";
            if (isset($request->icon) && $request->icon != 'null') {
                $category->icon = $request->icon;
            }

            if (isNull($category->slug)) {
                // Generate initial slug
                $slug = generateSlug($request->name, '-');

                // Ensure slug uniqueness
                $count = 1;
                while (Category::where('slug', $slug)->exists()) {
                    // If slug exists, append count to make it unique
                    $slug = generateSlug($request->name, '-') . '-' . $count;
                    $count++;
                }
                // Assign unique slug
                $category->slug = $slug;
            } else {
                // Regenerate slug if title is changed or if slug is not unique
                if ($request->name != $category->name) {
                    // Generate initial slug
                    $slug = generateSlug($request->name, '-');
                    // Ensure slug uniqueness
                    $count = 1;
                    while (Category::where('slug', $slug)->exists()) {
                        // If slug exists, append count to make it unique
                        $slug = generateSlug($request->name, '-') . '-' . $count;
                        $count++;
                    }
                    // Assign the new slug
                    $category->slug = $slug;
                }
            }

            if ($request->hasFile('banner')) {
                $imageName = "b" . Carbon::now()->timestamp . '.' . $request->banner->extension();
                $request->banner->storeAs('category', $imageName);
                $category->banner = $imageName;
            }
            $category->status = $request->has('status') ? 'active' : 'inactive';
            $category->position = $request->position;
            $category->save();

            // Set a flash message to indicate successful category update
            Session::flash('message', 'Category Updated Successfully!');

            // Redirect back
            return redirect()->back();
        }
    }

    public function categoryDelete($id)
    {
        // Check if the user is a super admin, if not, redirect to 404 page
        if (!canSuperStaffAccess('pse')) {
            return redirect()->route('404');
        }

        // Find the category by ID
        $category = Category::find($id);

        // If category does not exist, redirect back
        if (empty($category)) {
            return redirect()->back();
        }

        $category->status = "RecycleBin";
        $category->save();

        // Set a flash message to indicate successful category delete
        Session::flash('message', 'PSE Category Delete Successfully !');

        // Redirect back
        return redirect()->back();
    }

    /**
     * Update the position of a category.
     *
     * This method receives a request containing the new position value and the ID
     * of the category to be updated. It validates the input data and updates the
     * position of the category if it exists. If the category or input data is
     * invalid, appropriate error responses are returned.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pseCategoryPosition(Request $request)
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
                'status' => 'Category ID is missing'
            ]);
        }

        // Find the category by ID
        $category = Category::find($id);

        if (!$category) {
            // If category not found, return error response
            return response()->json([
                'data' => $id,
                'status' => 'Category not found'
            ]);
        }

        // Update the category position and save
        $category->position = $value;
        $category->save();

        // Return success response
        return response()->json([
            'data' => $category,
            'status' => 'Category Position Updated Successfully'
        ]);
    }

    /**
     * Update the status (active/inactive) of a category.
     *
     * This method receives a request containing the status value ('on' for active,
     * or any other value for inactive) and the ID of the category to be updated.
     * It validates the input data and updates the status of the category if it exists.
     * If the category or input data is invalid, appropriate error responses are returned.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pseCategoryStatus(Request $request)
    {
        // Extract value and ID from the request
        $value = $request->value;
        $id = $request->id;

        // Validate the input data
        if (empty($id)) {
            // If ID is missing, return error response
            return response()->json([
                'data' => $id,
                'status' => 'Category ID is missing'
            ]);
        }

        if ($value !== 'on') {
            // If value is not 'on', return error response
            return response()->json([
                'data' => $value,
                'status' => 'Invalid status value'
            ]);
        }

        // Find the category by ID
        $category = Category::find($id);

        if (!$category) {
            // If category not found, return error response
            return response()->json([
                'data' => $id,
                'status' => 'Category not found'
            ]);
        }

        // Toggle the category status
        $category->status = ($category->status == 'active') ? 'inactive' : 'active';
        $category->save();

        // Return success response
        return response()->json([
            'data' => $category,
            'status' => 'Category Status Updated Successfully'
        ]);
    }
}
