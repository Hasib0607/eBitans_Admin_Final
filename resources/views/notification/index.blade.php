@extends('admin.layouts.main')
@push('style')
    <style>
        .notificationList .card-body .table th, .notificationList .card-body .table td {
            text-align: start;
        }
    </style>
@endpush
@section('content')
    <main class="main-content position-relative h-100 border-radius-lg" id="admin-socket-notification">
        <div class="container-fluid navbars"
             style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item active">
                                <a href="{{ route('notification.notification.list') }}">
                                    <img src="{{URL::to('/')}}/img/cubes.png"> <br> Notification List
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
                    <h4>All Notification</h4>
                </div>
            </div>
            <div class="row notificationList">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" id="taskfilterresult" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="4%">SL</th>
                                        <th>title</th>
                                        <th width="10%">Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($notifications) && count($notifications) > 0)
                                        @foreach($notifications as $notification)
                                            <tr style="border-bottom:1px solid rgba(0, 0, 0, 0.125)">
                                                <td align="center">{{ ($notifications->currentPage() - 1) * $notifications->perPage() + $loop->iteration }}</td>
                                                <td>
                                                    @php
                                                        $url = route("notification.view-notification" , ['id' => $notification->id]);
                                                        if(isset($notification->link) && !empty($notification->link)){
                                                            $url = $notification->link;
                                                        }
                                                    @endphp
                                                    <a href="{{ $url }}">{{$notification->title}}</a>

                                                    <p>{{ Str::of($notification->body)->limit(40) }}</p>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($notification->created_at)->format("Y-m-d h:m:s A") }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                                <div class="">
                                    {{ $notifications->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
