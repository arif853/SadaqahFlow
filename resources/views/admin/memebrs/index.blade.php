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
        <div class="row" id="memberCard">
            @foreach ($members as $key => $member)
            <div class="col-sm-12 col-md-3 col-lg-3" >
                <div class="card">
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="50%"><strong>নং:</strong></td>
                                <td width="50%">{{ $key + 1 }}</td>
                            </tr>
                            <tr>
                                <td width="50%"><strong>জাকের নাম:</strong></td>
                                <td style="display: flex; align-items: center;" width="50%">
                                    <img src="{{ asset('storage/' . $member->image) }}" alt="Avatar" style="width: 50px; height: 50px; border-radius: 5%; margin-right: 10px;">
                                    <span>{{ $member->name }} {{$member->nickName ? '('.$member->nickName.')': ''}}</span>
                                    
                                </td>
                            </tr>
                            
                            <tr>
                                <td width="50%"><strong>কল্যাণ নাম্বার:</strong></td>
                                <td width="50%">{{ $member->kollan_id }}</td>
                            </tr>
                            <tr>
                                <td width="50%"><strong>ফোন নাম্বার:</strong></td>
                                <td width="50%">{{ $member->phone }}</td>
                            </tr>
                            <tr>
                                <td width="50%"><strong>কল্যাণ:</strong></td>
                                <td width="50%">
                                    @if($member->kollan_khedmot != null)
                                        <span>হাদিয়া : {{ $member->kollan_khedmot }} টাকা</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td width="50%"><strong>অবস্থা:</strong></td>
                                <td width="50%">
                                    @if($member->status == 1)
                                        <a href="{{route('members.status', $member->id)}}" class="badge bg-success text-white">সক্রিয়</a>
                                    @else
                                        <a href="{{route('members.status', $member->id)}}" class="badge bg-danger text-white">নিষ্ক্রিয়</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td width="50%"><strong>কর্মি:</strong></td>
                                <td width="50%">{{ $member->user[0]->name ?? 'নাই' }}</td>
                            </tr>
                            <tr>
                                <td width="50%"><strong>ক্রিয়াকলা:</strong></td>
                                <td width="50%">
                                    @can('show member')
                                    <a href="{{route('members.show', $member->id)}}" class="btn btn-outline-info btn-sm mr-4 view-btn" data-id="{{$member->id}}">
                                        <i class="fa fa-eye" ></i>
                                    </a>
                                    @endcan
                                    @can('update member')
                                    <a href="#" class="btn btn-outline-warning btn-sm mr-4 edit-btn" data-id="{{$member->id}}" data-bs-toggle="modal" data-bs-target="#EditModal">
                                        <i class="fa fa-pencil" ></i>
                                    </a>
                                    @endcan
                                    @can('delete member')
                                    <a href="#" class="btn btn-outline-danger btn-sm mr-4 delete-btn" data-id="{{$member->id}}">
                                        <i class="fa fa-trash" ></i>
                                    </a>
                                    @endcan
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div id="memberCardContainer" class="row">

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
            <form action="{{route('members.store')}}" method="POST" enctype="multipart/form-data">
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

            $(document).on('click','.edit-btn', function (event) {
                event.preventDefault();
                const id = $(this).data('id');
                $.ajax({
                    url: `/members/${id}/edit`,
                    method: 'GET',
                    success: function(response) {
                        console.log(response);
                        $('#EditModal').find('form').attr('action', `/members/${response.id}`);
                        // $('#EditModal').find('input[name="id"]').val(response.id);
                        $('#EditModal').find('input[name="name"]').val(response.name);
                        $('#EditModal').find('input[name="nickName"]').val(response.nickName);
                        $('#EditModal').find('input[name="phone"]').val(response.phone);
                        $('#EditModal').find('input[name="father_name"]').val(response.father_name);
                        $('#EditModal').find('input[name="spouse_name"]').val(response.spouse_name);
                        $('#EditModal').find('input[name="kollan_id"]').val(response.kollan_id);
                        $('#EditModal').find('input[name="kollan_khedmot"]').val(response.kollan_khedmot);
                        $('#EditModal').find('input[name="bloodType"]').val(response.bloodType);
                        $('#EditModal').find('input[name="status"]').prop('checked', response.status);
                        $('#EditModal').find('.image-preview2').html(`<img src="storage/${response.image}" alt="Image Preview" style="margin: 10px 0;max-width: 40%; height: auto;">`);
                        $('#EditModal').modal('show');

                        $('#EditModal').find('form').attr('method', 'PUT');

                    },
                    error: function(xhr) {
                        console.error('Error submitting form:', xhr);
                    }
                });
            });

            $(document).on('submit','#EditForm', function (event) {
                event.preventDefault();
                const formData = new FormData(this);
                console.log(formData);
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false, // Important: prevent jQuery from processing the data
                    contentType: false, // Important: prevent jQuery from setting content type
                    success: function(response) {
                        location.reload();

                        $('#EditModal').modal('hide');
                        showNotification(
                            response.status,
                            response.message,
                            response.status
                        );
                    },
                    error: function(xhr) {
                        console.error('Error submitting form:', xhr);
                    }
                });
            });

            $(document).on('click','.delete-btn', function (event) {
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
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/members/${id}`,
                            method: 'DELETE',
                            success: function(response) {
                                $('#member-table').load(location.href + ' #member-table');
                                Swal.fire('Saved!', '', 'success')
                            }
                        });
                    } else if (result.isDenied) {
                        Swal.fire('Changes are not saved', '', 'info')
                    }
                })
            });

            let term = '';

            $(document).on('keyup', '#searchInput', function () {
                term = $(this).val();
                searchHandler(term);
            });

            function searchHandler(term) {
                $.ajax({
                    url: `/members/member-search/search`,
                    type: "GET",
                    data: { term: term },
                    success: function (response) {
                        // console.log('Search Results:', response);
                        $('#memberCard').empty();
                        $('#memberCardContainer').empty();
                        response.forEach(function(member, key) {

                            // let formattedDate = dayjs(member.date).format('DD-MMM-YYYY');
                            
                            let user ='';
                            if(member.member_assigns){
                                user = member.member_assigns[0].user.name;
                            }
                            if(member.user){
                                user = member.user[0].name;
                            }
                            //<img src="${image}" alt="Avatar" style="width: 50px; height: 50px; border-radius: 5%; margin-right: 10px;">
                            let image = member.image ? `storage/${member.image}` : '';
                            $('#memberCardContainer').append(`
                                <div class="col-sm-12 col-md-3 col-lg-3" >
                                    <div class="card">
                                        <div class="card-body">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td width="50%"><strong>নং:</strong></td>
                                                    <td width="50%">${key + 1}</td>
                                                </tr>
                                                <tr>
                                                    <td width="50%"><strong>জাকের নাম:</strong></td>
                                                    <td style="display: flex; align-items: center;" width="50%">
                                                        
                                                        <span>${member.name}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%"><strong>Nick Name:</strong></td>
                                                    <td width="50%">${member.nickName?? ''}</td>
                                                </tr>
                                                <tr>
                                                    <td width="50%"><strong>কল্যাণ নাম্বার:</strong></td>
                                                    <td width="50%">${member.kollan_id}</td>
                                                </tr>
                                                <tr>
                                                    <td width="50%"><strong>ফোন নাম্বার:</strong></td>
                                                    <td width="50%">${member.phone ?? ''}</td>
                                                </tr>
                                                <tr>
                                                    <td width="50%"><strong>কল্যাণ:</strong></td>
                                                    <td width="50%">
                                                        <span>হাদিয়া : ${member.kollan_khedmot ? member.kollan_khedmot : 'নাই'} টাকা</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%"><strong>অবস্থা:</strong></td>
                                                    <td width="50%">
                                                        <a href="/members/status/${member.id}" class="badge bg-success text-white">${member.status ? 'সক্রিয়' : 'নিষ্ক্রিয়'}</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%"><strong>কর্মি:</strong></td>
                                                    <td width="50%">${user??''}</td>
                                                </tr>
                                                <tr>
                                                    <td width="50%"><strong>ক্রিয়াকলা:</strong></td>
                                                    <td width="50%">
                                                        @can('show member')
                                                        <a href="/members/${member.id}" class="btn btn-outline-info btn-sm mr-4 view-btn" data-id="${member.id}">
                                                            <i class="fa fa-eye" ></i>
                                                        </a>
                                                        @endcan
                                                        @can('update member')
                                                        <a href="#" class="btn btn-outline-warning btn-sm mr-4 edit-btn" data-id="${member.id}" data-bs-toggle="modal" data-bs-target="#EditModal">
                                                            <i class="fa fa-pencil" ></i>
                                                        </a>
                                                        @endcan
                                                        @can('delete member')
                                                        <a href="#" class="btn btn-outline-danger btn-sm mr-4 delete-btn" data-id="${member.id}">
                                                            <i class="fa fa-trash" ></i>
                                                        </a>
                                                        @endcan
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            `);
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        console.error('Response:', xhr.responseText);
                    }
                });
            }


        });

    </script>
@endpush
