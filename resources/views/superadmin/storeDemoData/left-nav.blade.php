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

<div class="col-md-3 p-0 left-menu card scrollable-nav"
     style="background-color: transparent; box-shadow: none; border:none;">
    <div class="card w-100">
        {{--        <div class="card-header ">--}}
        {{--            <h5>Menu</h5>--}}
        {{--        </div>--}}
        <div class="card-body pb-0 pt-3">
            <ul style="padding-left:0rem;">
                <li class="@if(request()->routeIs('superadmin.store.category.list') ||
                            request()->routeIs('superadmin.store.category.create') ||
                            request()->routeIs('superadmin.store.edit.category')
                            ) active @endif"
                    style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a
                        href="{{ route('superadmin.store.category.list') }}"
                        style="display:block">
                        Category
                    </a>
                </li>
                <li class="@if(request()->routeIs('superadmin.store.product.list') ||
                            request()->routeIs('superadmin.store.product.create') ||
                            request()->routeIs('superadmin.store.edit.product')) active @endif"
                    style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a
                        href="{{ route('superadmin.store.product.list') }}"
                        style="display:block">
                        Product
                    </a>
                </li>
                <li class="@if(request()->routeIs('superadmin.store.slider.list') ||
                            request()->routeIs('superadmin.store.slider.create') ||
                            request()->routeIs('superadmin.store.edit.slider')) active @endif"
                    style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a
                        href="{{ route('superadmin.store.slider.list') }}"
                        style="display:block">
                        Slider
                    </a>
                </li>
                <li class="@if(request()->routeIs('superadmin.store.banner.list') ||
                            request()->routeIs('superadmin.store.banner.create') ||
                            request()->routeIs('superadmin.store.edit.banner')) active @endif"
                    style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a
                        href="{{ route('superadmin.store.banner.list') }}"
                        style="display:block">
                        Banner
                    </a>
                </li>
                <li class="@if(request()->routeIs('superadmin.store.theme.list') ||
                            request()->routeIs('superadmin.store.theme.create') ||
                            request()->routeIs('superadmin.store.edit.theme')) active @endif"
                    style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a
                        href="{{ route('superadmin.store.theme.list') }}"
                        style="display:block">
                        Theme
                    </a>
                </li>
                <li class="@if(request()->routeIs('superadmin.store.header.list') ||
                            request()->routeIs('superadmin.store.header.create') ||
                            request()->routeIs('superadmin.store.edit.header')) active @endif"
                    style="margin-bottom:10px;border-radius:10px;cursor:pointer"><a
                        href="{{ route('superadmin.store.header.list') }}"
                        style="display:block">
                        Header
                    </a>
                </li>
            </ul>
        </div>
    </div>
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
