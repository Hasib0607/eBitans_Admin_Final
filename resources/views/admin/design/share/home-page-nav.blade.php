@php
    $active_design = DB::table('designs')->where('store_id', $store_id)->first();

    $designPosition = DB::table('design_positions')
                            ->where('store_id', $store_id)
                            ->orderBy('position', 'asc')
                            ->get();

    $designData = [
        "hero_slider" => [
            "route" => "slider",
            "bangla" => "স্লাইডার",
            "english" => "Slider",
            "position" => 1,
            "class" => "slider"
        ],
        "banner" => [
            "route" => "banner",
            "bangla" => "ব্যানার",
            "english" => "Banner",
            "position" => 1,
            "class" => "banner"
        ],
        "banner_bottom" => [
            "route" => "banner.bottom",
            "bangla" => "ব্যানার বটম",
            "english" => "Banner Bottom",
            "position" => 1,
            "class" => "banner_bottom"
        ],
        "feature_category" => [
            "route" => "featurecategory",
            "bangla" => "বৈশিষ্ট্য বিভাগ",
            "english" => "Feature Category",
            "position" => 1,
            "class" => "feature_category"
        ],
        "product" => [
            "route" => "product",
            "bangla" => "পণ্য",
            "english" => "Product",
            "position" => 1,
            "class" => "product"
        ],
        "feature_product" => [
            "route" => "featureproduct",
            "bangla" => "বৈশিষ্ট্য পণ্য",
            "english" => "Feature Product",
            "position" => 1,
            "class" => "product_feature"
        ],
        "best_sell_product" => [
            "route" => "bestsellproduct",
            "bangla" => "সেরা বিক্রয় পণ্য",
            "english" => "Best Sell Product",
            "position" => 1,
            "class" => "best_sell_product"
        ],
        "new_arrival" => [
            "route" => "recentaddproduct",
            "bangla" => "নতুন আগমন পণ্য",
            "english" => "New Arrival Product",
            "position" => 1,
            "class" => "new_arrival_product"
        ],
        "testimonial" => [
            "route" => "testimonial",
            "bangla" => "প্রশংসাপত্র",
            "english" => "Testimonial",
            "position" => 1,
            "class" => "testimonial"
        ],
        "youtube" => [
            "route" => "youtube",
            "bangla" => "ইউটিউব",
            "english" => "Youtube",
            "position" => 1,
            "class" => "youtube"
        ],
        "announcement" => [
            "route" => "announcement",
            "bangla" => "ঘোষণা",
            "english" => "Announcement",
            "position" => 1,
            "class" => "announcement"
        ],
        "about" => [
            "route" => "about",
            "bangla" => "সম্পর্কে",
            "english" => "About",
            "position" => 1,
            "class" => "about"
        ],
        "newsletter" => [
            "route" => "newsletter",
            "bangla" => "নিউজ লেটার",
            "english" => "Newsletter",
            "position" => 1,
            "class" => "newsletter"
        ],
        "brand" => [
            "route" => "brand",
            "bangla" => "ব্র্যান্ড",
            "english" => "Brand",
            "position" => 1,
            "class" => "brand"
        ],
        "blog" => [
            "route" => "blog",
            "bangla" => "ব্লগ",
            "english" => "Blog",
            "position" => 1,
            "class" => "blog"
        ],
    ];


    // Create an associative array for easy position updating
    $positionsMap = [];
    foreach ($designPosition as $element) {
        $positionsMap[$element->name] = $element->position ?? 1; // Assuming 'name' corresponds to keys in $designData
    }

    // Update the position in $designData based on the positions from the database
    foreach ($designData as $key => $value) {
        if (array_key_exists($key, $positionsMap)) {
            $designData[$key]['position'] = $positionsMap[$key]; // Update position
        }
    }

    // Step 1: Convert associative array to indexed array for sorting
    $indexedArray = [];
    foreach ($designData as $key => $value) {
        $value['key'] = $key; // Store the original key
        $indexedArray[] = $value; // Add to the indexed array
    }

    // Step 2: Sort the indexed array by 'position'
    usort($indexedArray, function($a, $b) {
        return $a['position'] <=> $b['position']; // Compare based on position
    });

    // Step 3: (Optional) Convert back to associative array if needed
    $sortedDesignData = [];
    foreach ($indexedArray as $item) {
        $sortedDesignData[$item['key']] = $item; // Restore the original keys
    }

