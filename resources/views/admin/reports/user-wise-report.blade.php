@extends('layouts.admin')
@section('title','কর্মীদের খেদমত তালিকা')
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
                <li class="breadcrumb-item active"><a href="javascript:void(0);">কর্মীদের খেদমত তালিকা</a></li>
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
                        {{-- <div class="d-flex justify-content-between"> --}}
                            <h4 class="card-title">কর্মীদের খেদমত তালিকা</h4>

                        {{-- </div> --}}

                    </div>
                    <div class="card-body">
                        <form action="{{url('/reports/user-wise-report/fetchKhedmot')}}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 col-xxl-3 mb-10">
                                    <label for="searchInput2">অনুষ্ঠান সিলেক্ট করুন <span class="text-danger">*</span></label>
                                    <select id="searchInput2" class="form-control" name="programId" required>
                                        <option value="">অনুষ্ঠান নির্বাচন করুন</option>
                                        @foreach ($programs as $program)
                                            <option value="{{$program->id}}">{{ $program->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if (Auth::user()->getRoleNames()->contains('Super Admin') || Auth::user()->getRoleNames()->contains('Admin'))
                                <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 col-xxl-3 mb-10">
                                    <label for="searchInput3">কর্মী দিয়ে খুঁজুন</label>
                                    <select id="searchInput3" class="form-control" name="userId" required>
                                        <option value="">কর্মী নাম নির্বাচন করুন</option>
                                        @foreach ($users as $user)
                                            <option value="{{$user->id}}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @else
                                <input type="hidden" name="userId" value="{{Auth::user()->id}}">
                                @endif
                                <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 col-xxl-3 d-flex">
                                    {{-- <button type="button" id="previewBtn" class="btn btn-primary btn-lg me-2 my-auto p-10">Preview</button> --}}
                                    <button type="submit" class="btn btn-danger btn-lg my-auto p-10">Download</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="khedmotCard">

        </div>
        <div id="khedmotCardContainer" class="row">

        </div>
    </div>
</div>
@endsection
@push('script')
<script src="https://cdn.jsdelivr.net/npm/dayjs@1.11.7/dayjs.min.js"></script>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let date = ''; // Initialize variables
            let name = '';
            let userId = '';
            let programId = '';



            $(document).on('change', '#searchInput3', function () {
                let pid = $('#searchInput2').val();
                if (pid == '') {
                    alert('Please select a program to preview the report');
                    return;
                }
                userId = $(this).val();

                $.ajax({
                    url: `/reports/user-wise-report/fetchKhedmot`, // Corrected to use base URL
                    type: "GET",
                    data: { userid: userId, programId: pid }, // Use `data` instead of query string
                    success: function (response) {
                        searchHandler(response.khedmots); // Trigger search
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        console.error('Response:', xhr.responseText);
                    }
                });
            });

            // Preview button click (explicit preview)
            $(document).on('click', '#previewBtn', function () {
                let pid = $('#searchInput2').val();
                if (pid == '') {
                    alert('Please select a program to preview the report');
                    return;
                }
                let uid = $('#searchInput3').val();
                // if (!uid) {
                //     alert('Please select a user to preview the report');
                //     return;
                // }
                $.ajax({
                    url: `/reports/user-wise-report/fetchKhedmot`,
                    type: "GET",
                    data: { userid: uid, programId: pid },
                    success: function (response) {
                        searchHandler(response.khedmots);
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        console.error('Response:', xhr.responseText);
                    }
                });
            });


            // Function to handle the search AJAX request
            function searchHandler(response) {
                // console.log(response);
                // $('#khedmotCard').empty();
                $('#khedmotCardContainer').empty();
                response.forEach(function(khedmot, key) {
                    // console.log(khedmot);
                    let programName = khedmot.program ? khedmot.program.name : '';

                    let khedmotAmount = '';
                    let manatAmount = '';

                    if(khedmot.khedmot_amount != null) {
                        khedmotAmount = khedmot.khedmot_amount;
                    }
                    if(khedmot.manat_amount != null) {
                        manatAmount = khedmot.manat_amount;
                    }

                    let isCollected = '';
                    if(khedmot.is_collected == 1){
                        isCollected = '<span class="badge bg-success text-white w-25 text-center">হ্যা</span>';
                    } else {
                        isCollected = '<span class="badge bg-danger text-white w-25 text-center">না</span>';
                    }

                    let userName = '';
                    if(khedmot.user != null) {
                        userName = khedmot.user.name;
                    }
                    let formattedDate = dayjs(khedmot.date).format('DD-MMM-YYYY');
                    let nicename = khedmot.member.nickName ? '('+khedmot.member.nickName+')' : '';
                    // console.log(nicename);

                    $('#khedmotCardContainer').append(`
                        <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 col-xxl-3" >
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="50%"><strong>নং:</strong></td>
                                            <td width="50%">${key + 1}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%" ><strong>তারিখ:</strong></td>
                                            <td width="50%">${formattedDate}</td>
                                        </tr>
                                        <tr>
                                            <td> <strong>কল্যাণ নাম্বার :</strong></td>
                                            <td>${khedmot.member.kollan_id}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%"><strong>জাকের নাম:</strong></td>
                                            <td width="50%">${khedmot.member.name} ${nicename}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%"><strong>অনুষ্ঠান নাম:</strong></td>
                                            <td>${programName}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%"><strong>খেদমত:</strong></td>
                                            <td width="50%">
                                                <span>খেদমত : ${khedmotAmount} টাকা</span>
                                                <span>মানত : ${manatAmount} টাকা</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" ><strong>মন্তব্য:</strong></td>
                                            <td width="50%">${khedmot.comment ?? ''}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%" ><strong>জমা করেছেন:</strong></td>
                                            <td width="50%">
                                                ${isCollected}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%"><strong>কর্মি:</strong></td>
                                            <td width="50%">${userName}</td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>
                    `);
                });
            }


        });
    </script>
@endpush
