@extends('layouts.admin')
@section('title', $config['title'] . ' সংগ্রহ')
@section('breadcrumb')
<div class="codex-breadcrumb">
    <div class="breadcrumb-contain">
        <div class="left-breadcrumb">
            <ul class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><h1>Dashboard</h1></a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0);">{{ $config['title'] }}</a></li>
            </ul>
        </div>
        <div class="right-breadcrumb">
            <ul>
                <li><div class="bread-wrap"><i class="fa fa-clock-o"></i></div><span class="liveTime"></span></li>
                <li><div class="bread-wrap"><i class="fa fa-calendar"></i></div><span class="getDate"></span></li>
            </ul>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="theme-body common-dash" data-simplebar>
    <div class="custom-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $config['title'] }} সংগ্রহ তালিকা</h4>
                        @can('create khedmot')
                        <button type="button" class="btn btn-success btn-md" data-bs-toggle="modal" data-bs-target="#addModal">নতুন {{ $config['title'] }} সংগ্রহ</button>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-3 mb-10">
                                <label for="filterDate">তারিখ</label>
                                <input type="date" class="form-control" id="filterDate">
                            </div>
                            <div class="col-12 col-md-3 mb-10">
                                <label for="filterMonth">মাস</label>
                                <input type="month" class="form-control" id="filterMonth">
                            </div>
                            <div class="col-12 col-md-3 mb-10">
                                <label for="filterName">খুঁজুন</label>
                                <input type="text" class="form-control" placeholder="জাকের নাম / কল্যাণ নাম্বার" id="filterName">
                            </div>
                            @if (Auth::user()->hasRole(['Super Admin','Admin']))
                            <div class="col-12 col-md-3 mb-10">
                                <label for="filterUser">কর্মী</label>
                                <select class="form-control" id="filterUser">
                                    <option value="">কর্মী নির্বাচন করুন</option>
                                    @foreach ($users as $u)
                                        <option value="{{$u->id}}">{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="collectionSummary" style="display: none;">
            <div class="col-12 mb-10">
                <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
                    <span class="badge bg-info text-white">মোট: <span id="summaryCount">0</span> টি</span>
                    <span class="badge bg-success text-white">{{ $config['title'] }}: &#2547;<span id="summaryAmount">0</span></span>
                </div>
            </div>
        </div>

        <div class="row" id="collectionList"></div>
        <div class="row">
            <div class="col-12 text-center mb-20">
                <div id="collectionLoader" style="display: none;"><span class="spinner-border spinner-border-sm" role="status"></span> লোড হচ্ছে...</div>
                <p id="collectionEmpty" class="text-center" style="display: none;">কোন ফলাফল পাওয়া যায়নি</p>
                <button type="button" id="collectionLoadMore" class="btn btn-outline-primary btn-md" style="display: none;">আরও দেখুন</button>
                <div id="collectionSentinel" style="height: 1px;"></div>
            </div>
        </div>
    </div>
</div>

@php
    $memberOptions = $members;
@endphp

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $config['title'] }} সংগ্রহ যোগ করুন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addForm" action="{{ $config['storeRoute'] }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-lable">জাকের নামে <span class="text-danger">*</span></label>
                        <select name="member_id" id="addMember" class="form-control">
                            <option value="">জাকের নাম নির্বাচন করুন</option>
                            @foreach ($memberOptions as $member)
                                <option value="{{$member->id}}">{{$member->name}} {{$member->nickName ? '('.$member->nickName.')' : ''}} - {{$member->kollan_id}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-lable">মাস <span class="text-danger">*</span></label>
                                <input class="form-control" type="month" name="month" value="{{ now()->format('Y-m') }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-lable">সংগ্রহের তারিখ <span class="text-danger">*</span></label>
                                <input class="form-control" type="date" name="date" value="{{ now()->toDateString() }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-lable">{{ $config['title'] }} পরিমাণ <span class="text-danger">*</span></label>
                        <input class="form-control" type="number" name="{{ $config['amountField'] }}" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label class="form-lable">মন্তব্য</label>
                        <textarea class="form-control" name="comment" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success btn-md pull-right">জমা করুণ</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $config['title'] }} সম্পাদন করুন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-lable">জাকের নামে</label>
                        <input class="form-control" type="text" id="editMemberName" disabled>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-lable">মাস <span class="text-danger">*</span></label>
                                <input class="form-control" type="month" name="month">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-lable">সংগ্রহের তারিখ <span class="text-danger">*</span></label>
                                <input class="form-control" type="date" name="date">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-lable">{{ $config['title'] }} পরিমাণ <span class="text-danger">*</span></label>
                        <input class="form-control" type="number" name="{{ $config['amountField'] }}" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label class="form-lable">মন্তব্য</label>
                        <textarea class="form-control" name="comment" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success btn-md pull-right">জমা করুণ</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    // Config injected from the controller so one view serves both কল্যাণ and ভাড়া.
    const CFG = {
        type: @json($config['type']),
        amountField: @json($config['amountField']),
        title: @json($config['title']),
        searchRoute: @json($config['searchRoute']),
        updateBase: @json($config['updateBase']),
    };

    function formatCardDate(dateStr) {
        if (!dateStr) return '';
        const p = String(dateStr).split('T')[0].split(' ')[0].split('-');
        if (p.length !== 3) return dateStr;
        const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        const mi = parseInt(p[1], 10) - 1;
        if (mi < 0 || mi > 11) return dateStr;
        return String(p[2]).padStart(2, '0') + '-' + months[mi] + '-' + p[0];
    }
    function formatMonth(ym) {
        if (!ym) return '';
        const p = String(ym).split('-');
        if (p.length < 2) return ym;
        const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        const mi = parseInt(p[1], 10) - 1;
        return (mi >= 0 && mi < 12) ? months[mi] + ' ' + p[0] : ym;
    }
</script>
<script>
    $(document).ready(function () {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        let currentPage = 0, lastPage = 1, isLoading = false, renderedCount = 0;
        let summary = { total: 0, amount: 0 };

        function debounce(fn, wait) {
            let t;
            return function (...a) { clearTimeout(t); t = setTimeout(() => fn.apply(this, a), wait); };
        }
        function gatherFilters() {
            return {
                date: $('#filterDate').val(),
                month: $('#filterMonth').val(),
                name: $('#filterName').val(),
                userid: $('#filterUser').val()
            };
        }
        function renderSummary() {
            $('#summaryCount').text(summary.total);
            $('#summaryAmount').text(summary.amount);
            $('#collectionSummary').show();
        }

        function createCard(rec, index) {
            const amount = rec[CFG.amountField] || 0;
            const isCollected = rec.is_collected == 1
                ? '<span class="badge bg-success text-white w-25 text-center">হ্যা</span>'
                : '<span class="badge bg-danger text-white w-25 text-center">না</span>';
            const userName = rec.user ? (rec.user.name || '') : '';
            const nick = rec.member && rec.member.nickName ? `(${rec.member.nickName})` : '';
            const memberName = rec.member ? rec.member.name : '';
            const kollan = rec.member ? rec.member.kollan_id : '';
            const html = `
                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-3" data-record-id="${rec.id}" data-amount="${amount}">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr><td width="50%"><strong>নং:</strong></td><td width="50%">${index + 1}</td></tr>
                                <tr><td><strong>মাস:</strong></td><td>${formatMonth(rec.month)}</td></tr>
                                <tr><td><strong>তারিখ:</strong></td><td>${formatCardDate(rec.date)}</td></tr>
                                <tr><td><strong>কল্যাণ নাম্বার:</strong></td><td>${kollan}</td></tr>
                                <tr><td><strong>জাকের নাম:</strong></td><td>${memberName} ${nick}</td></tr>
                                <tr><td><strong>${CFG.title}:</strong></td><td>${amount} টাকা</td></tr>
                                <tr><td><strong>মন্তব্য:</strong></td><td>${rec.comment || ''}</td></tr>
                                <tr><td><strong>জমা করেছেন:</strong></td><td>${isCollected}</td></tr>
                                <tr><td><strong>কর্মি:</strong></td><td>${userName}</td></tr>
                                <tr>
                                    <td><strong>ক্রিয়াকলা:</strong></td>
                                    <td>
                                        ${rec.is_collected ? '' : `
                                        @can('update khedmot')
                                        <button type="button" class="btn btn-outline-warning btn-md me-2 editBtn" data-id="${rec.id}"><i class="fa fa-pencil"></i></button>
                                        @endcan
                                        @can('delete khedmot')
                                        <button type="button" class="btn btn-outline-danger btn-md me-2 deleteBtn" data-id="${rec.id}"><i class="fa fa-trash"></i></button>
                                        @endcan`}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>`;
            const div = document.createElement('div');
            div.innerHTML = html.trim();
            return div.firstChild;
        }

        function renumber() {
            const $c = $('#collectionList').children('div');
            $c.each(function (i) { $(this).find('table tr:first td').eq(1).text(i + 1); });
            renderedCount = $c.length;
        }

        function loadRecords(reset) {
            if (isLoading) return;
            if (!reset && currentPage >= lastPage) return;
            isLoading = true;
            $('#collectionLoader').show();
            $('#collectionLoadMore').hide();
            const params = gatherFilters();
            params.page = reset ? 1 : currentPage + 1;
            $.ajax({
                url: CFG.searchRoute, type: 'GET', data: params, dataType: 'json',
                success: function (response) {
                    const data = response.data || [];
                    const meta = response.meta || {};
                    if (reset) { $('#collectionList').empty(); renderedCount = 0; }
                    const frag = document.createDocumentFragment();
                    data.forEach(function (rec) { frag.appendChild(createCard(rec, renderedCount++)); });
                    document.getElementById('collectionList').appendChild(frag);
                    currentPage = meta.current_page || params.page;
                    lastPage = meta.last_page || 1;
                    summary.total = meta.total || 0;
                    summary.amount = meta.amount_sum || 0;
                    renderSummary();
                    $('#collectionEmpty').toggle(summary.total === 0);
                    $('#collectionLoadMore').toggle(currentPage < lastPage);
                },
                error: function (xhr) { console.error('Collection load error:', xhr.responseText); },
                complete: function () { isLoading = false; $('#collectionLoader').hide(); }
            });
        }

        const debouncedReset = debounce(function () { loadRecords(true); }, 300);
        $(document).on('change', '#filterDate, #filterMonth, #filterUser', function () { loadRecords(true); });
        $(document).on('input', '#filterName', debouncedReset);
        $(document).on('click', '#collectionLoadMore', function () { loadRecords(false); });

        const scrollRoot = document.querySelector('.theme-body .simplebar-content-wrapper');
        if ('IntersectionObserver' in window) {
            const obs = new IntersectionObserver(function (entries) {
                if (entries[0].isIntersecting) { loadRecords(false); }
            }, { root: scrollRoot || null, rootMargin: '200px' });
            obs.observe(document.getElementById('collectionSentinel'));
        }

        loadRecords(true);

        // ---- Add ----
        $('#addModal').on('shown.bs.modal', function () {
            $('#addMember').select2({ dropdownParent: $('#addModal'), width: '100%', placeholder: 'জাকের নাম নির্বাচন করুন' });
        });
        $(document).on('submit', '#addForm', function (e) {
            e.preventDefault();
            const $form = $(this);
            const $btn = $form.find('button[type="submit"]');
            $btn.prop('disabled', true);
            $.ajax({
                url: $form.attr('action'), type: 'POST', data: new FormData(this),
                processData: false, contentType: false, dataType: 'json',
                success: function (response) {
                    if (response.record) {
                        $('#collectionList').prepend(createCard(response.record, 0));
                        renumber();
                        summary.total += 1;
                        summary.amount += Number(response.record[CFG.amountField]) || 0;
                        renderSummary();
                        $('#collectionEmpty').hide();
                    }
                    $('#addModal').modal('hide');
                    $form[0].reset();
                    $('#addMember').val('').trigger('change');
                    showNotification(response.status, response.message, response.status);
                },
                error: function (xhr) {
                    showNotification('danger', (xhr.responseJSON && xhr.responseJSON.message) || 'সংগ্রহ যোগ করা যায়নি।', 'Danger');
                },
                complete: function () { $btn.prop('disabled', false); }
            });
        });

        // ---- Edit ----
        $(document).on('click', '.editBtn', function () {
            const id = $(this).data('id');
            $.ajax({
                url: `/khedmots/${id}/edit`, type: 'GET', dataType: 'json',
                success: function (r) {
                    $('#editForm').attr('action', CFG.updateBase + '/' + id);
                    $('#editForm input[name="month"]').val(r.month);
                    $('#editForm input[name="date"]').val(r.date);
                    $('#editForm input[name="' + CFG.amountField + '"]').val(r[CFG.amountField]);
                    $('#editForm textarea[name="comment"]').val(r.comment);
                    $('#editMemberName').val(r.member ? r.member.name : '');
                    $('#editModal').modal('show');
                }
            });
        });
        $(document).on('submit', '#editForm', function (e) {
            e.preventDefault();
            const $form = $(this);
            const $btn = $form.find('button[type="submit"]');
            $btn.prop('disabled', true);
            $.ajax({
                url: $form.attr('action'), type: 'POST', data: new FormData(this),
                processData: false, contentType: false, dataType: 'json',
                success: function (response) {
                    if (response.record) {
                        const $old = $('[data-record-id="' + response.record.id + '"]');
                        const serial = parseInt($old.find('table tr:first td').eq(1).text(), 10) || 1;
                        summary.amount += (Number(response.record[CFG.amountField]) || 0) - (Number($old.data('amount')) || 0);
                        renderSummary();
                        const card = createCard(response.record, serial - 1);
                        if ($old.length) { $old.replaceWith(card); }
                    }
                    $('#editModal').modal('hide');
                    showNotification(response.status, response.message, response.status);
                },
                error: function (xhr) {
                    showNotification('danger', (xhr.responseJSON && xhr.responseJSON.message) || 'আপডেট করা যায়নি।', 'Danger');
                },
                complete: function () { $btn.prop('disabled', false); }
            });
        });

        // ---- Delete (reuses the khedmots destroy endpoint) ----
        $(document).on('click', '.deleteBtn', function () {
            const id = $(this).data('id');
            Swal.fire({
                title: 'ডিলেট করবেন?', text: 'আপনি এটি পুনরুদ্ধারিত করতে পারবেন না!', icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'হ্যা'
            }).then(function (result) {
                if (!result.isConfirmed) return;
                $.ajax({
                    url: `/khedmots/${id}`, method: 'DELETE', dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            const $card = $('[data-record-id="' + id + '"]');
                            summary.total = Math.max(0, summary.total - 1);
                            summary.amount -= Number($card.data('amount')) || 0;
                            renderSummary();
                            $card.remove();
                            renumber();
                            $('#collectionEmpty').toggle(summary.total === 0);
                        }
                        showNotification(response.status, response.message, response.status);
                    },
                    error: function (xhr) {
                        showNotification('danger', (xhr.responseJSON && xhr.responseJSON.message) || 'ডিলিট করা যায়নি।', 'Danger');
                    }
                });
            });
        });
    });
</script>
@endpush