@endphp
<style>
    .scrollable-nav {
        height: 70vh !important;
        /*overflow-y: scroll; !* Ensure vertical scrolling *!*/
        /*overflow-y: hidden; !* Hide vertical scrolling *!*/
        /*overflow-x: scroll; !* Enable horizontal scrolling *!*/
        /*white-space: nowrap;*/
        /*padding: 10px;*/
        /*box-sizing: border-box;*/
        /*outline: none;            !* Adjust height as needed *!*/
        overflow-y: auto;
        scroll-behavior: initial;
    }

    /* Custom Scrollbar for WebKit Browsers (e.g., Chrome, Safari) */
    .scrollable-nav::-webkit-scrollbar {
        width: 8px; /* Vertical scrollbar width */
    }

    .scrollable-nav::-webkit-scrollbar-track {
        background: #f1d0c9;
        border-radius: 10px;
    }

    .scrollable-nav::-webkit-scrollbar-thumb {
        background: #dd8d7c;
        border-radius: 10px;
        border: 2px solid transparent;
        background-clip: padding-box;
    }

    .scrollable-nav::-webkit-scrollbar-thumb:hover {
        background: #f1593a;
    }

    #toplist ul li:hover .active > a {
        color: #fff !important;
    }

    #toplist ul li.active:hover > a {
        color: #fff !important;
    }

    .designHeaderLi {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-left: 10px;
        padding-right: 10px;
    }

    .designHeaderLi input {
        width: 50px;
        border: 1px solid #ddd;
    }

    .designHeaderLi input:focus-visible {
        outline: none;
    }

    .designHeaderLi {
        padding-right: 5px !important;
    }

    .designHeaderLi a {
        flex-grow: 1;
    }
</style>

