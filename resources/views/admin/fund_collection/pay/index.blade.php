@extends('layouts.admin')
@section('title','খেদমত প্রদানের তালিকা')
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
                <li class="breadcrumb-item active"><a href="javascript:void(0);">খেদমত প্রদানের তালিকা</a></li>
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
            <div class="col-12 col-sm-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title d-block">খেদমত প্রদানের তালিকা</h4>
                        @can('create fund-collection')
                        <a href="{{route('fund.pay.create')}}" class="btn btn-success btn-md" >প্রদান করুন</a>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3 col-md-3 mb-10">
                                <label for="searchInput">তারিখ</label>
                                <input type="date" class="form-control" placeholder="তারিখ খুঁজুন" id="searchInput">
                            </div>
                            <div class="col-3 col-md-3 mb-10">
                                <label for="searchInput2">খেদমত খুঁজুন</label>
                                <input type="text" class="form-control" placeholder="কর্মী নাম" id="searchInput">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="khedmotCard">
            @foreach ($pays as $key => $pays)
            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 col-xxl-4" >
                <div class="card">
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="20%"><strong>নং:</strong></td>
                                <td width="50%">{{ $key + 1 }}</td>
                            </tr>
                            <tr>
                                <td width="20%" ><strong>তারিখ:</strong></td>
                                <td width="50%">{{ Carbon\Carbon::parse($pays->date)->format('d-M-Y') }}</td>
                            </tr>
                            <tr>
                                <td width="20%">কর্মী নাম</td>
                                <td width="50%">{{ $pays->payBy->name }}</td>
                            </tr>
                            <tr>
                                <td width="20%">খরচের নাম/ উদ্দেশ্য:</td>
                                <td width="50%">
                                    {{$pays->pay_to}}
                                </td>
                            </tr>
                            <tr>
                                <td  width="20%">কল্যাণ ফান্ড</td>
                                <td width="50%">
                                    @if($pays->khedmot_amount != null)
                                       <span>খেদমত : {{ $pays->khedmot_amount }} টাকা</span>
                                    @endif
                                    @if($pays->manat_amount != null)
                                       <span> মানত : {{ $pays->manat_amount }} টাকা</span>
                                    @endif
                                    @if($pays->kalyan_amount != null)
                                    <span> কল্যাণ : {{ $pays->kalyan_amount }} টাকা</span>
                                    @endif
                                    @if($pays->rent_amount != null)
                                        <span>ভাড়া : {{ $pays->rent_amount }} টাকা</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td  width="20%">মোট পরিমাণ</td>
                                <td width="50%">{{ number_format($pays->total_paid,2) }}</td>
                            </tr>
                            <tr>
                                <td  width="20%">মন্তব্য</td>
                                <td width="50%">{{ $pays->comment }}</td>
                            </tr>
                            <tr>
                                <td  width="20%">স্ট্যাটাস</td>
                                <td width="50%">
                                    <span class="badge bg-success text-white">{{ $pays->payment_status }},</span>
                                    {{ Carbon\Carbon::parse($pays->paid_at)->format('d-M-Y h:i A') }}
                                </td>
                            </tr>


                        </table>
                    </div>

                </div>
            </div>
            @endforeach
        </div>
        <div id="khedmotCardContainer" class="row">

        </div>
    </div>
</div>
 <!-- Edit Modal Start-->
 <div class="modal fade" id="fundCollectionModal" tabindex="-1" aria-labelledby="fundCollectionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="fundCollectionLabel">
                    খেদমত সম্পাদন করুন
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="editForm" method="POST" >
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-6 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="" class="form-lable">তারিখ </label>
                            <input class="form-control" type="date" placeholder="তারিখ " name="date">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">অনুষ্ঠান নাম <span class="text-danger">*</span></label>
                    <select name="program_name" id="program_name" class="form-control">
                        <option value="">অনুষ্ঠান নাম নির্বাচন করুন</option>
                        <option value="1">ওরশ পাক</option>
                        <option value="2">বেসালত দিবস</option>
                        <option value="3">জলসায়ে ওরশ পাক</option>
                        <option value="4">কল্যাণ</option>
                        <option value="5">ভাড়া</option>
                        <option value="6">অনন্যা</option>
                    </select>
                </div>
                <div class="form-group" id="other_program_name" style="display: none;">
                    <label for="" class="form-lable">অনন্যা খেদমতের নাম </label>
                    <input class="form-control" type="text" placeholder="অনন্যা খেদমতের নাম " name="other_program_name">
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="" class="form-lable">খেদমতের পরিমাণ </label>
                            <input class="form-control" type="number" placeholder="কেদমতের পরিমাণ" name="khedmot_amount" min="0" value="0">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="" class="form-lable">মানত পরিমাণ </label>
                            <input class="form-control" type="number" placeholder="মানত পরিমাণ" name="manat_amount" min="0" value="0">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="" class="form-lable">কল্যাণ পরিমাণ </label>
                            <input class="form-control" type="number" placeholder="কল্যাণ পরিমাণ" name="kollan_amount" min="0" value="0">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="" class="form-lable">ভাড়া পরিমাণ </label>
                            <input class="form-control" type="number" placeholder="ভাড়া পরিমাণ" name="rent_amount" min="0" value="0">
                        </div>
                    </div>
                </div>
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
<!-- Edit Modal end-->
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            $('#program_name').on('change', function() {
                if($('#program_name').val() == 6) {
                    $('#other_program_name').show();
                } else {
                    $('#other_program_name').hide();
                }
            });

            $(document).on('click', '.approveBtn', function (e) {
                e.preventDefault();
                const id = $(this).data('id');
                Swal.fire({
                    title: 'খেদমতটি গ্রহণ করতে চান?',
                    text: "আপনি এটি আর বতিল করতে পারবেন না!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'হ্যা, গ্রহণ করুন',
                    cancelButtonText: "না, বাতিল করুন!",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send an AJAX request to approve the fund collection
                        $.ajax({
                            url: `/fund_collections/${id}/approve`,
                            method: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                            },
                            success: function (response) {
                                // Swal.fire('Approved!', response.message, 'success');
                                Swal.fire({
                                    position: "top-end",
                                    icon: "success",
                                    title: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                // Optionally reload the page or update the UI
                                location.reload();
                            },
                            error: function (xhr) {
                                Swal.fire('Error!', 'Something went wrong. Please try again later.', 'error');
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire('Canceled', 'Your fund collection is safe :)', 'error');

                    }

                });
            });

            $(document).on('click', '.cancelBtn', function (e) {
                e.preventDefault();
                const id = $(this).data('id');
                Swal.fire({
                    title: 'খেদমতটি বাতিল করতে চান?',
                    text: "আপনি এটি আর গ্রহণ করতে পারবেন না!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'হ্যা, বাতিল করব!',
                    cancelButtonText: "না!",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send an AJAX request to cancel the fund collection
                        $.ajax({
                            url: `/fund_collections/${id}/cancel`,
                            method: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                            },
                            success: function (response) {
                                Swal.fire('Canceled!', response.message, 'success');
                                // Optionally reload the page or update the UI
                                location.reload();
                            },
                            error: function (xhr) {
                                Swal.fire('Error!', 'Something went wrong. Please try again later.', 'error');
                            }
                        });
                    }
                    else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire('Canceled', 'Your fund collection is safe :)', 'error');

                    }
                });
            });

        });
    </script>
@endpush
