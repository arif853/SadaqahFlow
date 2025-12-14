@extends('layouts.admin')
@section('title','Manage Permissions')
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
                <li class="breadcrumb-item active"><a href="javascript:void(0);">Permissions</a></li>
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
                        <div class="left pull-left">
                            {{-- <a href="#" class="btn btn-warning rounded">Permission</a> --}}
                            @can('create permission')
                            <button type="button" data-bs-toggle="modal" data-bs-target="#permissionModal"
                                class="btn btn-info rounded mr-5 btn-sm">Add Permission</button>
                            @endcan
                            @can('view role')
                            <a href="{{route('roles.index')}}" class="btn btn-success rounded mr-5 btn-sm">Roles</a>
                            @endcan
                            @can('view user')
                            <a href="{{route('users.index')}}" class="btn btn-success rounded btn-sm">Users</a>
                            @endcan
                        </div>
                        <div class="right pull-right">
                        </div>

                    </div>
                    <div class="card-body">
                        <p class="mb-4"><span class="text-warning">* Warning: </span> Make a permission name like <span
                                class="text-warning">Type + ( Permission For )</span>.
                            You can add 4 types of permission ( Create, Update, Delete, View ). If you need other type
                            contact technical team. <br>
                            <span class="text-success">Example: ( Create Order or Update Order )</span>

                            {{-- Bulk Delete btn --}}
                            <form id="bulkDeleteForm" method="POST" action="{{ route('permissions.bulk_delete') }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger mb-4 delete" id="bulkDeleteButton"
                                    style="display: none;">Delete Selected</button>
                            </form>
                            <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAll">
                                        </th>
                                        <th>#</th>
                                        <th>Permission Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permissions as $key => $permission)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selected_permissions[]"
                                                value="{{$permission->id }}" class="selectCheckbox">
                                        </td>
                                        <td>{{$key + 1}}</td>
                                        <td>{{$permission->name}}</td>

                                        <td>
                                            @can('edit permission')
                                            <a href="javascript:void(0)" class="btn btn-sm font-sm rounded btn-outline-warning mr-5 edit"
                                                data-bs-toggle="modal" data-bs-target="#permissionUpdateModal"
                                                data-permission-id="{{ $permission->id}}">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            @endcan
                                            @can('delete permission')
                                            <a href="{{route('permissions.destroy',$permission->id)}}" class="btn btn-sm font-sm btn-outline-danger rounded delete">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            @endcan
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

