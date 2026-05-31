@extends('admin.layouts.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css">

    <style>
        .colToText {
            width: 3% !important;
            padding: 0;
            flex: unset;
        }

        .tdInnerText {
            font-weight: 900;
            margin-bottom: 0;
        }

        @media (max-width: 768px) {
            .colToText {
                width: 100% !important;
            }
        }
    </style>
@endpush
@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb" class="m-0">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item @if(request()->routeIs('admin.abandoned.cart.list')) active @endif">
                                <a href="{{ route('admin.abandoned.cart.list') }}">
                                    <img src="https://admin.ebitans.com/img/icons/box.png"> <br> <span
                                        class="nav-link-text ms-1">
                                        @if (Session::has('lang') && Session::get('lang') == 'bn')
                                            পরিত্যক্ত কার্ট
                                        @else
                                            Abandoned Cart
                                        @endif
                                    </span>
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        <div class="container-fluid mt-4" id="toplist">
            <div class="row">
                <div class="col-md-8">
                    <h4>All Cart List</h4>
                </div>
            </div>
            <div class="row mt-4 mb-5 mb-md-0">
                <div class="col-12 mb-5">
                    <div class="card h-100 mt-4 mt-md-0">
                        <div class="card-header pb-0 p-3">
                            <div class="row">
                                <form action="{{ route('admin.abandoned.cart.list') }}" method="get"
                                      class="row">
                                    <div class="col-md-2">
                                        <input type="date" name="from_date" id="from_date"
                                               value="{{ $from_date ?? '' }}"
                                               class="form-control">
                                    </div>
                                    <div class="col colToText text-center mt-1">
                                        <label for="to_date">To</label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" name="to_date" id="to_date" value="{{ $to_date ?? '' }}"
                                               class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <input type="text" name="search" id="search" value="{{ $search ?? '' }}"
                                                   class="form-control">
                                            <span class="input-group-text" style="padding: 0.75rem 11px !important;">
                                                <i class="fa fa-search" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-2" style="padding-left:0px;">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body px-3 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="1%">
                                            SL
                                        </th>
                                        <th width="69%">
                                            User Info
                                        </th>
                                        <th width="25%">
                                            Contact
                                        </th>
                                        <th width="5%">
                                            Action
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($usersList) && count($usersList) > 0)
                                        @foreach($usersList as $item)
                                            <tr>
                                                <td>{{ ($usersList->currentPage() - 1) * $usersList->perPage() + $loop->iteration }}</td>
                                                <td>
                                                    @if(isset($item->user->name) && !empty($item->user->name))
                                                        <p class="tdInnerText">Name: {{ $item->user->name ?? "" }}</p>
                                                    @endif
                                                    @if(isset($item->user->phone) && !empty($item->user->phone))
                                                        <p class="tdInnerText">Phone: {{ $item->user->phone ?? "" }}</p>
                                                    @endif
                                                    @if(isset($item->user->email) && !empty($item->user->email))
                                                        <p class="tdInnerText">Email: {{ $item->user->email ?? "" }}</p>
                                                    @endif
                                                    @if(empty($item->user->name) && empty($item->user->phone) && empty($item->user->email))
                                                        {{ "Not Register Yet" }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(isset($item->phone) && !empty($item->phone))
                                                        <p class="tdInnerText">Phone: {{ $item->phone ?? "" }}</p>
                                                    @endif
                                                    @if(isset($item->email) && !empty($item->email))
                                                        <p class="tdInnerText">Email: {{ $item->email ?? "" }}</p>
                                                    @endif
                                                    @if(empty($item->email) && empty($item->phone))
                                                        {{ "Contact Not Update Yet" }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.abandoned.cart.item.list', ['id' => $item->id]) }}"
                                                       class="btn btn-info" target="_blank">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center">No record found</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                <div>
                                    {!! $usersList->appends(['search' => request('search'),'from_date' => request('from_date'), 'to_date' => request('to_date')])->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

