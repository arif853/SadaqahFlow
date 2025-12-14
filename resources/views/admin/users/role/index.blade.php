@extends('layouts.admin')
@section('title','Manage Roles')
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
                <li class="breadcrumb-item active"><a href="javascript:void(0);">Roles</a></li>
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

<style>
    .table tr td {
        vertical-align: middle;
    }

</style>

<div class="theme-body common-dash" data-simplebar>
    <div class="custom-container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>ম্যানেজ রোল</h5>
                        <div class="left pull-left">
                           
                        </div>
                        <div class="right pull-right">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#roleModal"
                                class="btn btn-info rounded mr-5 btn-sm">রোল যোগ করুন</button>
                            <a href="{{route('users.index')}}" class="btn btn-success rounded mr-5 btn-sm">ইউজারস</a>
                            <a href="{{route('permissions.index')}}"
                                class="btn btn-success rounded btn-sm">পারমিশনস</a>
                        </div>

                    </div>
                    <div class="card-body">
                        <table id="" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>রোল নাম</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="role-table">
                                @foreach ($roles as $key => $role)
                                <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$role->name}}</td>

                                    <td>
                                        <a href="{{route('roles.give-permissions', $role->id)}}"
                                            class="btn btn-sm font-sm btn-outline-info rounded mr-5">
                                            <i class="fa fa-key"></i> পারমিশন অ্যাসাইন
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-sm font-sm rounded btn-outline-warning edit mr-5"
                                            data-bs-toggle="modal" data-bs-target="#roleUpdateModal"
                                            data-role-id="{{ $role->id}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-sm font-sm btn-outline-danger rounded delete"
                                            data-id="{{ $role->id}}">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                       
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>

                        </table>
                    </div> <!-- card-body end// -->
                </div> <!-- card end// -->
            </div>
        </div>
    </div>
</div>

{{-- Role Update Modal --}}

<!-- Modal -->
<div class="modal fade" id="roleUpdateModal" tabindex="-1" aria-labelledby="roleUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLabel">Update Role</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="roleUpdateForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-12 mb-2">
                        <label for="role_name" class="form-label">Role Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="role_name" name="role_name" required>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </form>
      </div>
    </div>
</div>




{{-- Role Add Modal --}}
<!-- Modal -->
<div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLabel">New Role</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{route('roles.store')}}" method="post">
            @csrf
            @method('POST')
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-12 mb-2">
                        <label for="name" class="form-label">Role Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Role Name" required>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </form>
      </div>
    </div>
</div>




@endsection
@push('script')
<script>
    $(document).ready(function(){
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        // Edit ROle
        $(document).on('click', '.edit', function (e) {
            e.preventDefault();
            const roleId = $(this).data('role-id');
            console.log(roleId);

            $.ajax({
                url: `{{url('/dashboard/users/roles/${roleId}/edit')}}`,
                method: 'GET',
                data: {
                    id: roleId,
                },
                success: function (response) {
                    console.log(response);
                    $('#roleUpdateModal').find('form').attr('action', `/dashboard/users/roles/${response.role.id}`);
                    $('#role_id').val(response.role.id);
                    $('#role_name').val(response.role.name);
                }
            });
        });


        //Update Role
        $("#roleUpdateForm").submit(function (e) {
            e.preventDefault();
            const data = new FormData(this);
            // var roleId = $('#role_id').val();
            // console.log(roleId);

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: data,
                cache: false,
                processData: false,
                contentType: false,
                success: function (res) {
                    // console.log(res);
                    if (res.status == 200) {
                        // $("#sliderEditModal").modal('hide');
                        $('#role-table').load(location.href + ' #role-table');
                        $('#roleUpdateModal').modal('hide');
                        Swal.fire('Saved!', '', 'success')
                    }
                   
                },
                error: function (xhr, textStatus, errorThrown) {
                   
                    $("#roleUpdateModal").modal('hide');
                }
            })
        });

        $('.delete').on('click', function (event) {
                const id = $(this).data('id');
                console.log(id);

                Swal.fire({
                    title: 'রোল ডিলেট করবেন?',
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
                            url: `{{url('/dashboard/users/roles/${id}/delete')}}`,
                            method: 'DELETE',
                            success: function(response) {
                                $('#role-table').load(location.href + ' #role-table');
                                Swal.fire('Saved!', '', 'success')
                            }
                        });
                    } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            })
        });
    });

</script>
@endpush
