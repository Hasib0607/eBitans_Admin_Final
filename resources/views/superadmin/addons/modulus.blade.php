@extends('admin.layouts.main')
@section('content')
    {{-- styles --}}
    @include('superadmin.addons.share.style')

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        {{-- addons navbar --}}
        @include('superadmin.addons.share.addons-nav', ['modulus'=>true])

        <div class="container-fluid mt-4" id="toplist">

            <div class="row">
                {{--modulus header--}}
                <div class="col-md-6">
                    <h4>All Modulus</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;">
                            <a href="javascript:void(0)" class="btn btn-primary" onclick="createNew()"
                               style="display:block;border-radius:0px !important">Create New</a></li>
                    </ul>
                </div>
                {{--modulus create modal--}}
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" action="{{route('superadmin.modulus.store')}}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="modal-content">
                                {{--modulus create modal header--}}
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Create Modulus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                </div>
                                {{--modulus create modal body and input fields--}}
                                <div class="modal-body">

                                    <div class="form-group py-3">
                                        <label for="name">Modulus Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value=""
                                               placeholder="Enter modulus name">
                                    </div>

                                    <div class="form-group py-3">
                                        <label for="title">Modulus Subtitle</label>
                                        <input type="text" class="form-control" id="title" name="title" value=""
                                               placeholder="Enter modulus title">
                                    </div>

                                    <input type="checkbox" id="config_status" name="config_status"> <label
                                        for="config_status">Configurable</label>
                                    <select class='form-control' name="modulus_type" id="modulus_type">
                                        <option value="0" selected>Addon</option>
                                        <option value="1">Marketing</option>
                                    </select>
                                    <div class="form-group py-3">
                                        <label for="price">Modulus price</label>
                                        <input type="number" min="0" step="0.01" class="form-control" id="price"
                                               name="price" value=""
                                               placeholder="Enter modulus price">
                                    </div>
                                    <div class="form-group py-3">
                                        <label for="price_usd">Modulus price(USD)</label>
                                        <input type="number" min="0" step="0.01" class="form-control" id="price_usd"
                                               name="price_usd" value=""
                                               placeholder="Enter modulus usd price">
                                    </div>

                                    <div class="form-group py-3">
                                        <label for="image">Modulus Thumbnail</label>
                                        <input type="file" class="form-control" id="image" name="image" value=""
                                               placeholder="Enter modulus title">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group py-3">
                                                <label for="rating">Modulus Ratting</label>
                                                <input type="number" class="form-control" id="rating" name="rating"
                                                       value=""
                                                       placeholder="Enter modulus rating">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group py-3">
                                                <label for="no_of_rating">No of Ratting</label>
                                                <input type="number" class="form-control" id="no_of_rating"
                                                       name="no_of_rating" value=""
                                                       placeholder="Enter modulus no_of_rating">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group py-3">
                                                <label for="no_of_user">No of User</label>
                                                <input type="number" class="form-control" id="no_of_user"
                                                       name="no_of_user" value=""
                                                       placeholder="Enter modulus no_of_user">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group py-3">
                                                <label for="review">Review</label>
                                                <input type="number" class="form-control" id="review" name="review"
                                                       value=""
                                                       placeholder="Enter modulus review">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group py-3">
                                        <label for="position">Position</label>
                                        <input type="number" class="form-control" value="0" id="position"
                                               name="position"
                                               placeholder="Position">
                                    </div>

                                    <div class="form-group py-3">
                                        <input type="checkbox" id="status" name="status" value="1"> <label
                                            for="status">Status</label>
                                    </div>


                                </div>
                                {{--modulus create modal footer and buttons--}}
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close
                                    </button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            {{--modulus list main card--}}
            <div class="row mt-5 productlist">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            {{--mudulus success alert--}}
                            @if (Session::has('success_message'))
                                <div class="alert alert-success">{{Session::get('success_message')}}</div>
                            @endif
                            {{--modulus table list--}}
                            <div class="table-responsive">
                                <table class="table" id="taskfilterresult" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="4%"><input type="checkbox"></th>
                                        <th width="5%">Modulus Name</th>
                                        <th width="20%">thumbnail</th>
                                        <th width="10%">Price</th>
                                        <th width="10%">Type</th>
                                        <th width="10%">Modulus Type</th>
                                        <th width="10%">Position</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($data) && count($data)>0)
                                        {{--modulus table rows--}}
                                        @foreach($data as $key=>$dm)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                {{--modulus update modal--}}
                                                <div class="modal fade" id="exampleModal{{$key}}" tabindex="-1"
                                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <form method="post"
                                                              action="{{route('superadmin.modulus.store')}}"
                                                              enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-content">
                                                                {{--modulus update modal header--}}
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Edit
                                                                        Modulus</h5>
                                                                    <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                </div>
                                                                {{--modulus update modal body and input fields--}}
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="id"
                                                                           value="{{$dm->id ?? ''}}">
                                                                    <div class="form-group py-3">
                                                                        <label for="name">Modulus Name</label>
                                                                        <input type="text" class="form-control"
                                                                               id="name" name="name"
                                                                               value="{{$dm->name ?? ""}}"
                                                                               placeholder="Enter modulus title">
                                                                    </div>
                                                                    <div class="form-group py-3">
                                                                        <label for="title">Modulus Subtitle</label>
                                                                        <input type="text" class="form-control"
                                                                               id="title" name="title"
                                                                               value="{{$dm->title ?? ""}}"
                                                                               placeholder="Enter modulus title">
                                                                    </div>
                                                                    <input type="checkbox" id="config_status_edit"
                                                                           name="config_status" {{ $dm->config_status == 1 ? 'checked':'' }}>
                                                                    <label for="config_status_edit">Configurable</label>
                                                                    <select class='form-control' name="modulus_type"
                                                                            id="modulus_type">
                                                                        <option value="0"
                                                                                @if($dm->modulus_type == 0) selected @endif>
                                                                            Addon
                                                                        </option>
                                                                        <option value="1"
                                                                                @if($dm->modulus_type == 1) selected @endif>
                                                                            Marketing
                                                                        </option>
                                                                    </select>
                                                                    <div class="form-group py-3">
                                                                        <label for="price">Modulus price</label>
                                                                        <input type="number" class="form-control"
                                                                               id="price" name="price" min="0"
                                                                               step="0.01"
                                                                               value="{{$dm->price ?? ""}}"
                                                                               placeholder="Enter modulus price">
                                                                    </div>

                                                                    <div class="form-group py-3">
                                                                        <label for="price_usd">Modulus
                                                                            price(USD)</label>
                                                                        <input type="number" min="0" step="0.01"
                                                                               class="form-control"
                                                                               id="price_usd" name="price_usd"
                                                                               value="{{$dm->price_usd ?? ""}}"
                                                                               placeholder="Enter modulus usd price">
                                                                    </div>

                                                                    <div class="form-group py-3">
                                                                        <label for="image">modulus Thumbnail</label>
                                                                        <br>
                                                                        <div style="text-align: center;">
                                                                            <img
                                                                                src="{{ asset('modulus/'.$dm->image) }}"
                                                                                class="zoom p-3" width="30%">
                                                                        </div>


                                                                        <input type="file" class="form-control"
                                                                               id="image" name="image" value=""
                                                                               placeholder="Enter modulus title">
                                                                    </div>


                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group py-3">
                                                                                <label for="rating">Modulus
                                                                                    Ratting</label>
                                                                                <input type="number"
                                                                                       class="form-control" id="rating"
                                                                                       name="rating"
                                                                                       value="{{  $dm->rating??''  }}"
                                                                                       placeholder="Enter modulus rating">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group py-3">
                                                                                <label for="no_of_rating">No of
                                                                                    Ratting</label>
                                                                                <input type="number"
                                                                                       class="form-control"
                                                                                       id="no_of_rating"
                                                                                       name="no_of_rating"
                                                                                       value="{{  $dm->no_of_rating??''  }}"
                                                                                       placeholder="Enter modulus no_of_rating">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group py-3">
                                                                                <label for="no_of_user">No of
                                                                                    User</label>
                                                                                <input type="number"
                                                                                       class="form-control"
                                                                                       id="no_of_user" name="no_of_user"
                                                                                       value="{{  $dm->no_of_user??''  }}"
                                                                                       placeholder="Enter modulus no_of_user">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group py-3">
                                                                                <label for="review">Review</label>
                                                                                <input type="number"
                                                                                       class="form-control" id="review"
                                                                                       name="review"
                                                                                       value="{{  $dm->review??''  }}"
                                                                                       placeholder="Enter modulus review">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group py-3">
                                                                        <label for="position">Position</label>
                                                                        <input type="number" class="form-control"
                                                                               value="{{ $dm->position }}" id="position"
                                                                               name="position"
                                                                               placeholder="Position">
                                                                    </div>

                                                                    <input type="checkbox" id="status_edit"
                                                                           name="status"
                                                                           value="1" {{ $dm->status == 1 ? 'checked':'' }}>
                                                                    <label for="status_edit">Status</label>

                                                                </div>
                                                                {{--modulus update footer and buttons--}}
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Close
                                                                    </button>
                                                                    <button type="submit" class="btn btn-primary">Save
                                                                        changes
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                {{--modulus table row's column--}}
                                                <td><input type="checkbox" name="id" value="{{$dm->id ?? ''}}"></td>
                                                <td>
                                                    {{$dm->name ?? ""}}
                                                </td>
                                                <td>
                                                    <img src="{{ asset('modulus/'.$dm->image) }}" class="zoom"
                                                         width="50px">
                                                </td>

                                                <td> {{$dm->price ?? ""}}</td>
                                                <td> {{$dm->type == 0 ? 'One Time': 'Subcriptions'}}</td>
                                                <td> {{$dm->modulus_type == 0 ? 'Addon': 'Marketing'}}</td>
                                                <td> {{$dm->position}}</td>
                                                {{--modulas status toggle button--}}
                                                <td>
                                                    <div class="form-check form-switch" style="text-align:center;">
                                                        <input class="form-check-input switchstatus" type="checkbox"
                                                               data-id="{{$dm->id}}"
                                                               id="flexSwitchCheckChecked"
                                                               @if($dm->status== 1) checked=""
                                                               @endif style="margin:0 auto;">
                                                        <label class="form-check-label"
                                                               for="flexSwitchCheckChecked"></label>
                                                    </div>
                                                </td>
                                                {{--modulus edit button--}}
                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-info"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#exampleModal{{$key}}">Edit</a>
                                                    {{-- <a href="javascript:void(0)" class="btn btn-primary">Delete</a> --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
    {{--modulus active or deactive toggle functions--}}
    <script>
        $(document).ready(function () {
            $(".switchstatus").on("change", function () {
                $url = "{{ route('superadmin.modulus.status') }}";
                var value = $(this).val();
                var id = $(this).data('id');

                $.get($url, {value: value, id: id}, function (data) {
                    if (data) {
                        Swal.fire(
                            'Congratulations Mr. {{ auth()->user()->name }}!',
                            'Successfuly Change modulus status',
                            'success'
                        )
                    }
                });
            });
        });
    </script>
    {{--modulus create modal handle--}}
    <script>
        function createNew() {
            $('#exampleModal').modal('toggle');
        }
        {{--will remove future--}}
        // $(document).ready(function () {
        //   $("#taskfilter").on("keyup", function () {
        //     var value = $(this).val().toLowerCase();
        //     $("#taskfilterresult tbody tr").filter(function () {
        //       $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        //     });
        //   });
        //
        // });
        //
        // function exportTasks(_this) {
        //   let _url = $(_this).data('href');
        //   window.location.href = _url;
        // }
    </script>
@endpush
