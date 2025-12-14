@extends('layouts.admin')
@section('title','কল্যাণ খেদমত তালিকা')
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
                <li class="breadcrumb-item active"><a href="javascript:void(0);">কল্যাণ খেদমত</a></li>
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
                            <h4 class="card-title">কল্যাণ খেদমত তালিকা</h4>
                            @can('create khedmot')
                            <button type="button" class="btn btn-success btn-md" data-bs-toggle="modal" data-bs-target="#addModal">নতুন খেদমত যোগ করুন</button>
                            @endcan
                        {{-- </div> --}}

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 col-xxl-3 mb-10">
                                <label for="searchInput">তারিখ</label>
                                <input type="date" class="form-control" placeholder="তারিখ খুঁজুন" id="searchInput">
                            </div>

                            <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 col-xxl-3 mb-10">
                                <label for="searchInput2">খেদমত খুঁজুন</label>
                                <input type="text" class="form-control" placeholder="যাকের নাম / কল্যাণ নাম্বার খুঁজুন" id="searchInput2">
                            </div>
                            @if (Auth::user()->hasRole(['Super Admin','Admin']))
                            <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 col-xxl-3 mb-10">
                                <label for="searchInput3">কর্মী দিয়ে খুঁজুন</label>
                                <select class="form-control" id="searchInput3">
                                    <option value="">কর্মী নাম নির্বাচন করুন</option>
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                        </div>

                    </div>

                </div>
            </div>
        </div>
        <div class="row" id="khedmotCard">
            @foreach ($khedmots as $key => $khedmot)
            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 col-xxl-3" >
                <div class="card">
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="50%"><strong>নং:</strong></td>
                                <td width="50%">{{ $key + 1 }}</td>
                            </tr>
                            <tr>
                                <td width="50%" ><strong>তারিখ:</strong></td>
                                <td width="50%">{{ Carbon\Carbon::parse($khedmot->date)->format('d-M-Y') }}</td>
                            </tr>
                             <tr>
                                <td> <strong>কল্যাণ নাম্বার :</strong></td>
                                <td>{{ $khedmot->member->kollan_id }}</td>
                            </tr>
                            <tr>
                                <td width="50%"><strong>জাকের নাম:</strong></td>
                                <td width="50%">{{ $khedmot->member->name }} {{$khedmot->member->nickName? '('.$khedmot->member->nickName.')':''}}</td>

                            </tr>
                            <tr>
                                <td width="50%"><strong>অনুষ্ঠান নাম:</strong></td>
                                <td>
                                    {{-- @if($khedmot->program_name == 4)
                                        {{ $khedmot->other_program_name }}
                                    @elseif ($khedmot->program_name == 1)
                                        {{ 'ওরশ পাক' }}
                                    @elseif ($khedmot->program_name == 2)
                                        {{ 'বেসালত দিবস' }}
                                    @elseif ($khedmot->program_name == 3)
                                        {{ 'জলসায়ে ওরশ পাক' }}
                                    @endif --}}
                                    {{$khedmot->program ? $khedmot->program->name : ''}}
                                </td>
                            </tr>
                            <tr>
                                <td width="50%"><strong>খেদমত:</strong></td>
                                <td width="50%">
                                    @if($khedmot->khedmot_amount != null)
                                        <span>খেদমত : {{ $khedmot->khedmot_amount }} টাকা</span>
                                    @endif
                                    @if($khedmot->manat_amount != null)
                                        <span>মানত : {{ $khedmot->manat_amount }} টাকা</span>
                                    @endif
                                    @if($khedmot->kalyan_amount != null)
                                        <span>কল্যাণ : {{ $khedmot->kalyan_amount }} টাকা</span>
                                    @endif
                                    @if($khedmot->rent_amount != null)
                                        <span> ভাড়া : {{ $khedmot->rent_amount }} টাকা</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td width="50%" ><strong>মন্তব্য:</strong></td>
                                <td width="50%">{{ $khedmot->comment }}</td>
                            </tr>
                            <tr>
                                <td width="50%" ><strong>জমা করেছেন:</strong></td>
                                <td width="50%">
                                    @if($khedmot->is_collected == 1)
                                        <span class="badge bg-success text-white w-25 text-center">হ্যা</span>
                                    @else
                                        <span class="badge bg-danger text-white w-25 text-center">না</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td width="50%"><strong>কর্মি:</strong></td>
                                <td width="50%">{{ $khedmot->user->name }}</td>
                            </tr>
                            <tr>
                                <td width="50%"><strong>ক্রিয়াকলা:</strong></td>
                                <td width="50%">
                                    @can('update khedmot')
                                    @if (!$khedmot->is_collected)
                                    <button type="button" class="btn btn-outline-warning btn-md me-2 editBtn" data-id="{{$khedmot->id}}" data-bs-toggle="modal" data-bs-target="#editModal">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    @endif
                                    @endcan
                                    @can('show khedmot')
                                    <button type="button" class="btn btn-outline-info btn-md me-2 viewBtn" data-id="{{$khedmot->id}}" data-bs-toggle="modal" data-bs-target="#viewModal">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    @endcan
                                    @can('delete khedmot')
                                    @if (!$khedmot->is_collected)
                                    <button type="button" class="btn btn-outline-danger btn-md me-2 deleteBtn" data-id="{{$khedmot->id}}">
                                        <i class="fa fa-trash" ></i>
                                    </button>
                                    @endif
                                    @endcan
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
 <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">
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
                    <label for="" class="form-lable">জাকের নামে <span class="text-danger">*</span></label>
                    <select name="member_id" id="member_id2" class="form-control select2">
                        <option value="">জাকের নাম নির্বাচন করুন</option>
                        @foreach ($members as $member)
                            <option value="{{$member->id}}">
                                <img src="{{ asset('storage/' . $member->image) }}" alt="Avatar" style="width: 50px; height: 50px; border-radius: 5%; margin-right: 10px;">
                                {{$member->name}}
                                {{$member->nickName ? '('.$member->nickName.')':''}} - {{$member->kollan_id}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">অনুষ্ঠান নাম <span class="text-danger">*</span></label>
                    <select name="program_id" id="program_name" class="form-control">
                        <option value="">অনুষ্ঠান নাম নির্বাচন করুন</option>
                        @foreach (App\Models\ProgramType::where('status',1)->get() as $programType)
                            <option value="{{$programType->id}}">{{$programType->name}}</option>

                        @endforeach
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
                </div>
                {{-- <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="" class="form-lable">কল্যাণের পরিমাণ </label>
                            <input class="form-control" type="number" placeholder="কল্যাণের পরিমাণ" name="kalyan_amount" min="0" >
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="" class="form-lable">ভাড়া পরিমাণ </label>
                            <input class="form-control" type="number" placeholder="ভাড়া পরিমাণ" name="rent_amount" min="0">
                        </div>
                    </div>
                </div> --}}
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

 <!-- Add Modal Start-->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addModalLabel">
                    খেদমত যোগ করুন
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('khedmots.store')}}" method="POST" >
                @csrf
                @method('POST')
                <div class="row">
                    <div class="col-6 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="" class="form-lable">তারিখ </label>
                            <input class="form-control" type="date" placeholder="তারিখ " name="date">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">জাকের নামে <span class="text-danger">*</span></label>
                    <select name="member_id" id="member_id1" class="form-control">
                        <option value="">জাকের নাম নির্বাচন করুন</option>
                        @foreach ($members as $member)
                            <option value="{{$member->id}}">
                                <img src="{{ asset('storage/' . $member->image) }}" alt="Avatar" style="width: 50px; height: 50px; border-radius: 5%; margin-right: 10px;">
                                {{$member->name}}
                                {{$member->nickName ? '('.$member->nickName.')':''}} - {{$member->kollan_id}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">অনুষ্ঠান নাম <span class="text-danger">*</span></label>
                    <select name="program_id" id="program_name1" class="form-control">
                        <option value="">অনুষ্ঠান নাম নির্বাচন করুন</option>
                        @foreach (App\Models\ProgramType::where('status',1)->get() as $programType)
                            <option value="{{$programType->id}}">{{$programType->name}}</option>

                        @endforeach
                    </select>
                </div>
                <div class="form-group" id="other_program_name1" style="display: none;">
                    <label for="" class="form-lable">অনন্যা খেদমতের নাম </label>
                    <input class="form-control" type="text" placeholder="অনন্যা খেদমতের নাম " name="other_program_name">
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="" class="form-lable">খেদমতের পরিমাণ </label>
                            <input class="form-control" type="number" placeholder="খেদমতের পরিমাণ" name="khedmot_amount" min="0" value="0">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="" class="form-lable">মানত পরিমাণ </label>
                            <input class="form-control" type="number" placeholder="মানত পরিমাণ" name="manat_amount" min="0" value="0">
                        </div>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="" class="form-lable">কল্যাণের পরিমাণ </label>
                            <input class="form-control" type="number" placeholder="কল্যাণের পরিমাণ" name="kalyan_amount" min="0" >
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="" class="form-lable">ভাড়া পরিমাণ </label>
                            <input class="form-control" type="number" placeholder="ভাড়া পরিমাণ" name="rent_amount" min="0">
                        </div>
                    </div>
                </div> --}}
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
<!-- Add Modal end-->

 <!-- view Modal Start-->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">
                        খেদমত দেখুন
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <style>
                    #viewMemberImage {
                        width: 50px;
                        height: 50px;
                        border-radius: 50%;
                    }
                    #viewMember {
                        margin-left: 10px;
                        font-weight: bold;
                    }
                    #viewKollanId {
                        margin-left: 10px;
                        font-weight: bold;
                    }
                    #viewProgram {
                        margin-left: 10px;
                        font-weight: bold;
                    }
                    #viewKhedmot {
                        margin-left: 10px;
                        font-weight: bold;
                    }
                    #viewManat {
                        margin-left: 10px;
                        font-weight: bold;
                    }
                    #viewComment {
                        margin-left: 10px;
                        font-weight: bold;
                    }
                    #viewOtherProgram {
                        margin-left: 10px;
                        font-weight: bold;
                    }

                </style>
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <p class="mb-4">জাকের নাম :</p>
                        </td>
                        <td> <img src="{{asset('storage/images/members/')}}" alt="" id="viewMemberImage"> <span id="viewMember"></span></td>

                    </tr>
                    <tr>
                        <td> কল্যাণ নাম্বার :</td>
                        <td> <span id="viewKollanId"></span></td>
                    </tr>
                    <tr>
                        <td> অনুষ্ঠান নাম :</td>
                        <td> <span id="viewProgram"></span> <span id="viewOtherProgram"></span></td>
                    </tr>
                    <tr>
                        <td> খেদমতের পরিমাণ :</td>
                        <td> <span id="viewKhedmot"></span> টাকা</td>
                    </tr>
                    <tr>
                        <td> মানত পরিমাণ :</td>
                        <td> <span id="viewManat"></span> টাকা</td>
                    </tr>
                    {{-- <tr>
                        <td> ভাড়া পরিমাণ :</td>
                        <td> <span id="viewRent"></span> টাকা</td>
                    </tr>
                    <tr>
                        <td> কল্যাণ পরিমাণ :</td>
                        <td> <span id="viewKalyan"></span> টাকা</td>
                    </tr> --}}
                    <tr>
                        <td> মন্তব্য :</td>
                        <td> <span id="viewComment"></span></td>
                    </tr>
                    <tr>
                        <td> কর্মি :</td>
                        <td> <span id="viewUser"></span></td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</div>
