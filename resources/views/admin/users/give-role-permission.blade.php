
@extends('layouts.admin')
@section('title','Permission Assign')
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
                <li class="breadcrumb-item active"><a href="javascript:void(0);">Give Permission</a></li>
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
    .table tr td{
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
                            <p>Assign Permission To </p>
                            <h3>Role : <span>{{$role->name}}</span></h3>
                        </div>
                        <div class="right pull-right">
                            <a href="javascript:history.back()" class="btn btn-outline-danger"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{url('/dashboard/users/roles/'.$role->id.'/give-permissions')}}" method="post">
                            @csrf
                            @method('put')
                            <div class="row">
                                @foreach($groupedPermissions as $category => $types)
                                <div class="col-lg-3">
                                    <div class="card">
                                        <div class="card-header bg-info d-flex justify-content-between">
                                            <h4 class=" uppercase">{{ $category }}</h4>
                                            <label class=" uppercase" for="selectall_{{$category}}">
                                                <input type="checkbox" name="" id="selectall_{{$category}}" class="select_All me-1" data-category="{{$category}}">
                                                Select ALL
                                            </label>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @foreach($types as $type => $permissions)
                                                    @foreach($permissions as $index => $permission)

                                                    <div class="col-lg-6 col-md-6 col-sm-6 mb-3">
                                                        <label for="permission_{{$permission->id}}">
                                                            <input type="checkbox" name="permission[]"
                                                            id="permission_{{$permission->id}}"
                                                            value="{{$permission->name}}"
                                                            data-category="{{$category}}"
                                                            @if(in_array($permission->id, $rolePermission->pluck('pivot.permission_id')->toArray())) checked @endif
                                                            >
                                                            <span class=" uppercase">
                                                                {{ $permission->name }}
                                                            </span>
                                                            <br>
                                                        </label>
                                                    </div>

                                                    @endforeach
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <a href="javascript:history.back()" class="btn btn-outline-danger"><i class="fa fa-times"></i> Cancel</a>

                            <button type="submit" class="btn btn-outline-success btn-lg pull-right">Save</button>
                        </form>

                    </div> <!-- card-body end// -->
                </div> <!-- card end// -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        function updateSelectAll(category) {
            var allChecked = $(`input[type='checkbox'][data-category='${category}']:not(.select_All)`).length === $(`input[type='checkbox'][data-category='${category}']:not(.select_All):checked`).length;
            $(`#selectall_${category}`).prop('checked', allChecked);
        }

        $('.select_All').change(function() {
            var category = $(this).data('category');
            var checkboxes = $(`input[type='checkbox'][data-category='${category}']:not(.select_All)`);
            checkboxes.prop('checked', $(this).prop('checked'));
        });

        $('input[type="checkbox"]:not(.select_All)').change(function() {
            var category = $(this).data('category');
            updateSelectAll(category);
        });

        // Initialize the state of the select all checkboxes on page load
        $('.select_All').each(function() {
            var category = $(this).data('category');
            updateSelectAll(category);
        });
    });
</script>
@endpush
