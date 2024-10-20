@php
    $fonts = [
            ['name' => 'Arial', 'path' => 'ARIAL.TTF'],
    ];
@endphp
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Thẻ An Toàn Điện</title>
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
            font-family: 'Arial', serif; /* Sử dụng phông chữ Times New Roman Bold */
        }

        .normal-text {
            font-family: 'Arial', serif; /* Sử dụng phông chữ Times New Roman Bold */
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
            width: 241px;
            height: 150px;
            background-color: #ffff99;
            position: relative;
            padding: 6px 2px 6px 2px;
            margin-bottom: 10px;
        }

        .card-bottom {
            margin-bottom: 49px;
        }

        .company {
            /*margin-top: 6px;*/
            font-size: 11px;
            font-weight: 400;
            text-align: center;
        }

        .branch {
            font-size: 11px;
            font-weight: 700;
            text-align: center;
            /*letter-spacing: -0.1em;*/
        }

        .safety-card {
            margin-left: 70px;
            width: 167px;
            height: 85px;
            font-size: 22px;
            color: #E40613;;
            font-weight: 600;
            text-align: center;
            word-spacing: -0.1em;
        }

        .card-number {
            font-size: 12px;
            text-align: center;
            font-weight: 400;
            margin-top: 26px;
            color: #000000;
            letter-spacing: 0em;
        }

        .photo-box {
            position: absolute;
            width: 55px;
            height: 83px;
            margin-right: 13px;
            font-size: 12px;
            letter-spacing: -0.1em;
        }

        .image {
            width: 55px;
            height: 83px;
            object-fit: cover; /* Cắt ảnh sao cho luôn lấp đầy container */
            object-position: center; /* Đảm bảo cắt ở giữa ảnh */
        }

        .border-image {
            border: 1px solid #E5E5E5;
        }

        .photo-box span {
            font-size: 12px;
            text-align: center;
        }

        .content {
            width: 235px;
            height: 85px;
            margin-top: 22px;
        }

        .img-noty {
            text-align: center;
            padding: 21px 0 21px 0;
        }

        .backgroup-none {
            background: none;
        }

        .font-dejavu {
            font-size: 12px;
        }

        .signature {
            margin: auto;
            width: 50px;
            height: 29px;
            object-fit: contain;
        }

        .location {
            margin-top: 15px;
            margin-left: 110px;
            text-align: center;
            font-size: 10px;
            line-height: 11px;
        }

        .info {
            font-size: 12px;
            margin-top: 0px;
            margin-left: 6px;
            line-height: 12px;
            text-align: left;
            height: 84px;
        }
        .level-save {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
            -webkit-line-clamp: 1; /* Number of lines to display */
            max-height: 14px; /* Adjust based on the number of lines and line height */
        }
    </style>
</head>
<body>
@for ($iGroup = 0; $iGroup < $total_group; $iGroup++)
    <div class="page-break page-after">
            <?php
            $data_font_size_cards = $group_font_size_cards[$iGroup][0];
            ?>
        <div class="card">
            <div class="company normal-text">TỔNG CÔNG TY KHOÁNG SẢN-TKV</div>
            <div class="branch bold-text">CHI NHÁNH LUYỆN ĐỒNG LÀO CAI-VIMICO</div>
            <div class="content">
                    <?php $image = $data_font_size_cards['image'] ?? null ?>
                <div class="photo-box {{!$image ? 'border-image' : null}}">
                    @if($image)
                        <img class="image" src="data:image/png;base64,{{$image}}" alt="image">
                    @else
                        <div class="img-noty normal-text">Ảnh 2x3<br>(đóng dấu <br> giáp lai)</div>
                    @endif
                </div>
                <div class="safety-card bold-text">
                    <div class="safety-card-header bold-text">THẺ</div>
                    <div>AN TOÀN ĐIỆN</div>
                    <div class="card-number normal-text">
                        Số: {{$data_font_size_cards['certificate_id'] ?? null}}
                    </div>
                </div>
            </div>
        </div>

            <?php
            $data_back_size_cards = $group_back_size_cards[$iGroup][1];
            ?>
        <div class="card card-bottom">
            <div class="info normal-text">
                <div>Họ tên: <strong class="bold-text">{{$data_back_size_cards['name'] ?? null}}</strong></div>
                <div>Công việc, đơn vị công tác: {{$data_back_size_cards['description'] ?? null}}</div>
                <div class="level-save">Bậc an toàn: {{$data_back_size_cards['level'] ?? null}}</div>
                <div>Cấp ngày {{$data_back_size_cards['day_created'] ?? null}}
                    tháng {{$data_back_size_cards['month_created'] ?? null}}
                    năm {{$data_back_size_cards['year_created'] ?? null}}</div>
            </div>
            <div class="location">
                <div><strong class="bold-text">PHÓ GIÁM ĐỐC</strong></div>
                <div>
                    <img class="signature"
                         src="data:image/png;base64,{{$data_back_size_cards['signature_photo'] ?? null}}">
                </div>
                <div><strong class="bold-text">{{$data_back_size_cards['director_name'] ?? null}}</strong></div>
            </div>
        </div>
    </div>
@endfor
</body>
</html>
