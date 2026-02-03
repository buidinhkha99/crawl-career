@php
    $fonts = [
            ['name' => 'Font Times New Roman', 'path' => 'times.ttf'],
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
            src: url("/storage/{{$font['path']}}") format('truetype');
        }

        @endforeach
        * {
            margin-left: 0;
            margin-right: 0;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .bold-text {
            font-family: 'Font Times New Roman', serif; /* Sử dụng phông chữ Times New Roman Bold */
        }

        .normal-text {
            font-family: 'Font Times New Roman', serif; /* Sử dụng phông chữ Times New Roman Bold */
        }

        .page-break {
            page-break-after: always;
            page-break-inside: avoid;
            /*margin: 0;*/
        }

        .page-after {
            margin-top: 30px;
            margin-left: 20px;
        }

        .card {
            /*display: inline-block;*/
            width: 170px;
            height: 256px;
            border: 3px solid #324376;
            padding: 1px;
            margin-bottom: 10px;
        }

        .card-inner {
            width: 168px;
            height: 254px;
            border: 1px solid #324376;
            text-align: center;
        }

        .title {
            font-size: 10px;
            font-weight: bold;
            color: #E40613;
            margin-top: 19px;
        }

        .image-cover {
            margin: 19px 38px 19px 38px;
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
            object-fit: cover; /* Cắt ảnh sao cho luôn lấp đầy container */
            object-position: center; /* Đảm bảo cắt ở giữa ảnh */
        }

        .header {
            margin-top: 7px;
            font-size: 10px;
            color: #000000;
            font-weight: 700;
        }

        .header-back {
            margin-top: 2px;
            font-size: 7px;
            color: #000000;
            font-weight: bold;
            height: 18px;
        }

        .title-back {
            font-size: 11px;
            font-weight: bold;
            color: #E40613;
            margin-top: 5px;
        }

        .footer {
            margin-top: 19px;
            font-size: 10px;
            text-align: center;
        }

        .footer-back {
            margin-top: 3px;
            font-size: 10px;
            text-align: center;
        }

        .info {
            font-size: 10px;
            margin-top: 3px;
            margin-left: 6px;
            line-height: 11px;
            text-align: left;
            height: 117px;
        }

        .normal-text {
        }

        .font-dejavu {
            font-size: 10px;
        }

        .card-between {
            margin-right: 6px;
        }

        .column-gap {
            margin-bottom: 3px;
        }

        .location {
            margin-top: 5px;
            margin-left: 36px;
            text-align: center;
            font-size: 10px;
            line-height: 11px;
            height: 67px;
        }

        .signature {
            margin: auto;
            width: 50px;
            height: 29px;
            object-fit: contain;
        }

        .underline {
            width: 60px;
            margin: 2px auto;
            height: 1px;
            background-color: black;
        }

        .border-none {
            border: none;
        }

        .small-font {
            font-size: 8px; /* Decrease font size by 1 */
        }
    </style>
</head>
<body>
@for ($iGroup = 0; $iGroup < $total_group; $iGroup++)
    <div class="page-break page-after">
            <?php
            $data_font_size_cards = $group_font_size_cards[$iGroup][0];
            ?>
        <div class="card card-between">
            <div class="card-inner">
                <div class="header">
                    <div class="bold-text">CHI NHÁNH LUYỆN ĐỒNG</div>
                    <div class="bold-text">LÀO CAI - VIMICO</div>
                </div>
                <div class="title bold-text" style="margin-top: 19px;">THẺ AN TOÀN LAO ĐỘNG</div>
                    <?php $image = $data_font_size_cards['image'] ?? null ?>
                <div class="image-cover {{!$image ? 'border-image' : null}}">
                    @if($image)
                        <img class="image" src="data:image/png;base64,{{$image}}" alt="image">
                    @else
                        <div class="image normal-text" style="margin-top: 35px">Ảnh 3x4 <br> (đóng dấu giáp lai)</div>
                    @endif
                </div>
                <div class="footer normal-text">
                    Số: {{$data_font_size_cards['certificate_id'] ?? null}} </div>
            </div>
        </div>

            <?php
            $data_back_size_cards = $group_back_size_cards[$iGroup][2];
            ?>
        <div class="card">
            <div class="card-inner">
                <div class="header-back">
                    <div class=" bold-text">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
                    <div class=" bold-text">Độc lập - Tự do - Hạnh phúc</div>
                    <div class="underline"></div>
                </div>
                <div class="title-back bold-text">THẺ AN TOÀN LAO ĐỘNG</div>
                <div class="info normal-text">
                    <div class="normal-text">Họ và tên: <strong
                            class="bold-text">{{$data_back_size_cards['name'] ?? null}}</strong>
                    </div>
                    <div class="normal-text">Sinh
                        ngày: {{$data_back_size_cards['dob'] ?? null}}</div>
                    <div class="normal-text job"><span style="font-size: 10px">Công việc: </span>
                        {{$data_back_size_cards['job'] ?? null}}</div>
                    <div class="normal-text">
                        Đã hoàn thành khóa huấn
                        luyện: {{$data_back_size_cards['description'] ?? null}}
                    </div>
                    <div class="normal-text">Từ
                        ngày {{$data_back_size_cards['complete_from'] ?? null}} đến
                        ngày {{$data_back_size_cards['complete_to'] ?? null}}</div>
                </div>
                <div class="location">
                    <div class="normal-text">{{$data_back_size_cards['place'] ?? null}},
                        ngày {{$data_back_size_cards['created_at'] ?? null}}</div>
                    <div style="margin-top: 3px"><strong class="bold-text">GIÁM ĐỐC</strong></div>
                    <div>
                        <img class="signature"
                             src="data:image/png;base64,{{$data_back_size_cards['signature_photo'] ?? null}}">
                    </div>
                    <div><strong
                            class="bold-text">{{$data_back_size_cards['director_name'] ?? null}}</strong>
                    </div>
                </div>
                <div class="footer-back normal-text">
                    Thẻ có giá trị đến ngày {{$data_back_size_cards['effective_to'] ?? null}}
                </div>
            </div>
        </div>
    </div>
@endfor

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var textContent = document.querySelector('.job');
        var lineHeight = parseInt(window.getComputedStyle(textContent).lineHeight);
        var maxHeight = lineHeight * 3; // 3 lines limit

        if (textContent.scrollHeight > maxHeight) {
            textContent.classList.add('small-font');
        }
    });
</script>
</body>
</html>
