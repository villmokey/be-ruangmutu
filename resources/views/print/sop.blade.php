<!DOCTYPE html>
<html lang="en">

<head>
    <title>Standar Operasional</title>
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

        .w-100 {
            width: 100%;
        }

        .w-20 {
            width: 20%;
        }

        .w-80 {
            width: 80%;
        }


        .box {
            background: #FFFFFF;
            border: 2px solid #6A9695;
            padding: 5px;
            font-size: 12px;
        }

        .title {
            font-weight: bold;
            font-size: 14p;
            color: #6A9695;
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

        #id table thead td {
            background: #FFFFFF;
            border: 2px solid #6A9695;
            padding: 5px;
            font-size: 12px;
        }

        #pageCounter {
            counter-reset: pageTotal;
        }

        #pageCounter footer {
            counter-increment: pageTotal; 
        }

        #pageNumbers {
            counter-reset: currentPage;
        }
        
        #pageNumbers div:before { 
            counter-increment: currentPage; 
            content: "Page " counter(currentPage) " of "; 
        }

        #pageNumbers div:after { 
        content: counter(pageTotal); 
        }

    </style>
</head>

<body id="pageCounter">
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
                <td id="pageNumber">
                    <div>
                        <script type="text/php">
                            if (isset($pdf)) {
                                $pdf->page_text(290, 807, "Halaman {PAGE_NUM} Dari {PAGE_COUNT}", null, 7, array(0, 0, 0));
                            }
                        </script>
                    </div>
                    <div class="text-bg " style="text-align: center;">
                        PUSKESMAS KECAMATAN GAMBIR
                    </div>
                </td>
            </tr>
        </table>
    </footer>
    <main>
        <div class="container">
            <table width="100%" id="head">
                <thead>
                    <tr>
                        <td class="box" rowspan="5" style="text-align: center; align-items: center; width: 25%;">
                            <div>
                                <img src="{{ public_path('images/logo-jakarta.png') }}" />
                            </div>
                        </td>
                        <td class="box" colspan="5" style="text-align: center; align-items: center; width: 50%;">
                            <div class="title">{{$data['name']}}</div>
                        </td>
                        <td class="box" rowspan="5" style="text-align: center; align-items: center; width: 25%;">
                            <div>
                                <img src="{{ public_path('images/logo-puskesmas.png') }}" />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="box" rowspan="4" style="writing-mode: tb; text-orientation: upright; text-align: center; align-items: center; width: 20px;">
                            <div class="title">
                                <p>S</p>
                                <p>O</p>
                                <p>P</p>
                            </div>
                        </td>
                        <td class="box">
                            <div>No Dokumen</div>
                        </td>
                        <td class="box" colspan="3">
                            <div>{{$data['document_number']}}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="box">
                            <div>
                                No Revisi
                            </div>
                        </td>
                        <td class="box" colspan="3">
                            <div>{{$data['revision_number']}}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="box">
                            <div>Tanggal Terbit</div>
                        </td>
                        <td class="box" colspan="3">
                            <div>{{$data['released_date']}}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="box">
                            <div>Halaman</div>
                        </td>
                        <td class="box" style="text-align: center; align-items: center;">
                            <div>
                                
                            </div>
                        </td>
                        <td style="text-align: center; align-items: center; font-size: 12px;">
                            <div>
                                Dari
                            </div>    
                        </td>
                        <td class="box" style="text-align: center; align-items: center;">
                            <div>
                                <script type="text/php">
                                    if (isset($pdf)) {
                                        $font = $fontMetrics->getFont("Arial", "bold");
                                        $pdf->page_text(300, 224, "{PAGE_NUM}", $font, 10, array(0, 0, 0));
                                    }
                                    if (isset($pdf)) {
                                        $font = $fontMetrics->getFont("Arial", "bold");
                                        $pdf->page_text(400, 224, "{PAGE_COUNT}", $font, 10, array(0, 0, 0));
                                    }
                                </script>
                            </div>    
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                    </tr>
                    <tr>
                        <td class="box" style="text-align: center;">
                            <div>
                                PUSKESMAS KECAMATAN GAMBIR
                            </div>
                        </td>
                        <td class="box" colspan="5" style="text-align: center;">
                            <div>
                                <img style="width: 120px;" src="{{ public_path('images/sample-ttd-1.png') }}" />
                            </div>
                        </td>
                        <td class="box" style="text-align: center;">
                            <div>DR. RATNA, MKM</div>
                            <div>NIP. 102020202020202</div>
                        </td>
                    </tr>
                </thead>
            </table>
            <table width='100%'>
                <tr>
                    <td class="box w-20">
                        <div class="title">
                            1. Pengertian
                        </div>
                    </td>
                    <td class="box w-80">
                        <div >
                            {!! $data['meaning'] !!}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="box w-20">
                        <div class="title">
                            2. Tujuan
                        </div>
                    </td>
                    <td class="box w-80">
                        <div>
                            {!! $data['goal'] !!}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="box w-20">
                        <div class="title">
                            3. Kebijakan
                        </div>
                    </td>
                    <td class="box w-80">
                        <div >
                            {!! $data['policy'] !!}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="box w-20">
                        <div class="title">
                            4. Referensi
                        </div>
                    </td>
                    <td class="box w-80">
                        <div>
                            {!! $data['reference'] !!}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="box w-20">
                        <div class="title">
                            5. Alat & Bahan
                        </div>
                    </td>
                    <td class="box w-80">
                        <div>
                            {!! $data['tools'] !!}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="box w-20">
                        <div class="title">
                            6. Prosedur/Langkah
                        </div>
                    </td>
                    <td class="box w-80">
                        <div>
                            {!! $data['procedures'] !!}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="box w-20">
                        <div class="title">
                            7. Diagram Alir
                        </div>
                    </td>
                    <td class="box w-80">
                        <div>
                            @if ($data['flow_image'] !== '')
                                <img style="width: 100%" src="data:image/png;base64, {!! $data['flow_image'] !!}"/>
                            @else
                                <p>Tidak ada diagram alir diunggah</p>
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="box w-20">
                        <div class="title">
                            8. Unit Terkait
                        </div>
                    </td>
                    <td class="box w-80">
                        <div class="d-flex" style="margin: 5px">
                            @forelse ($data['programs'] as $item)
                                <span class="text-bg">
                                    {{$item->name}}
                                </span>
                            @empty
                                -
                            @endforelse
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class=" w-20">
                    </td>
                    <td class="box w-80">
                        <div class="title">Catatan Mutu</div>
                        <div >
                            {!! $data['notes'] !!}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="box w-20 title">
                        <div>9. History Perubahan</div>
                    </td>
                    <td class="box w-80">
                        <table class="w-100">
                            <tr class="text-black">
                                <th style="width: 25px;">No.</th>
                                <th style="width: 20%;">YANG DIRUBAH</th>
                                <th>ISI PERUBAHAN</th>
                                <th class="w-20">TANGGAL MULAI DITERAPKAN</th>
                            </tr>
                            @forelse ($data['histories'] as $value)
                                <tr>
                                    <td class="box">
                                        {{$loop->iteration}}
                                    </td>
                                    <td class="box">
                                        {{$value['name']}}
                                    </td>
                                    <td class="box">
                                        {{$value['value']}}
                                    </td>
                                    <td class="box w-20">
                                        {{$value['publish']}}
                                    </td>
                                </tr>
                            @empty
                                
                            @endforelse
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </main>
</body>

</html>