<div class="col-md-3 mt-4 p-0 left-menu card mt-4 scrollable-nav"
     style="background-color: transparent; box-shadow: none; border:none;">
    <div class="card w-100">
        <div class="card-header ">
            <h5>Designs Sections</h5>
        </div>
        <div class="card-body">
            <ul style="padding-left:0rem;">
                @if(!is_null($active_design->header))
                    <li class="@isset($header) active @endisset"
                        style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a
                            href="{{ route('admin.design.homepage.common_designs', ['column'=>'header']) }}"
                            style="display:block">
                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                হেডার
                            @else
                                Header
                            @endif
                        </a></li>
                @endif

                @foreach($sortedDesignData as $key => $elementName)
                    @if(!is_null($active_design->$key))
                        @php
                            $classMatch = $elementName["class"];
                        @endphp

                        <li class="designHeaderLi @isset($$classMatch) active @endisset"
                            style="margin-bottom:10px;border-radius:10px;cursor:pointer">
                            <a href="{{ route('admin.design.homepage.'. $elementName['route']) }}"
                               style="display:block">
                                @if(Session::has('lang') && Session::get('lang') == 'bn')
                                    {{ $elementName['bangla'] }}
                                @else
                                    {{ $elementName['english'] }}
                                @endif
                            </a>
                            @if(ModulusStatus($store_id, 111))
                                <input type="number" value="{{ $elementName['position'] }}" name="position"
                                       onchange="changeHeaderPosition(event, '{{ $key }}')">
                            @endif
                        </li>
                    @endif
                @endforeach

                @if(!is_null($active_design->footer))
                    <li class="@isset($footer) active @endisset"
                        style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a
                            href="{{ route('admin.design.homepage.footer') }}" style="display:block">
                            @if (Session::has('lang') && Session::get('lang') == 'bn')
                                ফুটার
                            @else
                                Footer
                            @endif
                        </a></li>
                @endif
            </ul>
        </div>
    </div>
    @if(ModulusStatus($store_id, 111))
        <div class="card w-100 mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Additional Designs</h5>
                <a href="/cache-clear" class="btn btn-primary" style="padding: 8px 15px;"><i class="fa fa-refresh"
                                                                                             aria-hidden="true"></i></a>
            </div>
            <div class="card-body">
                <ul style="padding-left:0rem;">
                    {{--                    @dd($active_design)--}}
                    @if(!is_null($active_design->single_product_page))
                        <li class="@isset($single_product_page) active @endisset"
                            style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a
                                href="{{ route('admin.design.homepage.additional_designs', ['column'=>'single_product_page']) }}"
                                style="display:block">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    একক পণ্য পেজ
                                @else
                                    Single Product Page
                                @endif
                            </a></li>
                    @endif
                    @if(!is_null($active_design->shop_page))
                        <li class="@isset($shop_page) active @endisset"
                            style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a
                                href="{{ route('admin.design.homepage.additional_designs', ['column'=>'shop_page']) }}"
                                style="display:block">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    শপ পেজ
                                @else
                                    Shop Page
                                @endif
                            </a></li>
                    @endif
                    @if(!is_null($active_design->checkout_page))
                        <li class="@isset($checkout_page) active @endisset"
                            style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a
                                href="{{ route('admin.design.homepage.additional_designs', ['column'=>'checkout_page']) }}"
                                style="display:block">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    চেকআউট পেজ
                                @else
                                    Checkout Page
                                @endif
                            </a></li>
                    @endif
                    @if(!is_null($active_design->login_page))
                        <li class="@isset($login_page) active @endisset"
                            style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a
                                href="{{ route('admin.design.homepage.additional_designs', ['column'=>'login_page']) }}"
                                style="display:block">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    লগইন পেজ
                                @else
                                    Login Page
                                @endif
                            </a></li>
                    @endif
                    @if(!is_null($active_design->product_card))
                        <li class="@isset($product_card) active @endisset"
                            style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a
                                href="{{ route('admin.design.homepage.additional_designs', ['column'=>'product_card']) }}"
                                style="display:block">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    পণ্য কার্ট
                                @else
                                    Product Cart
                                @endif
                            </a></li>
                    @endif
                    @if(!is_null($active_design->preloader))
                        <li class="@isset($preloader) active @endisset"
                            style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a
                                href="{{ route('admin.design.homepage.additional_designs', ['column'=>'preloader']) }}"
                                style="display:block">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    প্রিলোডার
                                @else
                                    Preloader
                                @endif
                            </a></li>
                    @endif
                    @if(!is_null($active_design->mobile_bottom_menu))
                        <li class="@isset($mobile_bottom_menu) active @endisset"
                            style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a
                                href="{{ route('admin.design.homepage.additional_designs', ['column'=>'mobile_bottom_menu']) }}"
                                style="display:block">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    মোবাইলের নিচের মেনু
                                @else
                                    Mobile Bottom Menu
                                @endif
                            </a></li>
                    @endif
                    @if(!is_null($active_design->offer))
                        <li class="@isset($offer) active @endisset"
                            style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a
                                href="{{ route('admin.design.homepage.additional_designs', ['column'=>'offer']) }}"
                                style="display:block">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    অফার
                                @else
                                    Offer
                                @endif
                            </a></li>
                    @endif
                    @if(isset($active_design->contact) && !is_null($active_design->contact))
                        <li class="@isset($contact) active @endisset"
                            style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a
                                href="{{ route('admin.design.homepage.additional_designs', ['column'=>'contact']) }}"
                                style="display:block">
                                @if (Session::has('lang') && Session::get('lang') == 'bn')
                                    কন্টাক্ট
                                @else
                                    Contact
                                @endif
                            </a></li>
                    @endif
                </ul>
            </div>
        </div>
    @endif
</div>
<script>
    // Wait until the DOM is loaded
    document.addEventListener('DOMContentLoaded', function () {
        const scrollContainer = document.querySelector('.scrollable-nav');

        // Function to scroll the element vertically
        function handleScroll(event) {
            event.preventDefault(); // Prevent the default scrolling behavior
            scrollContainer.scrollTop += event.deltaY; // Scroll vertically based on mouse wheel movement
        }

        // Attach mouse wheel event listener
        scrollContainer.addEventListener('wheel', handleScroll);
    });

</script>

@push('scripts')
    <script !src="">

        // Change design header position
        const changeHeaderPosition = (event, name) => {
            const position = event.target.value;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: "{{ route('admin.change.design.header.position') }}",
                data: {
                    name: name,
                    position: position
                },
                success: function (data) {
                    if (data.status) {
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }
    </script>
@endpush
