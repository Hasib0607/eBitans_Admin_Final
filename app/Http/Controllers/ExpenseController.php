<?php

namespace App\Http\Controllers;


use App\Models\Expense;
use App\Models\ExpenseCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|string',
            'category' => 'required|string|max:100',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $user_id = auth()->id();

        $expense = new Expense();
        $expense->description = $request->description;
        $expense->amount = $request->amount;
        $expense->date = $request->date;
        $expense->category = $request->category;
        $expense->notes = $request->notes ?? NULL;
        $expense->user_id = $user_id;
        $expense->save();

        return sendResponse("Expense added successfully!");
    }

    public function ajaxIndex(Request $request)
    {
        $search = $request->input('search');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        $query = Expense::with("category")
            ->where('user_id', auth()->id())
            ->orderBy('date', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%$search%")
                    ->orWhere('amount', 'like', "%$search%")
                    ->orWhere('notes', 'like', "%$search%")
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            });
        }


        if ($from_date && !$to_date) {
            $query->where('date', '>=', $from_date);
        } elseif (!$from_date && $to_date) {
            $query->where('date', '<=', $to_date);
        } elseif ($from_date && $to_date) {
            $query->whereBetween('date', [$from_date, $to_date]);
        }

        // Get total sum before pagination
        $total = $query->sum('amount');

        // Paginate after getting the total
        $expenses = $query->paginate(10);

        return response()->json([
            'data' => $expenses->items(),
            'current_page' => $expenses->currentPage(),
            'last_page' => $expenses->lastPage(),
            'total' => $total // Use the pre-paginated total
        ]);
    }

    public function destroy(Expense $expense)
    {
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }

        $expense->delete();

        return sendResponse("Expense deleted successfully!");
    }


    public function getExpanseCategory()
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];

        return ExpenseCategory::where('store_id', $store_id)->get();
    }

    public function storeExpanseCategory(Request $request)
    {
        $userData = getUserData();
        $store_id = $userData['store_id'];

        $request->validate([
            'categoryName' => 'required|string|max:255',
        ]);

        $category = ExpenseCategory::where("id", $request->categoryId)->first();
        if (!isset($category)) {
            $category = new ExpenseCategory();
        }

        $category->name = $request->categoryName;
        $category->store_id = $store_id;
        $category->save();

        return response()->json($category, 201);
    }

    public function deleteExpanseCategory($id)
    {
        // Check if ID is empty
        if (empty($id)) {
            return response()->json([
                'success' => false,
                'message' => 'Category ID is required'
            ], 400);
        }

        // Find the category
        $category = ExpenseCategory::find($id);

        // Check if category exists
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        // Delete the category
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

}
