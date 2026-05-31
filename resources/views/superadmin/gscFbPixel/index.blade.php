@extends('admin.layouts.main')
@section('content')
    <main class="main-content position-relative h-100 border-radius-lg">
        <div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item active">
                                <a href="{{ route('messages') }}">
                                    <img src="{{ URL::to('/') }}/img/cubes.png"> <br> Messages
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-6">
                    <h4>All Google Analytics, Google Search Console & Facebook Pixel</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;"><a href="" class="btn btn-primary"
                                style="display:block;border-radius:0px !important">Create New</a></li>
                        <!--<li><a href="">Import</a></li>-->
                        <li style="padding:0px;border:0px;"><a href="#"
                                style="display:block;border-radius:0px !important" class="btn btn-secondary">Export</a></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-5 productlist">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            All Store name
                        </div>
                        <div class="card-body pt-1" style="height: 65vh; overflow-y: scroll;">
                            <table class="table">
                                @foreach ($storesList as $key => $mmbs)
                                    <tr style="background: #ff5733b0;" class="listdigital">

                                        <td style="text-align:start; line-height: initial;">
                                            <a href="#" style="display:block; color: white;font-size: 20px;"
                                                onclick="details({{ $mmbs->store_id }})">
                                                    <input type="hidden" name="storeName" id="storeName{{ $mmbs->store_id }}" value="{{ $mmbs->storeInfo->name ?? 'No Name' }}">
                                                {{ $mmbs->storeInfo->name ?? 'No Name' }}
                                            </a>
                                            <span style="color: black; font-size: 12px;">{{ $mmbs->created_at }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>

                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">

                        </div>
                        <div class="card-body">
                            <h4 class="text-center" id="store_name">Store Name</h4>

                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <label for="fbPixel">Facebook Pixel</label>
                                    <textarea class="form-control" name="" id="fbPixel" rows="10"></textarea>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="fbPixel">Google Analytics</label>
                                    <textarea class="form-control" name="" id="ga" rows="10"></textarea>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="fbPixel">Google Search Console</label>
                                    <textarea class="form-control" name="" id="gsc" rows="10"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        function details(id) {
            $url = "/gsc-fb-pixel-details";
            name = $('#storeName'+id).val(); 
            $.get($url, {
                id: id
            }, function(data) {
                $('#store_name').html(name);
                $('#fbPixel').val(data.facebook_pixel);
                $('#ga').val(data.google_analytics);
                $('#gsc').val(data.google_search_console);
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            $(".switchstatus").on("change", function() {
                $url = "/changedesignstatus";
                var value = $(this).val();
                console.log(value);
                var id = $(this).data('id');
                console.log(id);
                $.get($url, {
                    value: value,
                    id: id
                }, function(data) {
                    console.log(data);
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#taskfilter").on("keyup", function() {
                debugger;
                var value = $(this).val().toLowerCase();
                debugger;
                $("#taskfilterresult tbody tr").filter(function() {
                    debugger;
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    debugger;
                });
            });
        });

        function exportTasks(_this) {
            let _url = $(_this).data('href');
            window.location.href = _url;
        }
    </script>




    <script>
        jQuery(document).ready(function($) {

            $('.listdigital td').each(function() {
                $(this).attr('data-search-term', $(this).text().toLowerCase());
            });

            $('#live-search-box').on('keyup', function() {

                var searchTerm = $(this).val().toLowerCase();

                $('.listdigital td').each(function() {

                    if ($(this).filter('[data-search-term *= ' + searchTerm + ']').length > 0 ||
                        searchTerm.length < 1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }

                });

            });

        });
    </script>
@endpush
