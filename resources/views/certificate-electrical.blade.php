@php
    $fonts = [
            ['name' => 'Font1', 'path' => 'fonts/times.ttf'],
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
            padding: 6px 2px 6px 2px;
        }

        .card-bottom {
            margin-bottom: 49px;
        }

        .card-next {
            margin-right: 50px;
        }

        .card h1, .card h2 {
            text-align: center;
            margin: 0;
        }

        .company {
            margin-top: 6px;
            font-size: 12px;
            font-weight: 400;
            text-align: center;
            font-family: 'Roboto', 'DejaVu Sans', sans-serif;
        }

        .branch {
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            letter-spacing: -0.1em;
            font-family: 'Roboto', 'DejaVu Sans', sans-serif;
        }

        .safety-card {
            margin-left: 70px;
            width: 167px;
            height: 85px;
            font-size: 20px;
            color: #E40613;;
            font-weight: 600;
            text-align: center;
            word-spacing: -0.1em;
        }

        .card-number {
            font-size: 12px;
            text-align: center;
            font-weight: 400;
            margin-top: 13px;
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

        .safety-card-header {
            margin-bottom: -6px
        }

        .backgroup-none {
            background: none;
        }

        .font-dejavu {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
        }

        .signature {
            margin: auto;
            width: 50px;
            height: auto;
            object-fit: contain;
        }

        .location {
            margin-top: 5px;
            font-family: 'Font1', sans-serif;
            margin-left: 110px;
            text-align: center;
            font-size: 10px;
            line-height: 11px;
        }

        .info {
            font-size: 12px;
            margin-top: 0px;
            margin-left: 6px;
            line-height: 11px;
            text-align: left;
            height: 84px;
        }

        .font-time {
            font-family: 'Font1', 'DejaVu Sans', sans-serif;
        }
    </style>
</head>
<body>
@for ($iGroup = 0; $iGroup < $total_group; $iGroup++)
    <div class="page-break page-after">
        @for($i= 1; $i <= count($group_font_size_cards[$iGroup]); $i++)
                <?php
                $style = "";
                if ($i % 2 == 0 && $i + 2 < 9) {
                    $style = "card card-bottom";
                } elseif ($i + 2 >= 9) {
                    if ($i % 2 == 0) {
                        $style = "card";
                    } else {
                        $style = "card card-next";
                    }
                } else {
                    $style = "card card-next card-bottom";
                }
                ?>

            <div class="{{$style}} {{!($group_font_size_cards[$iGroup][$i - 1]['is_fake'] ?? false) ? null : 'backgroup-none'}}">
                @if(!($group_font_size_cards[$iGroup][$i - 1]['is_fake'] ?? false))
                    <div class="company">TỔNG CÔNG TY KHOÁNG SẢN-TKV</div>
                    <div class="branch">CHI NHÁNH LUYỆN ĐỒNG LÀO CAI-VIMICO</div>
                    <div class="content">
                            <?php $image = $group_font_size_cards[$iGroup][$i - 1]['image'] ?? null ?>
                        <div class="photo-box {{!$image ? 'border-image' : null}}">
                            @if($image)
                                <img class="image" src="data:image/png;base64,{{$image}}" alt="image">
                            @else
                                <div class="img-noty">Ảnh 2x3<br>(đóng dấu giáp lai)</div>
                            @endif
                        </div>
                        <div class="safety-card">
                            <div class="safety-card-header">THẺ</div>
                            <div>AN TOÀN ĐIỆN</div>
                            <div class="card-number">
                                Số: {{$group_font_size_cards[$iGroup][$i - 1]['certificate_id'] ?? null}}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endfor
    </div>

    <div class="{{$iGroup + 1 < $total_group ? 'page-break' : null}} page-after">
        @for($i= 1; $i <= count($group_back_size_cards[$iGroup]); $i++)
                <?php
                $style = "";
                if ($i % 2 == 0 && $i + 2 < 9) {
                    $style = "card card-bottom";
                } elseif ($i + 2 >= 9) {
                    if ($i % 2 == 0) {
                        $style = "card";
                    } else {
                        $style = "card card-next";
                    }
                } else {
                    $style = "card card-next card-bottom";
                }
                ?>
            <div class="{{$style}} {{!($group_back_size_cards[$iGroup][$i - 1]['is_fake'] ?? false) ? null : 'backgroup-none'}}">
                @if(!($group_back_size_cards[$iGroup][$i - 1]['is_fake'] ?? false))
                    <div class="info">
                        <div>Họ tên: <strong class="font-dejavu">{{$group_back_size_cards[$iGroup][$i - 1]['name'] ?? null}}</strong></div>
                        <div>Công việc, đơn vị công tác: {{$group_back_size_cards[$iGroup][$i - 1]['description'] ?? null}}</div>
                        <div>Bậc an toàn: {{$group_back_size_cards[$iGroup][$i - 1]['level'] ?? null}}</div>
                        <div>Cấp ngày {{$group_back_size_cards[$iGroup][$i - 1]['day_created'] ?? null}}
                            tháng {{$group_back_size_cards[$iGroup][$i - 1]['month_created'] ?? null}}
                            năm {{$group_back_size_cards[$iGroup][$i - 1]['year_created'] ?? null}}</div>
                    </div>
                    <div class="location">
                        <div><strong class="font-time">PHÓ GIÁM ĐỐC</strong></div>
                        <div>
                            <img class="signature" src="data:image/png;base64,{{$group_back_size_cards[$iGroup][$i - 1]['signature_photo'] ?? null}}">
                        </div>
                        <div><strong class="font-time">{{$group_back_size_cards[$iGroup][$i - 1]['director_name'] ?? null}}</strong></div>
                    </div>
                @endif
            </div>
        @endfor
    </div>
@endfor
</body>
</html>
