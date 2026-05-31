@extends('admin.layouts.main')
@push('styles')
    <style>
        .listdigital {
            list-style: none;
            padding-left: 0px;
        }

        .listdigital li {
            float: unset !important;
        }


        .cssload-wrap {
            text-align: center;
            line-height: 195px;
        }

        .cssload-container {
            display: inline-block;
        }

        .cssload-dots {
            display: inline-block;
            position: relative;
        }

        .cssload-dots:not(:last-child) {
            margin-right: 9px;
        }

        .cssload-dots:before,
        .cssload-dots:after {
            content: "";
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            position: absolute;
        }

        .cssload-dots:nth-child(1):before {
            transform: translateY(-200%);
            -o-transform: translateY(-200%);
            -ms-transform: translateY(-200%);
            -webkit-transform: translateY(-200%);
            -moz-transform: translateY(-200%);
            animation: cssload-animBefore 1.15s linear infinite;
            -o-animation: cssload-animBefore 1.15s linear infinite;
            -ms-animation: cssload-animBefore 1.15s linear infinite;
            -webkit-animation: cssload-animBefore 1.15s linear infinite;
            -moz-animation: cssload-animBefore 1.15s linear infinite;
            animation-delay: -1.04s;
            -o-animation-delay: -1.04s;
            -ms-animation-delay: -1.04s;
            -webkit-animation-delay: -1.04s;
            -moz-animation-delay: -1.04s;
            background-color: rgb(255, 0, 0);
        }

        .cssload-dots:nth-child(1):after {
            transform: translateY(200%);
            -o-transform: translateY(200%);
            -ms-transform: translateY(200%);
            -webkit-transform: translateY(200%);
            -moz-transform: translateY(200%);
            animation: cssload-animAfter 1.15s linear infinite;
            -o-animation: cssload-animAfter 1.15s linear infinite;
            -ms-animation: cssload-animAfter 1.15s linear infinite;
            -webkit-animation: cssload-animAfter 1.15s linear infinite;
            -moz-animation: cssload-animAfter 1.15s linear infinite;
            animation-delay: -1.04s;
            -o-animation-delay: -1.04s;
            -ms-animation-delay: -1.04s;
            -webkit-animation-delay: -1.04s;
            -moz-animation-delay: -1.04s;
            background-color: rgb(119, 119, 119);
        }

        .cssload-dots:nth-child(2):before {
            transform: translateY(-200%);
            -o-transform: translateY(-200%);
            -ms-transform: translateY(-200%);
            -webkit-transform: translateY(-200%);
            -moz-transform: translateY(-200%);
            animation: cssload-animBefore 1.15s linear infinite;
            -o-animation: cssload-animBefore 1.15s linear infinite;
            -ms-animation: cssload-animBefore 1.15s linear infinite;
            -webkit-animation: cssload-animBefore 1.15s linear infinite;
            -moz-animation: cssload-animBefore 1.15s linear infinite;
            animation-delay: -2.07s;
            -o-animation-delay: -2.07s;
            -ms-animation-delay: -2.07s;
            -webkit-animation-delay: -2.07s;
            -moz-animation-delay: -2.07s;
            background-color: rgb(255, 0, 0);
        }

        .cssload-dots:nth-child(2):after {
            transform: translateY(200%);
            -o-transform: translateY(200%);
            -ms-transform: translateY(200%);
            -webkit-transform: translateY(200%);
            -moz-transform: translateY(200%);
            animation: cssload-animAfter 1.15s linear infinite;
            -o-animation: cssload-animAfter 1.15s linear infinite;
            -ms-animation: cssload-animAfter 1.15s linear infinite;
            -webkit-animation: cssload-animAfter 1.15s linear infinite;
            -moz-animation: cssload-animAfter 1.15s linear infinite;
            animation-delay: -2.07s;
            -o-animation-delay: -2.07s;
            -ms-animation-delay: -2.07s;
            -webkit-animation-delay: -2.07s;
            -moz-animation-delay: -2.07s;
            background-color: rgb(119, 119, 119);
        }

        .cssload-dots:nth-child(3):before {
            transform: translateY(-200%);
            -o-transform: translateY(-200%);
            -ms-transform: translateY(-200%);
            -webkit-transform: translateY(-200%);
            -moz-transform: translateY(-200%);
            animation: cssload-animBefore 1.15s linear infinite;
            -o-animation: cssload-animBefore 1.15s linear infinite;
            -ms-animation: cssload-animBefore 1.15s linear infinite;
            -webkit-animation: cssload-animBefore 1.15s linear infinite;
            -moz-animation: cssload-animBefore 1.15s linear infinite;
            animation-delay: -3.11s;
            -o-animation-delay: -3.11s;
            -ms-animation-delay: -3.11s;
            -webkit-animation-delay: -3.11s;
            -moz-animation-delay: -3.11s;
            background-color: rgb(255, 0, 0);
        }

        .cssload-dots:nth-child(3):after {
            transform: translateY(200%);
            -o-transform: translateY(200%);
            -ms-transform: translateY(200%);
            -webkit-transform: translateY(200%);
            -moz-transform: translateY(200%);
            animation: cssload-animAfter 1.15s linear infinite;
            -o-animation: cssload-animAfter 1.15s linear infinite;
            -ms-animation: cssload-animAfter 1.15s linear infinite;
            -webkit-animation: cssload-animAfter 1.15s linear infinite;
            -moz-animation: cssload-animAfter 1.15s linear infinite;
            animation-delay: -3.11s;
            -o-animation-delay: -3.11s;
            -ms-animation-delay: -3.11s;
            -webkit-animation-delay: -3.11s;
            -moz-animation-delay: -3.11s;
            background-color: rgb(119, 119, 119);
        }

        .cssload-dots:nth-child(4):before {
            transform: translateY(-200%);
            -o-transform: translateY(-200%);
            -ms-transform: translateY(-200%);
            -webkit-transform: translateY(-200%);
            -moz-transform: translateY(-200%);
            animation: cssload-animBefore 1.15s linear infinite;
            -o-animation: cssload-animBefore 1.15s linear infinite;
            -ms-animation: cssload-animBefore 1.15s linear infinite;
            -webkit-animation: cssload-animBefore 1.15s linear infinite;
            -moz-animation: cssload-animBefore 1.15s linear infinite;
            animation-delay: -4.14s;
            -o-animation-delay: -4.14s;
            -ms-animation-delay: -4.14s;
            -webkit-animation-delay: -4.14s;
            -moz-animation-delay: -4.14s;
            background-color: rgb(255, 0, 0);
        }

        .cssload-dots:nth-child(4):after {
            transform: translateY(200%);
            -o-transform: translateY(200%);
            -ms-transform: translateY(200%);
            -webkit-transform: translateY(200%);
            -moz-transform: translateY(200%);
            animation: cssload-animAfter 1.15s linear infinite;
            -o-animation: cssload-animAfter 1.15s linear infinite;
            -ms-animation: cssload-animAfter 1.15s linear infinite;
            -webkit-animation: cssload-animAfter 1.15s linear infinite;
            -moz-animation: cssload-animAfter 1.15s linear infinite;
            animation-delay: -4.14s;
            -o-animation-delay: -4.14s;
            -ms-animation-delay: -4.14s;
            -webkit-animation-delay: -4.14s;
            -moz-animation-delay: -4.14s;
            background-color: rgb(119, 119, 119);
        }

        .cssload-dots:nth-child(5):before {
            transform: translateY(-200%);
            -o-transform: translateY(-200%);
            -ms-transform: translateY(-200%);
            -webkit-transform: translateY(-200%);
            -moz-transform: translateY(-200%);
            animation: cssload-animBefore 1.15s linear infinite;
            -o-animation: cssload-animBefore 1.15s linear infinite;
            -ms-animation: cssload-animBefore 1.15s linear infinite;
            -webkit-animation: cssload-animBefore 1.15s linear infinite;
            -moz-animation: cssload-animBefore 1.15s linear infinite;
            animation-delay: -5.18s;
            -o-animation-delay: -5.18s;
            -ms-animation-delay: -5.18s;
            -webkit-animation-delay: -5.18s;
            -moz-animation-delay: -5.18s;
            background-color: rgb(255, 0, 0);
        }

        .cssload-dots:nth-child(5):after {
            transform: translateY(200%);
            -o-transform: translateY(200%);
            -ms-transform: translateY(200%);
            -webkit-transform: translateY(200%);
            -moz-transform: translateY(200%);
            animation: cssload-animAfter 1.15s linear infinite;
            -o-animation: cssload-animAfter 1.15s linear infinite;
            -ms-animation: cssload-animAfter 1.15s linear infinite;
            -webkit-animation: cssload-animAfter 1.15s linear infinite;
            -moz-animation: cssload-animAfter 1.15s linear infinite;
            animation-delay: -5.18s;
            -o-animation-delay: -5.18s;
            -ms-animation-delay: -5.18s;
            -webkit-animation-delay: -5.18s;
            -moz-animation-delay: -5.18s;
            background-color: rgb(119, 119, 119);
        }

        .cssload-dots:nth-child(6):before {
            transform: translateY(-200%);
            -o-transform: translateY(-200%);
            -ms-transform: translateY(-200%);
            -webkit-transform: translateY(-200%);
            -moz-transform: translateY(-200%);
            animation: cssload-animBefore 1.15s linear infinite;
            -o-animation: cssload-animBefore 1.15s linear infinite;
            -ms-animation: cssload-animBefore 1.15s linear infinite;
            -webkit-animation: cssload-animBefore 1.15s linear infinite;
            -moz-animation: cssload-animBefore 1.15s linear infinite;
            animation-delay: -6.21s;
            -o-animation-delay: -6.21s;
            -ms-animation-delay: -6.21s;
            -webkit-animation-delay: -6.21s;
            -moz-animation-delay: -6.21s;
            background-color: rgb(255, 0, 0);
        }

        .cssload-dots:nth-child(6):after {
            transform: translateY(200%);
            -o-transform: translateY(200%);
            -ms-transform: translateY(200%);
            -webkit-transform: translateY(200%);
            -moz-transform: translateY(200%);
            animation: cssload-animAfter 1.15s linear infinite;
            -o-animation: cssload-animAfter 1.15s linear infinite;
            -ms-animation: cssload-animAfter 1.15s linear infinite;
            -webkit-animation: cssload-animAfter 1.15s linear infinite;
            -moz-animation: cssload-animAfter 1.15s linear infinite;
            animation-delay: -6.21s;
            -o-animation-delay: -6.21s;
            -ms-animation-delay: -6.21s;
            -webkit-animation-delay: -6.21s;
            -moz-animation-delay: -6.21s;
            background-color: rgb(119, 119, 119);
        }

        .cssload-dots:nth-child(7):before {
            transform: translateY(-200%);
            -o-transform: translateY(-200%);
            -ms-transform: translateY(-200%);
            -webkit-transform: translateY(-200%);
            -moz-transform: translateY(-200%);
            animation: cssload-animBefore 1.15s linear infinite;
            -o-animation: cssload-animBefore 1.15s linear infinite;
            -ms-animation: cssload-animBefore 1.15s linear infinite;
            -webkit-animation: cssload-animBefore 1.15s linear infinite;
            -moz-animation: cssload-animBefore 1.15s linear infinite;
            animation-delay: -7.25s;
            -o-animation-delay: -7.25s;
            -ms-animation-delay: -7.25s;
            -webkit-animation-delay: -7.25s;
            -moz-animation-delay: -7.25s;
            background-color: rgb(255, 0, 0);
        }

        .cssload-dots:nth-child(7):after {
            transform: translateY(200%);
            -o-transform: translateY(200%);
            -ms-transform: translateY(200%);
            -webkit-transform: translateY(200%);
            -moz-transform: translateY(200%);
            animation: cssload-animAfter 1.15s linear infinite;
            -o-animation: cssload-animAfter 1.15s linear infinite;
            -ms-animation: cssload-animAfter 1.15s linear infinite;
            -webkit-animation: cssload-animAfter 1.15s linear infinite;
            -moz-animation: cssload-animAfter 1.15s linear infinite;
            animation-delay: -7.25s;
            -o-animation-delay: -7.25s;
            -ms-animation-delay: -7.25s;
            -webkit-animation-delay: -7.25s;
            -moz-animation-delay: -7.25s;
            background-color: rgb(119, 119, 119);
        }

        .cssload-dots:nth-child(8):before {
            transform: translateY(-200%);
            -o-transform: translateY(-200%);
            -ms-transform: translateY(-200%);
            -webkit-transform: translateY(-200%);
            -moz-transform: translateY(-200%);
            animation: cssload-animBefore 1.15s linear infinite;
            -o-animation: cssload-animBefore 1.15s linear infinite;
            -ms-animation: cssload-animBefore 1.15s linear infinite;
            -webkit-animation: cssload-animBefore 1.15s linear infinite;
            -moz-animation: cssload-animBefore 1.15s linear infinite;
            animation-delay: -8.28s;
            -o-animation-delay: -8.28s;
            -ms-animation-delay: -8.28s;
            -webkit-animation-delay: -8.28s;
            -moz-animation-delay: -8.28s;
            background-color: rgb(255, 0, 0);
        }

        .cssload-dots:nth-child(8):after {
            transform: translateY(200%);
            -o-transform: translateY(200%);
            -ms-transform: translateY(200%);
            -webkit-transform: translateY(200%);
            -moz-transform: translateY(200%);
            animation: cssload-animAfter 1.15s linear infinite;
            -o-animation: cssload-animAfter 1.15s linear infinite;
            -ms-animation: cssload-animAfter 1.15s linear infinite;
            -webkit-animation: cssload-animAfter 1.15s linear infinite;
            -moz-animation: cssload-animAfter 1.15s linear infinite;
            animation-delay: -8.28s;
            -o-animation-delay: -8.28s;
            -ms-animation-delay: -8.28s;
            -webkit-animation-delay: -8.28s;
            -moz-animation-delay: -8.28s;
            background-color: rgb(119, 119, 119);
        }

        .cssload-dots:nth-child(9):before {
            transform: translateY(-200%);
            -o-transform: translateY(-200%);
            -ms-transform: translateY(-200%);
            -webkit-transform: translateY(-200%);
            -moz-transform: translateY(-200%);
            animation: cssload-animBefore 1.15s linear infinite;
            -o-animation: cssload-animBefore 1.15s linear infinite;
            -ms-animation: cssload-animBefore 1.15s linear infinite;
            -webkit-animation: cssload-animBefore 1.15s linear infinite;
            -moz-animation: cssload-animBefore 1.15s linear infinite;
            animation-delay: -9.32s;
            -o-animation-delay: -9.32s;
            -ms-animation-delay: -9.32s;
            -webkit-animation-delay: -9.32s;
            -moz-animation-delay: -9.32s;
            background-color: #F00;
        }

        .cssload-dots:nth-child(9):after {
            transform: translateY(200%);
            -o-transform: translateY(200%);
            -ms-transform: translateY(200%);
            -webkit-transform: translateY(200%);
            -moz-transform: translateY(200%);
            animation: cssload-animAfter 1.15s linear infinite;
            -o-animation: cssload-animAfter 1.15s linear infinite;
            -ms-animation: cssload-animAfter 1.15s linear infinite;
            -webkit-animation: cssload-animAfter 1.15s linear infinite;
            -moz-animation: cssload-animAfter 1.15s linear infinite;
            animation-delay: -9.32s;
            -o-animation-delay: -9.32s;
            -ms-animation-delay: -9.32s;
            -webkit-animation-delay: -9.32s;
            -moz-animation-delay: -9.32s;
            background-color: #777;
        }

        .cssload-dots:nth-child(10):before {
            transform: translateY(-200%);
            -o-transform: translateY(-200%);
            -ms-transform: translateY(-200%);
            -webkit-transform: translateY(-200%);
            -moz-transform: translateY(-200%);
            animation: cssload-animBefore 1.15s linear infinite;
            -o-animation: cssload-animBefore 1.15s linear infinite;
            -ms-animation: cssload-animBefore 1.15s linear infinite;
            -webkit-animation: cssload-animBefore 1.15s linear infinite;
            -moz-animation: cssload-animBefore 1.15s linear infinite;
            animation-delay: -10.35s;
            -o-animation-delay: -10.35s;
            -ms-animation-delay: -10.35s;
            -webkit-animation-delay: -10.35s;
            -moz-animation-delay: -10.35s;
            background-color: #F00;
        }

        .cssload-dots:nth-child(10):after {
            transform: translateY(200%);
            -o-transform: translateY(200%);
            -ms-transform: translateY(200%);
            -webkit-transform: translateY(200%);
            -moz-transform: translateY(200%);
            animation: cssload-animAfter 1.15s linear infinite;
            -o-animation: cssload-animAfter 1.15s linear infinite;
            -ms-animation: cssload-animAfter 1.15s linear infinite;
            -webkit-animation: cssload-animAfter 1.15s linear infinite;
            -moz-animation: cssload-animAfter 1.15s linear infinite;
            animation-delay: -10.35s;
            -o-animation-delay: -10.35s;
            -ms-animation-delay: -10.35s;
            -webkit-animation-delay: -10.35s;
            -moz-animation-delay: -10.35s;
            background-color: #777;
        }




        @keyframes cssload-animBefore {
            0% {
                transform: scale(1) translateY(-200%);
                z-index: 1;
            }

            25% {
                transform: scale(1.3) translateY(0);
                z-index: 1;
            }

            50% {
                transform: scale(1) translateY(200%);
                z-index: -1;
            }

            75% {
                transform: scale(0.7) translateY(0);
                z-index: -1;
            }

            100% {
                transform: scale(1) translateY(-200%);
                z-index: -1;
            }
        }

        @-o-keyframes cssload-animBefore {
            0% {
                -o-transform: scale(1) translateY(-200%);
                z-index: 1;
            }

            25% {
                -o-transform: scale(1.3) translateY(0);
                z-index: 1;
            }

            50% {
                -o-transform: scale(1) translateY(200%);
                z-index: -1;
            }

            75% {
                -o-transform: scale(0.7) translateY(0);
                z-index: -1;
            }

            100% {
                -o-transform: scale(1) translateY(-200%);
                z-index: -1;
            }
        }

        @-ms-keyframes cssload-animBefore {
            0% {
                -ms-transform: scale(1) translateY(-200%);
                z-index: 1;
            }

            25% {
                -ms-transform: scale(1.3) translateY(0);
                z-index: 1;
            }

            50% {
                -ms-transform: scale(1) translateY(200%);
                z-index: -1;
            }

            75% {
                -ms-transform: scale(0.7) translateY(0);
                z-index: -1;
            }

            100% {
                -ms-transform: scale(1) translateY(-200%);
                z-index: -1;
            }
        }

        @-webkit-keyframes cssload-animBefore {
            0% {
                -webkit-transform: scale(1) translateY(-200%);
                z-index: 1;
            }

            25% {
                -webkit-transform: scale(1.3) translateY(0);
                z-index: 1;
            }

            50% {
                -webkit-transform: scale(1) translateY(200%);
                z-index: -1;
            }

            75% {
                -webkit-transform: scale(0.7) translateY(0);
                z-index: -1;
            }

            100% {
                -webkit-transform: scale(1) translateY(-200%);
                z-index: -1;
            }
        }

        @-moz-keyframes cssload-animBefore {
            0% {
                -moz-transform: scale(1) translateY(-200%);
                z-index: 1;
            }

            25% {
                -moz-transform: scale(1.3) translateY(0);
                z-index: 1;
            }

            50% {
                -moz-transform: scale(1) translateY(200%);
                z-index: -1;
            }

            75% {
                -moz-transform: scale(0.7) translateY(0);
                z-index: -1;
            }

            100% {
                -moz-transform: scale(1) translateY(-200%);
                z-index: -1;
            }
        }

        @keyframes cssload-animAfter {
            0% {
                transform: scale(1) translateY(200%);
                z-index: -1;
            }

            25% {
                transform: scale(0.7) translateY(0);
                z-index: -1;
            }

            50% {
                transform: scale(1) translateY(-200%);
                z-index: 1;
            }

            75% {
                transform: scale(1.3) translateY(0);
                z-index: 1;
            }

            100% {
                transform: scale(1) translateY(200%);
                z-index: 1;
            }
        }

        @-o-keyframes cssload-animAfter {
            0% {
                -o-transform: scale(1) translateY(200%);
                z-index: -1;
            }

            25% {
                -o-transform: scale(0.7) translateY(0);
                z-index: -1;
            }

            50% {
                -o-transform: scale(1) translateY(-200%);
                z-index: 1;
            }

            75% {
                -o-transform: scale(1.3) translateY(0);
                z-index: 1;
            }

            100% {
                -o-transform: scale(1) translateY(200%);
                z-index: 1;
            }
        }

        @-ms-keyframes cssload-animAfter {
            0% {
                -ms-transform: scale(1) translateY(200%);
                z-index: -1;
            }

            25% {
                -ms-transform: scale(0.7) translateY(0);
                z-index: -1;
            }

            50% {
                -ms-transform: scale(1) translateY(-200%);
                z-index: 1;
            }

            75% {
                -ms-transform: scale(1.3) translateY(0);
                z-index: 1;
            }

            100% {
                -ms-transform: scale(1) translateY(200%);
                z-index: 1;
            }
        }

        @-webkit-keyframes cssload-animAfter {
            0% {
                -webkit-transform: scale(1) translateY(200%);
                z-index: -1;
            }

            25% {
                -webkit-transform: scale(0.7) translateY(0);
                z-index: -1;
            }

            50% {
                -webkit-transform: scale(1) translateY(-200%);
                z-index: 1;
            }

            75% {
                -webkit-transform: scale(1.3) translateY(0);
                z-index: 1;
            }

            100% {
                -webkit-transform: scale(1) translateY(200%);
                z-index: 1;
            }
        }

        @-moz-keyframes cssload-animAfter {
            0% {
                -moz-transform: scale(1) translateY(200%);
                z-index: -1;
            }

            25% {
                -moz-transform: scale(0.7) translateY(0);
                z-index: -1;
            }

            50% {
                -moz-transform: scale(1) translateY(-200%);
                z-index: 1;
            }

            75% {
                -moz-transform: scale(1.3) translateY(0);
                z-index: 1;
            }

            100% {
                -moz-transform: scale(1) translateY(200%);
                z-index: 1;
            }
        }
    </style>
