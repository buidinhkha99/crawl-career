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
            /*margin: 0;*/
        }

        .page-after {
            margin-top: 30px;
            margin-left: 20px;
        }

        .card {
            display: inline-block;
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
    </style>
</head>
<body>
@for ($iGroup = 0; $iGroup < $total_group; $iGroup++)
    <div class="page-break page-after">
        @for($i= 1; $i <= count($group_font_size_cards[$iGroup]); $i++)
                <?php
                $style = "";

                if ($i % 3 == 0 && $i + 3 < 10) {
                    $style = "card column-gap";
                } elseif ($i + 3 >= 10) {
                    if ($i % 3 == 0) {
                        $style = "card";
                    } else {
                        $style = "card card-between";
                    }
                } else {
                    $style = "card card-between column-gap";
                }
                ?>

            <div class="{{$style}}">
                <div class="card-inner">
                    <div class="header">
                        <div>CHI NHÁNH LUYỆN ĐỒNG</div>
                        <div>LAO CAI - VIMICO</div>
                    </div>
                    <div class="title" style="margin-top: 19px;">THẺ AN TOÀN LAO ĐỘNG</div>
                    <?php  $image = $group_font_size_cards[$iGroup][$i - 1]['image'] ?? null ?>
                    <div class="image-cover {{!$image ? 'border-image' : null}}">
                        @if($image)
                            <img class="image" src="data:image/png;base64,{{$image}}" alt="image">
                        @else
                            <div style="margin-top: 35px">Ảnh 3x4 <br> (đóng dấu giáp lai)</div>
                        @endif
                    </div>
                    <div class="footer">Số: {{$group_font_size_cards[$iGroup][$i - 1]['certificate_id'] ?? null}} </div>
                </div>
            </div>
        @endfor
    </div>

    <div class="{{$iGroup + 1 < $total_group ? 'page-break' : null}} page-after">
        @for($i= 1; $i <= count($group_back_size_cards[$iGroup]); $i++)
                <?php
                $style = "";

                if ($i % 3 == 0 && $i + 3 < 10) {
                    $style = "card column-gap";
                } elseif ($i + 3 >= 10) {
                    if ($i % 3 == 0) {
                        $style = "card";
                    } else {
                        $style = "card card-between";
                    }
                } else {
                    $style = "card card-between column-gap";
                }
                ?>
            <div class="{{$style}}">
                <div class="card-inner">
                    <div class="header-back">
                        <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
                        <div>Độc lập - Tự do - Hạnh phúc</div>
                        <div class="underline"></div>
                    </div>
                    <div class="title-back">THẺ AN TOÀN LAO ĐỘNG</div>
                    <div class="info">
                        <div class="font-time">Họ và tên: <strong class="font-dejavu">{{$group_back_size_cards[$iGroup][$i - 1]['name'] ?? null}}</strong></div>
                        <div class="font-time">Sinh ngày: {{$group_back_size_cards[$iGroup][$i - 1]['dob'] ?? null}}</div>
                        <div class="font-time">Chức vụ: {{$group_back_size_cards[$iGroup][$i - 1]['job'] ?? null}}</div>
                        <div class="font-time">
                            Đã hoàn thành khóa huấn luyện: {{$group_back_size_cards[$iGroup][$i - 1]['description'] ?? null}}
                        </div>
                        <div class="font-time">Từ ngày {{$group_back_size_cards[$iGroup][$i - 1]['complete_from'] ?? null}} đến ngày {{$group_back_size_cards[$iGroup][$i - 1]['complete_to'] ?? null}}</div>
                    </div>
                    <div class="location">
                        <div class="font-time">{{$group_back_size_cards[$iGroup][$i - 1]['place'] ?? null}}, ngày {{$group_back_size_cards[$iGroup][$i - 1]['created_at'] ?? null}}</div>
                        <div><strong class="font-dejavu">GIÁM ĐỐC</strong></div>
                        <div>
                            <img class="signature" src="data:image/png;base64,{{$group_back_size_cards[$iGroup][$i - 1]['signature_photo'] ?? null}}">
                        </div>
                        <div><strong class="font-dejavu">{{$group_back_size_cards[$iGroup][$i - 1]['director_name'] ?? null}}</strong></div>
                    </div>
                    <div class="footer-back">
                        Thẻ có giá trị đến ngày {{$group_back_size_cards[$iGroup][$i - 1]['effective_to'] ?? null}}
                    </div>
                </div>
            </div>
        @endfor
    </div>
@endfor
</body>
</html>
