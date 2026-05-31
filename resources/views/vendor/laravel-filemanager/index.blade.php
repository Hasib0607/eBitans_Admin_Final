<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=EDGE"/>
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="#333844">
    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="#333844">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-status-bar-style" content="#333844">

    <title>{{ trans('laravel-filemanager::lfm.title-page') }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('vendor/laravel-filemanager/img/72px color.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-ui-dist@1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/laravel-filemanager/css/cropper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/laravel-filemanager/css/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/laravel-filemanager/css/mime-icons.min.css') }}">
    <style>{!! \File::get(base_path('vendor/unisharp/laravel-filemanager/public/css/lfm.css')) !!}</style>
    {{-- Use the line below instead of the above if you need to cache the css. --}}
    {{-- <link rel="stylesheet" href="{{ asset('/vendor/laravel-filemanager/css/lfm.css') }}"> --}}

    <style>
        #fileManagerCKEditorModal {
            z-index: 10099 !important; /* Higher than default Bootstrap modal */
        }

        /*.modal-backdrop.show {*/
        /*    z-index: 10099 !important; !* Ensure backdrop also appears below modal *!*/
        /*    display: none !important;*/
        /*    opacity: 0 !important;*/
        /*}*/
        #tree .m-3.d-block.d-lg-none {
            display: none !important;
        }

        .copy-btn {
            font-size: 0.75rem;
            padding: 4px 10px;
            transition: all 0.2s ease;
            color: #ffffff;
            border-color: #ffffff;
        }

        button.btn.btn-sm.ml-2.copy-btn.btn-outline-primary {
            box-shadow: none;
        }
    </style>
