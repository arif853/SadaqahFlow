@extends('layouts.admin')
@section('title','খেদমত')
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
                <li class="breadcrumb-item active"><a href="javascript:void(0);">খেদমত</a></li>
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
                        <h4 class="card-title d-block">খেদমত সংগ্রহের তালিকা</h4>
                        @can('create fund-collection')
                        <a href="{{route('fund.receive.create')}}" class="btn btn-success btn-md" >জমা করুন</a>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-3 col-xl-3 mb-10">
                                <label for="searchInput">তারিখ</label>
                                <input type="date" class="form-control" placeholder="তারিখ খুঁজুন" id="searchInput">
                            </div>
                            <div class="col-12 col-md-3 col-xl-3 mb-10">
                                <label for="searchInput2">খেদমত খুঁজুন</label>
                                <input type="text" class="form-control" placeholder="কর্মী নাম" id="searchInput">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="khedmotCard">
            @foreach ($fundCollections as $key => $fundCollection)
            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 col-xxl-3" >
                <div class="card">
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>নং:</strong></td>
                                <td width="50%">{{ $key + 1 }}</td>
                            </tr>
                            <tr>
                                <td width="40%" ><strong>তারিখ:</strong></td>
                                <td width="50%">{{ Carbon\Carbon::parse($fundCollection->date)->format('d-M-Y') }}</td>
                            </tr>
                             <tr>
                                <td width="40%">জাকের</td>
                                <td width="50%">
                                    @foreach ($fundCollection->khedmots as $index => $khedmot)
                                    {{$index+1}}. {{ $khedmot->member->name }}{{$khedmot->member->nickName ? '('.$khedmot->member->nickName.')':''}},<br>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td width="40%">কর্মী নাম</td>
                                <td width="50%">{{ $fundCollection->submitor->name }}</td>
                            </tr>
                            <tr>
                                <td width="40%">অনুষ্ঠান নাম:</td>
                                <td width="50%">
                                    @if($fundCollection->program_name == 1)
                                        ওরশ পাক
                                    @elseif($fundCollection->program_name == 2)
                                        বেসালত দিবস
                                    @elseif($fundCollection->program_name == 3)
                                        জলসায়ে ওরশ পাক
                                    @elseif($fundCollection->program_name == 4)
                                        কল্যাণ
                                    @elseif($fundCollection->program_name == 5)
                                        ভাড়া
                                    @elseif($fundCollection->program_name == 6)
                                        অনন্যা - {{$fundCollection->other_program_name}}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td  width="40%">কল্যাণ ফান</td>
                                <td width="50%">
                                    @if($fundCollection->khedmot_amount != null)
                                       <span>খেদমতের : {{ $fundCollection->khedmot_amount }} টাকা</span>
                                    @endif
                                    @if($fundCollection->manat_amount != null)
                                       <span> মানতের : {{ $fundCollection->manat_amount }} টাকা</span>
                                    @endif
                                    @if($fundCollection->kollan_amount != null)
                                    <span> কল্যাণের : {{ $fundCollection->kollan_amount }} টাকা</span>
                                    @endif
                                    @if($fundCollection->rent_amount != null)
                                        <span>ভাড়া : {{ $fundCollection->rent_amount }} টাকা</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td  width="40%">মোট পরিমাণ</td>
                                <td width="50%">{{ number_format($fundCollection->total_amount,2) }}</td>
                            </tr>
                            <tr>
                                <td  width="40%">মন্তব্য</td>
                                <td width="50%">{{ $fundCollection->comment }}</td>
                            </tr>
                            @if ($fundCollection->collected_at || $fundCollection->canceled_at)
                            <tr>
                                <td  width="40%">
                                    @if ($fundCollection->status == 'collected')
                                    সংগ্রহ করেছে
                                    @elseif ($fundCollection->status == 'canceled')
                                    বাতিল করেছে
                                    @endif
                                </td>
                                <td width="50%">
                                    @if ($fundCollection->status == 'collected')
                                    {{ $fundCollection->collector->name }},<br>
                                    {{ Carbon\Carbon::parse($fundCollection->collected_at)->format('d-M-Y h:i a') }}
                                    @elseif ($fundCollection->status == 'canceled')
                                    {{ $fundCollection->canceller->name }},<br>
                                    {{ Carbon\Carbon::parse($fundCollection->canceled_at)->format('d-M-Y h:i a') }}
                                    @endif

                                </td>
                            </tr>
                            @endif

                            <tr>
                                <td  width="40%">ক্রিয়াকলা</td>
                                <td width="50%">
                                    @role('Super Admin|Admin')

                                        @if ($fundCollection->status == 'pending')
                                        <a href="#" class="btn btn-outline-success btn-md me-2 approveBtn"
                                            data-id="{{ $fundCollection->id }}">
                                            <i class="fa fa-thumbs-up"></i>
                                        </a>
                                        <a href="#" class="btn btn-outline-danger btn-md me-2 cancelBtn"
                                            data-id="{{ $fundCollection->id }}">
                                            <i class="fa fa-times"></i>
                                        </a>
                                        @elseif ($fundCollection->status == 'collected')
                                        <a href="#" class="btn btn-outline-success btn-md me-2">Collected</a>
                                        @elseif ($fundCollection->status == 'canceled')
                                        <a href="#" class="btn btn-outline-danger btn-md me-2" data-id="{{ $fundCollection->id }}">Canceled</a>
                                        @endif

                                    @else

                                        @if ($fundCollection->status == 'pending')
                                        <a href="#" class="btn btn-outline-warning btn-md me-2">  Pending</a>
                                        @elseif ($fundCollection->status == 'collected')
                                        <a href="#" class="btn btn-outline-success btn-md me-2">Collected</a>
                                        @elseif ($fundCollection->status == 'canceled')
                                        <a href="#" class="btn btn-outline-danger btn-md me-2">Canceled</a>
                                        @endif
                                    @endrole

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
                            url: `/fund/receive/${id}/approve`,
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
                            url: `/fund/receive/${id}/cancel`,
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
