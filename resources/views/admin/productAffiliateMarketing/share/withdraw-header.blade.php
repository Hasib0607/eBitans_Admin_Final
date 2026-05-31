@php
    $current_page = explode('/',Request::path());
    if (isset($current_page[2])){
        $current_page = $current_page[2];
    }
    else{
        $current_page = 'all';
    }
@endphp
<div class="row">
    <div class="col-md-6">
        <h4>{{ucwords($current_page)}} Request List</h4>
    </div>
    <div class="col-md-6">
        <ul>
            <li style="padding:0px;border:0px;">
                <a href="{{ route('admin.product_affiliate.withdraw_requests.rejected') }}"
                   class="btn @if($current_page == 'rejected') btn-secondary @else btn-primary @endif"
                   style="display:block;border-radius:0px !important">Rejected List</a>
            </li>
            <li style="padding:0px;border:0px;">
                <a href="{{ route('admin.product_affiliate.withdraw_requests.pending') }}"
                   class="btn @if($current_page == 'pending') btn-secondary @else btn-primary @endif"
                   style="display:block;border-radius:0px !important">Pending List</a>
            </li>
            <li style="padding:0px;border:0px;">
                <a href="{{ route('admin.product_affiliate.withdraw_requests.approved') }}"
                   class="btn @if($current_page == 'approved') btn-secondary @else btn-primary @endif"
                   style="display:block;border-radius:0px !important">Approved List</a>
            </li>
        </ul>
    </div>
</div>
