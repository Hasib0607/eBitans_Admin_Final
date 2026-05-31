<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\AdPse;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\AdPseStoreRequest;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Category;
use Carbon\Carbon;

class PseAdSuperAdminController extends Controller
{

    protected function getAllCategory()
    {
        return Category::select('categories.*')
            ->withCount('products')
            ->whereNull('categories.store_id')
            ->whereNull('categories.customer_id')
            ->where('categories.status', '!=', 'RecycleBin')
            ->groupBy('categories.id', 'categories.name')
            ->get();
    }
    public function AdPse()
    {
        if (Auth::user()->type == 'superadmin') {
            $getAllAds = AdPse::select('ads_pse.*')->orderBy('position', 'asc')->paginate(20);
            return view('superadmin.ad.index', compact('getAllAds'));
        }
    }
    public function AdCreatePse()
    {
        if (Auth::user()->type == 'superadmin') {
            $catagories = $this->getAllCategory();
            return view('superadmin.ad.create', compact('catagories'));
        }
    }

    public function AdPseStore(AdPseStoreRequest $request)
    {
        $image_type = (int) $request->image_type;

        if (is_null($image_type)) {
            Session::flash('message', 'Please select the image type');
            return redirect()->back();
        }

        $imgName = Carbon::now()->timestamp . '.' . $request->banner->extension();

        AdPse::create([
            'name' => strtolower($request->name),
            'link' => $request->link,
            'category_id' => $request->category_id,
            'banner' => $imgName,
            'position' => $request->position,
            'image_type' => $image_type,
            'status' => $request->status == 'on' ? 1 : 0,
        ]);

        $request->banner->storeAs('ads_pse_image', $imgName);

        Session::flash('message', 'Ad Save Successfully!');
        return redirect()->route('superadmin.pse.ad');
    }

    public function AdEditPse(Request $request, $id)
    {
        if (Auth::user()->type == 'superadmin') {
            $adGet = AdPse::find($id);

            if (!empty($adGet)) {
                $categories = $this->getAllCategory();

                return view('superadmin.ad.edit', compact('adGet', 'categories'));
            } else {
                Session::flash('message', 'Update Ad\'s!');
                return redirect()->route('superadmin.pse.ad');
            }
        }
    }

    public function AdUpdatePse(Request $request, $id)
    {
        if (Auth::user()->type == 'superadmin') {
            $findAd = AdPse::find($id);

            if (empty($findAd)) {
                Session::flash('message', 'Ad not found!');
                return redirect()->back();
            }

            // Delete the old banner file if it exists
            if ($request->hasFile('banner') && $findAd->banner) {
                $oldImagePath = public_path('assets/images/ads_pse_image/' . $findAd->banner);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $imgName = null;

            if ($request->hasFile('banner')) {
                $imgName = Carbon::now()->timestamp . '.' . $request->banner->extension();
                $request->banner->move(public_path('assets/images/ads_pse_image'), $imgName);
            }

            $image_type = (int) $request->image_type;

            if (is_null($image_type)) {
                Session::flash('message', 'Please select the image type');
                return redirect()->back();
            }

            $findAd->update([
                'name' => strtolower($request->name),
                'link' => $request->link,
                'category_id' => $request->category_id,
                'banner' => $imgName ?: $findAd->banner,
                'position' => $request->position,
                'image_type' => $image_type,
                'status' => $request->status == 'on' ? 1 : 0,
            ]);

            Session::flash('message', 'Ad Update Successfully!');
            return redirect()->route('superadmin.pse.ad');
        }
    }
    public function AdPseStatus(Request $request)
    {
        $adGetFromPse = AdPse::where('id', $request->id)->first();
        if (isset($adGetFromPse) && $adGetFromPse->status == 1) {
            $adGetFromPse->status = 0;
            $status = 'Ad inactive Successfully !';
        } else {
            $adGetFromPse->status = 1;
            $status = 'Ad active Successfully !';
        }
        $adGetFromPse->save();

        return response()->json(['data' => $adGetFromPse, 'status' => $status]);
    }

    public function AdPsePosition(Request $request)
    {
        $indexNumber = $request->value;
        $adFindPosition = AdPse::where('id', $request->id)->first();
        if (!empty($adFindPosition)) {
            $adFindPosition->position = $indexNumber;
            $adFindPosition->save();
            $status = 'Ad position save Successfully !';
        } else {
            return response()->json(['data' => $request->id, 'status' => 'Somethig wants wrong.']);
        }

        return response()->json(['data' => $adFindPosition, 'status' => $status]);
    }

    public function AdDeleteFromPse($id)
    {
        if (Auth::user()->type == 'superadmin') {
            $adFindById = AdPse::find($id);
            if (!empty($adFindById)) {
                $adFindById->delete();
                $message = 'Ad Delete Successfully !';
            } else {
                return response()->json(['data' => $id, 'status' => 'Somethig wants wrong.']);
            }

            return response()->json(['data' => $adFindById, 'status' => 200, 'message' => $message]);
        }
    }
}