<!-- view Modal end-->

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

            // Debounce function to limit API calls
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            }

            // Centralized search function
            function performSearch(params) {
                $.ajax({
                    url: '/khedmots/khedmot-search/search',
                    type: 'GET',
                    data: params,
                    success: searchHandler,
                    error: function(xhr, status, error) {
                        console.error('Search Error:', error);
                        console.error('Response:', xhr.responseText);
                    }
                });
            }

            // Debounced search function (300ms delay)
            const debouncedSearch = debounce(performSearch, 300);

            // Event delegation for all search inputs
            $(document).on('change', '#searchInput', function() {
                performSearch({
                    date: $(this).val()
                });
            });

            $(document).on('input', '#searchInput2', function() {
                debouncedSearch({
                    name: $(this).val()
                });
            });

            $(document).on('change', '#searchInput3', function() {
                performSearch({
                    userid: $(this).val()
                });
            });

            // Optimized search handler with template literal
            function searchHandler(response) {
                const khedmotCard = $('#khedmotCard');
                khedmotCard.empty();
                const $container = $('#khedmotCardContainer');
                $container.empty();

                if (!response || response.length === 0) {
                    $container.append('<p class="text-center">কোন ফলাফল পাওয়া যায়নি</p>');
                    return;
                }
                // Use DocumentFragment for better performance
                const fragment = document.createDocumentFragment();
                response.forEach((khedmot, index) => {
                    const card = createKhedmotCard(khedmot, index);
                    fragment.appendChild(card);
                });
                $container.append(fragment);
            }

            // Separate function to create card (easier to maintain)
            function createKhedmotCard(khedmot, index) {
                // console.log(khedmot);

                const khedmotAmount = khedmot.khedmot_amount || 0;
                const manatAmount = khedmot.manat_amount || 0;
                const isCollected = khedmot.is_collected == 1 ?
                    '<span class="badge bg-success text-white w-25 text-center">হ্যা</span>' :
                    '<span class="badge bg-danger text-white w-25 text-center">না</span>';
                const userName = khedmot.user?.name || '';
                const formattedDate = dayjs(khedmot.date).format('DD-MMM-YYYY');
                const nicename = khedmot.member.nickName ? `(${khedmot.member.nickName})` : '';

                const cardHTML = `
                    <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 col-xxl-3">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="50%"><strong>নং:</strong></td>
                                        <td width="50%">${index + 1}</td>
                                    </tr>
                                    <tr>
                                        <td width="50%"><strong>তারিখ:</strong></td>
                                        <td width="50%">${formattedDate}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>কল্যাণ নাম্বার:</strong></td>
                                        <td>${khedmot.member.kollan_id}</td>
                                    </tr>
                                    <tr>
                                        <td width="50%"><strong>জাকের নাম:</strong></td>
                                        <td width="50%">${khedmot.member.name} ${nicename}</td>
                                    </tr>
                                    <tr>
                                        <td width="50%"><strong>অনুষ্ঠান নাম:</strong></td>
                                        <td>${khedmot?.program?.name || ''}</td>
                                    </tr>
                                    <tr>
                                        <td width="50%"><strong>খেদমত:</strong></td>
                                        <td width="50%">
                                            <span>খেদমত: ${khedmotAmount} টাকা</span><br>
                                            <span>মানত: ${manatAmount} টাকা</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%"><strong>মন্তব্য:</strong></td>
                                        <td width="50%">${khedmot.comment || ''}</td>
                                    </tr>
                                    <tr>
                                        <td width="50%"><strong>জমা করেছেন:</strong></td>
                                        <td width="50%">${isCollected}</td>
                                    </tr>
                                    <tr>
                                        <td width="50%"><strong>কর্মি:</strong></td>
                                        <td width="50%">${userName}</td>
                                    </tr>
                                    <tr>
                                        <td width="50%"><strong>ক্রিয়াকলা:</strong></td>
                                        <td width="50%">
                                            ${khedmot.is_collected ? `<style>.editBtn[data-id="${khedmot.id}"], .deleteBtn[data-id="${khedmot.id}"] { display: none !important; }</style>` : ''}
                                            @can('update khedmot')
                                            <button type="button" class="btn btn-outline-warning btn-md me-2 editBtn" data-id="${khedmot.id}" data-bs-toggle="modal" data-bs-target="#editModal">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            @endcan
                                            @can('show khedmot')
                                            <button type="button" class="btn btn-outline-info btn-md me-2 viewBtn" data-id="${khedmot.id}" data-bs-toggle="modal" data-bs-target="#viewModal">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            @endcan
                                            @can('delete khedmot')
                                            <button type="button" class="btn btn-outline-danger btn-md me-2 deleteBtn" data-id="${khedmot.id}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            @endcan
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                `;

                const div = document.createElement('div');
                div.innerHTML = cardHTML.trim();
                return div.firstChild;
            }

            // $('#program_name1').on('change', function() {
            //     if($('#program_name1').val() == 4) {
            //         $('#other_program_name1').show();
            //     } else {
            //         $('#other_program_name1').hide();
            //     }
            // });
            // $('#program_name').on('change', function() {
            //     if($('#program_name').val() == 4) {
            //         $('#other_program_name').show();
            //     } else {
            //         $('#other_program_name').hide();
            //     }
            // });

            $(document).on('click', '.editBtn', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: `khedmots/${id}/edit`,
                    type: "GET",
                    success: function(response) {
                        console.log(response);
                        $('#editForm').attr('action', "{{route('khedmots.update', '')}}/" + id);
                        $('#editForm input[name="date"]').val(response.date);
                        $('#editForm select[name="member_id"]').val(response.member_id).change();
                        $('#editForm select[name="program_id"]').val(response.program_id).change();
                        $('#editForm input[name="other_program_name"]').val(response.other_program_name);
                        $('#editForm input[name="khedmot_amount"]').val(response.khedmot_amount);
                        $('#editForm input[name="manat_amount"]').val(response.manat_amount);
                        // $('#editForm input[name="kalyan_amount"]').val(response.kalyan_amount);
                        // $('#editForm input[name="rent_amount"]').val(response.rent_amount);
                        $('#editForm textarea[name="comment"]').val(response.comment);
                        $('#editModal').modal('show');

                    }
                });

                $('#editModal').on('shown.bs.modal', function () {
                    // Destroy if already initialized to prevent duplicates
                    // $('#member_id1').select2('destroy');

                    // Re-initialize with proper parent and width
                    $('#member_id2').select2({
                        dropdownParent: $('#editModal'),
                        width: '100%',
                        placeholder: 'জাকের নাম নির্বাচন করুন'
                    });
                });
            });

            $(document).on('submit', '#editForm', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                console.log(formData);
                $.ajax({
                    url: $(this).attr('action'),
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        $('#editModal').modal('hide');
                        $('#editForm')[0].reset();
                       location.reload();
                        showNotification(
                            response.status,
                            response.message,
                            response.status
                        );
                    },
                    error: function(response) {
                        console.log(response);
                        $('#editModal').modal('hide');
                        $('#editForm')[0].reset();
                       location.reload();

                    }
                });
            });

            $(document).on('click', '.viewBtn', function() {
                var id = $(this).data('id');
                console.log(id);
                $.ajax({
                    url: `khedmots/${id}`,
                    type: "GET",
                    success: function(response) {
                        console.log(response);

                        $('#viewProgram').text(response.program.name);
                        $('#viewModal').modal('show');
                        $('#viewDate').text(response.date);
                        $('#viewMember').text(response.member.name);
                        $('#viewMemberImage').attr('src', `{{asset('storage/')}}/${response.member.image}`);
                        $('#viewKollanId').text(response.member.kollan_id);
                        $('#viewKhedmot').text(response.khedmot_amount);
                        $('#viewManat').text(response.manat_amount);
                        $('#viewKalyan').text(response.kalyan_amount);
                        $('#viewRent').text(response.rent_amount);
                        $('#viewComment').text(response.comment);
                        $('#viewUser').text(response.user.name);
                    }
                });
            });

            $(document).on('click', '.deleteBtn', function (event) {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'খেদমত ডিলেট করবেন?',
                    text: "আপনি এটি পুনরুদ্ধারিত করতে পারবেন না!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'হ্যা'
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/khedmots/${id}`,
                            method: 'DELETE',
                            success: function(response) {
                                location.reload();
                                showNotification(
                                    response.status,
                                    response.message,
                                    response.status
                                );
                            }
                        });
                    } else if (result.isDenied) {
                        Swal.fire('Changes are not saved', '', 'info')
                    }
                })
            });

             // When modal opens, (re)initialize select2
            $('#addModal').on('shown.bs.modal', function () {
                // Destroy if already initialized to prevent duplicates
                // $('#member_id1').select2('destroy');

                // Re-initialize with proper parent and width
                $('#member_id1').select2({
                    dropdownParent: $('#addModal'),
                    width: '100%',
                    placeholder: 'জাকের নাম নির্বাচন করুন'
                });
            });
        });
    </script>
@endpush
