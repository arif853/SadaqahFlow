@extends('layouts.admin')
@section('title','Dashboard')
@section('breadcrumb')
<div class="codex-breadcrumb">
    <div class="breadcrumb-contain">
        <div class="left-breadcrumb">
            <ul class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.html">
                        <h1>Dashboard</h1>
                    </a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0);">Default</a></li>
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
<style>
    .custome-card-bg {
        background: #fbcccc !important;
    }
    .custome-card-bg-2{
        background: #c0f08f  !important;
    }
</style>
<div class="theme-body common-dash" data-simplebar>
    <div class="custom-container">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 cdx-xl-100">
                {{-- K:{{$khedmots->sum('khedmot_amount')}} <br>
                R:{{$khedmots->sum('rent_amount')}} <br>
                KL:{{$khedmots->sum('kalyan_amount')}} <br>
                M:{{$khedmots->sum('manat_amount')}} <br> --}}
                <div class="row">
                    @if (auth()->user()->getRoleNames()->contains('Super Admin') || auth()->user()->getRoleNames()->contains('Admin'))
                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-2">
                        <div class="card project-status custome-card-bg-2">
                            <div class="card-header">
                                <h6>সর্বমোট(Cash Box) </h6>
                            </div>
                            <div class="card-body progressCounter">
                                <div class="media">
                                    <div>
                                        <h5>&#2547 <span class="count1">{{ $totalCentralFunds }}</span></h5>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                        <div class="card project-status custome-card-bg">
                            <div class="card-header">
                                <h6>খেদমত(সংগ্রহ)</h6>
                            </div>
                            <div class="card-body progressCounter">
                                <div class="media">
                                    <div>
                                        <h5>&#2547 <span class="count">{{ $KhedmotAmount }}</span></h5>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                        <div class="card project-status custome-card-bg">
                            <div class="card-header">
                                <h6>মানত(সংগ্রহ)</h6>

                            </div>
                            <div class="card-body progressCounter">
                                <div class="media">
                                    <div>
                                        <h5>&#2547 <span class="count">{{ $ManatAmount }}</span></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                        <div class="card project-status custome-card-bg">
                            <div class="card-header">
                                <h6>কল্যাণ(সংগ্রহ)</h6>

                            </div>
                            <div class="card-body progressCounter">
                                <div class="media">
                                    <div>
                                        <h5>&#2547 <span class="count">{{ $KalyanAmount }}</span></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                        <div class="card project-status custome-card-bg">
                            <div class="card-header">
                                <h6>ভাড়া(সংগ্রহ)</h6>
                            </div>
                            <div class="card-body progressCounter">
                                <div class="media">
                                    <div>
                                        <h5>&#2547 <span class="count">{{ $RentAmount }}</span></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                        <div class="card project-status">
                            <div class="card-header">
                                <h6>খেদমত(জমা)</h6>
                            </div>
                            <div class="card-body progressCounter">
                                <div class="media">
                                    <div>
                                        <h5>&#2547 <span class="count1">{{ $totalKhedmotAmount }}</span></h5>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                        <div class="card project-status">
                            <div class="card-header">
                                <h6>মানত(জমা)</h6>
                            </div>
                            <div class="card-body progressCounter">
                                <div class="media">
                                    <div>
                                        <h5>&#2547 <span class="count">{{ $totalManatAmount }}</span></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                        <div class="card project-status">
                            <div class="card-header">
                                <h6>কল্যাণ (জমা)</h6>
                            </div>
                            <div class="card-body progressCounter">
                                <div class="media">
                                    <div>
                                        <h5>&#2547 <span class="count">{{ $totalKalyanAmount }}</span></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                        <div class="card project-status">
                            <div class="card-header">
                                <h6>ভাড়া (জমা)</h6>
                            </div>
                            <div class="card-body progressCounter">
                                <div class="media">
                                    <div>
                                        <h5>&#2547 <span class="count">{{ $totalRentAmount }}</span></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                        <div class="card project-status">
                            <div class="card-header">
                                <h6>জাকেরদের সংখ্যা</h6>

                            </div>
                            <div class="card-body progressCounter">
                                <div class="media">
                                    <div>
                                        <h5><span class="count">{{ $totalMembers }} </span> জন</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (auth()->user()->getRoleNames()->contains('Super Admin') || auth()->user()->getRoleNames()->contains('Admin'))
            <div class="col-xl-6 cdx-xl-50">
                <div class="card project-summarytbl">
                    <div class="card-header">
                        <h4>খেদমত গ্রহনকারি করমি তালিকা</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>নং</th>
                                        <th>করমির নাম</th>
                                        <th>ফোন</th>
                                        <th>ঠিকানা</th>
                                        <th>প্রক্রিয়া</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->address }}</td>
                                        <td></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="col-xl-6 cdx-xl-50">
                <div class="card recent-ordertbl">
                    <div class="card-header">
                        <h4>শেষ ১০টি সংগৃহীত খেদমত</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>নং</th>
                                        <th>জাকের নাম</th>
                                        <th>তারিখ</th>
                                        <th>অনুষ্ঠান</th>
                                        <th>খেদমত পরিমাণ</th>
                                        <th>কর্মী নাম</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($khedmots as $khedmot)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $khedmot->member->name }}</td>
                                        <td>{{ $khedmot->date }}</td>
                                        <td>
                                            @if($khedmot->program_name == 1)
                                                <span>ওরশ পাক</span>
                                            @elseif($khedmot->program_name == 2)
                                                <span>বেসালত দিবস</span>
                                            @elseif($khedmot->program_name == 3)
                                                <span>জলসায়ে ওরশ পাক</span>
                                            @elseif($khedmot->program_name == 4)
                                                <span>অনন্যা - {{$khedmot->other_program_name}}</span>
                                            @endif
                                        </td>
                                        <td>{{ $khedmot->khedmot_amount }}</td>
                                        <td>{{ $khedmot->user->name }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @if (auth()->user()->getRoleNames()->contains('Super Admin') || auth()->user()->getRoleNames()->contains('Admin'))
            <div class="col-xl-12 cdx-xl-50">
                <div class="card visitor-ratetbl">
                    <div class="card-header">
                        <h4>করমিদের খেদমত সংগ্রহ</h4>

                    </div>
                    <div class="card-body p-0">
                        <div id="apex-columnchart"></div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    $(document).ready(function(){

        //*** column chart start **** //
        var options = {
            series: [{
                data: [
                @foreach ($chartUsers as $user)
                {{ $user->khedmots->sum('khedmot_amount') }},
                @endforeach
            ]
        }],
            chart: {
            height: 400,
            type: 'bar',
            events: {
                click: function(chart, w, e) {
                    // console.log(chart, w, e)
                }
            }
        },
        plotOptions: {
            bar: {
            columnWidth: '40%',
            distributed: true,
            }
        },
        dataLabels: {
            enabled: true
        },
        legend: {
            show: true
        },
        xaxis: {
            categories: [
            @foreach ($chartUsers as $user)
            ['{{ $user->name }}'],
            @endforeach
            ],
            labels: {
            style: {
                fontSize: '12px'
            }
            }
        }
        };

        var chart = new ApexCharts(document.querySelector("#apex-columnchart"), options);
        chart.render();
        //*** column chart end **** //
    });
</script>
@endpush
