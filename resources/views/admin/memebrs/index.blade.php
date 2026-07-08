@extends('layouts.admin')
@section('title','Member List')
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
                <li class="breadcrumb-item active"><a href="javascript:void(0);">Members</a></li>
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
                        <h4 class="card-title d-block">জাকেরদের তালিকা</h4>

                        @can('create member')
                        <button class="btn btn-md btn-primary mb-15 float-end" type="button"
                        data-bs-toggle="modal" data-bs-target="#DataModal">নতুন জাকের যোগ</button>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-3 col-lg-3 col-xl-3">
                                <div class="mb-10">
                                    <label for="searchInput">জাকেরদের খুঁজুন</label>
                                    <input type="text" class="form-control" placeholder="যাকের নাম/ ফোন নাম্বার / কল্যাণ নাম্বার খুঁজুন" id="searchInput">
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        {{-- Result summary: total member count for the current filter --}}
        <div class="row" id="memberSummary" style="display: none;">
            <div class="col-12 mb-10">
                <span class="badge bg-info text-white">মোট: <span id="memberCount">0</span> জন</span>
            </div>
        </div>
        {{-- Single AJAX-driven, paginated card list --}}
        <div class="row" id="memberList"></div>
        <div class="row">
            <div class="col-12 text-center mb-20">
                <div id="memberLoader" style="display: none;">
                    <span class="spinner-border spinner-border-sm" role="status"></span> লোড হচ্ছে...
                </div>
                <p id="memberEmpty" class="text-center" style="display: none;">কোন ফলাফল পাওয়া যায়নি</p>
                <button type="button" id="memberLoadMore" class="btn btn-outline-primary btn-md" style="display: none;">আরও দেখুন</button>
                <div id="memberSentinel" style="height: 1px;"></div>
            </div>
        </div>
    </div>
</div>

 <!-- Data Modal Start-->
 <div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="EditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="EditModalLabel">
                একজন জাকের সদস্য আপডেট করুন
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="EditForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="" class="form-lable">জাকের নামে<span class="text-danger">*</span></label>
                  <input class="form-control" type="text" placeholder="জাকের নামে লিখুন" name="name" required>
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">Nick Name</label>
                  <input class="form-control" type="text" placeholder="নিক নাম লিখুন" name="nickName">
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">ফোন নাম্বার </label>
                    <input class="form-control" type="text" placeholder="ফোন নাম্বার" name="phone">
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">পিতার নাম</label>
                  <input class="form-control" type="text" placeholder="পিতার নাম" name="father_name">
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">স্বামী/ স্ত্রী নাম</label>
                  <input class="form-control" type="text" placeholder="স্বামী/ স্ত্রী নাম" name="spouse_name">
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">কল্যাণ আইডি নাম্বার<span class="text-danger">*</span></label>
                  <input class="form-control" type="text" placeholder="কল্যাণ আইডি নাম্বার" name="kollan_id" required>
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">কল্যাণ খেদমত</label>
                    <input class="form-control" type="number" placeholder="কল্যাণ খেদমত লিখুন" name="kollan_khedmot" min="0" value="0">
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">রক্তের গ্রুপ</label>
                    <input class="form-control" type="text" placeholder="রক্তের গ্রুপ" name="bloodType">
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">ছবি </label>
                    <input class="form-control" type="file" name="image2" id="image-input2">
                    <div class="image-preview2">

                    </div>
                </div>
                <div class="form-group">
                    <label for="status" class="form-lable">
                        <input class="form-checkbox" type="checkbox" name="status" id="status">
                        সক্রিয় করুন
                    </label>
                </div>
                <button type="submit" class="btn btn-success btn-md pull-right">জমা করুণ</button>
            </form>
        </div>

        </div>
    </div>
