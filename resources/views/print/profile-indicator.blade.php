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
                <p class="text-1">PROFIL INDIKATOR MUTU</p>
                <p class="text-1">PUSKESMAS KECAMATAN GAMBIR</p>
            </div>
            <table width='100%'>
                <tr>
                    <td width='50%'>
                        <div class="column-left">
                            <div class="wrapper">
                                <p class="text-2">
                                    PROGRAM MUTU
                                </p>
                                <div class="box">
                                    {{ $data->program->name }}
                                </div>
                            </div>
                            <div class="wrapper">
                                <p class="text-2">
                                    SUB PROGRAM
                                </p>
                                <div class="box">
                                    {{ $data->sub_program->id ?? '-' }}
                                </div>
                            </div>
                            <div class="wrapper">
                                <p class="text-2">
                                    JUDUL INDIKATOR
                                </p>
                                <div class="box">
                                    {{ $data->title }}
                                </div>
                            </div>
                            <div class="wrapper">
                                <p class="text-2">
                                    DASAR PEMILIHAN INDIKATOR
                                </p>
                                <div class="box">
                                    {{ $data->indicator_selection_based }}
                                </div>
                            </div>
                            <div class="wrapper">
                                <p class="text-2">
                                    DIMENSI MUTU
                                </p>
                                <div class="box">
                                    @foreach ($list_dimension as $item)
                                        <label>
                                            <input id="{{ $item['value'] }}" type="checkbox"
                                                {{ $item['value'] === 'Manfaat' ? 'checked' : '' }} />
                                            {{ $item['value'] }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="wrapper">
                                <p class="text-2">
                                    TUJUAN
                                </p>
                                <div class="box">
                                    {{ $data->objective }}
                                </div>
                            </div>
                            <div class="wrapper">
                                <p class="text-2">
                                    DEFINISI OPERASIONAL
                                </p>
                                <div class="box">
                                    {{ $data->operational_definition }}
                                </div>
                            </div>
                            <div class="wrapper">
                                <p class="text-2">
                                    TIPE INDIKATOR
                                </p>
                                <div class="box">
                                    @foreach ($list_indicator_options as $item)
                                        <div>
                                            <label>
                                                <input type="checkbox"
                                                    {{ $item['value'] === 'Output' ? 'checked' : '' }} />
                                                {{ $item['value'] }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="wrapper">
                                <p class="text-2">
                                    STATUS PENGUKURAN
                                </p>
                                <div class="box">
                                    {{ $data->measurement_status }}
                                </div>
                            </div>
                            <div class="wrapper">
                                <p class="text-2">
                                    NUMERATOR
                                </p>
                                <div class="box">
                                    {{ $data->numerator }}
                                </div>
                            </div>
                            <div class="wrapper">
                                <p class="text-2">
                                    DENOMINATOR
                                </p>
                                <div class="box">
                                    {{ $data->denominator }}
                                </div>
                            </div>
                            <div class="wrapper">
                                <p class="text-2">
                                    TARGET CAPAIAN
                                </p>
                                <div class="box">
                                    {{ $data->achievement_target }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td width='50%' style="vertical-align: start;">
                        <div class="column-right">
                            <div class="wrapper">
                                <p class="text-2">
                                    KRITERIA INKLUSI & EKSKLUSI
                                </p>
                                <div class="box">
                                    {{ $data->criteria }}
                                </div>
                            </div>
                            <div class="wrapper">
                                <p class="text-2">
                                    FORMULA PENGUKURAN
                                </p>
                                <div class="box">
                                    {{ $data->measurement_formula }}
                                </div>
                            </div>
                            <div class="wrapper">
                                <p class="text-2">
                                    PENGUMPULAN DATA
                                </p>
                                <div class="box">
                                    {{ $data->data_collection_design }}
                                </div>
                            </div>
                            <div class="wrapper">
                                <p class="text-2">
                                    SUMBER DATA
                                </p>
                                <div class="box">
                                    {{ $data->data_source }}
                                </div>
                            </div>
                            <div class="wrapper">
                                <p class="text-2">
                                    POPULASI ATAU SAMPEL
                                </p>
                                <div class="box">
                                    {{ $data->population }}
                                </div>
                            </div>
                            <div class="wrapper">
                                <p class="text-2">
                                    FREKUENSI PENGUMPULAN DATA
                                </p>
                                <div class="box">
                                    @foreach ($list_frequently as $item)
                                        <label>
                                            <input type="checkbox"
                                                {{ $item['value'] === 'Mingguan' ? 'checked' : '' }} />
                                            {{ $item['value'] }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="wrapper">
                                <p class="text-2">
                                    PERIODE WAKTU PELAPORAN
                                </p>
                                <div class="box">
                                    @foreach ($list_reports as $item)
                                        <label>
                                            <input type="checkbox"
                                                {{ $item['value'] === 'Tahunan' ? 'checked' : '' }} />
                                            {{ $item['value'] }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="wrapper">
                                <p class="text-2">
                                    PERIODE ANALISIS
                                </p>
                                <div class="box">
                                    @foreach ($list_reports as $item)
                                        <label>
                                            <input type="checkbox"
                                                {{ $item['value'] === 'Triwulan' ? 'checked' : '' }} />
                                            {{ $item['value'] }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="wrapper">
                                <p class="text-2">
                                    PENYAJIAN DATA
                                </p>
                                <div class="box">
                                    {{ $data->data_presentation }}
                                </div>
                            </div>
                            <div class="wrapper">
                                <p class="text-2">
                                    PENANGGUNG JAWAB INDIKATOR
                                </p>
                                <div class="box">
                                    {{ $data->created_by }}
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
            <table width='100%'>
                <tr>
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
                </tr>
            </table>
        </div>
    </main>
</body>

</html>

{{-- <div class="d-flex" style="justify-content: space-between; align-items: center;">
    <div class="d-flex italic" style="flex-direction: column;">
        <p class="p-none m-none f-12">Dilarang menduplikat dokumen tanpa izin Manajemen Mutu</p>
        <p class="p-none m-none f-12">Puskesmas Kecamatan Gambir</p>
    </div>
    <div class="d-flex" style="align-items: center">
        <p class="p-none m-none f-12" style="margin-right: 10px;">Halaman 1 dari 5</p>
        <div class="text-bg">
            PUSKESMAS KECAMATAN GAMBIR
        </div>

    </div>
</div> --}}
