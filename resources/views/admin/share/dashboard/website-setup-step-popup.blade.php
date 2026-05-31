<div class="row" id="websitesettptour">
    <div class="col-md-12 mt-1">
        <div class="p-3">
            <div class="row guide-row">
                <a class="col-md-4 col-sm-2 guide-card" href="{{ route('admin.addproducts') }}">
                        <span class="guide-card-logo">
                            <i class="material-icons text-danger text-gradient">shopping_cart</i>
                        </span>
                    <div class="guide-card-content">
                        <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0">
                                <span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        পণ্য যোগ করুন
                                    @else
                                        Product Add
                                    @endif
                                    </span>

                        </h6>
                    </div>
                </a>

                <a class="col-md-4 col-sm-2 guide-card" href="{{ route('admin.setting') }}">
                        <span class="guide-card-logo">
                            <i class="material-icons text-info text-gradient">settings</i>
                        </span>
                    <div class="guide-card-content">
                        <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0">
                            <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    লোগো যোগ করুন
                                @else
                                    Add Logo
                                @endif
                                </span>
                        </h6>
                    </div>
                </a>

                <a class="col-md-4 col-sm-2 guide-card" href="{{ route('admin.design.theme') }}">
                        <span class="guide-card-logo">
                            <i class="material-icons text-primary text-gradient">palette</i>
                        </span>
                    <div class="guide-card-content">
                        <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0">
                            <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    ওয়েবসাইট ডিজাইন
                                @else
                                    Website Design
                                @endif
                                </span>

                        </h6>
                    </div>
                </a>

                <a class="col-md-4 col-sm-2 guide-card" href="{{ route('admin.order') }}">
                        <span class="guide-card-logo">
                            <i class="material-icons text-success text-gradient">local_shipping</i>
                        </span>
                    <div class="guide-card-content">
                        <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0">
                                    <span class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            অর্ডার ম্যানেজমেন্ট
                                        @else
                                            Order Management
                                        @endif
                                    </span>
                        </h6>
                    </div>
                </a>
                <a class="col-md-4 col-sm-2 guide-card" href="{{ route('admin.report') }}">
                    <span class="guide-card-logo">
                            <i class="material-icons text-warning text-gradient">insert_chart</i>
                        </span>
                    <div class="guide-card-content">
                        <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0">
                                <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                        ররিপোর্ট
                                    @else
                                        Report
                                    @endif
                                </span>
                        </h6>
                    </div>
                </a>

                <a class="col-md-4 col-sm-2 guide-card" href="{{ route('admin.domain') }}">
                        <span class="guide-card-logo">
                            <i class="material-icons text-dark text-gradient">vpn_lock</i>
                        </span>
                    <div class="guide-card-content">
                        <h6 class="cursor-pointer text-dark text-sm font-weight-bold mb-0">
                            <span class="nav-link-text ms-1">
                                    @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    ডোমেন যোগ করা হয়েছে
                                @else
                                    Domain Added
                                @endif
                                </span>
                        </h6>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