</div>
<!-- Data Modal end-->

 <!-- Data Modal Start-->
 <div class="modal fade" id="DataModal" tabindex="-1" aria-labelledby="DataModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="DataModalLabel">
                একজন জাকের সদস্য যোগ করুন
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="AddForm" action="{{route('members.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="form-group">
                    <label for="" class="form-lable">জাকের নামে <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" placeholder="জাকের নামে লিখুন" name="name" required>
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">Nick Name</label>
                  <input class="form-control" type="text" placeholder="নিক নাম লিখুন" name="nickName">
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">ফোন নাম্বার </label>
                    <input class="form-control" type="text" placeholder="ফোন নাম্বার" name="phone">
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">পিতার নাম </label>
                  <input class="form-control" type="text" placeholder="পিতার নাম" name="father_name"   >
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">স্বামী/ স্ত্রী নাম </label>
                  <input class="form-control" type="text" placeholder="স্বামী/ স্ত্রী নাম" name="spouse_name" >
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">কল্যাণ আইডি নাম্বার <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" placeholder="কল্যাণ আইডি নাম্বার" name="kollan_id" required>
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">কল্যাণ খেদমত</label>
                    <input class="form-control" type="number" placeholder="কল্যাণ খেদমত লিখুন" name="kollan_khedmot" min="0" value="0">
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">রক্তের গ্রুপ </label>
                  <input class="form-control" type="text" placeholder="রক্তের গ্রুপ" name="bloodType" >
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">ছবি </label>
                    <input class="form-control" type="file" name="image" id="image-input">
                    <div class="image-preview">

                    </div>
                </div>
                <div class="form-group">
                    <label for="status" class="form-lable">
                        <input class="form-checkbox" type="checkbox" name="status" id="status" checked>
                        সক্রিয় করুন
                    </label>
                </div>
                <button type="submit" class="btn btn-success btn-md pull-right">জমা করুণ</button>
            </form>
        </div>

        </div>
    </div>
</div>
<!-- Data Modal end-->


