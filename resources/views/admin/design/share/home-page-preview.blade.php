@php
    $current_page = explode('/',Request::path())[2];
    $current_page2 = explode('/',Request::path())[3] ?? "";
@endphp
<style>
    .center-cell {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .tutorial-section {
        background-color: #F1593A;
        color: #ffffff;
        min-height: 12vh;
        border-radius: 10px;
        font-size: 18px;
        font-weight: bold;
    }

    .preview-section {
        background-image: url("{{ URL::to('/') }}/assets/images/bg-layer.png");
        object-fit: cover;
        background-position: center;
        min-height: 40vh;
        background-repeat: no-repeat;
        background-size: 24vw 45vh;
    }

    .preview-section img {
        border: 1px solid dimgray;
    }

    .detail-section {
        padding: 30px !important;
    }

    .detail-section .header {
        font-weight: bold;
        font-size: large;
    }

    .detail-section .description {
        font-size: small;
        font-style: italic;
    }

    .right-menu {
        height: 70vh !important;
        /*overflow-y: scroll; !* Ensure vertical scrolling *!*/
        /*overflow-y: hidden; !* Hide vertical scrolling *!*/
        /*overflow-x: scroll; !* Enable horizontal scrolling *!*/
        /*white-space: nowrap;*/
        padding: 10px;
        box-sizing: border-box;
        outline: none; /* Adjust height as needed */
        overflow-y: auto;
        scroll-behavior: initial;
    }

    /* Custom Scrollbar for WebKit Browsers (e.g., Chrome, Safari) */
    .right-menu::-webkit-scrollbar {
        width: 8px; /* Vertical scrollbar width */
    }

    .right-menu::-webkit-scrollbar-track {
        background: #f1d0c9;
        border-radius: 10px;
    }

    .right-menu::-webkit-scrollbar-thumb {
        background: #dd8d7c;
        border-radius: 10px;
        border: 2px solid transparent;
        background-clip: padding-box;
    }

    .right-menu::-webkit-scrollbar-thumb:hover {
        background: #f1593a;
    }

    @media only screen and (max-width: 600px) {
        .preview-section {
            background-size: 70vw 35vh;
            min-height: 30vh;
        }
    }
</style>
<div class="col-md-4 mt-4 p-0 right-menu card mt-4"
     style="background-color: transparent; box-shadow: none; border:none;">
    <div class="card">
        <div class="card-body center-cell tutorial-section">
            <div><i class="fa fa-play mr-2" aria-hidden="true"></i> Tutorial</div>

        </div>
    </div>
    <div class="card mt-2">
        <div class="card-body preview-section center-cell">
            <img id="preview-design" width="100%" src="{{ URL::to('/') }}/assets/images/default-preview-design.png"
                 alt="">
        </div>
    </div>
    <div class="card mt-2 card-details">
        <div class="card-body detail-section">
            <div class="header">Design Details</div>
            <input type="hidden" id="current-page" value="{{$current_page}}">

            @if($current_page == 'header')
                @include('admin.design.share.custom-preview.header-forms')
            @elseif($current_page == 'banner')
                @include('admin.design.share.custom-preview.ads-banners')
            @elseif($current_page == 'slider')
                @include('admin.design.share.custom-preview.slider-preview')
            @elseif($type == 'checkout_page')
                @include('admin.design.share.custom-preview.checkout-form')
            @else
                <form action="{{route('admin.design.store_design_save')}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="{{$type}}">
                    <input type="hidden" name="store_id" value="{{$store_id}}">
                    @include('admin.design.share.common.store_design_input')
                    <button class="d-none btn btn-primary" id="storeDesignSubmit" type="submit">Save</button>
                </form>
            @endif
        </div>
    </div>
</div>

<script>

    // Wait until the DOM is loaded
    document.addEventListener('DOMContentLoaded', function () {
        const scrollContainer = document.querySelector('.right-menu');

        // Function to scroll the element vertically
        function handleScroll(event) {
            event.preventDefault(); // Prevent the default scrolling behavior
            scrollContainer.scrollTop += event.deltaY; // Scroll vertically based on mouse wheel movement
        }

        // Attach mouse wheel event listener
        scrollContainer.addEventListener('wheel', handleScroll);
    });

    function isPath(value) {
        return /^https?:\/\//.test(value) || value.startsWith('storage/');
    }

    function getPath(value, folder = null) {
        const base = window.location.origin.replace(/\/$/, ''); // Similar to env("APP_URL")
        let path = value.replace(/^\/+|\/+$/g, ''); // Trim slashes

        if (isPath(value)) {
            return `${base}/${path}`;
        } else {
            if (folder) {
                folder = folder.replace(/^\/+|\/+$/g, '');
                return `${base}/${folder}/${path}`;
            }
            return `${base}/${path}`;
        }
    }


    $(document).ready(function () {
        // Use mouseover event
        $('.design_preview').on('mouseover', function () {
            var imageUrl = $(this).data('img');
            var design = $(this).data('design');
            var storeDesign = $(this).data('storedesign');
            if (!imageUrl) {
                imageUrl = 'default-preview-design.png';
            }
            $('#preview-design').attr('src', getPath(imageUrl, 'assets/images/design'));
            $('.titleInput').addClass('d-none');
            $('.subtitleInput').addClass('d-none');
            $('.buttonInput').addClass('d-none');
            $('.button1Input').addClass('d-none');
            $('.linkInput').addClass('d-none');
            $('.bgImageInput').addClass('d-none');
            $('.card-details').addClass('d-none');
            var currentPage = $('#current-page').val();

            var title = storeDesign?.title ? storeDesign?.title : design.title;
            var title_color = storeDesign?.title_color ? storeDesign?.title_color : design.title_color;

            var subtitle = storeDesign?.subtitle ? storeDesign?.subtitle : design.subtitle;
            var subtitle_color = storeDesign?.subtitle_color ? storeDesign?.subtitle_color : design.subtitle_color;

            var button = storeDesign?.button ? storeDesign?.button : design.button;
            var button_color = storeDesign?.button_color ? storeDesign?.button_color : design.button_color;
            var button_bg_color = storeDesign?.button_bg_color ? storeDesign?.button_bg_color : design.button_bg_color;

            var button1 = storeDesign?.button1 ? storeDesign?.button1 : design.button1;
            var button1_color = storeDesign?.button1_color ? storeDesign?.button1_color : design.button1_color;
            var button1_bg_color = storeDesign?.button1_bg_color ? storeDesign?.button1_bg_color : design.button1_bg_color;

            var image_description = storeDesign?.image_description ? storeDesign?.image_description : design.image_description;
            var bg_image = storeDesign?.bg_image ? storeDesign?.bg_image : design.bg_image;

            var link = typeof storeDesign?.link === "string"
                ? storeDesign.link
                : typeof design?.link === "string"
                    ? design.link
                    : "";

            var linkCheck = typeof design?.link === "string" ? design.link : null;


            if (currentPage == 'header' || currentPage == 'banner' || currentPage == 'slider' || currentPage == 'youtube' || currentPage == 'brand') {
                $('.card-details').removeClass('d-none')
            }

            var currentPage2 = '{{ $current_page2 }}';
            if (currentPage2 == 'checkout_page') {
                $('.card-details').removeClass('d-none')
            }

            if (design.title != null) {
                $('#storeDesignSubmit').removeClass('d-none')
                $('.titleInput').removeClass('d-none')
                $('#title').attr('value', title);
                $('#title_color').attr('value', title_color);
            }
            if (design.subtitle != null) {
                $('#storeDesignSubmit').removeClass('d-none')
                $('.subtitleInput').removeClass('d-none')
                $('#subtitle').attr('value', subtitle);
                $('#subtitle_color').attr('value', subtitle_color);
            }
            if (design.button != null) {
                $('#storeDesignSubmit').removeClass('d-none')
                $('.buttonInput').removeClass('d-none')
                $('#button').attr('value', button);
                $('#button_color').attr('value', button_color);
                $('#button_bg_color').attr('value', button_bg_color);
            }
            if (design.button1 != null) {
                $('#storeDesignSubmit').removeClass('d-none')
                $('.button1Input').removeClass('d-none')
                $('#button1').attr('value', button1);
                $('#button1_color').attr('value', button1_color);
                $('#button1_bg_color').attr('value', button1_bg_color);
            }
            if (design.image_description != null) {
                $('.card-details').removeClass('d-none')
                $('#storeDesignSubmit').removeClass('d-none')
                $('p#image_description').html(image_description);
            }
            if (design.bg_image != null) {
                $('#storeDesignSubmit').removeClass('d-none');
                $('.bgImageInput').removeClass('d-none');
                $('#bgImageElement').show();

                // Correct way to set the src attribute using jQuery
                $('#bgImageElement').attr('src', getPath(bg_image, 'assets/images/design'));
            } else {
                // If bg_image is null, hide the image element
                $('#bgImageElement').hide();
            }

            if (linkCheck != null) {
                $('#storeDesignSubmit').removeClass('d-none')
                $('.linkInput').removeClass('d-none')
                $('#link').val(link);
            }
        });

        // Mouseleave event to show the checked image
        $('.design_preview').on('mouseleave', function () {
            var checkedImageUrl = $('.design_radio:checked').data('img');
            var design = $('.design_radio:checked').data('design');
            var storeDesign = $(this).data('storedesign');
            if (!checkedImageUrl) {
                checkedImageUrl = 'default-preview-design.png';
            }
            $('#preview-design').attr('src', getPath(checkedImageUrl, 'assets/images/design'));
            $('.titleInput').addClass('d-none');
            $('.subtitleInput').addClass('d-none');
            $('.buttonInput').addClass('d-none');
            $('.button1Input').addClass('d-none');
            $('.linkInput').addClass('d-none');
            $('.bgImageInput').addClass('d-none');
            $('.card-details').addClass('d-none');
            var currentPage = $('#current-page').val();

            if (currentPage == 'header' || currentPage == 'banner' || currentPage == 'slider' || currentPage == 'youtube' || currentPage == 'brand') {
                $('.card-details').removeClass('d-none')
            }

            var currentPage2 = '{{ $current_page2 }}';
            if (currentPage2 == 'checkout_page') {
                $('.card-details').removeClass('d-none')
            }

            var title = storeDesign?.title ? storeDesign?.title : design.title;
            var title_color = storeDesign?.title_color ? storeDesign?.title_color : design.title_color;

            var subtitle = storeDesign?.subtitle ? storeDesign?.subtitle : design.subtitle;
            var subtitle_color = storeDesign?.subtitle_color ? storeDesign?.subtitle_color : design.subtitle_color;

            var button = storeDesign?.button ? storeDesign?.button : design.button;
            var button_color = storeDesign?.button_color ? storeDesign?.button_color : design.button_color;
            var button_bg_color = storeDesign?.button_bg_color ? storeDesign?.button_bg_color : design.button_bg_color;

            var button1 = storeDesign?.button1 ? storeDesign?.button1 : design.button1;
            var button1_color = storeDesign?.button1_color ? storeDesign?.button1_color : design.button1_color;
            var button1_bg_color = storeDesign?.button1_bg_color ? storeDesign?.button1_bg_color : design.button1_bg_color;

            var image_description = storeDesign?.image_description ? storeDesign?.image_description : design.image_description;
            var bg_image = storeDesign?.bg_image ? storeDesign?.bg_image : design.bg_image;

            var link = typeof storeDesign?.link === "string"
                ? storeDesign.link
                : typeof design?.link === "string"
                    ? design.link
                    : "";

            var linkCheck = typeof design?.link === "string" ? design.link : null;


            if (design.title != null) {
                $('#storeDesignSubmit').removeClass('d-none')
                $('.titleInput').removeClass('d-none')
                $('#title').attr('value', title);
                $('#title_color').attr('value', title_color);
            }
            if (design.subtitle != null) {
                $('#storeDesignSubmit').removeClass('d-none')
                $('.subtitleInput').removeClass('d-none')
                $('#subtitle').attr('value', subtitle);
                $('#subtitle_color').attr('value', subtitle_color);
            }
            if (design.button != null) {
                $('#storeDesignSubmit').removeClass('d-none')
                $('.buttonInput').removeClass('d-none')
                $('#button').attr('value', button);
                $('#button_color').attr('value', button_color);
                $('#button_bg_color').attr('value', button_bg_color);
            }
            if (design.button1 != null) {
                $('#storeDesignSubmit').removeClass('d-none')
                $('.button1Input').removeClass('d-none')
                $('#button1').attr('value', button1);
                $('#button1_color').attr('value', button1_color);
                $('#button1_bg_color').attr('value', button1_bg_color);
            }
            if (design.bg_image != null) {
                $('#storeDesignSubmit').removeClass('d-none');
                $('.bgImageInput').removeClass('d-none');
                $('#bgImageElement').show();

                // Correct way to set the src attribute using jQuery
                $('#bgImageElement').attr('src', getPath(bg_image, 'assets/images/design'));
            } else {
                // If bg_image is null, hide the image element
                $('#bgImageElement').hide();
            }

            if (design.image_description != null) {
                $('.card-details').removeClass('d-none')
                $('#storeDesignSubmit').removeClass('d-none')
                $('p#image_description').html(image_description);
            }
            if (linkCheck != null) {
                $('#storeDesignSubmit').removeClass('d-none')
                $('.linkInput').removeClass('d-none')
                $('#link').val(link);
            }
        });

        // Trigger mouseover event on page load for the checked radio button
        $('.design_radio:checked').trigger('mouseover');
    });
</script>
