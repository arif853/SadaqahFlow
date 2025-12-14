@extends('layouts.admin')
@section('title','User List')
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
                <li class="breadcrumb-item active"><a href="javascript:void(0);">ইউজার</a></li>
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
                        <div class="d-flex justify-content-start">
                            <div class="">
                                <h4 class="card-title mb-20">ইউজারদের তালিকা</h4>
                                <div class="">
                                    <label for="searchInput2">ইউজার খুঁজুন</label>
                                    <input type="text" class="form-control" placeholder="ইউজার নাম, নাম্বার দিয়ে খুঁজুন" id="searchInput">
                                </div>
                            </div>
                        </div>
                        <div class="">
                            @can('create user')
                            <button class="btn btn-md btn-primary  float-end" type="button"
                            data-bs-toggle="modal" data-bs-target="#DataModal">নতুন ইউজার</button>
                            @endcan
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="row" id="userCard">
            @foreach ($users as $key => $user)
            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 col-xxl-3" >
                <div class="card">

                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="50%"><strong>নং:</strong></td>
                                <td width="50%">{{ $key + 1 }}</td>
                            </tr>
                            <tr>
                                <td width="50%" ><strong>নাম:</strong></td>
                                <td width="50%">{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td width="50%" ><strong>ইউজার নাম:</strong></td>
                                <td width="50%">{{ $user->username }}</td>
                            </tr>
                            <tr>
                                <td width="50%"><strong>ফোন:</strong></td>
                                <td width="50%">{{ $user->phone }}</td>
                            </tr>
                            <tr>
                                <td width="50%"><strong>ইমেইল:</strong></td>
                                <td width="50%">{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td width="50%" ><strong>রক্তের গ্রুপ:</strong></td>
                                <td width="50%">{{ $user->bloodType }}</td>
                            </tr>
                            <tr>
                                <td width="50%" ><strong>ঠিকানা:</strong></td>
                                <td width="50%">{{ $user->address }}</td>
                            </tr>
                            <tr>
                                <td width="50%" ><strong>মোট জাকের:</strong></td>
                                <td width="50%">{{ $user->members->count() }} জন</td>
                            </tr>
                            <tr>
                                <td width="50%" ><strong>ইউজারে ধরন:</strong></td>
                                <td width="50%">
                                    <span class="badge bg-info text-white">
                                        {{$user->getRoleNames()->first()}}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="50%" ><strong>সট্যাটাস:</strong></td>
                                <td>
                                    @if ($user->status == 1)
                                        <a href="{{route('users.status', $user->id)}}" class="badge bg-success text-white">সক্রিয়</a>
                                    @else
                                        <a href="{{route('users.status', $user->id)}}" class="badge bg-danger text-white">নিষ্ক্রিয়</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td width="50%"><strong>ক্রিয়াকলা:</strong></td>
                                <td width="50%">
                                    @can('assign member')
                                    <a href="#" class="btn btn-outline-info btn-sm mr-4 assign-member-btn" data-bs-toggle="modal" data-bs-target="#AssignMemberModal" data-id="{{$user->id}}">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                    @endcan
                                    @can('show user')
                                    <a href="#" class="btn btn-outline-secondary btn-sm mr-4 show-user-btn" data-bs-toggle="modal" data-bs-target="#ShowModal" data-id="{{$user->id}}">
                                        <i class="fa fa-eye" aria-label="fa fa-low-vision"></i>
                                    </a>
                                    @endcan
                                    @can('update user')
                                    <a href="#" class="btn btn-outline-warning btn-sm mr-4 edit-btn"
                                    data-bs-toggle="modal" data-bs-target="#EditModal" data-id="{{$user->id}}">
                                        <i class="fa fa-pencil" aria-label="fa fa-low-vision"></i>
                                        </a>
                                    @endcan
                                    @can('delete user')
                                    <a href="#" class="btn btn-outline-danger btn-sm mr-4 delete-btn d-none" data-id="{{$user->id}}">
                                        <i class="fa fa-trash" aria-label="fa fa-low-vision"></i>
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
        <div class="row" id="userCardContainer" >

        </div>
    </div>
</div>

 <!-- user update Modal Start-->
 <div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="EditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="EditModalLabel">
                ইউজার যোগ করুন
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <span>(<span class="text-danger">*</span>)অবশ্যই পূরণ করতে হবে</span>
            <form id="EditForm">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="" class="form-lable">নাম<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" placeholder="ইউজারে পুরো নাম লিখুন" name="name" required>
                  @error('name')
                    <span class="text-danger">{{$message}}</span>
                  @enderror
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">ইউজার নাম<span class="text-danger">*</span></label>
                  <input class="form-control" type="text" placeholder="ইউজার নাম লিখুন" name="username" required>
                  @error('username')
                    <span class="text-danger">{{$message}}</span>
                  @enderror
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">ঠিকানা</label>
                  <input class="form-control" type="text" placeholder="ঠিকানা লিখুন" name="address">
                  @error('address')
                    <span class="text-danger">{{$message}}</span>
                  @enderror
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">ফোন নাম্বার<span class="text-danger">*</span> </label>
                    <input class="form-control" type="text" name="phone" placeholder="ফোন নাম্বার লিখুন" required>
                    @error('phone')
                    <span class="text-danger">{{$message}}</span>
                  @enderror
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">ইমেইল আইডি</label>
                  <input class="form-control" type="email" placeholder="ইমেইল আইডি লিখুন" name="email">
                  @error('email')
                    <span class="text-danger">{{$message}}</span>
                  @enderror
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">রক্তের গ্রুপ</label>
                  <input class="form-control" type="text" placeholder="রক্তের গ্রুপ দিন" name="bloodType">
                  @error('bloodType')
                    <span class="text-danger">{{$message}}</span>
                  @enderror
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">রোল নির্বাচন করুন <span class="text-danger">*</span></label>
                    <select name="role" id="role" class="form-control">
                        <option value="">রোল নির্বাচন করুন</option>
                        @foreach ($roles as $role)
                            <option value="{{$role->name}}">{{$role->name}}</option>
                        @endforeach
                    </select>
                    @error('role')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="" class="form-lable">পাসওয়ার্ড</label>
                    <input class="form-control" type="text" name="password" placeholder="একটি সঠিক পাসওয়ার্ড দিন">
                    @error('password')
                    <span class="text-danger">{{$message}}</span>
                  @enderror

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
<!-- user update Modal end-->

 <!-- user add Modal Start-->
 <div class="modal fade" id="DataModal" tabindex="-1" aria-labelledby="DataModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="DataModalLabel">
                ইউজার যোগ করুন
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <span>(<span class="text-danger">*</span>) অবশ্যই পূরণ করতে হবে</span>
            <form action="{{route('users.store')}}" method="POST">
                @csrf
                @method('POST')
                <div class="form-group">
                    <label for="" class="form-lable">নাম<span class="text-danger">*</span></label>
                  <input class="form-control" type="text" placeholder="ইউজারে পুরো নাম লিখুন" name="name" required>
                  @error('name')
                    <span class="text-danger">{{$message}}</span>
                  @enderror
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">ইউজার নাম<span class="text-danger">*</span></label>
                  <input class="form-control" type="text" placeholder="ইউজার নাম লিখুন" name="username" required>
                  @error('username')
                    <span class="text-danger">{{$message}}</span>
                  @enderror
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">ঠিকানা</label>
                  <input class="form-control" type="text" placeholder="ঠিকানা লিখুন" name="address">
                  @error('address')
                    <span class="text-danger">{{$message}}</span>
                  @enderror
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">ফোন নাম্বার<span class="text-danger">*</span> </label>
                    <input class="form-control" type="text" name="phone" placeholder="ফোন না���্বার লিখুন" required>
                    @error('phone')
                    <span class="text-danger">{{$message}}</span>
                  @enderror
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">ইমেইল আইডি</label>
                  <input class="form-control" type="email" placeholder="ইমেইল আইডি লিখুন" name="email">
                  @error('email')
                    <span class="text-danger">{{$message}}</span>
                  @enderror
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">রক্তের গ্রুপ</label>
                  <input class="form-control" type="text" placeholder="রক্তের গ্রুপ দিন" name="bloodType">
                  @error('bloodType')
                    <span class="text-danger">{{$message}}</span>
                  @enderror
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">রোল নির্বাচন করুন <span class="text-danger">*</span></label>
                    <select name="role" class="form-control">
                        <option value="">রোল নির্বাচন করুন</option>
                        @foreach ($roles as $role)
                            <option value="{{$role->name}}">{{$role->name}}</option>
                        @endforeach
                    </select>
                    @error('role')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="" class="form-lable">পাসওয়ার্ড<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="password" placeholder="একটি সঠিক পাসওয়ার্ড দিন" required>
                    @error('password')
                    <span class="text-danger">{{$message}}</span>
                  @enderror

                </div>
                <div class="form-group">
                    <label for="status" class="form-lable">
                        <input class="form-checkbox" type="checkbox" name="status" id="status" checked>
                        সক্রিয় করুন
                    </label>
                </div>
                <button type="submit" class="btn btn-success btn-md pull-right">���মা করুণ</button>
            </form>
        </div>

        </div>
    </div>
</div>
<!-- user add Modal end-->

 <!-- user assign member Modal Start-->
 <div class="modal fade" id="AssignMemberModal" tabindex="-1" aria-labelledby="AssignMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="AssignMemberModalLabel">
                জাকের যোগ করুন
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <span class="mb-5">(<span class="text-danger">*</span>) অবশ্যই পূরণ করতে হবে</span>
            <form id="AssignMemberForm">
                @csrf
                @method('POST')
                <div class="form-group">
                    <label for="" class="form-lable mb-5">জাকের নির্বাচন করুন <span class="text-danger">*</span></label>
                    <select name="members[]" id="members" class="form-control select2 mt-5" multiple="multiple" style="width: 100%" required>
                        <option value="">জাকের নির্বাচন করুন</option>
                        @foreach ($members as $member)
                            <option value="{{$member->id}}">{{$member->name}} - {{$member->kollan_id}}</option>
                        @endforeach
                    </select>

                    @error('member')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-success btn-md pull-right">জমা করুণ</button>
            </form>
        </div>

        </div>
    </div>
</div>
<!-- user assign member Modal end-->

 <!-- user show Modal Start-->
 <div class="modal fade" id="ShowModal" tabindex="-1" aria-labelledby="ShowModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ShowModalLabel">
                    ইউজার তথ্য
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="user-info">
                    <h5>ইউজার তথ্য</h5>
                    <table class="table table-bordered">
                        <tr>
                            <td>নাম</td>
                            <td><span class="card-title text-success">  </span></td>
                        </tr>
                        <tr>
                            <td>ইউজার নাম</td>
                            <td><span class="card-username text-success">  </span></td>
                        </tr>
                        <tr>
                            <td>ইিকানা</td>
                            <td><span class="card-address text-success">   </span></td>
                        </tr>
                        <tr>
                            <td>ফোন নাম্বার</td>
                            <td><span class="card-phone text-success">   </span></td>
                        </tr>
                        <tr>
                            <td>ইমেইল আইডি</td>
                            <td><span class="card-email text-success">  </span></td>
                        </tr>
                        <tr>
                            <td>রক্তের গ্রুপ</td>
                            <td><span class="card-bloodType text-success">  </span></td>
                        </tr>
                    </table>
                </div>
                <div class="member-info mt-10">
                    <h5>জাকেরদের তালিকা</h5>
                    <table class="table table-bordered" id="member-table">
    
                    </table>
                </div>
    
            </div>
        </div>
    </div>
</div>
<!-- user show Modal end-->

@endsection
@push('script')
    <script>
        $(document).ready(function() {
            // $.ajaxSetup({
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     }
            // });
            $(document).on('change', '.member-select', function() {
                $('.member-select').select2({
                    placeholder: "সদস্য নির্বাচন করুন",
                    allowClear: true
                });
            });

            $(document).on('change', '#image-input', function() {
                const file = this.files[0];
                const previewContainer = $('.image-preview');

                if (file) {
                    const reader = new FileReader();

                    reader.onload = function(event) {
                        previewContainer.html(`<img src="${event.target.result}" alt="Image Preview" style="margin: 10px 0;max-width: 100%; height: auto;">`);
                        previewContainer.show();
                    };

                    reader.readAsDataURL(file);
                } else {
                    previewContainer.html('');
                    previewContainer.hide();
                }
            });

            $(document).on('click','.edit-btn', function (event) {
                const id = $(this).data('id');

                // Make AJAX request to get user data
                $.ajax({
                    url: `/users/${id}/edit`,
                    method: 'GET',
                    success: function(response) {
                        // Fill the edit form with user data
                        const form = $('#EditModal form');
                        form.attr('action', `/users/${id}`);
                        form.find('input[name="name"]').val(response.user.name);
                        form.find('input[name="username"]').val(response.user.username);
                        form.find('input[name="address"]').val(response.user.address);
                        form.find('input[name="phone"]').val(response.user.phone);
                        form.find('input[name="email"]').val(response.user.email);
                        form.find('input[name="bloodType"]').val(response.user.bloodType);
                        form.find('input[name="status"]').prop('checked', response.user.status);
                        form.find('#role').val(response.user.roles[0].name);

                        // Change form method to PUT for update
                        form.find('input[name="_method"]').val('PUT');
                    },
                    error: function(xhr) {
                        console.error('Error fetching user data:', xhr);
                        alert('Failed to load user data');
                    }
                });
            });

            $(document).on('submit', '#EditForm', function(event) {
                event.preventDefault();
                // console.log('Form submitted');

                // Ensure FormData is correctly instantiated
                const formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false, // Important: prevent jQuery from processing the data
                    contentType: false, // Important: prevent jQuery from setting content type
                    success: function(response) {
                        // console.log(response);
                        $('#EditModal').modal('hide');
                        showNotification(
                            response.status,
                            response.message,
                            response.status
                        );
                        location.reload();
                    },
                    error: function(xhr) {
                        console.error('Error submitting form:', xhr);
                        $('#EditModal').modal('hide');

                    }
                });
            });

            $(document).on('click','.delete-btn', function (event) {
                const id = $(this).data('id');
                // console.log(id);

                Swal.fire({
                    title: 'ইউজার ডিলেট করবেন?',
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
                            url: `/users/${id}`,
                            method: 'DELETE',
                            success: function(response) {
                                showNotification(
                                    response.status,
                                    response.message,
                                    response.status
                                );
                                location.reload();
                            }
                        });
                    } else if (result.isDenied) {
                        showNotification('error', 'Changes are not saved', 'info')
                    }
                })
            });

            $(document).on('click','.assign-member-btn', function(event) {
                const id = $(this).data('id');
                // console.log(id);
                $.ajax({
                    url: `/users/members/${id}/get-member`,
                    method: 'GET',
                    success: function(response) {
                        // Update the form action URL dynamically
                        $('#AssignMemberForm').attr('action', `/users/members/${id}/assign`);
                        // Show the modal
                        $('#AssignMemberModal').modal('show');
                        // Check if members exist in the response
                        if (response.members) {
                            const memberIds = response.members.map(member => member.id); // Extract member IDs
                            // Set the selected values in the select element
                            $('#AssignMemberForm').find('#members').val(memberIds).trigger('change'); // Trigger change to update Select2
                        }
                    },
                    error: function(xhr) {
                        console.error('Error fetching user data:', xhr);
                        alert('Failed to load user data');
                    }
                });
            });

            $(document).on('submit', '#AssignMemberForm', function(event) {
                event.preventDefault();
                const formData = new FormData(this);
                // console.log(formData);
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false, // Important: prevent jQuery from processing the data
                    contentType: false, // Important: prevent jQuery from setting content type
                    success: function(response) {
                        // console.log(response);
                        $('#AssignMemberModal').modal('hide');
                        showNotification(
                            response.status,
                            response.message,
                            response.status
                        );
                        location.reload();
                    },
                    error: function(xhr) {
                        console.error('Error submitting form:', xhr);
                    }
                });
            });

            $(document).on('click','.show-user-btn', function (event) {
                const id = $(this).data('id');
                $('#member-table').empty();
                // console.log(id);
                $.ajax({
                    url: `/users/${id}`,
                    method: 'GET',
                    success: function(response) {
                        // console.log(response);
                        const user = response.user;
                        const members = response.members;
                        $('#ShowModal').modal('show');
                        $('#ShowModal').find('.card-title').text(user.name);
                        $('#ShowModal').find('.card-username').text(user.username);
                        $('#ShowModal').find('.card-address').text(user.address);
                        $('#ShowModal').find('.card-phone').text(user.phone);
                        $('#ShowModal').find('.card-email').text(user.email);
                        $('#ShowModal').find('.card-bloodType').text(user.bloodType);
                        // <img src="{{asset('storage/${member.image}')}}" alt="" style="width: 50px;height: 50px;border-radius: 50%;margin-right: 10px;">
                        members.forEach(function(member, index) {
                            // console.log(member.name);
                            $('#ShowModal').find('#member-table').append(
                                `<tr>
                                    <td>${index+1}.</td>
                                    <td>
                                        
                                        <div>
                                        <span class="text-success">${member.name}${member.nickName ? '-' + member.nickName : ''}</span> <br>
                                        <span>${member.kollan_id}</span>
                                        </div>

                                    </td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-danger btn-sm member-rm" data-user-id="${user.id}" data-member-id="${member.id}">X</a>
                                    </td>
                                </tr>`
                            );
                        });
                    },
                    error: function(xhr) {
                        console.error('Error fetching user data:', xhr);
                        alert('Failed to load user data');
                    }
                });
            });

            $(document).on('click', '.member-rm', function (event) {
                event.preventDefault(); // Prevent default anchor behavior
                const memberId = $(this).data('member-id');
                const userId = $(this).data('user-id');
                console.log(memberId, userId);
                if (confirm('মেম্বার সরাতে চাচ্ছেন?')) {
                    $.ajax({
                        url: `/users/assign-member/remove/${memberId}/${userId}`,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                        },
                        success: function (response) {
                            showNotification(response.status, response.message, response.status);

                            // Optionally, remove the member from the UI
                            if (response.status == 'success') {
                                $(`[data-member-id="${memberId}"][data-user-id="${userId}"]`).closest('tr').remove(); // Adjust the selector if necessary
                            }
                        },
                        error: function (xhr) {
                            console.error('Error removing member:', xhr);
                            alert('মেম্বার সরাতে ব্যর্থ হয়েছে');
                        }
                    });
                }
            });

            $(document).on('input', '#searchInput', function () {
                name = $(this).val();
                $.ajax({
                    url: `/users/users-search/search-by-name`, // Corrected to use base URL
                    type: "GET",
                    data: { name: name }, // Use `data` instead of query string
                    success: function (response) {
                        searchHandler(response); // Trigger search

                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        console.error('Response:', xhr.responseText);
                    }
                });
            });

            // Function to handle the search AJAX request
            function searchHandler(response) {
                // console.log(date, name);

                $('#userCard').empty();
                $('#userCardContainer').empty();
                response.forEach(function(user, key) {
                    console.log(user);
                    let status = '';
                    if(user.status == 1){
                        status = `<a href="{{url('/users/status/${user.id}')}}" class="badge bg-success text-white">সক্রিয়</a>`;
                    } else {
                        status = `<a href="{{url('/users/status/${user.id}')}}" class="badge bg-danger text-white">নিষ্ক্রিয়</a>`;
                    }

                    $('#userCardContainer').append(`
                        <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 col-xxl-3" >
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="50%"><strong>নং:</strong></td>
                                            <td width="50%">${key + 1}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%" ><strong>নাম:</strong></td>
                                            <td width="50%">${user.name}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%" ><strong>ইউজার নাম:</strong></td>
                                            <td width="50%">${user.username}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%"><strong>ফোন:</strong></td>
                                            <td width="50%">${user.phone}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%"><strong>ইমেইল:</strong></td>
                                            <td width="50%">${user.email}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%" ><strong>রক্তের গ্রুপ:</strong></td>
                                            <td width="50%">${user.bloodType}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%" ><strong>ঠিকানা:</strong></td>
                                            <td width="50%">${user.address}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%" ><strong>ইউজারে ধরন:</strong></td>
                                            <td width="50%">
                                                <span class="badge bg-info text-white">
                                                    ${user.roles[0].name}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" ><strong>সট্যাটাস:</strong></td>
                                            <td>
                                                ${status}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%"><strong>ক্রিয়াকলা:</strong></td>
                                            <td width="50%">
                                                @can('assign member')
                                                <a href="#" class="btn btn-outline-info btn-sm mr-4 assign-member-btn" data-bs-toggle="modal" data-bs-target="#AssignMemberModal" data-id="${user.id}">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                                @endcan
                                                @can('show user')
                                                <a href="#" class="btn btn-outline-secondary btn-sm mr-4 show-user-btn" data-bs-toggle="modal" data-bs-target="#ShowModal" data-id="${user.id}">
                                                    <i class="fa fa-eye" aria-label="fa fa-low-vision"></i>
                                                </a>
                                                @endcan
                                                @can('update user')
                                                <a href="#" class="btn btn-outline-warning btn-sm mr-4 edit-btn"
                                                data-bs-toggle="modal" data-bs-target="#EditModal" data-id="${user.id}">
                                                    <i class="fa fa-pencil" aria-label="fa fa-low-vision"></i>
                                                    </a>
                                                @endcan
                                                @can('delete user')
                                                <a href="#" class="btn btn-outline-danger btn-sm mr-4 delete-btn d-none" data-id="${user.id}">
                                                    <i class="fa fa-trash" aria-label="fa fa-low-vision"></i>
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
            }

        });

    </script>
@endpush
