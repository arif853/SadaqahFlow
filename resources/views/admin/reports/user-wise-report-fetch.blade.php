<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User wise Report </title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Rochester">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">

    <style>

        @font-face {
            font-family: 'nikosh';
            src: url('{{ public_path("resources/fonts/Nikosh.ttf") }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Kalpurush';
            src: url('{{ public_path("resources/fonts/Kalpurush.ttf") }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @page{
            margin-top: 150px;
            margin-bottom: 90px;
        }

        header{
            position: fixed;
            left: 0px;
            right: 0px;
            height: 0px;
            top: -100px;
        }

        .footer {
            position: fixed;
            bottom: -90px;
            left: 0px;
            right: 0px;
            height: 50px;
            font-size: 13px !important;
            text-align: center;
        }

        body{
            font-family: 'nikosh', sans-serif;
        }

        .name{
            padding-top:385px;
            padding-left: 70px;
            float: right;
        }


        table {
            font-family: 'nikosh', sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        /* td, th {
          border: 1px solid #dddddd;
          text-align: left;
          padding: 8px;
        }

        tr:nth-child(even) {
          background-color: #dddddd;
        } */

        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            font-family: 'nikosh', sans-serif;
        }
        tr{
            height: 25px;
        }
    </style>
</head>
<body>
    <div class="block-content">

        <div class="p-sm-4 p-xl-7" >
            <header>
                <div class="row" style="width: 100%">
                    <div class="col-10" style="width: 100% ;text-align: center; ">
                        <p style="font-family: 'nikosh', sans-serif; font-size: 16px !important; margin:0;">বসিবার স্থান দক্ষিণ কুতুবখালি</p>
                        <p style=" font-size: 13px !important;margin:0;">রসুলপুর, যাত্রাবাড়ী, ঢাকা ১২৩৬</p>
                        <p style=" font-size: 13px !important;margin:0;">কর্মী খেদমত রিপোর্ট </p>
                    </div>
                </div>

            </header>
            <div>
                <span>তারিখ : {{date('d-m-Y')}}</span>
            </div>
            <!-- Table -->
            <div class="table-responsive push" style="margin-top: 0px; font-size: 13px !important;">
                <table class="table table-bordered" style="font-size: 11px; text-align:center;font-family: 'nikosh', sans-serif;">
                    <thead>
                        <tr>
                            <td>Sl</td>
                            <td>তারিখ</td>
                            <td>জাকের নাম</td>
                            <td>খেদমত </td>
                            <td>মানতের </td>
                            <td>ভাড়া</td>
                            {{-- <td>কর্মী</td> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalKhedmot = 0;
                            $totalmanot = 0;
                            $totalRent = 0;
                        @endphp

                        @foreach ($khedmots as $key => $khedmot)
                        @if($khedmots->isEmpty())
                        <tr>
                            <td colspan="6">No data available</td>
                        </tr>
                        @else
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $khedmot->date }}</td>
                            <td>{{ $khedmot->member->name }}</td>
                            <td>{{ $khedmot->khedmot_amount ?? 0 }}</td>
                            <td>{{ $khedmot->manat_amount ?? 0 }}</td>
                            <td>{{ $khedmot->rent_amount ?? 0 }}</td>
                            @php
                                $totalKhedmot += $khedmot->khedmot_amount ?? 0;
                                $totalmanot += $khedmot->manat_amount ?? 0;
                                $totalRent += $khedmot->rent_amount ?? 0;
                            @endphp
                        </tr>
                        @endif
                        @endforeach
                        <tr>
                            <td colspan="3">Total</td>
                            <td>{{$totalKhedmot}}</td>
                            <td>{{$totalmanot}}</td>
                            <td>{{$totalRent}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- END Table -->

            <!-- Footer -->
            <footer>

            </footer>
            <div class="footer" style="width: 100%">
                <div class="col-4"  style="width: 33% ; text-align: left; float: left; background-color:">

                    <span>Prepared By -  </span>
                </div>
                <div class="col-4"  style="width: 33% ;text-align: left; float: left; padding-left: 50px; ">

                    <span>Checked By - </span>
                </div>
                <div class="col-4"  style="width: 33% ; text-align: left; float: left; padding-left: 100px;">

                    <span>Approved By - </span>
                </div>
                <div class="col-4"  style="width: 100% ; text-align: center; padding-left: 20px; padding-top: 20px;">
                    <br>
                    <span>Prepared At : {{date('Y-m-d H:i:s')}} </span>
                </div>
            </div>
            <!-- END Footer -->
        </div>
    </div>
</body>
</html>
