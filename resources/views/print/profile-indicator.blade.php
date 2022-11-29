<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $data->title ?? 'Profile Indikator Mutu' }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        .container {
            padding: 0 20px;
        }

        .p-generate {
            font-size: 12px;
            font-style: italic;
            color: grey;
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
            height: 120px;
            width: 120px;
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

        .sign-text {
            font-size: 11px;
            color: grey;
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
            margin: 100px 25px 200px 25px;
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
            bottom: -100px; 
            left: 0px; 
            right: 0px;
            height: 100px; 
        }

        input[type=checkbox] {
            flex: 2;
        }

        label {
            display: flex;
            align-items: center;
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
            <img src="data:image/png;base64, {!! $qr_image !!}">
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
                <p class="text-1">PROFIL INDIKATOR {{$data->type === 'quality' ? 'MUTU' : 'KINERJA'}}</p>
                <p class="text-1">PUSKESMAS KECAMATAN GAMBIR</p>
            </div>
            <table width="100%" id="table-content">
                <tr>
                    <td>
                        <div class="wrapper">
                            <p class="text-2">
                                PROGRAM MUTU
                            </p>
                            <div class="box">
                                {{ $data->program->name }}
                            </div>
                        </div>
                    </td>
                </tr>
               
                <tr>
                    <td>
                        <div class="wrapper">
                            <p class="text-2">
                                SUB PROGRAM
                            </p>
                            <div class="box">
                                {{ $data->subProgram->name ?? '-' }}
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="wrapper">
                            <p class="text-2">
                                JUDUL INDIKATOR
                            </p>
                            <div class="box">
                                {{ $data->title }}
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td> <div class="wrapper">
                        <p class="text-2">
                                DASAR PEMILIHAN INDIKATOR
                            </p>
                            <div class="box">
                                {{ $data->indicator_selection_based }}
                            </div>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <div class="wrapper">
                            <p class="text-2">
                                TARGET CAPAIAN
                            </p>
                            <div class="box">
                                {{ $data->achievement_target }}
                            </div>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <div class="wrapper">
                            <p class="text-2">
                                TUJUAN
                            </p>
                            <div class="box">
                                {{ $data->objective }}
                            </div>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <div class="wrapper">
                            <p class="text-2">
                                DIMENSI MUTU
                            </p>
                            <div class="box">
                                @foreach ($list_dimension as $item)
                                    <label>
                                        {{-- @foreach ( as $x) --}}
                                            @if ($data->qualityDimension->where('name', $item['value'])->first())
                                                <input id="{{ $item['value'] }}" checked type="checkbox"/> {{ $item['value'] }}
                                            @else
                                                <input id="{{ $item['value'] }}" type="checkbox"/> {{ $item['value'] }}
                                            @endif
                                        {{-- @endforeach --}}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </td>
                </tr>
                {{-- <tr>
                    <td>
                        <div class="wrapper">
                            <p class="text-2">
                                DIMENSI MUTU
                            </p>
                            <div class="box">
                                @foreach ($list_dimension as $item)
                                    <label>
                                        @foreach ($data->qualityDimension->where('name', $item['value'])->first() as $x)
                                            @if ($x->name === $item['value'])
                                                <input id="{{ $item['value'] }}" checked type="checkbox"/> {{ $item['value'] }}
                                            @else
                                                <input id="{{ $item['value'] }}" type="checkbox"/> {{ $item['value'] }}
                                            @endif
                                        @endforeach
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </td>
                </tr> --}}
                
                <tr>
                    <td>
                        <div class="wrapper">
                            <p class="text-2">
                                POPULASI ATAU SAMPEL
                            </p>
                            <div class="box">
                                {{ $data->population }}
                            </div>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <div class="wrapper">
                            <p class="text-2">
                                NUMERATOR
                            </p>
                            <div class="box">
                                {{ $data->numerator }}
                            </div>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <div class="wrapper">
                            <p class="text-2">
                                DENOMINATOR
                            </p>
                            <div class="box">
                                {{ $data->denominator }}
                            </div>
                        </div>
                    </td>
                </tr>
{{-- ll --}}
                <tr>
                    <td>
                        <div class="wrapper">
                            <p class="text-2">
                                KRITERIA INKLUSI & EKSKLUSI
                            </p>
                            <div class="box">
                                {{ $data->criteria }}
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="wrapper">
                            <p class="text-2">
                                FORMULA PENGUKURAN
                            </p>
                            <div class="box">
                                {{ $data->measurement_formula }}
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="wrapper">
                            <p class="text-2">
                                DESAIN PENGUMPULAN DATA
                            </p>
                            <div class="box">
                                {{ $data->data_collection_design }}
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="wrapper">
                            <p class="text-2">
                                SUMBER DATA
                            </p>
                            <div class="box">
                                {{ $data->data_source }}
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="wrapper">
                            <p class="text-2">
                                PENYAJIAN DATA
                            </p>
                            <div class="box">
                                {{ $data->data_presentation }}
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="wrapper">
                            <p class="text-2">
                                STATUS PENGUKURAN
                            </p>
                            <div class="box">
                                {{ $data->measurement_status }}
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>    
                        <div class="wrapper">
                            <p class="text-2">
                                DEFINISI OPERASIONAL
                            </p>
                            <div class="box">
                                {{ $data->operational_definition }}
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="wrapper">
                            <p class="text-2">
                                PERIODE WAKTU PELAPORAN
                            </p>
                            <div class="box">
                                @foreach ($list_reports as $item)
                                    <label>
                                        @foreach ($data->dataPeriod as $x)
                                            @if ($x->name === $item['value'])
                                                <input id="{{ $item['value'] }}" checked type="checkbox"/> {{ $item['value'] }}
                                            @else
                                                <input id="{{ $item['value'] }}" type="checkbox"/> {{ $item['value'] }}
                                            @endif
                                        @endforeach
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="wrapper">
                            <p class="text-2">
                                FREKUENSI PENGUMPULAN DATA
                            </p>
                            <div class="box">
                                @foreach ($list_frequently as $item)
                                    <label>
                                            @if ($data->dataFrequency->where('name', $item['value'])->first())
                                                <input id="{{ $item['value'] }}" checked type="checkbox"/> {{ $item['value'] }}
                                            @else
                                                <input id="{{ $item['value'] }}" type="checkbox"/> {{ $item['value'] }}
                                            @endif
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="wrapper">
                            <p class="text-2">
                                TIPE INDIKATOR
                            </p>
                            <div class="box">
                                @foreach ($list_indicator_options as $item)
                                    <div>
                                        <label>
                                            @foreach ($data->indicatorType as $x)
                                                @if ($x->name === $item['value'])
                                                    <input id="{{ $item['value'] }}" checked type="checkbox"/> {{ $item['value'] }}
                                                @else
                                                    <input id="{{ $item['value'] }}" type="checkbox"/> {{ $item['value'] }}
                                                @endif
                                            @endforeach
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="wrapper">
                            <p class="text-2">
                                PERIODE ANALISIS
                            </p>
                            <div class="box">
                                @foreach ($list_reports as $item)
                                    <label>
                                            @if ($data->analystPeriod->where('name', $item['value'])->first())
                                                <input id="{{ $item['value'] }}" checked type="checkbox"/> {{ $item['value'] }}
                                            @else
                                                <input id="{{ $item['value'] }}" type="checkbox"/> {{ $item['value'] }}
                                            @endif
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="wrapper">
                            <p class="text-2">
                                PENANGGUNG JAWAB INDIKATOR
                            </p>
                            <div class="box">
                                {{ $data->created_by }}
                            </div>
                        </div>
                    </td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <td>
                       <p class="p-generate">Digenerate Pada: {{date('d F Y H:m:s')}}</p>
                    </td>
                </tr>
            </table>
            <table width='100%'>
                @if ($data && count($data->signature) > 0)
                <tr>
                    @foreach ($data->signature as $item)
                        <td width="50%" style="text-align: center">
                            {{-- <p class="text-2">Penanggung Jawab</p> --}}
                            <p class="text-2">
                                {{$item->level === 1 ? 'Pembuat Dokumen' : ($item->level === 2 ? 'Penanggung Jawab 1' : 'Penanggung Jawab 2')}}
                            </p>
                            <p class="text-2">
                                {{$item->user && $item->user->position && $item->user->position->name ? $item->user->position->name : '-'}}
                            </p>
                            @if ($item->signed === 1)
                                @if ($item->user->signature)
                                    <img style="width: 100px; height: 100px;" src="{{ public_path('storage/'.$item->user->signature->file_path) }}" />
                                @else
                                    <img style="width: 100px; height: 100px;" src="{{ public_path('images/square_ruang_mutu.png') }}" />
                                    {{-- <p class="sign-text">Signed</p> --}}
                                @endif
                            @else
                                <p class="sign-text">Not Signed</p>
                            @endif

                            <p class="text-2">
                                {{ $item->user->name}}
                            </p>
                        </td>
                    @endforeach
                </tr>
                @endif
            </table>
        </div>
    </main>
</body>
</html>

        {{-- <tr>
            <td width="50%" style="text-align: center">
                <p class="text-2">
                    Ketua Puskesmas Gambir
                </p>
                <img style="width: 200px;" src="{{ public_path('images/sample-ttd-1.png') }}" />
                <p class="text-2">
                    {{ 'dr. Ratna Sari, MKM' }}
                </p>
            </td>
            <td width="50%" style="text-align: center">
                <p class="text-2">
                    Penanggung Jawab, Humas
                </p>
                <img style="width: 200px;" src="{{ public_path('images/sample-ttd-1.png') }}" />
                <p class="text-2">
                    {{ 'Visi Gita Nurlaini, Psi' }}
                </p>
            </td>
        </tr> --}}