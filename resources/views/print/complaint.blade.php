<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $data->title ?? 'Keluhan Pelanggan' }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        .container {
            padding: 0 20px;
        }

        .title {
            font-style: normal;
            font-weight: 700;
            font-size: 14px;
            color: #5A7D7C;
            padding: 15px;
        }

        .content {
            font-style: normal;
            font-weight: 400;
            font-size: 14px;
            color: #5A7D7C;
        }

        .text-1 {
            color: #5A7D7C;
            font-size: 18px;
            font-weight: bold;
            line-height: 10px;
        }

        .italic {
            font-style: italic;
        }

        .text-2 {
            color: #5A7D7C;
            font-size: 12px;
            font-weight: bold;
            line-height: 22px;
            margin: 2px 0;
        }

        .header {
            text-align: center;
        }

        .row {
            display: flex !important;
        }

        .row .col {
            flex: 1;
            padding: 10px
        }

        input[type="checkbox"] {
            vertical-align: middle;
        }


        .box {
            background: #FFFFFF;
            border: 1.5px solid #6A9695;
            min-height: 18px;
            padding: 5px;
            font-size: 12px;
        }

        .qr-box {
            height: 60px;
            width: 60px;
            padding: 3px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid black;
            margin-bottom: 10px;
        }

        .header-line {
            margin: 0 0 10px 0;
        }

        hr {
            color: #6A9695;
        }

        .footer-line {
            margin: 10px 0 0 0;
        }

        .d-flex {
            display: flex;
        }

        .p-none {
            padding: 0;
        }

        .m-none {
            margin: 0;
        }

        .f-12 {
            font-size: 12px;
        }

        .text-bg {
            font-size: 12px;
            font-weight: bold;
            color: white;
            background: #6A9695;
            padding: 8px 6px;
        }

        .column-left {
            padding: 0 10px 0 0;
        }

        .column-right {
            padding: 0 0 0 10px;
        }

        label {
            font-size: 12px;
            color: #6A9695;
            align-items: center;
        }

        @page {
            margin: 100px 25px 130px 25px;
        }

        header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
            height: 50px;

        }

        footer {
            position: fixed;
            bottom: -40px;
            left: 0px;
            right: 0px;
            height: 50px;
        }

        input[type=checkbox] {
            flex: 1;
        }

        label {
            display: flex;
            align-items: center;
        }
        tr {
            padding: 10px;
        }

        td:nth-child(1) {
            width: 40%;
        }

        td:nth-child(2) {
            width: 10%;
        }

        td:nth-child(3) {
            width: 50%;
        }

    </style>
</head>

<body>
    <header>
        <div>
            <img src="{{ public_path('images/logo-ruang-mutu.png') }}"
                style="width: 120px; height: 30px;margin: 0 0 -3px -5px;" />
        </div>
        <hr class="header-line" />
    </header>
    <footer>
        <div class="qr-box" id="qrcode-2">
            <img src="data:image/png;base64, {!! $qrcode !!}">
        </div>
        <hr class="footer-line" style="margin: 0 0 !important" />
        <table width='100%'>
            <tr>
                <td>
                    <div class="d-flex italic" style="flex-direction: column; color: black;">
                        <p class="p-none m-none f-12">Dilarang menduplikat dokumen tanpa izin Manajemen Mutu</p>
                        <p class="p-none m-none f-12">Puskesmas Kecamatan Gambir</p>
                    </div>
                </td>
                <td>
                    <div class="text-bg "style="text-align: center;">
                        PUSKESMAS KECAMATAN GAMBIR
                    </div>
                </td>
            </tr>
        </table>
    </footer>
    <main>
        <div class="container">
            <div class="header">
                <img src="{{ public_path('images/logo-puskes-gambir.png') }}" style="width: 50px; height: 50px;" />
                <p class="text-1">KLARIFIKASI KELUHAN</p>
                <p class="text-1">PELANGGAN</p>
            </div>
            <table width="100%">
                <tr>
                  <td class="title">FASKES</td>
                  <td>:</td>
                  <td class="content">
                    {{$data->healthService->name}}    
                  </td>
                </tr>
                <tr>
                  <td class="title">HARI/TANGGAL</td>
                  <td>:</td>
                  <td class="content">
                    {{$data->complaint_date}}
                  </td>
                </tr>
                <tr>
                  <td class="title">ID KELUHAN</td>
                  <td>:</td>
                  <td class="content">{{$data->complaint_id}}</td>
                </tr>
                <tr>
                  <td class="title">PELAPOR</td>
                  <td>:</td>
                  <td class="content">{{$data->reported_by ?? 'Anonym'}}</td>
                </tr>
                <tr>
                  <td class="title">SUMBER</td>
                  <td>:</td>
                  <td class="content">{{$data->source}}</td>
                </tr>
                <tr>
                  <td class="title">ISI LAPORAN</td>
                  <td>:</td>
                  <td class="content">{{$data->report}}</td>
                </tr>
                <tr>
                  <td class="title">KOORDINASI</td>
                  <td>:</td>
                  <td class="content">
                    {{$data->coordination}}
                  </td>
                </tr>
                <tr>
                  <td class="title">TANGGAL KLARIFIKASI</td>
                  <td>:</td>
                  <td class="content">
                    {{$data->clarification_date}}
                  </td>
                </tr>
                <tr>
                  <td class="title">TINDAK LANJUT</td>
                  <td>:</td>
                  <td class="content">
                    {{$data->follow_up}}
                  </td>
                </tr>
                <tr>
                  <td class="title">STATUS</td>
                  <td>:</td>
                  <td class="content">
                    @if ($data->status === 'DONE')
                    Selesai
                    @elseif ($data->status === 'PENDING')
                    Pending                    
                    @else
                        {{$data->status}}
                    @endif</td>
                </tr>
              </table>
        </div>
    </main>
</body>

</html>