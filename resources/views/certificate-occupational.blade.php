<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thẻ An Toàn Lao Động</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .page {
            padding: 30px;
            width: 595px;
            height: 842px;
            display: flex;
            flex-wrap: wrap;
        }

        .card {
            width: 170px;
            height: 256px;
            border: 3px solid #324376;
            padding: 1px;
        }

        .card-inner {
            width: 168px;
            height: 254px;
            border: 1px solid #324376;
            text-align: center;
        }

        .title {
            font-size: 11px;
            font-weight: bold;
            color: #E40613;
            margin-top: 10px;
        }
        .header {
            font-weight: bold;
            font-size: 10px;
            margin-top: 12px;
            color: #000000;
        }

        .image {
            width: 85px; /* Kích thước khung ảnh */
            height: 113px; /* Kích thước khung ảnh */
            border: 1px dashed #000;
            margin: 19px 38px 19px 38px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
        }

        .info {
            font-size: 12px;
            margin-top: 10px;
            line-height: 1.4;
        }

        .card-between {
            margin-right: 12.5px;
        }

        .column-gap {
            margin-bottom: 7px;
        }
    </style>
</head>
<body>
{{-- Trang 1: Mặt trước --}}
<div class="page">
    @for ($i = 0; $i < 9; $i++)
        <div class="card card-between column-gap">
            <div class="card-inner">
                <div class="header">
                    <div style="font-size: 10px;">CHI NHÁNH LUYỆN ĐỒNG</div>
                    <div style="font-size: 10px;">LAO CAI - VIMICO</div>
                </div>
                <div class="title" style="margin-top: 19px;">THẺ AN TOÀN LAO ĐỘNG</div>
                <div class="image">
                    Ảnh 3x4 <br> (đóng dấu giáp lai)
                </div>
                <div class="footer">Số: 23/2024/TATLD</div>
            </div>
        </div>
    @endfor
</div>

Trang 2: Mặt sau
<div class="page">
    @for ($i = 0; $i < 9; $i++)
            <div class="card card-between column-gap">
                <div class="card-inner">
                    <div class="header">
                        <div style="font-size: 10px;">CHI NHÁNH LUYỆN ĐỒNG</div>
                        <div style="font-size: 10px;">LAO CAI - VIMICO</div>
                    </div>
                    <div class="title" style="margin-top: 19px;">THẺ AN TOÀN LAO ĐỘNG</div>
                    <div class="image">
                        Ảnh 3x4 <br> (đóng dấu giáp lai)
                    </div>
                    <div class="footer">Số: 23/2024/TATLD</div>
                </div>
            </div>

{{--        @if($i/3 == 0 && $i != 0)--}}
{{--            <div class="card card-between column-gap">--}}
{{--                <div class="card-inner">--}}
{{--                    <div class="header">--}}
{{--                        <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>--}}
{{--                        <div>Độc lập - Tự do - Hạnh phúc</div>--}}
{{--                    </div>--}}
{{--                    <div class="title">THẺ AN TOÀN LAO ĐỘNG</div>--}}
{{--                    <div class="info">--}}
{{--                        Họ và tên: Hoàng Văn Hải <br>--}}
{{--                        Sinh ngày: 12/4/1990 <br>--}}
{{--                        Chức vụ: Chuyên viên tổ vận động, PX Luyện Acid 2 <br>--}}
{{--                        Lao động tại chi nhánh Luyện Đồng, sử dụng thẻ cho đến khi không còn làm việc. <br>--}}
{{--                        Ngày cấp: 23/4/2023 <br>--}}
{{--                        Giám đốc: Hoàng Ngọc Minh--}}
{{--                    </div>--}}
{{--                    <div class="footer">--}}
{{--                        Thẻ có giá trị đến: 25/12/2025--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @else--}}
{{--            <div class="card column-gap">--}}
{{--                <div class="card-inner">--}}
{{--                    <div class="header">--}}
{{--                        <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>--}}
{{--                        <div>Độc lập - Tự do - Hạnh phúc</div>--}}
{{--                    </div>--}}
{{--                    <div class="title">THẺ AN TOÀN LAO ĐỘNG</div>--}}
{{--                    <div class="info">--}}
{{--                        Họ và tên: Hoàng Văn Hải <br>--}}
{{--                        Sinh ngày: 12/4/1990 <br>--}}
{{--                        Chức vụ: Chuyên viên tổ vận động, PX Luyện Acid 2 <br>--}}
{{--                        Lao động tại chi nhánh Luyện Đồng, sử dụng thẻ cho đến khi không còn làm việc. <br>--}}
{{--                        Ngày cấp: 23/4/2023 <br>--}}
{{--                        Giám đốc: Hoàng Ngọc Minh--}}
{{--                    </div>--}}
{{--                    <div class="footer">--}}
{{--                        Thẻ có giá trị đến: 25/12/2025--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endif--}}
    @endfor
</div>
</body>
</html>