<script>
    document.getElementById('selectAll').addEventListener('change', function (e) {
        let checkboxes = document.querySelectorAll('.selectCheckbox');
        checkboxes.forEach(checkbox => checkbox.checked = e.target.checked);
        toggleBulkDeleteButton();
    });

    document.querySelectorAll('.selectCheckbox').forEach(checkbox => {
        checkbox.addEventListener('change', toggleBulkDeleteButton);
    });

    function toggleBulkDeleteButton() {
        let selected = document.querySelectorAll('.selectCheckbox:checked').length;
        document.getElementById('bulkDeleteButton').style.display = selected > 0 ? 'block' : 'none';
    }

    document.getElementById('bulkDeleteButton').addEventListener('click', function (event) {
        event.preventDefault();

        form = this.closest('form');

        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

</script>

<!-- Permission Update Modal -->
<div class="modal fade" id="permissionUpdateModal" tabindex="-1" aria-labelledby="permissionUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="permissionUpdateModalLabel">Update Permission</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="permissionUpdateForm" >
            @csrf
            @method('PUT')
            <div class="modal-body">
                <p class="mb-4"><span class="text-warning">* Warning: </span> Make a permission name like <span class="text-warning">Type + ( Permission For )</span>.
                    You can add 4 types of permission ( Create, Update, Delete, View ). If you need other type contact technical team. <br>
                    <span class="text-success">Example: ( Create Order or Update Order )</span>
                <div class="row g-3">
                    <div class="col-md-12 mb-2">
                        <label for="permission_name" class="form-label">Permission Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="permission_name" name="permission_name" required>
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

<!-- Permission Add Modal -->
<div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="permissionModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLabel">New Permission</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{route('permissions.store')}}" method="post">
            @csrf
            @method('POST')
            <div class="modal-body">
                <p class="mb-4"><span class="text-warning">* Warning: </span> Make a permission name like <span class="text-warning">Type + ( Permission For )</span>.
                    You can add 4 types of permission ( Create, Update, Delete, View ). If you need other type contact technical team. <br>
                    <span class="text-success">Example: ( Create Order or Update Order )</span>
                   </p>
                <div class="row g-3">
                    <div class="col-md-12 mb-2">
                        <label for="name" class="form-label">Permission Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Permission Name" required>
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
    $(document).ready(function () {

        $('#selectAll').on('change', function () {
            $('.selectCheckbox').prop('checked', this.checked);
            // toggleBulkDeleteButton();
        });

        $('.selectCheckbox').on('change', function () {
            if ($('.selectCheckbox:checked').length === $('.selectCheckbox').length) {
                $('#selectAll').prop('checked', true);
            } else {
                $('#selectAll').prop('checked', false);
            }
            // toggleBulkDeleteButton();
        });

        function toggleBulkDeleteButton() {
            if ($('.selectCheckbox:checked').length > 0) {
                $('#bulkDeleteButton').show();
            } else {
                $('#bulkDeleteButton').hide();
            }
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Edit permission
        $(document).on('click', '.edit', function (e) {
            e.preventDefault();
            var permissionId = $(this).data('permission-id');

            $.ajax({
                url: '{{url('dashboard/users/permissions')}}' + '/' + permissionId + '/edit',
                method: 'GET',
                data: {
                    id: permissionId,
                },
                success: function (response) {  
                    $('#permissionUpdateModal').modal('show');
                    var url = `{{url('dashboard/users/permissions')}}/${response.permission.id}`;
                    $('#permissionUpdateForm').attr('action', url);
                    $('#permission_id').val(response.permission.id);
                    $('#permission_name').val(response.permission.name);
                    $('#permissionUpdateForm').attr('method', 'PUT');
                }
            });
        });

        //Update Permission
        $("#permissionUpdateForm").submit(function (e) {
            e.preventDefault();
            const data = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: data,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#permissionUpdateModal').modal('hide');
                    showNotification(
                        response.status,
                        response.message,
                        response.status
                    );
                    $('#permission-table').load(location.href + ' #permission-table');
                },
                error: function (xhr, textStatus, errorThrown) {
                    $("#permissionUpdateModal").modal('hide');
                }
            })
        });

        $('.delete').on('click', function (event) {
            event.preventDefault(); // Prevent the default link behavior

            var url = $(this).attr('href');
            var row = $(this).closest('tr');

            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: form.serialize(),
                        success: function (response) {
                            Swal.fire(
                                'Deleted!',
                                response.success,
                                'success'
                            );
                            row.remove(); // Remove the row from the table
                        },
                        error: function (response) {
                            Swal.fire(
                                'Error!',
                                'There was an error deleting the permission.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        $('#bulkDeleteButton').on('click', function (event) {
            event.preventDefault(); // Prevent the default button behavior

            var form = $('#bulkDeleteForm');
            var url1 = form.attr('action');
            console.log(url1);

            var selectedPermissions = [];
            $('.selectCheckbox:checked').each(function () {
                selectedPermissions.push($(this).val());
                console.log(selectedPermissions);
            });

            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete them!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url1,
                        type: 'DELETE',
                        data: {
                            '_token': $('input[name="_token"]').val(),
                            'selected_permissions': selectedPermissions
                        },
                        success: function (response) {
                            Swal.fire(
                                'Deleted!',
                                response.success,
                                'success'
                            );
                            $('.selectCheckbox:checked').closest('tr')
                        .remove(); // Remove selected rows from the table
                            $('#selectAll').prop('checked',
                            false); // Uncheck the select all checkbox
                            toggleBulkDeleteButton(); // Hide the bulk delete button
                        },
                        error: function (response) {
                            Swal.fire(
                                'Error!',
                                'There was an error deleting the permissions.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
    // $(document).ready(function{


    // document.querySelectorAll('.delete').forEach(function (element) {
    //     element.addEventListener('click', function (event) {
    //         event.preventDefault(); // Prevent the default link behavior

    //         var form = this.closest('form');

    //         Swal.fire({
    //             title: 'Are you sure?',
    //             text: 'You won\'t be able to revert this!',
    //             icon: 'warning',
    //             showCancelButton: true,
    //             confirmButtonColor: '#3085d6',
    //             cancelButtonColor: '#d33',
    //             confirmButtonText: 'Yes, delete it!'
    //         }).then((result) => {
    //             // If confirmed, submit the corresponding form
    //             if (result.isConfirmed) {
    //                 form.submit();
    //             }
    //         });
    //     });
    // });
    // });

</script>
@endpush
