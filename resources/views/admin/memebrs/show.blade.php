@extends('layouts.admin')
@section('title', 'Member Details')
@section('breadcrumb')
    <div class="codex-breadcrumb">
        <div class="breadcrumb-contain">
            <div class="left-breadcrumb">
                <ul class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <h1>Dashboard</h1>
                        </a>
                    </li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0);">সদস্য বিবরণ</a></li>
                </ul>
            </div>
            <div class="right-breadcrumb">
                <ul>
                    <li>
                        <div class="bread-wrap"><i class="fa fa-clock-o"></i></div><span class="liveTime"></span>
                    </li>
                    <li>
                        <div class="bread-wrap"><i class="fa fa-calendar"></i></div><span class="getDate"></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="theme-body common-dash" data-simplebar>
        <div class="custom-container">
            <div class="row">
                <div class="col-xl-4 col-md-6 cdx-xxl-50 cdx-xl-50">
                    <div class="card contact-card">
                        <div class="card-body">
                            <div class="media align-items-center">
                                <div class="user-imgwrapper">
                                    <img class="img-fluid rounded-50" src="{{ asset('storage/' . $member->image) }}"
                                        alt="Avatar">
                                </div>
                                <div class="media-body">
                                    <h4>{{ $member->name }}</h4>
                                    <h6 class="text-light">{{ $member->kollan_id }}</h6>
                                </div>
                                {{-- <div class="user-setting">
                                        <div class="action-menu">
                                        <div class="action-toggle"><i data-feather="more-vertical"></i></div>
                                        <ul class="action-dropdown">
                                            <li><a class="modal-toggle" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#contactmodal"> <i data-feather="edit">                </i>Edit contact</a></li>
                                            <li>
                                            <button class="btn"><i data-feather="trash"></i>Delete contact</button>
                                            </li>
                                            <li>
                                            <button class="btn"><i data-feather="slash"></i>block contact</button>
                                            </li>
                                        </ul>
                                        </div>
                                    </div> --}}
                            </div>
                            <div class="user-detail">
                                <h5 class="text-primary mb-10"> <i class="fa fa-info-circle mr-10"></i>infomation</h5>
                                {{-- <p class="text-light">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt.</p> --}}
                                <ul class="info-list">
                                    <li><span>Father's Name :- </span>{{ $member->father_name }}
                                    </li>
                                    <li><span>Spouse Name :-</span>{{ $member->spouse_name }}
                                    </li>
                                    <li><span>Blood Group :-</span>{{ $member->bloodType }}
                                    </li>
                                    <li><span>Kollan Id :-</span>{{ $member->kollan_id }}
                                    </li>
                                    <li><span>Kollan Khedmot :-</span>{{ $member->kollan_khedmot }}
                                    </li>
                                    <li><span>Phone :-</span><a href="tel:{{ $member->phone }}">{{ $member->phone }}</a>
                                    </li>
                                </ul>
                                <div class="user-action">
                                    {{-- <a class="btn btn-primary" href="javascript:void(0);">
                                            <i class="fa fa-commenting"></i>
                                        </a> --}}
                                    <a class="btn btn-secondary" href="tel:{{ $member->phone }}">
                                        <i class="fa fa-phone"></i>
                                    </a>
                                    {{-- <a class="btn btn-success" href="javascript:void(0);">
                                            <i class="fa fa-video-camera"></i>
                                        </a> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="text-primary mb-10"> <i class="fa fa-book mr-10"></i>খেদমত সমূহ</h5>
                            <ul class="info-list">
                                <table class="table table-bordered mb-4">
                                    <tr>
                                        <td>তারিখ</td>
                                        <td>খেদমত</td>
                                        <td>মানত</td>
                                        <td>কল্যাণ</td>
                                        <td>ভাড়া</td>
                                        <td>অনুষ্ঠান</td>
                                    </tr>
                                    @foreach($khedmots as $khedmot)
                                    <tr>
                                        <td>{{ Carbon\Carbon::parse($khedmot->date)->format('d M, Y') }}</td>
                                        <td>{{ number_format($khedmot->khedmot_amount, 0) }}</td>
                                        <td>{{ number_format($khedmot->manat_amount, 0) }}</td>
                                        <td>{{ number_format($khedmot->kalyan_amount, 0) }}</td>
                                        <td>{{ number_format($khedmot->rent_amount, 0) }}</td>
                                        <td>{{ $khedmot?->program?->name }}</td>
                                    </tr>
                                    @endforeach

                                </table>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
