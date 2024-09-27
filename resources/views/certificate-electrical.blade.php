@php
    $fonts = [
            ['name' => 'Font1', 'path' => 'fonts/times.ttf'],
            ['name' => 'Font2', 'path' => 'fonts/timne-bold.otf'],
    ];
@endphp
    <!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Thẻ An Toàn Lao Động</title>
    <style>
        @foreach($fonts as $font)
        @font-face {
            font-family: '{{ $font['name'] }}';
            src: url('{{ resource_path($font['path']) }}') format('truetype');
        }

        @endforeach
        * {
            margin-left: 0;
            margin-right: 0;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .page-break {
            page-break-after: always;
            page-break-inside: avoid;
        }

        .page-after {
            margin-top: 30px;
            margin-left: 20px;
        }

        .card {
            display: inline-block;
            width: 241px;
            height: 150px;
            background-color: #ffff99;
            position: relative;
            margin-right: 50px;
            margin-bottom: 61px;
        }

        .card-inner {
            width: 168px;
            height: 254px;
            border: 1px solid #324376;
            text-align: center;
        }

        .title {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            font-weight: bold;
            color: #E40613;
            margin-top: 19px;
        }

        .image-cover {
            margin: 19px 38px 19px 38px;
            font-family: 'Font1', sans-serif;
            width: 85px; /* Kích thước khung ảnh */
            height: 113px; /* Kích thước khung ảnh */
            justify-content: center;
            font-size: 12px;
        }
        .border-image {
            border: 1px dashed #E5E5E5;
        }
        .image {
            width: 85px; /* Kích thước khung ảnh */
            height: 113px; /* Kích thước khung ảnh */
        }

        .header {
            font-family: 'DejaVu Sans', sans-serif;
            margin-top: 7px;
            font-size: 10px;
            color: #000000;
            font-weight: bold;
        }

        .header-back {
            font-family: "DejaVu Sans", sans-serif;
            margin-top: 2px;
            font-size: 7px;
            color: #000000;
            font-weight: bold;
        }

        .title-back {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 10px;
            font-weight: bold;
            color: #E40613;
            margin-top: 3px;
        }

        .footer {
            font-family: 'Font1', sans-serif;
            margin-top: 19px;
            font-size: 10px;
            text-align: center;
        }

        .footer-back {
            font-family: 'Font1', sans-serif;
            margin-top: 2px;
            font-size: 10px;
            text-align: center;
        }

        .info {
            font-size: 10px;
            margin-top: 0px;
            margin-left: 6px;
            line-height: 11px;
            text-align: left;
            height: 117px;
        }

        .font-time {
            font-family: 'Font1', sans-serif;
        }

        .font-dejavu {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
        }

        .card-between {
            margin-right: 6px;
        }

        .column-gap {
            margin-bottom: 3px;
        }

        .location {
            margin-top: 5px;
            font-family: 'Font1', sans-serif;
            margin-left: 36px;
            text-align: center;
            font-size: 10px;
            line-height: 11px;
        }

        .signature {
            margin: auto;
            width: 50px;
            height: auto;
            object-fit: contain;
        }

        .underline {
            width: 60px;
            margin: auto;
            height: 1px;
            background-color: black;
        }

        .card h1, .card h2 {
            text-align: center;
            margin: 0;
        }

        .company {
            font-family: 'Font1', 'DejaVu Sans', sans-serif;
            font-size: 11px;
            font-weight: 400;
            text-align: center;
        }

        .branch {
            font-family: 'Font1', 'DejaVu Sans', sans-serif;
            font-size: 11px;
            font-weight: 700;
            text-align: center;
        }

        .safety-card {
            font-size: 24px;
            color: red;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }

        .card-number {
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
        }

        .photo-box {
            width: 80px;
            height: 100px;
            border: 1px solid #000;
            position: absolute;
            top: 50px;
            left: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .photo-box span {
            font-size: 12px;
            text-align: center;
        }
    </style>
</head>
<body>
@for ($iGroup = 0; $iGroup < $total_group; $iGroup++)
    <div class="page-break page-after">
        <div class="card">
            <div class="company">TỔNG CÔNG TY KHOÁNG SẢN - TKV</div>
            <div class="branch">CHI NHÁNH LUYỆN ĐỒNG LÀO CAI - VIMICO</div>
            <div class="photo-box">
                <span>Ảnh 2x3<br>(đóng dấu giáp lai)</span>
            </div>
            <div class="safety-card">THẺ AN TOÀN ĐIỆN</div>
            <div class="card-number">Số: 23/LĐV/TATĐ</div>
        </div>
        <div class="card" style="margin-right: 0">
            <div class="company">TỔNG CÔNG TY KHOÁNG SẢN - TKV</div>
            <div class="branch">CHI NHÁNH LUYỆN ĐỒNG LÀO CAI - VIMICO</div>
            <div class="photo-box">
                <span>Ảnh 2x3<br>(đóng dấu giáp lai)</span>
            </div>
            <div class="safety-card">THẺ AN TOÀN ĐIỆN</div>
            <div class="card-number">Số: 23/LĐV/TATĐ</div>
        </div>
        <div class="card">
            <div class="company">TỔNG CÔNG TY KHOÁNG SẢN - TKV</div>
            <div class="branch">CHI NHÁNH LUYỆN ĐỒNG LÀO CAI - VIMICO</div>
            <div class="photo-box">
                <span>Ảnh 2x3<br>(đóng dấu giáp lai)</span>
            </div>
            <div class="safety-card">THẺ AN TOÀN ĐIỆN</div>
            <div class="card-number">Số: 23/LĐV/TATĐ</div>
        </div>
        <div class="card" style="margin-right: 0">
            <div class="company">TỔNG CÔNG TY KHOÁNG SẢN - TKV</div>
            <div class="branch">CHI NHÁNH LUYỆN ĐỒNG LÀO CAI - VIMICO</div>
            <div class="photo-box">
                <span>Ảnh 2x3<br>(đóng dấu giáp lai)</span>
            </div>
            <div class="safety-card">THẺ AN TOÀN ĐIỆN</div>
            <div class="card-number">Số: 23/LĐV/TATĐ</div>
        </div>
        <div class="card">
            <div class="company">TỔNG CÔNG TY KHOÁNG SẢN - TKV</div>
            <div class="branch">CHI NHÁNH LUYỆN ĐỒNG LÀO CAI - VIMICO</div>
            <div class="photo-box">
                <span>Ảnh 2x3<br>(đóng dấu giáp lai)</span>
            </div>
            <div class="safety-card">THẺ AN TOÀN ĐIỆN</div>
            <div class="card-number">Số: 23/LĐV/TATĐ</div>
        </div>
        <div class="card" style="margin-right: 0">
            <div class="company">TỔNG CÔNG TY KHOÁNG SẢN - TKV</div>
            <div class="branch">CHI NHÁNH LUYỆN ĐỒNG LÀO CAI - VIMICO</div>
            <div class="photo-box">
                <span>Ảnh 2x3<br>(đóng dấu giáp lai)</span>
            </div>
            <div class="safety-card">THẺ AN TOÀN ĐIỆN</div>
            <div class="card-number">Số: 23/LĐV/TATĐ</div>
        </div>
        <div class="card" style="margin-bottom: 0">
            <div class="company">TỔNG CÔNG TY KHOÁNG SẢN - TKV</div>
            <div class="branch">CHI NHÁNH LUYỆN ĐỒNG LÀO CAI - VIMICO</div>
            <div class="photo-box">
                <span>Ảnh 2x3<br>(đóng dấu giáp lai)</span>
            </div>
            <div class="safety-card">THẺ AN TOÀN ĐIỆN</div>
            <div class="card-number">Số: 23/LĐV/TATĐ</div>
        </div>
        <div class="card" style="margin-bottom: 0; margin-right: 0">
            <div class="company">TỔNG CÔNG TY KHOÁNG SẢN - TKV</div>
            <div class="branch">CHI NHÁNH LUYỆN ĐỒNG LÀO CAI - VIMICO</div>
            <div class="photo-box">
                <span>Ảnh 2x3<br>(đóng dấu giáp lai)</span>
            </div>
            <div class="safety-card">THẺ AN TOÀN ĐIỆN</div>
            <div class="card-number">Số: 23/LĐV/TATĐ</div>
        </div>
    </div>

    <div class="{{$iGroup + 1 < $total_group ? 'page-break' : null}} page-after">
    </div>
@endfor
</body>
</html>
