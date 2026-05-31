<style>
    .table thead th {
        padding: 8px;
    }

    .form-control-sm {
        padding: 0.25rem 0.75rem !important;
    }

    .form-group label {
        color: #000;
    }
</style>

<?php
$userData = getUserData();
$store_id = $userData['store_id'];

$lists = DB::table("checkout_forms")->where('store_id', $store_id)->get();

$checkout = array();
foreach ($lists as $list) {
    $checkout[$list->name] = $list->status;
}
?>


<form action="{{ route('admin.save.design.checkout.form') }}" method="post">
    @csrf
    <div class="mb-3 row mt-2">
        <div class="col-md-12">
            <div class="form-group">
                <label for="Home" class="menuname"
                       style="text-align:center;color:#7b809a">@if(Session::has('lang') && Session::get('lang')=='bn')
                        চেকআউট ফর্ম ক্ষেত্র
                    @else
                        Checkout Form Field
                    @endif</label>
            </div>
            <div class="form-group ">
                <input type="hidden" id="Name" name="checkbox[name]" value="0">
                <input type="checkbox" id="Name" name="checkbox[name]"
                       @if(isset($checkout['name']) && $checkout['name'] == 1) checked
                       @endif value="1">
                <label for="Name">Name</label>
            </div>
            <div class="form-group ">
                <input type="hidden" id="Phone" name="checkbox[phone]" value="0">
                <input type="checkbox" id="Phone" name="checkbox[phone]"
                       @if(isset($checkout['phone']) && $checkout['phone'] == 1) checked
                       @endif value="1">
                <label for="Phone">Phone</label>
            </div>
            <div class="form-group ">
                <input type="hidden" id="Email" name="checkbox[email]" value="0">
                <input type="checkbox" id="Email" name="checkbox[email]"
                       @if(isset($checkout['email']) && $checkout['email'] == 1) checked
                       @endif value="1">
                <label for="Email">Email</label>
            </div>
            <div class="form-group ">
                <input type="hidden" id="Address" name="checkbox[address]" value="0">
                <input type="checkbox" id="Address" name="checkbox[address]"
                       @if(isset($checkout['address']) && $checkout['address'] == 1) checked
                       @endif value="1">
                <label for="Address">Address</label>
            </div>
            <div class="form-group">
                <input type="hidden" id="Note" name="checkbox[note]" value="0">
                <input type="checkbox" id="Note" name="checkbox[note]"
                       @if(isset($checkout['note']) && $checkout['note'] == 1) checked @endif value="1">
                <label for="Note">Note</label>
            </div>
            <div class="form-group ">
                <input type="hidden" id="District" name="checkbox[district]" value="0">
                <input type="checkbox" id="District" name="checkbox[district]"
                       @if(isset($checkout['district']) && $checkout['district'] == 1) checked
                       @endif value="1">
                <label for="District">District</label>
            </div>
            <div class="form-group ">
                <input type="hidden" id="Language" name="checkbox[language]" value="0">
                <input type="checkbox" id="Language" name="checkbox[language]"
                       @if(isset($checkout['language']) && $checkout['language'] == 1) checked
                       @endif value="1">
                <label for="Language">Language</label>
            </div>
        </div>
        <div class="form-group px-3 d-flex flex-column flex-sm-row align-items-center">
            <button type="submit" class="btn btn-info mt-3">
                @if(Session::has('lang') && Session::get('lang')=='bn')
                    সাবমিট
                @else
                    Submit
                @endif
            </button>
        </div>
    </div>
</form>
