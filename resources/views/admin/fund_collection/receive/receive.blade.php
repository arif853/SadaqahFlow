@extends('layouts.admin')
@section('title','খেদমত আদায়')
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
                <li class="breadcrumb-item active"><a href="javascript:void(0);">খেদমত আদায়</a></li>
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
            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 col-xxl-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">খেদমত আদায় তালিকা</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tbody>
                                @foreach ($khedmots as $key => $khedmot)
                                    <tr>
                                        <td width="50px">{{ $key + 1 }}</td>
                                        <td width="200px">
                                            <a href="#" data-id="{{ $khedmot->id }}" id="khedmot" class="khedmot">
                                                <span class="text-info">{{ $khedmot->member->name }} <br> {{ $khedmot->member->kollan_id }}</span>
                                            </a>
                                        </td>
                                        <td width="200px">
                                            খেদমত:<span class="khedmot-amount"> {{ $khedmot->khedmot_amount }}</span>
                                            মানত:<span class="manat-amount"> {{ $khedmot->manat_amount }}</span>
                                            কল্যাণ:<span class="kollan-amount"> {{ $khedmot->kalyan_amount }}</span>
                                            ভাড়া:<span class="rent-amount"> {{ $khedmot->rent_amount }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-lg-8 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">খেদমত জমা করুন</h4>

                    </div>
                    <div class="card-body">
                        <form id="khedmotSubmitForm" method="POST" action="{{ route('fund.receive.store') }}" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div class="row">
                                <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                    <div class="form-group">
                                        <label for="" class="form-lable">তারিখ <span class="text-danger">*</span> </label>
                                        <input class="form-control" type="date" placeholder="তারিখ " name="date" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="form-lable">অনুষ্ঠান নাম <span class="text-danger">*</span></label>
                                <select name="program_id" id="program_id" class="form-control" required>
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
                                        <input readonly class="form-control" type="number" placeholder="কেদমতের পরিমাণ" name="khedmot_amount" min="0" value="0">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="" class="form-lable">মানত পরিমাণ </label>
                                        <input readonly class="form-control" type="number" placeholder="মানত পরিমাণ" name="manat_amount" min="0" value="0">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="" class="form-lable">কল্যাণ পরিমাণ </label>
                                        <input readonly class="form-control" type="number" placeholder="কল্যাণ পরিমাণ" name="kollan_amount" min="0" value="0">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="" class="form-lable">ভাড়া পরিমাণ </label>
                                        <input readonly class="form-control" type="number" placeholder="ভাড়া পরিমাণ" name="rent_amount" min="0" value="0">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="" class="form-lable">মোট পরিমাণ </label>
                                        <input readonly class="form-control" type="number" placeholder="মোট পরিমাণ" name="total_amount" min="0" value="0">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="khedmot_id" id="khedmot_id" class="form-control">
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
    </div>
</div>

@endsection
@push('script')
    <script>
        $(document).ready(function() {
            // $('#program_name').on('change', function() {
            //     if($('#program_name').val() == 6) {
            //         $('#other_program_name').show();
            //     } else {
            //         $('#other_program_name').hide();
            //     }
            // });
            // Initialize total variables globally
            let total_khedmot_amount = 0;
            let total_manat_amount = 0;
            let total_kollan_amount = 0;
            let total_rent_amount = 0;
            var khedmotId = [];

            $(document).on('click', '.khedmot', function (e) {
                e.preventDefault();
                var khedmot_id = $(this).data('id');

                let parentRow = $(this).closest('tr'); // Locate the parent row
                // console.log(parentRow.find('.khedmot-amount').text());

                let khedmot_amount = parentRow.find('.khedmot-amount').text() || 0;
                let manat_amount = parentRow.find('.manat-amount').text() || 0;
                let kollan_amount = parentRow.find('.kollan-amount').text() || 0;
                let rent_amount = parentRow.find('.rent-amount').text() || 0;

                // console.log("Amounts from clicked row:", { khedmot_amount, manat_amount, kollan_amount, rent_amount });
                 // Check if the link is disabled
                if ($(this).hasClass('disabled')) {
                    khedmotId.splice(khedmotId.indexOf(khedmot_id), 1);
                    // Subtract the amounts from the totals
                    total_khedmot_amount -= parseInt(khedmot_amount) || 0;
                    total_manat_amount -= parseInt(manat_amount) || 0;
                    total_kollan_amount -= parseInt(kollan_amount) || 0;
                    total_rent_amount -= parseInt(rent_amount) || 0;

                    // Remove the disabled class and CSS styles
                    $(this).removeClass('disabled')
                    parentRow.css({
                        'pointer-events': '',
                        'background-color': '',
                        'text-decoration': ''
                    });
                } else {
                    khedmotId.push(khedmot_id);
                    // Add the amounts to the totals
                    total_khedmot_amount += parseInt(khedmot_amount) || 0;
                    total_manat_amount += parseInt(manat_amount) || 0;
                    total_kollan_amount += parseInt(kollan_amount)  || 0;
                    total_rent_amount += parseInt(rent_amount) || 0;

                    // Disable the clicked link
                    $(this).addClass('disabled')
                    parentRow.css({
                        'pointer-events': '',
                        'background-color': 'rgb(201, 255, 171)',
                        'text-decoration': 'none'
                    });
                }
                // console.log(khedmotId);
                // Update input fields with the new totals
                $('#khedmotSubmitForm').find('input[name="khedmot_amount"]').val(total_khedmot_amount || 0);
                $('#khedmotSubmitForm').find('input[name="manat_amount"]').val(total_manat_amount || 0);
                $('#khedmotSubmitForm').find('input[name="kollan_amount"]').val(total_kollan_amount || 0);
                $('#khedmotSubmitForm').find('input[name="rent_amount"]').val(total_rent_amount || 0);

                $('#khedmotSubmitForm').find('input[name="khedmot_id"]').val(khedmotId);

                // Calculate the grand total and update the corresponding field
                let total_amount = total_khedmot_amount + total_manat_amount + total_kollan_amount + total_rent_amount;
                $('#khedmotSubmitForm').find('input[name="total_amount"]').val(total_amount);

                // console.log("Updated Totals:", {
                //     total_khedmot_amount,
                //     total_manat_amount,
                //     total_amount
                // });
            });


            // $('#khedmotSubmitForm').on('submit', function(e) {
            //     e.preventDefault();
            //     let formData = new FormData(this);
            //     console.log(formData);
            //     $.ajax({
            //         url: $(this).attr('action'),
            //         type: $(this).attr('method'),
            //         data: formData,
            //         processData: false,
            //         contentType: false,
            //         success: function(response) {
            //             console.log(response);

            //         }
            //     });
            // });

        });
    </script>
@endpush
