<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\Plan;
use App\Models\Posplan;
use App\Models\Product;
use App\Models\Staff;
use App\Models\Store;
use App\Models\Veriant;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Storage;

class ImportProduct implements ToCollection, WithHeadingRow
{


    public function collection(Collection $rows)
    {

        foreach ($rows as $request) {
            $qut = 0;

            //  $imagePath = Storage::putFile('images', $request['images']); // Assuming 'image' is the column name for the image in your XLSX file


            $user = Auth::user()->id;
            $user_type = Auth::user()->type;
            if ($user_type == "admin" || $user_type == "dropshipper") {
                $customer = Customer::where('uid', $user)->first();
                $store_id = $customer->active_store;
                $customer_id = $customer->id;
            } else {
                $staff = Staff::where('uid', Auth::user()->id)->first();
                $store_id = $staff->store_id;
                $customer_id = $staff->customer_id;
            }
            $store_id = $store_id;
            $store = Store::find($store_id);
            $limit = 0;
            if ($store->plan_id != 'NULL') {
                $plan = Plan::find($store->plan_id);
                if ($store->expiry_date >= Carbon::now()) {
                    if (isset($store->pos_plan_id)) {
                        if ($store->pos_plan_expiry_date >= Carbon::now()) {
                            $posplan = Posplan::find($store->pos_plan_id);
                            if ($plan->product > $posplan->product) {
                                $limit = $plan->product;
                            } else {
                                $limit = $posplan->product;
                            }
                        } else {
                            $limit = $plan->product;
                        }
                    } else {
                        $limit = $plan->product;
                    }
                } else {
                    $limit = $limit;
                }
            } else {
                if (isset($store->pos_plan_id)) {
                    if ($store->pos_plan_expiry_date >= Carbon::now()) {
                        $posplan = Posplan::find($store->pos_plan_id);
                        $limit = $posplan->product;
                    } else {
                        $limit = $limit;
                    }
                } else {
                    $limit = $limit;
                }
            }

            if (isset($store->digital_plan_id)) {
                if ($store->plan_id != 'NULL') {
                    $plan = Plan::find($store->plan_id);
                    if ($store->expiry_date >= Carbon::now()) {
                        if (isset($store->pos_plan_id)) {
                            if ($store->pos_plan_expiry_date >= Carbon::now()) {
                                $posplan = Posplan::find($store->pos_plan_id);
                                if ($plan->product > $posplan->product) {
                                    $limit = $plan->product;
                                } else {
                                    $limit = $posplan->product;
                                }
                            } else {
                                $limit = $plan->product;
                            }
                        } else {
                            $limit = $plan->product;
                        }
                    } else {
                        $limit = $limit;
                    }
                } else {
                    if ($store->digital_plan_end_date >= Carbon::now()) {
                        $limit = 50;
                    } else {
                        $limit = $limit;
                    }
                }
            }


            $store_id = $store_id;
            $store = Store::find($store_id);

            $plan = Plan::find($store->plan_id);
            if ($store->expiry_date >= Carbon::now()) {
                if (isset($store->pos_plan_id)) {
                    if ($store->pos_plan_expiry_date >= Carbon::now()) {
                        $posplan = Posplan::find($store->pos_plan_id);
                        if ($plan->product > $posplan->product) {
                            $limit = $plan->product;
                        } else {
                            $limit = $posplan->product;
                        }
                    } else {
                        $limit = $plan->product;
                    }
                } else {
                    $limit = $plan->product;
                }
            } else {
                $limit = 0;
            }

            $proCount = Product::where('store_id', $store_id)->where('status', 'active')->count();

            // dd($proCount);
            if ($limit <= $proCount) {
                Session::flash('error', 'Please update your package to add more products.!');
                return back();
            }

            $allproduct = Product::where('store_id', $store_id)->where('status', '!=', 'RecycleBin')->count();
            if ($allproduct > $limit) {
                Session::flash('error', 'Product Add Limit Reacted');
                return back()->withInput();
            }

            $produt = Product::where('SKU', $request['sku'])->where('store_id', $store_id)->first();

            // dd($request->all());
            $user = Auth::user()->id;
            $user_type = Auth::user()->type;
            if ($user_type == "admin" || $user_type == "dropshipper") {
                $customer = Customer::where('uid', $user)->first();
                $store_id = $customer->active_store;
                $customer_id = $customer->id;
            } else {
                $staff = Staff::where('uid', Auth::user()->id)->first();
                $store_id = $staff->store_id;
                $customer_id = $staff->customer_id;
            }
            $store_id = $store_id;
            $store = Store::find($store_id);
            $plan = Plan::find($store->plan_id);


            if (empty($request['sku'])) {
                $sku = 'SKU' . mt_rand(1000, 9999) . time();
            } else {
                $sku = $request['sku'];
            }

            $produt = Product::where('SKU', $sku)->where('store_id', $store_id)->first();
            if (isset($produt)) {
                Session::flash('error', 'SKU Already Taken !');
                return back()->withInput();
            }

            if ($request['category'] == "Select") {
                Session::flash('error', 'Category Must be Given !');
                return back()->withInput();
            }

            $product = new Product;

            if (empty($request['name'])) {
                $name = 'Product name not available';
            } else {
                $name = $request['name'];
            }

            $product->name = $name;
            $product->description = $request['description'];
            $product->regular_price = $request['regular_price'];
            $product->discount_type = $request['discount_type'];
            $product->promotional_price = $request['promotional_price'];
            $product->quantity = $request['quantity'];
            $product->seo_keywords = $request['seo_keywords'];
            $product->cost = $request['cost'];
            $product->currency_id = $store->currency;
            $product->pse = 0;
            // $product->images   = $imagePath;

            $id = date('y') . rand(1, 10000);
            $product->barcode = $id;

            if (isset($request['image'])) {
                foreach ($request['image'] as $key => $image) {
                    $imgName = Carbon::now()->timestamp . $key . '.' . $image->extension();
                    $image->storeAs('product', $imgName);
                    $imagesname[] = $imgName;
                }
                $product['images'] = implode(',', $imagesname);
            } else {
                $product->images = 'demo.webp';
            }

            $product->images = 'demo.webp';

            $product->category = $request['category'];
            $product->subcategory = $request['subcategory'];
            $product->tags = $request['seo_keywords'];
            $product->status = "active";
            $product->SKU = $sku;

            $product->feature = 0;

            $user = Auth::user()->id;
            $user_type = Auth::user()->type;

            $store_id = $store_id;
            $product->uid = $user;
            $product->customer_id = $customer_id;
            $product->store_id = $store_id;
            $product->creator = $user;
            $product->editor = $user;
            $product->save();
        }
    }
}