@endsection
@push('script')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#image-input').on('change', function() {
                const file = this.files[0];
                const previewContainer = $('.image-preview');

                if (file) {
                    const reader = new FileReader();

                    reader.onload = function(event) {
                        previewContainer.html(`<img src="${event.target.result}" alt="Image Preview" style="margin: 10px 0;max-width: 40%; height: auto;">`);
                        previewContainer.show();
                    };

                    reader.readAsDataURL(file);
                } else {
                    previewContainer.html('');
                    previewContainer.hide();
                }
            });
            $('#image-input2').on('change', function() {
                const file = this.files[0];
                const previewContainer = $('.image-preview2');

                if (file) {
                    const reader = new FileReader();

                    reader.onload = function(event) {
                        previewContainer.html(`<img src="${event.target.result}" alt="Image Preview" style="margin: 10px 0;max-width: 40%; height: auto;">`);
                        previewContainer.show();
                    };

                    reader.readAsDataURL(file);
                } else {
                    previewContainer.html('');
                    previewContainer.hide();
                }
            });

            // ---- Paginated, infinite-scroll list state ----
            let currentPage = 0;
            let lastPage = 1;
            let isLoading = false;
            let renderedCount = 0;
            let totalCount = 0;

            function debounce(func, wait) {
                let t;
                return function (...args) { clearTimeout(t); t = setTimeout(() => func.apply(this, args), wait); };
            }

            function renderCount() {
                $('#memberCount').text(totalCount);
                $('#memberSummary').show();
            }

            // Resolve the assigned collector name from either relation shape.
            function memberUserName(member) {
                if (member.member_assigns && member.member_assigns.length && member.member_assigns[0].user) {
                    return member.member_assigns[0].user.name || 'নাই';
                }
                if (member.user && member.user.length && member.user[0]) {
                    return member.user[0].name || 'নাই';
                }
                return 'নাই';
            }

            function createMemberCard(member, index) {
                const userName = memberUserName(member);
                const img = member.image ? `/storage/${member.image}` : '';
                const nick = member.nickName ? `(${member.nickName})` : '';
                const status = member.status
                    ? '<span class="badge bg-success text-white">সক্রিয়</span>'
                    : '<span class="badge bg-danger text-white">নিষ্ক্রিয়</span>';
                const cardHTML = `
                    <div class="col-sm-12 col-md-3 col-lg-3" data-member-id="${member.id}">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr><td width="50%"><strong>নং:</strong></td><td width="50%">${index + 1}</td></tr>
                                    <tr>
                                        <td width="50%"><strong>জাকের নাম:</strong></td>
                                        <td style="display: flex; align-items: center;" width="50%">
                                            ${img ? `<img src="${img}" alt="Avatar" style="width:50px;height:50px;border-radius:5%;margin-right:10px;">` : ''}
                                            <span>${member.name} ${nick}</span>
                                        </td>
                                    </tr>
                                    <tr><td width="50%"><strong>কল্যাণ নাম্বার:</strong></td><td width="50%">${member.kollan_id}</td></tr>
                                    <tr><td width="50%"><strong>ফোন নাম্বার:</strong></td><td width="50%">${member.phone ?? ''}</td></tr>
                                    <tr>
                                        <td width="50%"><strong>কল্যাণ:</strong></td>
                                        <td width="50%"><span>হাদিয়া : ${member.kollan_khedmot ? member.kollan_khedmot : 'নাই'} টাকা</span></td>
                                    </tr>
                                    <tr>
                                        <td width="50%"><strong>অবস্থা:</strong></td>
                                        <td width="50%"><a href="/members/status/${member.id}">${status}</a></td>
                                    </tr>
                                    <tr><td width="50%"><strong>কর্মি:</strong></td><td width="50%">${userName}</td></tr>
                                    <tr>
                                        <td width="50%"><strong>ক্রিয়াকলা:</strong></td>
                                        <td width="50%">
                                            @can('show member')
                                            <a href="/members/${member.id}" class="btn btn-outline-info btn-sm mr-4 view-btn" data-id="${member.id}"><i class="fa fa-eye"></i></a>
                                            @endcan
                                            @can('update member')
                                            <a href="#" class="btn btn-outline-warning btn-sm mr-4 edit-btn" data-id="${member.id}" data-bs-toggle="modal" data-bs-target="#EditModal"><i class="fa fa-pencil"></i></a>
                                            @endcan
                                            @can('delete member')
                                            <a href="#" class="btn btn-outline-danger btn-sm mr-4 delete-btn" data-id="${member.id}"><i class="fa fa-trash"></i></a>
                                            @endcan
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>`;
                const div = document.createElement('div');
                div.innerHTML = cardHTML.trim();
                return div.firstChild;
            }

            function renumberMemberCards() {
                const $cards = $('#memberList').children('div');
                $cards.each(function (i) { $(this).find('table tr:first td').eq(1).text(i + 1); });
                renderedCount = $cards.length;
            }

            function loadMembers(reset) {
                if (isLoading) return;
                if (!reset && currentPage >= lastPage) return;
                isLoading = true;
                $('#memberLoader').show();
                $('#memberLoadMore').hide();
                const params = { term: $('#searchInput').val(), page: reset ? 1 : currentPage + 1 };
                $.ajax({
                    url: '/members/member-search/search',
                    type: 'GET',
                    data: params,
                    dataType: 'json',
                    success: function (response) {
                        const data = response.data || [];
                        const meta = response.meta || {};
                        if (reset) { $('#memberList').empty(); renderedCount = 0; }
                        const fragment = document.createDocumentFragment();
                        data.forEach(function (m) { fragment.appendChild(createMemberCard(m, renderedCount++)); });
                        document.getElementById('memberList').appendChild(fragment);
                        currentPage = meta.current_page || params.page;
                        lastPage = meta.last_page || 1;
                        totalCount = meta.total || 0;
                        renderCount();
                        $('#memberEmpty').toggle(totalCount === 0);
                        $('#memberLoadMore').toggle(currentPage < lastPage);
                    },
                    error: function (xhr) { console.error('Member load error:', xhr.responseText); },
                    complete: function () { isLoading = false; $('#memberLoader').hide(); }
                });
            }

            const debouncedReset = debounce(function () { loadMembers(true); }, 300);
            $(document).on('keyup', '#searchInput', debouncedReset);
            $(document).on('click', '#memberLoadMore', function () { loadMembers(false); });

            const scrollRoot = document.querySelector('.theme-body .simplebar-content-wrapper');
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver(function (entries) {
                    if (entries[0].isIntersecting) { loadMembers(false); }
                }, { root: scrollRoot || null, rootMargin: '200px' });
                observer.observe(document.getElementById('memberSentinel'));
            }

            // Seed the search from the global header search (?q=...) before first load
            (function applyGlobalQuery() {
                const q = new URLSearchParams(window.location.search).get('q');
                if (q) { $('#searchInput').val(q); }
            })();

            // Initial load
            loadMembers(true);

            // ---- Edit: populate modal ----
            $(document).on('click','.edit-btn', function (event) {
                event.preventDefault();
                const id = $(this).data('id');
                $.ajax({
                    url: `/members/${id}/edit`,
                    method: 'GET',
                    success: function(response) {
                        $('#EditModal').find('form').attr('action', `/members/${response.id}`);
                        $('#EditModal').find('input[name="name"]').val(response.name);
                        $('#EditModal').find('input[name="nickName"]').val(response.nickName);
                        $('#EditModal').find('input[name="phone"]').val(response.phone);
                        $('#EditModal').find('input[name="father_name"]').val(response.father_name);
                        $('#EditModal').find('input[name="spouse_name"]').val(response.spouse_name);
                        $('#EditModal').find('input[name="kollan_id"]').val(response.kollan_id);
                        $('#EditModal').find('input[name="kollan_khedmot"]').val(response.kollan_khedmot);
                        $('#EditModal').find('input[name="bloodType"]').val(response.bloodType);
                        $('#EditModal').find('input[name="status"]').prop('checked', response.status);
                        $('#EditModal').find('.image-preview2').html(response.image ? `<img src="/storage/${response.image}" alt="Image Preview" style="margin: 10px 0;max-width: 40%; height: auto;">` : '');
                        $('#EditModal').modal('show');
                    },
                    error: function(xhr) { console.error('Edit fetch error:', xhr); }
                });
            });

            // ---- Add: AJAX + prepend ----
            $(document).on('submit', '#AddForm', function (e) {
                e.preventDefault();
                const $form = $(this);
                const $btn = $form.find('button[type="submit"]');
                $btn.prop('disabled', true);
                $.ajax({
                    url: $form.attr('action'),
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (response) {
                        if (response.member) {
                            $('#memberList').prepend(createMemberCard(response.member, 0));
                            renumberMemberCards();
                            totalCount += 1;
                            renderCount();
                            $('#memberEmpty').hide();
                        }
                        $('#DataModal').modal('hide');
                        $form[0].reset();
                        $form.find('.image-preview').html('');
                        showNotification(response.status, response.message, response.status);
                    },
                    error: function (xhr) {
                        const message = xhr.responseJSON?.message || 'জাকের যোগ করা যায়নি।';
                        showNotification('danger', message, 'Danger');
                    },
                    complete: function () { $btn.prop('disabled', false); }
                });
            });

            // ---- Edit submit: in-place card update ----
            $(document).on('submit','#EditForm', function (event) {
                event.preventDefault();
                const $form = $(this);
                const $btn = $form.find('button[type="submit"]');
                $btn.prop('disabled', true);
                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.member) {
                            const $old = $('[data-member-id="' + response.member.id + '"]');
                            const serial = parseInt($old.find('table tr:first td').eq(1).text(), 10) || 1;
                            const card = createMemberCard(response.member, serial - 1);
                            if ($old.length) { $old.replaceWith(card); }
                        }
                        $('#EditModal').modal('hide');
                        showNotification(response.status, response.message, response.status);
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'জাকের আপডেট করা যায়নি।';
                        showNotification('danger', message, 'Danger');
                    },
                    complete: function () { $btn.prop('disabled', false); }
                });
            });

            // ---- Delete: in-place removal ----
            $(document).on('click','.delete-btn', function (event) {
                event.preventDefault();
                const id = $(this).data('id');
                Swal.fire({
                    title: 'জাকের ডিলেট করবেন?',
                    text: "আপনি এটি পুনরুদ্ধারিত করতে পারবেন না!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'হ্যা'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/members/${id}`,
                            method: 'DELETE',
                            dataType: 'json',
                            success: function(response) {
                                if (response.status === 'success') {
                                    $('[data-member-id="' + id + '"]').remove();
                                    renumberMemberCards();
                                    totalCount = Math.max(0, totalCount - 1);
                                    renderCount();
                                    $('#memberEmpty').toggle(totalCount === 0);
                                }
                                showNotification(response.status, response.message, response.status);
                            },
                            error: function(xhr) {
                                const message = xhr.responseJSON?.message || 'জাকের ডিলিট করা যায়নি।';
                                showNotification('danger', message, 'Danger');
                            }
                        });
                    }
                });
            });


        });

    </script>
@endpush
