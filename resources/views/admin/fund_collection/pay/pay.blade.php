@extends('layouts.admin')
@section('title','New Payment')
@section('breadcrumb')
<div class="codex-breadcrumb">
    <div class="breadcrumb-contain">
        <div class="left-breadcrumb">
            <ul class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{route('dashboard')}}">
                        <h1>Dashboard</h1>
                    </a>
                </li>
                <li class="breadcrumb-item active"><a href="javascript:void(0);">New Payment</a></li>
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

            <div class="col-md-8 col-lg-8 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">খেদমত প্রদান করুন</h4>
                    </div>
                    <div class="card-body">
                        <form id="khedmotSubmitForm" method="POST" action="{{ route('fund.pay.store') }}" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div class="row">
                                <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label for="" class="form-lable">তারিখ <span class="text-danger">*</span> </label>
                                        <input class="form-control" type="date" placeholder="তারিখ " name="date" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="form-lable">খেদমত প্রদানের নাম/উদ্দেশ্য <span class="text-danger">*</span></label>
                                <input required type="text" name="pay_to" id="payerName" class="form-control" placeholder="লিখুন খেদমত প্রদানের নাম/উদ্দেশ্য" required>
                            </div>

                            <div class="row">
                                <div class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label for="" class="form-lable">
                                            মোট খেদমত:
                                            <span id="totalKhedmotAmount">{{$receives->sum('khedmot_amount')}}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label for="" class="form-lable">
                                            মোট মানত:
                                            <span id="totalManotAmount">{{$receives->sum('manat_amount')}}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label for="" class="form-lable">
                                            মোট কল্যাণ:
                                            <span id="totalKalyanAmount">{{$receives->sum('kalyan_amount')}}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label for="" class="form-lable">
                                            মোট ভাড়া:
                                            <span id="totalRentAmount">{{$receives->sum('rent_amount')}}</span></label>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <div class="form-group">
                                        <label for="" class="form-lable">খেদমতের পরিমাণ </label>
                                        <input  class="form-control" type="number" placeholder="কেদমতের পরিমাণ" name="khedmot_amount" min="0" value="0">
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <div class="form-group">
                                        <label for="" class="form-lable">মানত পরিমাণ </label>
                                        <input  class="form-control" type="number" placeholder="মানত পরিমাণ" name="manat_amount" min="0" value="0">
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <div class="form-group">
                                        <label for="" class="form-lable">কল্যাণ পরিমাণ </label>
                                        <input  class="form-control" type="number" placeholder="কল্যাণ পরিমাণ" name="kollan_amount" min="0" value="0">
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <div class="form-group">
                                        <label for="" class="form-lable">ভাড়া পরিমাণ </label>
                                        <input  class="form-control" type="number" placeholder="ভাড়া পরিমাণ" name="rent_amount" min="0" value="0">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="" class="form-lable">মোট প্রদানের পরিমাণ </label>
                                        <input readonly class="form-control" type="number" placeholder="মোট পরিমাণ" name="total_paid" min="0" value="0">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="" class="form-lable">অবশিষ্ট ক্যাশ</label>
                                        <input readonly class="form-control" type="number" placeholder="অবশিষ্ট ক্যাশ" name="left_amount" min="0" value="0">
                                    </div>
                                </div>
                            </div>
                            {{-- <input type="hidden" name="khedmot_id" id="khedmot_id" class="form-control"> --}}
                            <div class="form-group">
                                <label for="" class="form-lable">মন্তব্য</label>
                                <textarea class="form-control" type="text" placeholder="মন্তব্য লিখুন" name="comment" rows="3" cols="50"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-md pull-right">জমা করুণ</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            function calculateTotalAmount() {
                var previousTotalkhedmotAmount = parseFloat($('#totalKhedmotAmount').text()) || 0;
                var previousTotalManotAmount = parseFloat($('#totalManotAmount').text()) || 0;
                var previousTotalKalyanAmount = parseFloat($('#totalKalyanAmount').text()) || 0;
                var previousTotalRentAmount = parseFloat($('#totalRentAmount').text()) || 0;
                console.log(previousTotalkhedmotAmount, previousTotalManotAmount, previousTotalKalyanAmount, previousTotalRentAmount);


                var khedmotAmount = parseFloat($('input[name="khedmot_amount"]').val()) || 0;
                var manatAmount = parseFloat($('input[name="manat_amount"]').val()) || 0;
                var kollanAmount = parseFloat($('input[name="kollan_amount"]').val()) || 0;
                var rentAmount = parseFloat($('input[name="rent_amount"]').val()) || 0;

                var previousTotalAmount = previousTotalkhedmotAmount + previousTotalManotAmount + previousTotalKalyanAmount + previousTotalRentAmount;
                var totalAmount = khedmotAmount + manatAmount + kollanAmount + rentAmount;
                var leftover = previousTotalAmount - totalAmount;
                $('input[name="total_paid"]').val(totalAmount.toFixed(2));
                $('input[name="left_amount"]').val(leftover.toFixed(2));
            }

            $('input[name="khedmot_amount"], input[name="manat_amount"], input[name="kollan_amount"], input[name="rent_amount"]').on('input', calculateTotalAmount);
        });
    </script>
@endpush
