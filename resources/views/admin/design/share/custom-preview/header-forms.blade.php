<?php
$lists = DB::table("menus")->where('store_id', $store_id)->orderBy("sort")->get();

$menulist = [
    'Home',
    'Shop',
    'About',
    'Contact',
    'Category',
    'Blog',
    'Helps',
    'Offer',
];

$menus = array();

if (isset($lists) && count($lists) > 0) {
    foreach ($lists as $list) {
        $menus[$list->url == "" ? "home" : $list->url] = $list;
    }
} else {
    $userData = getUserData();
    $user_id = $userData['user_id'] ?? NULL;
    $customer_id = $userData['customer_id'] ?? NULL;

    foreach ($menulist as $menu) {
        $slug = $menu == "Home" || $menu == "home" ? '' : generateSlug($menu);

        \App\Models\Menu::create([
            'store_id' => $store_id,
            'uid' => $user_id,
            'url' => $slug,
            'name' => $menu,
            'status' => 0,
            'sort' => 1,
            'custom_link' => NULL,
            'creator' => $user_id,
            'editor' => $user_id,
            'customer_id' => $customer_id,
        ]);
    }

    $lists = DB::table("menus")->where('store_id', $store_id)->orderBy("sort")->get();

    foreach ($lists as $list) {
        $menus[$list->url == "" ? "home" : $list->url] = $list;
    }
}

?>

@push('styles')
    <style>
        .form-group {
            display: flex;
        }

        label.position {
            width: 17%;
        }

        label.position input {
            width: 24% !important;
            min-width: 40px;
        }

        label.link input {
            width: 100% !important;
        }

        .menuInput {
            width: 100%;
            margin-right: -10px;
            min-width: 90px;
            max-width: 45%;
        }
    </style>
@endpush

<form action="{{route('admin.header_design_save')}}" method="post" style="padding-bottom: 20px;">
    @csrf
    <div class="mb-3 row">
        <div class="col-md-6">
            <label for="" class="pe-2">
                Background Color:
            </label>
            <input type="color" id="head" name="headercolor" value="{{$active_design->header_color ?? '#fff'}}"
                   style="width:150px;">
            @error('headercolor')
            <p class="text-danger" role="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="" class="pe-2">
                Text Color:
            </label>
            <input type="color" id="head" name="textcolor" value="{{$active_design->text_color ?? '#000'}}"
                   style="width:150px;">
            @error('textcolor')
            <p class="text-danger" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-md-12 mt-3" id="menuContainer">
            <div class="form-group" style="gap: 60px">
                <label for="Home" class="menuname"
                       style="text-align:center;width:100%">@if(Session::has('lang') && Session::get('lang')=='bn')
                        মেনু নাম
                    @else
                        Menu Name
                    @endif</label>
                <label for="" class="menuposition"
                       style="text-align:center;margin-left: 80px;">@if(Session::has('lang') && Session::get('lang')=='bn')
                        অবস্থান
                    @else
                        Position
                    @endif</label>
                <label for="" class="customLink"
                       style="text-align:center;width: 100%; margin-right: 40px;">@if(Session::has('lang') && Session::get('lang')=='bn')
                        Custome Link
                    @else
                        কাস্টম লিঙ্ক
                    @endif</label>
            </div>
            @if(count($menus) > 0)
                @foreach($menus as $key => $menu)
                    <div class="form-group ">
                        <input type="checkbox" id="{{ $menu->name }}" name="menus[{{$key}}][active]"
                               @if(isset($menu->status) && $menu->status == 1) checked @endif value="{{ $menu->name }}">
                        <label for="{{$menu->name}}" class="menuInput">
                            <input type="text" name="menus[{{$key}}][name]" class="menunamefield"
                                   value="{{ $menu->name ?? '' }}">
                        </label>
                        <input type="hidden" name="menus[{{$key}}][id]" value="{{ $menu->id }}">
                        <label for="" class="position">
                            <input type="number" name="menus[{{$key}}][sort]"
                                   class="menupositionfield"
                                   value="{{$menu->sort ?? '1'}}"
                                   style="width:50%">
                        </label>
                        <label for="" class="link">
                            <input type="text" name="menus[{{$key}}][custom_link]"
                                   class="menupositionfield"
                                   value="{{ $menu->custom_link ?? '' }}"
                                   style="width:50%">
                        </label>
                        @if(!in_array($menu->name, $menulist))
                            <a href="javascript:void(0);"
                               onclick="confirmDelete('{{ route('admin.header_menu_delete', ['id' => $menu->id]) }}')"
                               style="margin-left: 5px;"><img
                                    src="{{ URL::to('/') }}/img/delete.png"
                                    alt="" width="30px"></a>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
        <div class="form-group px-3 d-flex flex-row justify-content-between flex-sm-row align-items-center">
            <button type="submit" class="btn btn-info mt-3">
                @if(Session::has('lang') && Session::get('lang')=='bn')
                    সাবমিট
                @else
                    Submit
                @endif
            </button>
            <button class="btn btn-primary mt-3" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="Add Menu" id="add-menu">
                @if(Session::has('lang') && Session::get('lang')=='bn')
                    মেনু যোগ করুন
                @else
                    Add More Menu
                @endif
            </button>
        </div>
    </div>
</form>
@push('scripts')
    <script !src="">
        document.addEventListener('DOMContentLoaded', function () {
            const menuContainer = document.getElementById('menuContainer');
            const addMenuButton = document.getElementById('add-menu');

            let menuIndex = {{ count($menus) }}; // Start from the current number of menus

            // Function to add a new menu item
            addMenuButton.addEventListener('click', function (e) {
                e.preventDefault();
                const newMenu = document.createElement('div');
                newMenu.classList.add('form-group', 'menu-item');
                newMenu.innerHTML = `
            <input type="checkbox" id="menu_${menuIndex}" name="menus[${menuIndex}][active]" value="">
            <label for="menu_${menuIndex}" class="menuInput">
                <input type="text" name="menus[${menuIndex}][name]" class="menunamefield" value="">
            </label>
            <input type="hidden" name="menus[${menuIndex}][id]" value="">
            <label class="position">
                <input type="number" name="menus[${menuIndex}][sort]" class="menupositionfield" value="1" style="width:50%">
            </label>
            <label class="link">
                <input type="text" name="menus[${menuIndex}][custom_link]" class="menupositionfield" value="" style="width:50%">
            </label>
            <a href="javascript:void(0)" class="remove-menu" style="margin-left: 5px;"><img
                                                    src="{{ URL::to('/') }}/img/delete.png"
                                                    alt="" width="30px"></a>
        `;
                menuContainer.appendChild(newMenu);
                menuIndex++; // Increment the index for the next menu
            });

            // Event delegation to handle removing menu items
            menuContainer.addEventListener('click', function (event) {
                if (event.target.closest('.remove-menu')) { // Check if the clicked element or its parent is a "remove-menu" button
                    const menuItem = event.target.closest('.menu-item'); // Get the closest menu-item container
                    if (menuItem) menuItem.remove(); // Remove the menu item
                }
            });

        });


        function confirmDelete(url) {
            swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
            }).then((result) => {
                if (result.value) {
                    window.location.href = url;
                }
            });
        }

    </script>
@endpush
