@php
    $fonts = [
            ['name' => 'Arial', 'path' => 'ARIAL.TTF'],
    ];
@endphp
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>

    <style type="text/css">
        body {
            margin: 0;
        }

        .page {
            padding-top: 2px;
            margin-left: 1cm;
            margin-right: 1cm;
            margin-bottom: 0;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            height: 29.660cm;
        }

        .card {
            background-color: white;
            border: 1px solid #000000;
            width: 95mm;
            height: 63mm
        }

        .card-inner {
            width: 85mm;
            height: 53mm;
            margin: 0.5cm;
            border: 1px solid #000000;
            text-align: center;
            font-size: 16px;
            font-weight: 400;
            color: #000000;
            background-color: #ffff99;
        }

        .bold-text {
            font-family: 'Arial', serif; /* Sử dụng phông chữ Times New Roman Bold */
        }

        .normal-text {
            font-family: 'Arial', serif; /* Sử dụng phông chữ Times New Roman Bold */
        }

        .company {
            margin-top: 6px;
            font-size: 16px;
            font-weight: 400;
            text-align: center;
        }

        .branch {
            margin-top: 3px;
            font-size: 16px;
            font-weight: 700;
            text-align: center;
            letter-spacing: -0.03em;
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

        .safety-card {
            margin-left: 109px;
            width: 205px;
            height: 85px;
            font-size: 29px;
            color: #E40613;
            font-weight: 700;
            text-align: center;
        }

        .card-number {
            font-size: 16px;
            text-align: center;
            font-weight: 400;
            margin-top: 26px;
            color: #000000;
            letter-spacing: 0em;
        }

        .photo-box {
            position: absolute;
            width: 2cm;
            height: 3cm;
            margin-right: 13px;
            font-size: 16px;
            letter-spacing: -0.1em;
            margin-left: 1px;
        }

        .image {
            width: 2cm;
            height: 3cm;
            object-fit: cover; /* Cắt ảnh sao cho luôn lấp đầy container */
            object-position: center; /* Đảm bảo cắt ở giữa ảnh */
        }

        .border-image {
            border: 1px solid #E5E5E5;
        }

        .photo-box span {
            font-size: 16px;
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
            font-size: 16px;
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
            font-size: 16px;
            /*line-height: 11px;*/
        }

        .info {
            font-size: 16px;
            margin-top: 8px;
            margin-left: 15px;
            line-height: 17px;
            text-align: left;
            height: 111px;
        }

        .level-save {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
            -webkit-line-clamp: 1;
            max-height: 20px;
        }

        .work-description {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
            -webkit-line-clamp: 4;
        }

    </style>

</head>
<body>
@for ($iGroup = 0; $iGroup < $total_group; $iGroup++)
    <div class="page">
        @for($i= 1; $i <= count($group_font_size_cards[$iGroup]); $i++)
                <?php
                $data_font_size_cards = $group_font_size_cards[$iGroup][$i - 1];
                ?>
            <div class="card">

                @if(!($data_font_size_cards['is_fake'] ?? false))
                    <div class="card-inner">
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
                @endif
            </div>
        @endfor
    </div>

    <div class="page">
        @for($i= 1; $i <= count($group_back_size_cards[$iGroup]); $i++)
                <?php
                $data_back_size_cards = $group_back_size_cards[$iGroup][$i - 1];
                ?>
            <div class="card">
                @if(!($data_back_size_cards['is_fake'] ?? false))
                    <div class="card-inner">
                        <div class="info normal-text">
                            <div>Họ tên: <strong class="bold-text">{{$data_back_size_cards['name'] ?? null}}</strong>
                            </div>
                            <div class="work-description">Công việc, đơn vị công tác: {{$data_back_size_cards['description'] ?? null}}</div>
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
                            <div><strong class="bold-text">{{$data_back_size_cards['director_name'] ?? null}}</strong>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endfor
    </div>
@endfor
</body>
</html>
