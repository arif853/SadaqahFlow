<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>রিপোর্ট</title>
    <style>
        body {
            font-family: 'nikosh', sans-serif !important;
        }
        @page{
            margin-top: 180px;
            margin-bottom: 90px;
        }

        header{
            position: fixed;
            left: 0px;
            right: 0px;
            top: -140px;
            height: 150px;
            font-size: 20px;
        }

        /* Top small phrases */
        .header-top-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        .header-top-table td {
            font-family: 'nikosh', sans-serif;
            font-weight: 700;
            font-size: 18px;
            width: 33%; /* This ensures equal spacing across the top */
            border: none;
        }
        
        .ht1 { text-align: center; }
        /* Using center for the middle one often looks better in reports */
        .ht2 { text-align: center; padding-left: 20px; } 
        .ht3 { text-align: center; padding-left: 20px; }
        
        .header-center {
            text-align: center;
        }

        /* Row holding logo at left and title centered */
        .header-row{
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            position: relative;
        }

        .header-left{
            position: absolute;
            left: 0;
            top: 0;
            width: 80px;
            padding-left: 20px;
        }

        .header-left img{
            max-width: 80px;
            height: auto;
            display: block;
        }

        .header-center{
            flex: 1 1 auto;
            text-align: center;
            font-family: 'nikosh', sans-serif;
        }

        .header-center h2{ margin: 2px 0; }
        .header-center p{ margin: 0; font-size: 14px; }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            text-align: center;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
        }
        .text-center {
            text-align: center;
        }
        p{
            padding: 0;
            margin: 0;
        }
    </style>
</head>
<body >
    <header>
        <table class="header-top-table">
            <tr>
                <td class="ht1">ইয়া আল্লাহু !</td>
                <td class="ht2">ইয়া রাহ্‌মানু !!</td> 
                <td class="ht3">ইয়া রাহীম !!!</td>
            </tr>
        </table>
        <div class="header-row">
            {{-- <div class="header-left">
                <img src="{{ public_path('assets/images/logo-2.png') }}" alt="Logo">
            </div> --}}
            <div class="header-center">
                <h2 style="margin: 5px 0;">বসিবার স্থান দক্ষিণ কুতুবখালি</h2>
                <p>রসুলপুর, যাত্রাবাড়ী, ঢাকা ১২৩৬</p>
                <p>কর্মী খেদমত রিপোর্ট  - কর্মীর নামঃ <span style="font-size: 12px;">{{$user->name}}</span></p>
                
            </div>
        </div>
    </header>

    <p>তারিখ: {{ date('d-m-Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Sl</th>
                <th style="font-size: 16px;">তারিখ</th>
                <th style="font-size: 16px;" width="15%">অনুষ্ঠানের নাম</th>
                <th style="font-size: 16px;">জাকের নাম</th>
                <th style="font-size: 16px;">খেদমত</th>
                <th style="font-size: 16px;">মানতের</th>
                {{-- <th style="font-size: 16px;">ভাড়া</th> --}}
                <th style="font-size: 16px;" width="20%">মন্তব্য</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalKhedmot = 0;
                $totalManot = 0;
                $totalRent = 0;
            @endphp

            @forelse ($khedmots as $key => $khedmot)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ Carbon\Carbon::parse($khedmot->date)->format('d-M-Y')}}</td>
                    @php
                        $date = new DateTime($khedmot->date);
                        $year = "২৫"
                        // $date->format('y');
                    @endphp
                    <td style="font-size: 16px;">
                        {{ $khedmot->program ? $khedmot->program->name : 'N/A' }}
                    </td>
                    <td style="font-size: 16px;">
                        {{ $khedmot->member->name }} {{ $khedmot->member->nickName? '('.$khedmot->member->nickName.')':'' }}<br>
                        <p style="font-size: 10px; color:rgb(138, 138, 138)">Id: {{$khedmot->member->kollan_id}}</p>
                    </td>
                    <td>{{ number_format($khedmot->khedmot_amount) ?? 0 }}</td>
                    <td>{{ number_format($khedmot->manat_amount) ?? 0 }}</td>
                    {{-- <td>{{ number_format($khedmot->rent_amount) ?? 0 }}</td> --}}
                    <td>{{$khedmot->comment}}</td>
                </tr>
                @php
                    $totalKhedmot += $khedmot->khedmot_amount ?? 0;
                    $totalManot += $khedmot->manat_amount ?? 0;
                    $totalRent += $khedmot->rent_amount ?? 0;
                @endphp
            @empty
                <tr>
                    <td colspan="6">No data available</td>
                </tr>
            @endforelse

            <tr>
                <td colspan="4"><strong>Total</strong></td>
                <td><strong>{{ number_format($totalKhedmot,2) }}</strong></td>
                <td><strong>{{ number_format($totalManot,2) }}</strong></td>
                {{-- <td><strong>{{ number_format($totalRent,2) }}</strong></td> --}}
                <td></td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 30px;">
        <p class="text-center" style="font-size: 12px;">Prepared At: {{ date('Y-m-d H:i:s') }}</p>
    </div>

</body>
</html>