@endpush
@section('content')


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('superadmin.savecontent') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Write Content</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Store</label>

                            <select class="form-control" name="store">
                                <option value="0">Select Store</option>
                                @if (isset($lists) && count($lists) > 0)
                                    @foreach ($lists as $list)
                                        <option value="{{ $list->id }}" {{ $id == $list->id ? 'selected' : '' }}>
                                            {{ $list->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control" name="type" onchange="StaticContent(this.value)">
                                <option value="0">Select Type</option>
                                <option value="Static Content">Static Content</option>
                                <option value="Video Content">Video Content</option>
                                <option value="Gify Content">Gify Content</option>
                                <option value="Caption Writting">Caption Wrtting</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group" id="condiXcontent">
                            <label>Content</label>
                            <input type="file" name="content" id="condiXcontent1" class="form-control">
                        </div>
                        <div class="form-group d-none" id="condiXcontent2">
                            <label>Content</label>
                            <textarea name="" id="condiXcontent22" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Note</label>
                            <textarea name="note" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="imageUploadLoading()">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="exampleModal22" tabindex="-1" aria-labelledby="exampleModalLabel22" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('updatecontent') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel22">Edit Content</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label>Store</label>
                            <input type="text" name="store" id="store" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <input type="text" name="type" id="type" class="form-control" value=""
                                readonly>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="form-group" id="editCondiXcontent">
                            <label>Content</label>
                            <h6 style="color: red;" id="contentName"></h6>
                            <img class="form-control mb-2" style="width: 100%;" id="editImage" src=""
                                alt="Content File" onerror="this.src='{{ asset('file.jpg') }}'">
                            <input type="hidden" name="" id="condiXcontentHidden">
                            <input type="file" name="" id="editCondiXcontentValue" onclick="selectImage()"
                                class="form-control">
                        </div>

                        <div class="form-group d-none" id="editCondiXcontent2">
                            <label>Content</label>
                            <textarea name="" id="editCondiXcontent22" rows="3" class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Note</label>
                            <textarea name="note" rows="3" id="note" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <main class="main-content position-relative  h-100 border-radius-lg">
        <div class="container-fluid navbars" style="background-image: linear-gradient(53deg, #42424a 0%, #191919 100%);">
            <div class="row">
                <div class="col-md-12">
                    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color:transparent;color:#fff">
                            <li class="breadcrumb-item">
                                <a href="{{ URL::to('/') }}/superadmin/digitalmarketing">
                                    <img src="{{ URL::to('/') }}/img/icons/box.png"> <br> <span
                                        class="nav-link-text ms-1">Dashboard</span>
                                </a>
                            </li>

                            <li class="breadcrumb-item">
                                <a href="{{ route('superadmin.required.content') }}">
                                    <img src="{{ URL::to('/') }}/img/icons/box.png"> <br> <span
                                        class="nav-link-text ms-1">Required Information</span>
                                </a>
                            </li>


                            <!-- <li class="breadcrumb-item" aria-current="page">
                            <a href="{{URL::to('/')}}/superadmin/boosting">
                                <img src="{{URL::to('/')}}/img/icons/categories.png"> <br><span
                                    class="nav-link-text ms-1">Boosting</span>
                            </a>
                        </li> -->
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{ URL::to('/') }}/superadmin/content">
                                    <img src="{{ URL::to('/') }}/img/icons/categories.png"> <br><span
                                        class="nav-link-text ms-1">Content</span>
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
                    <h4>Content</h4>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li style="padding:0px;border:0px;"><a data-bs-toggle="modal" data-bs-target="#exampleModal"
                                style="display:block;border-radius:0px !important" class="btn btn-primary">Create
                                Content</a></li>
                        <li style="padding:0px;border:0px;"><a style="display:block;border-radius:0px !important"
                                class="btn btn-secondary">Print</a></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-5 productlist">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>List</h4>
                            <input type="search" id="live-search-box" class="form-control"
                                placeholder="Store Search...">
                        </div>
                        <div class="card-body">
                            <ul class="listdigital">
                                @if (isset($lists) && count($lists) > 0)
                                    @foreach ($lists as $list)
                                        <li @if (isset($id) && $list->id == $id) class="active" @endif><a
                                                href="{{ route('content.view', $list->id) }}">{{ $list->name }}</a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            List of Content
                        </div>
                        <div class="card-body">
                            @if (isset($content) && count($content) > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Sl</th>
                                                <th>Store Name</th>
                                                <th>Content Name</th>
                                                <th>Content</th>
                                                <th>Package</th>
                                                <th>Note</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($content as $key => $data)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $data->store->name }}</td>
                                                    <td>{{ $data->name }}</td>
                                                    <td>{{ $data->details }}</td>
                                                    <td data-toggle="tooltip" data-placement="top"
                                                        title="SC-{{ $sc }}/{{ $data->store->digitalplan->static_content }}, VC-{{ $vc }}/{{ $data->store->digitalplan->video_content }}, GC-{{ $gc }}/{{ $data->store->digitalplan->gify_content }}, CW-{{ $cw }}/{{ $data->store->digitalplan->caption_writting }}">
                                                        {{ $data->store->digitalplan->name }}
                                                    </td>
                                                    <td>{{ $data->note }}</td>
                                                    <td>
                                                        <a onclick="edit('{{ $data->id }}')"
                                                            class="btn btn-info">Edit</a>
                                                        <a href="{{ route('content.delete', $data->id) }}"
                                                            class="btn btn-primary">Delete</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <h1>Please Select a Store</h1>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')

