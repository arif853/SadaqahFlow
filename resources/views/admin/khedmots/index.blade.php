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
                        {{-- <div class="d-flex justify-content-between"> --}}
                            <h4 class="card-title">খেদমত তালিকা</h4>
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

                            <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 col-xxl-3 mb-10">
                                <label for="searchInputProgram">অনুষ্ঠান দিয়ে খুঁজুন</label>
                                <select class="form-control" id="searchInputProgram">
                                    <option value="">সকল অনুষ্ঠান</option>
                                    @foreach ($programTypes as $programType)
                                        <option value="{{$programType->id}}">{{ $programType->name }}{{ $programType->status ? ' (সক্রিয়)' : '' }}</option>
                                    @endforeach
                                </select>
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
        {{-- Result summary: total count + running totals for the current filter --}}
        <div class="row" id="khedmotSummary" style="display: none;">
            <div class="col-12 mb-10">
                <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
                    <span class="badge bg-info text-white">মোট: <span id="summaryCount">0</span> টি</span>
                    <span class="badge bg-success text-white">খেদমত: &#2547;<span id="summaryKhedmot">0</span></span>
                    <span class="badge bg-warning text-dark">মানত: &#2547;<span id="summaryManat">0</span></span>
                </div>
            </div>
        </div>
        {{-- Single AJAX-driven, paginated card list --}}
        <div class="row" id="khedmotList"></div>
        <div class="row">
            <div class="col-12 text-center mb-20">
                <div id="khedmotLoader" style="display: none;">
                    <span class="spinner-border spinner-border-sm" role="status"></span> লোড হচ্ছে...
                </div>
                <p id="khedmotEmpty" class="text-center" style="display: none;">কোন ফলাফল পাওয়া যায়নি</p>
                <button type="button" id="loadMoreBtn" class="btn btn-outline-primary btn-md" style="display: none;">আরও দেখুন</button>
                <div id="khedmotSentinel" style="height: 1px;"></div>
            </div>
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
            <form id="addForm" action="{{route('khedmots.store')}}" method="POST" >
                @csrf
                @method('POST')
                <div class="row">
                    <div class="col-6 col-sm-12 col-md-6">
                        <div class="form-group">
                            <label for="" class="form-lable">তারিখ </label>
                            <input class="form-control" type="date" placeholder="তারিখ " name="date" value="{{ now()->toDateString() }}">
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
                            <option value="{{$programType->id}}" {{ (isset($activeProgram) && $activeProgram && $activeProgram->id == $programType->id) ? 'selected' : '' }}>{{$programType->name}}</option>

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

            // ---- Paginated, infinite-scroll list state ----
            let currentPage = 0;
            let lastPage = 1;
            let isLoading = false;
            let renderedCount = 0; // running serial for the number column
            let summary = { total: 0, khedmot: 0, manat: 0 };

            // Gather every active filter so they combine instead of overriding each other
            function gatherFilters() {
                return {
                    date: $('#searchInput').val(),
                    name: $('#searchInput2').val(),
                    userid: $('#searchInput3').val(),
                    program_id: $('#searchInputProgram').val()
                };
            }

            function renderSummary() {
                $('#summaryCount').text(summary.total);
                $('#summaryKhedmot').text(summary.khedmot);
                $('#summaryManat').text(summary.manat);
                $('#khedmotSummary').show();
            }

            // Load one page of results. reset=true clears the list and starts at page 1.
            function loadKhedmots(reset) {
                if (isLoading) return;
                if (!reset && currentPage >= lastPage) return;

                isLoading = true;
                $('#khedmotLoader').show();
                $('#loadMoreBtn').hide();

                const params = gatherFilters();
                params.page = reset ? 1 : currentPage + 1;

                $.ajax({
                    url: '/khedmots/khedmot-search/search',
                    type: 'GET',
                    data: params,
                    dataType: 'json',
                    success: function(response) {
                        const data = response.data || [];
                        const meta = response.meta || {};

                        if (reset) {
                            $('#khedmotList').empty();
                            renderedCount = 0;
                        }

                        const fragment = document.createDocumentFragment();
                        data.forEach(function(khedmot) {
                            fragment.appendChild(createKhedmotCard(khedmot, renderedCount++));
                        });
                        document.getElementById('khedmotList').appendChild(fragment);
                        if (window.feather) { feather.replace(); }

                        currentPage = meta.current_page || params.page;
                        lastPage = meta.last_page || 1;

                        summary.total = meta.total || 0;
                        summary.khedmot = meta.khedmot_sum || 0;
                        summary.manat = meta.manat_sum || 0;
                        renderSummary();

                        $('#khedmotEmpty').toggle(summary.total === 0);
                        $('#loadMoreBtn').toggle(currentPage < lastPage);
                    },
                    error: function(xhr) {
                        console.error('Search Error:', xhr.responseText);
                    },
                    complete: function() {
                        isLoading = false;
                        $('#khedmotLoader').hide();
                    }
                });
            }

            const debouncedReset = debounce(function() { loadKhedmots(true); }, 300);

            // Any filter change resets to page 1
            $(document).on('change', '#searchInput, #searchInput3, #searchInputProgram', function() {
                loadKhedmots(true);
            });
            $(document).on('input', '#searchInput2', debouncedReset);

            // Manual "load more" (reliable fallback and explicit control)
            $(document).on('click', '#loadMoreBtn', function() {
                loadKhedmots(false);
            });

            // Auto-load the next page when the sentinel scrolls into view.
            // SimpleBar scrolls an inner element, so use it as the observer root when present.
            const scrollRoot = document.querySelector('.theme-body .simplebar-content-wrapper');
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver(function(entries) {
                    if (entries[0].isIntersecting) {
                        loadKhedmots(false);
                    }
                }, { root: scrollRoot || null, rootMargin: '200px' });
                observer.observe(document.getElementById('khedmotSentinel'));
            }

            // Seed the name filter from the global header search (?q=...) before first load
            (function applyGlobalQuery() {
                const q = new URLSearchParams(window.location.search).get('q');
                if (q) { $('#searchInput2').val(q); }
            })();

            // Initial load
            loadKhedmots(true);

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
                    <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 col-xxl-3" data-khedmot-id="${khedmot.id}" data-khedmot-amount="${khedmotAmount}" data-manat-amount="${manatAmount}">
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
                const $form = $(this);
                const $submitBtn = $form.find('button[type="submit"]');
                $submitBtn.prop('disabled', true);
                $.ajax({
                    url: $form.attr('action'),
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.khedmot) {
                            // Rebuild the edited card in place, preserving its serial number.
                            const k = response.khedmot;
                            const $old = $('[data-khedmot-id="' + k.id + '"]');
                            const serial = parseInt($old.find('table tr:first td').eq(1).text(), 10) || 1;
                            // Adjust running totals by the delta between old and new amounts.
                            summary.khedmot += (Number(k.khedmot_amount) || 0) - (Number($old.data('khedmot-amount')) || 0);
                            summary.manat += (Number(k.manat_amount) || 0) - (Number($old.data('manat-amount')) || 0);
                            renderSummary();
                            const newCard = createKhedmotCard(k, serial - 1);
                            if ($old.length) {
                                $old.replaceWith(newCard);
                            }
                            if (window.feather) { feather.replace(); }
                        }
                        $('#editModal').modal('hide');
                        $form[0].reset();
                        showNotification(response.status, response.message, response.status);
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'খেদমত আপডেট করা যায়নি।';
                        showNotification('danger', message, 'Danger');
                    },
                    complete: function() {
                        $submitBtn.prop('disabled', false);
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
                            dataType: 'json',
                            success: function(response) {
                                if (response.status === 'success') {
                                    // Adjust running totals, then remove the card and re-sync serials.
                                    const $card = $('[data-khedmot-id="' + id + '"]');
                                    summary.total = Math.max(0, summary.total - 1);
                                    summary.khedmot -= Number($card.data('khedmot-amount')) || 0;
                                    summary.manat -= Number($card.data('manat-amount')) || 0;
                                    renderSummary();
                                    $card.remove();
                                    renumberKhedmotCards();
                                    $('#khedmotEmpty').toggle(summary.total === 0);
                                }
                                showNotification(
                                    response.status,
                                    response.message,
                                    response.status
                                );
                            },
                            error: function(xhr) {
                                const message = xhr.responseJSON?.message || 'খেদমত ডিলিট করা যায়নি।';
                                showNotification('danger', message, 'Danger');
                            }
                        });
                    } else if (result.isDenied) {
                        Swal.fire('Changes are not saved', '', 'info')
                    }
                })
            });

            // Keep the number (serial) column in sync after a card is added/removed.
            function renumberKhedmotCards() {
                const $cards = $('#khedmotList').children('div');
                $cards.each(function (i) {
                    $(this).find('table tr:first td').eq(1).text(i + 1);
                });
                renderedCount = $cards.length;
            }

            // Quick-entry add: submit via AJAX and prepend the new card so the
            // collector isn't bounced through a full page reload on every record.
            $(document).on('submit', '#addForm', function (e) {
                e.preventDefault();
                const $form = $(this);
                const $submitBtn = $form.find('button[type="submit"]');
                $submitBtn.prop('disabled', true);

                $.ajax({
                    url: $form.attr('action'),
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (response) {
                        if (response.khedmot) {
                            const k = response.khedmot;
                            $('#khedmotList').prepend(createKhedmotCard(k, 0));
                            renumberKhedmotCards();
                            if (window.feather) { feather.replace(); }
                            // Keep running totals in sync with the new record.
                            summary.total += 1;
                            summary.khedmot += Number(k.khedmot_amount) || 0;
                            summary.manat += Number(k.manat_amount) || 0;
                            renderSummary();
                            $('#khedmotEmpty').hide();
                        }
                        $('#addModal').modal('hide');
                        // Reset the form but keep the sensible defaults (today's
                        // date and the active program) ready for the next entry.
                        $form[0].reset();
                        $('#member_id1').val('').trigger('change');
                        showNotification(response.status, response.message, response.status);
                    },
                    error: function (xhr) {
                        const message = xhr.responseJSON?.message || 'খেদমত যোগ করা যায়নি।';
                        showNotification('danger', message, 'Danger');
                    },
                    complete: function () {
                        $submitBtn.prop('disabled', false);
                    }
                });
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
