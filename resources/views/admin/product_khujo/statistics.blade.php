@php
    use App\Models\Pse\PseVisitorCounter;
    use App\Models\Customer;
    use App\Models\Staff;

    $user = Auth::user()->id;
    $user_type = Auth::user()->type;
    if ($user_type == 'admin' || $user_type == 'dropshipper') {
        $customer = Customer::where('uid', $user)->first();
        $store_id = $customer->active_store;
        $customer_id = $customer->id;
    } elseif ($user_type == 'staff') {
        $staff = Staff::where('uid', Auth::user()->id)->first();
        $store_id = $staff->store_id;
        $customer_id = $staff->customer_id;
        $customer = Customer::where('id', $staff->customer_id)->first();
    }

    $totalVisitor = PseVisitorCounter::where('store_id', $store_id)->count();
    $totalProducts = PseVisitorCounter::where('store_id', 14)
        ->groupBy('product_id')
        ->selectRaw('COUNT(product_id) as total_products')
        ->get()
        ->count();
@endphp
<div class="row mb-4 mt-5 justify-content-center">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <a href="#" rel="noopener noreferrer">
            <div class="card  mb-2">
                <div class="card-header p-3 pt-2">
                    <div
                        class="icon icon-lg icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-icons opacity-10">devices</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Total Visitors</p>
                        <h4 class="mb-0">{{ $totalVisitor }}</h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                    <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+55% </span>than last
                        week
                    </p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 mt-sm-0 mt-4">
        <div class="card  mb-2">
            <div class="card-header p-3 pt-2">
                <div
                    class="icon icon-lg icon-shape bg-gradient-primary shadow-primary shadow text-center border-radius-xl mt-n4 position-absolute">
                    <i class="material-icons opacity-10">computer</i>
                </div>
                <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Total Products</p>
                    <h4 class="mb-0">{{ $totalProducts }}</h4>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
                <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+3% </span>than last month
                </p>
            </div>
        </div>
    </div>
</div>
