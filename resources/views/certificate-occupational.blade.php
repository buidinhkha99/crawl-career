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
            width: 210px;
            height: 297px;
            display: flex;
            flex-wrap: wrap;
            padding: 5px;
            box-sizing: border-box;
            page-break-after: always;
        }
        .card {
            width: 170px; /* Kích thước border ngoài */
            height: 256px; /* Kích thước border ngoài */
            border: 1px solid #000;
            margin: 5px;
            padding: 4px;
            box-sizing: border-box;
        }
        .card-inner {
            width: 162px; /* Kích thước border trong */
            height: 248px; /* Kích thước border trong */
            border: 1px solid #0a47a3;
            padding: 10px;
            box-sizing: border-box;
            text-align: center;
        }
        .title {
            font-weight: bold;
            color: red;
            margin-top: 10px;
        }
        .image {
            width: 85px; /* Kích thước khung ảnh */
            height: 113px; /* Kích thước khung ảnh */
            border: 1px dashed #000;
            margin: 20px auto;
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
    </style>
</head>
<body>
{{-- Trang 1: Mặt trước --}}
<div class="page">
    @for ($i = 0; $i < 9; $i++)
        <div class="card">
            <div class="card-inner">
                <div class="header">
                    <div>CHI NHÁNH LUYỆN ĐỒNG</div>
                    <div>LAO CAI - VIMICO</div>
                </div>
                <div class="title">THẺ AN TOÀN LAO ĐỘNG</div>
                <div class="image">
                    Ảnh 3x4 <br> (đóng dấu giáp lai)
                </div>
                <div class="footer">Số: 23/2024/TATLD</div>
            </div>
        </div>
    @endfor
</div>

{{-- Trang 2: Mặt sau --}}
<div class="page">
    @for ($i = 0; $i < 9; $i++)
        <div class="card">
            <div class="card-inner">
                <div class="header">
                    <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
                    <div>Độc lập - Tự do - Hạnh phúc</div>
                </div>
                <div class="title">THẺ AN TOÀN LAO ĐỘNG</div>
                <div class="info">
                    Họ và tên: Hoàng Văn Hải <br>
                    Sinh ngày: 12/4/1990 <br>
                    Chức vụ: Chuyên viên tổ vận động, PX Luyện Acid 2 <br>
                    Lao động tại chi nhánh Luyện Đồng, sử dụng thẻ cho đến khi không còn làm việc. <br>
                    Ngày cấp: 23/4/2023 <br>
                    Giám đốc: Hoàng Ngọc Minh
                </div>
                <div class="footer">
                    Thẻ có giá trị đến: 25/12/2025
                </div>
            </div>
        </div>
    @endfor
</div>
</body>
</html>