<script>
    function imageUploadLoading() {
        $('#imageUploadLoading').show();
    }
</script>
    <script>
        function StaticContent(pera) {

            if ('Caption Writting' == pera) {
                document.getElementById('condiXcontent1').name = '';
                document.getElementById('condiXcontent22').name = 'content';
                $('#condiXcontent2').removeClass('d-none');
                $('#condiXcontent').addClass('d-none');
            } else {
                document.getElementById('condiXcontent1').name = 'content';
                document.getElementById('condiXcontent22').name = '';
                $('#condiXcontent').removeClass('d-none');
                $('#condiXcontent2').addClass('d-none');
            }
        }
    </script>

    <script>
        jQuery(document).ready(function($) {

            $('.listdigital li').each(function() {
                $(this).attr('data-search-term', $(this).text().toLowerCase());
            });

            $('#live-search-box').on('keyup', function() {

                var searchTerm = $(this).val().toLowerCase();

                $('.listdigital li').each(function() {

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

    <script>
        function edit(id) {
            console.log(id);
            $.get('/contentdetails', {
                id: id
            }, function(data) {

                if ('Caption Writting' == data.type) {
                    $('#editCondiXcontent22').val(data.details);
                    document.getElementById('condiXcontentHidden').name = '';
                    document.getElementById('editCondiXcontentValue').name = '';
                    document.getElementById('editCondiXcontent22').name = 'content';
                    $('#editCondiXcontent2').removeClass('d-none');
                    $('#editCondiXcontent').addClass('d-none');
                } else {
                    document.getElementById('condiXcontentHidden').name = 'oldContent';
                    document.getElementById('editCondiXcontentValue').name = '';
                    document.getElementById('editCondiXcontent22').name = '';
                    $('#editCondiXcontent').removeClass('d-none');
                    $('#editCondiXcontent2').addClass('d-none');
                    $('#condiXcontentHidden').val(data.details);
                    $('#contentName').html(data.details);
                }

                $('#id').val(data.id);
                $('#store').val(data.storename);
                $('#type').val(data.type);
                $('#name').val(data.name);
                $('#editImage').attr('src', '{{ asset('clientContent') }}' + '/' + data.details);
                $('#content').val(data.details);
                $('#condiXcontentHidden').val(data.details);
                $('#note').val(data.note);
                $('#exampleModal22').modal('show');
            })
        }

        function selectImage() {
            document.getElementById('editCondiXcontentValue').name = 'content';
        }
    </script>
@endpush
