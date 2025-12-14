@extends('layouts.admin')
@section('title','অনুষ্ঠান ধরন তালিকা')
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
                <li class="breadcrumb-item active"><a href="javascript:void(0);">অনুষ্ঠান ধরন</a></li>
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
                        <h4 class="card-title d-block">অনুষ্ঠান ধরন তালিকা</h4>

                        @can('create member')
                        <button class="btn btn-md btn-primary mb-15 float-end" type="button"
                        data-bs-toggle="modal" data-bs-target="#programTypeModal">নতুন অনুষ্ঠান যোগ</button>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-3 col-lg-3 col-xl-3">
                                <div class="mb-10">
                                    <label for="searchInput">অনুষ্ঠান ধরন খুঁজুন</label>
                                    <input type="text" class="form-control" placeholder="যাকের নাম/ ফোন নাম্বার / কল্যাণ নাম্বার খুঁজুন" id="searchInput">
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        <div class="row" id="memberCard">
            @foreach ($programTypes as $key => $program)
            <div class="col-sm-12 col-md-3 col-lg-3" >
                <div class="card">
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="50%"><strong>নং:</strong></td>
                                <td width="50%">{{ $key + 1 }}</td>
                            </tr>
                            <tr>
                                <td width="50%"><strong>অনুষ্ঠানের নাম:</strong></td>
                                <td width="50%">

                                    <span>{{ $program->name }} </span>

                                </td>
                            </tr>

                            <tr>
                                <td width="50%"><strong>অনুষ্ঠানের তারিখ:</strong></td>
                                <td width="50%">{{ Carbon\Carbon::parse($program->date)->format('d-m-Y') ?? $program->date }}</td>
                            </tr>
                            <tr>
                                <td width="50%"><strong>অবস্থা:</strong></td>
                                <td width="50%">
                                    @if($program->status == 1)
                                        <a href="{{route('program-types.status', $program->id)}}" class="badge bg-success text-white">সক্রিয়</a>
                                    @else
                                        <a href="{{route('program-types.status', $program->id)}}" class="badge bg-danger text-white">নিষ্ক্রিয়</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td width="50%"><strong>ক্রিয়াকলা:</strong></td>
                                <td width="50%">
                                    @can('update program type')
                                    <a href="#" class="btn btn-outline-warning btn-sm mr-4 edit-btn" data-id="{{$program->id}}" data-bs-toggle="modal" data-bs-target="#EditModal">
                                        <i class="fa fa-pencil" ></i>
                                    </a>
                                    @endcan
                                    @can('delete program type')
                                    <a href="#" class="btn btn-outline-danger btn-sm mr-4 delete-btn" data-id="{{$program->id}}">
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
    </div>
</div>

 <!-- Data Modal Start-->
 <div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="EditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-vertical-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="EditModalLabel">
                অনুষ্ঠানের ধরন আপডেট করুন
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="EditForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="" class="form-lable">অনুষ্ঠানের নামে <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" placeholder="অনুষ্ঠানের নামে লিখুন" name="name" required>
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">অনুষ্ঠানের তারিখ <span class="text-danger">*</span></label>
                    <input class="form-control" type="date" placeholder="অনুষ্ঠানের তারিখ" name="date" required>
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
 <div class="modal fade" id="programTypeModal" tabindex="-1" aria-labelledby="programTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="programTypeModalLabel">
                অনুষ্ঠান ধরন যোগ করুন
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('program-types.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="form-group">
                    <label for="" class="form-lable">অনুষ্ঠানের নামে <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" placeholder="অনুষ্ঠানের নামে লিখুন" name="name" required>
                </div>
                <div class="form-group">
                    <label for="" class="form-lable">অনুষ্ঠানের তারিখ <span class="text-danger">*</span></label>
                    <input class="form-control" type="date" placeholder="অনুষ্ঠানের তারিখ" name="date" required>
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

            $(document).on('click','.edit-btn', function (event) {
                event.preventDefault();
                const id = $(this).data('id');
                $.ajax({
                    url: `/dashboard/program-types/${id}/edit`,
                    method: 'GET',
                    success: function(response) {
                        console.log(response);
                        $('#EditModal').find('form').attr('action', `/dashboard/program-types/${response.id}`);
                        // $('#EditModal').find('input[name="id"]').val(response.id);
                        $('#EditModal').find('input[name="name"]').val(response.name);
                        $('#EditModal').find('input[name="date"]').val(response.date);
                        $('#EditModal').find('input[name="status"]').prop('checked', response.status);

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
                    title: 'আপনি ডিলেট করবেন?',
                    text: "এটি পুনরুদ্ধারিত করতে পারবেন না!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'হ্যা'
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/dashboard/program-types/${id}`,
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

        });

    </script>
@endpush