</head>
<body>
<nav class="navbar sticky-top navbar-expand-lg navbar-dark" id="nav">
    <a class="navbar-brand invisible-lg d-none d-lg-inline" id="to-previous">
        <i class="fas fa-arrow-left fa-fw"></i>
        <span class="d-none d-lg-inline">{{ trans('laravel-filemanager::lfm.nav-back') }}</span>
    </a>
    <a class="navbar-brand d-block d-lg-none" id="show_tree">
        <i class="fas fa-bars fa-fw"></i>
    </a>
    <a class="navbar-brand d-block d-lg-none" id="current_folder"></a>
    <a id="loading" class="navbar-brand"><i class="fas fa-spinner fa-spin"></i></a>
    <div class="ml-auto px-2">
        <a class="navbar-link d-none" id="multi_selection_toggle">
            <i class="fa fa-check-double fa-fw"></i>
            <span class="d-none d-lg-inline">{{ trans('laravel-filemanager::lfm.menu-multiple') }}</span>
        </a>
    </div>
    <a class="navbar-toggler collapsed border-0 px-1 py-2 m-0" data-toggle="collapse" data-target="#nav-buttons">
        <i class="fas fa-cog fa-fw"></i>
    </a>
    <div class="collapse navbar-collapse flex-grow-0" id="nav-buttons">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-display="grid">
                    <i class="fas fa-th-large fa-fw"></i>
                    <span>{{ trans('laravel-filemanager::lfm.nav-thumbnails') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-display="list">
                    <i class="fas fa-list-ul fa-fw"></i>
                    <span>{{ trans('laravel-filemanager::lfm.nav-list') }}</span>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                   aria-expanded="false">
                    <i class="fas fa-sort fa-fw"></i>{{ trans('laravel-filemanager::lfm.nav-sort') }}
                </a>
                <div class="dropdown-menu dropdown-menu-right border-0"></div>
            </li>
        </ul>
    </div>
</nav>

<nav class="bg-light fixed-bottom border-top d-none" id="actions">
    <a data-action="open" data-multiple="false"><i
            class="fas fa-folder-open"></i>{{ trans('laravel-filemanager::lfm.btn-open') }}</a>
    <a data-action="preview" data-multiple="true"><i
            class="fas fa-images"></i>{{ trans('laravel-filemanager::lfm.menu-view') }}</a>
    <a data-action="use" data-multiple="true"><i
            class="fas fa-check"></i>{{ trans('laravel-filemanager::lfm.btn-confirm') }}</a>
</nav>

<div class="d-flex flex-row">
    <div id="tree"></div>

    <div id="main">
        <div id="alerts"></div>

        <nav aria-label="breadcrumb" class="d-none d-lg-block" id="breadcrumbs">
            <ol class="breadcrumb">
                <li class="breadcrumb-item invisible">Home</li>
            </ol>
        </nav>

        <div id="empty" class="d-none">
            <i class="far fa-folder-open"></i>
            {{ trans('laravel-filemanager::lfm.message-empty') }}
        </div>

        <div id="content"></div>
        <div id="pagination"></div>

        <a id="item-template" class="d-none">
            <div class="square"></div>

            <div class="info">
                <div class="item_name text-truncate"></div>
                <time class="text-muted font-weight-light text-truncate"></time>
            </div>
        </a>
    </div>

    <div id="fab"></div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">{{ trans('laravel-filemanager::lfm.title-upload') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aia-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('custom.lfm.upload') }}" role='form' id='uploadForm' name='uploadForm'
                      method='post' enctype='multipart/form-data' class="dropzone">
                    <div class="form-group" id="attachment">
                        <div class="controls text-center">
                            <div class="input-group w-100">
                                <a class="btn btn-primary w-100 text-white"
                                   id="upload-button">{{ trans('laravel-filemanager::lfm.message-choose') }}</a>
                            </div>
                        </div>
                    </div>
                    <input type='hidden' name='working_dir' id='working_dir'>
                    <input type='hidden' name='type' id='type' value='{{ request("type") }}'>
                    <input type='hidden' name='_token' value='{{csrf_token()}}'>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary w-100"
                        data-dismiss="modal">{{ trans('laravel-filemanager::lfm.btn-close') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="notify" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary w-100"
                        data-dismiss="modal">{{ trans('laravel-filemanager::lfm.btn-close') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary w-100"
                        data-dismiss="modal">{{ trans('laravel-filemanager::lfm.btn-close') }}</button>
                <button type="button" class="btn btn-primary w-100"
                        data-dismiss="modal">{{ trans('laravel-filemanager::lfm.btn-confirm') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="dialog" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary w-100"
                        data-dismiss="modal">{{ trans('laravel-filemanager::lfm.btn-close') }}</button>
                <button type="button" class="btn btn-primary w-100"
                        data-dismiss="modal">{{ trans('laravel-filemanager::lfm.btn-confirm') }}</button>
            </div>
        </div>
    </div>
</div>

<div id="carouselTemplate" class="d-none carousel slide bg-light" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#previewCarousel" data-slide-to="0" class="active"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <a class="carousel-label"></a>
            <div class="carousel-image"></div>
        </div>
    </div>
    <a class="carousel-control-prev" href="#previewCarousel" role="button" data-slide="prev">
        <div class="carousel-control-background" aria-hidden="true">
            <i class="fas fa-chevron-left"></i>
        </div>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#previewCarousel" role="button" data-slide="next">
        <div class="carousel-control-background" aria-hidden="true">
            <i class="fas fa-chevron-right"></i>
        </div>
        <span class="sr-only">Next</span>
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.2.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-ui-dist@1.12.1/jquery-ui.min.js"></script>
<script src="{{ asset('vendor/laravel-filemanager/js/cropper.min.js') }}"></script>
<script src="{{ asset('vendor/laravel-filemanager/js/dropzone.min.js') }}"></script>
<script>
    var lang = {!! json_encode(trans('laravel-filemanager::lfm')) !!};
    var actions = [
        // {
        //   name: 'use',
        //   icon: 'check',
        //   label: 'Confirm',
        //   multiple: true
        // },
        // {
        //     name: 'rename',
        //     icon: 'edit',
        //     label: lang['menu-rename'],
        //     multiple: false
        // },
        {
            name: 'download',
            icon: 'download',
            label: lang['menu-download'],
            multiple: true
        },
        // {
        //   name: 'preview',
        //   icon: 'image',
        //   label: lang['menu-view'],
        //   multiple: true
        // },
        {
            name: 'move',
            icon: 'paste',
            label: lang['menu-move'],
            multiple: true
        },
        {
            name: 'resize',
            icon: 'arrows-alt',
            label: lang['menu-resize'],
            multiple: false
        },
        {
            name: 'crop',
            icon: 'crop',
            label: lang['menu-crop'],
            multiple: false
        },
        {
            name: 'trash',
            icon: 'trash',
            label: lang['menu-delete'],
            multiple: true
        },
    ];

    var sortings = [
        {
            by: 'alphabetic',
            icon: 'sort-alpha-down',
            label: lang['nav-sort-alphabetic']
        },
        {
            by: 'time',
            icon: 'sort-numeric-down',
            label: lang['nav-sort-time']
        }
    ];
</script>
<script>{!! \File::get(base_path('vendor/unisharp/laravel-filemanager/public/js/script.js')) !!}</script>
{{-- Use the line below instead of the above if you need to cache the script. --}}
{{-- <script src="{{ asset('vendor/laravel-filemanager/js/script.js') }}"></script> --}}
<script>
    Dropzone.options.uploadForm = {
        paramName: "upload[]", // The name that will be used to transfer the file
        uploadMultiple: false,
        parallelUploads: 5,
        timeout: 0,
        clickable: '#upload-button',
        dictDefaultMessage: lang['message-drop'],
        init: function () {
            var _this = this; // For the closure
            let successCount = 0;
            let totalCount = 0;

            this.on('addedfile', function () {
                totalCount++;
            });


            this.on('success', function (file, response) {
                if (response == 'OK') {
                    successCount++;
                    loadFolders();
                } else {
                    if (typeof response === 'object' && response.error) {
                        this.defaultOptions.error(file, response.error.message);
                    } else {
                        this.defaultOptions.error(file, response.join('\n'));
                    }
                }
            });

            //Handle when all files in the queue are done
            this.on('queuecomplete', function () {
                if (successCount === totalCount && totalCount > 0) {
                    // All successful
                    $("#uploadModal").modal('hide');
                    this.removeAllFiles(true);

                    // Optional: reset counters
                    successCount = 0;
                    totalCount = 0;
                }
            });
        },
        headers: {
            'Authorization': 'Bearer ' + getUrlParam('token')
        },
        acceptedFiles: "{{ implode(',', $helper->availableMimeTypes()) }}",
        maxFilesize: ({{ $helper->maxUploadSize() }} / 1000)
    }


    function trash(items) {
        let message = items.length > 1
            ? "⚠️ You are about to delete multiple files or folders.\n\nIf any of these are used in product images or other content, they may break or show ‘file not found’ errors.\n\nAre you sure you want to continue?"
            : "⚠️ Deleting this file/folder may break product images or cause ‘file not found’ errors if it's in use.\n\nAre you sure you want to delete it?";

        confirm(message, function () {
            performLfmRequest('delete', {
                items: items.map(function (item) {
                    return item.name;
                })
            }).done(refreshFoldersAndItems);
        });
    }


    $(document).on('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && !multi_selection_enabled) {
            multi_selection_enabled = true;
        }
    });

    $(document).on('keyup', function (e) {
        // When Ctrl or Cmd is released, disable multi-select
        if (!(e.ctrlKey || e.metaKey) && multi_selection_enabled) {
            multi_selection_enabled = false;
        }
    });

    function preview(items) {
        var carousel = $('#carouselTemplate').clone().attr('id', 'previewCarousel').removeClass('d-none');
        var imageTemplate = carousel.find('.carousel-item').clone().removeClass('active');
        var indicatorTemplate = carousel.find('.carousel-indicators > li').clone().removeClass('active');
        carousel.children('.carousel-inner').html('');
        carousel.children('.carousel-indicators').html('');
        carousel.children('.carousel-indicators,.carousel-control-prev,.carousel-control-next').toggle(items.length > 1);

        items.forEach(function (item, index) {
            var carouselItem = imageTemplate.clone()
                .addClass(index === 0 ? 'active' : '');

            if (item.thumb_url) {
                carouselItem.find('.carousel-image').css('background-image', 'url(\'' + item.url + '?timestamp=' + item.time + '\')');
            } else {
                carouselItem.find('.carousel-image').css('width', '50vh').append($('<div>').addClass('mime-icon ico-' + item.icon));
            }

            carouselItem.find('.carousel-label').attr('target', '_blank').attr('href', item.url)
                .text(item.name)
                .append($('<i class="fas fa-external-link-alt ml-2"></i>'))
                .append($('<button>')
                    .addClass('btn btn-sm btn-outline-primary ml-2 copy-btn')
                    .text('Copy URL')
                    .attr('data-url', item.url)
                );

            carousel.children('.carousel-inner').append(carouselItem);

            var carouselIndicator = indicatorTemplate.clone()
                .addClass(index === 0 ? 'active' : '')
                .attr('data-slide-to', index);
            carousel.children('.carousel-indicators').append(carouselIndicator);
        });


        // carousel swipe control
        var touchStartX = null;

        carousel.on('touchstart', function (event) {
            var e = event.originalEvent;
            if (e.touches.length == 1) {
                var touch = e.touches[0];
                touchStartX = touch.pageX;
            }
        }).on('touchmove', function (event) {
            var e = event.originalEvent;
            if (touchStartX != null) {
                var touchCurrentX = e.changedTouches[0].pageX;
                if ((touchCurrentX - touchStartX) > 60) {
                    touchStartX = null;
                    carousel.carousel('prev');
                } else if ((touchStartX - touchCurrentX) > 60) {
                    touchStartX = null;
                    carousel.carousel('next');
                }
            }
        }).on('touchend', function () {
            touchStartX = null;
        });
        // end carousel swipe control

        // Clipboard copy
        carousel.on('click', '.copy-btn', function (e) {
            e.preventDefault();
            const btn = $(this);
            const url = btn.data('url');

            // Copy to clipboard
            navigator.clipboard.writeText(url).then(() => {
                const originalText = btn.text();
                btn.text('Copied!');
                btn.removeClass('btn-outline-primary').addClass('btn-success');

                setTimeout(() => {
                    btn.text(originalText);
                    btn.removeClass('btn-success').addClass('btn-outline-primary');
                }, 1500);
            }).catch(() => {
                Swal.fire('⚠️ Failed to copy URL.', '', 'error');
            });
        });


        notify(carousel);
    }

</script>
</body>
</html>
