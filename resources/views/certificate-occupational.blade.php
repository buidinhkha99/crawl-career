@php
    $fonts = [
            ['name' => 'Font Times New Roman', 'path' => 'times.ttf'],
    ];
@endphp
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thẻ An Toàn Lao Động</title>

    <style>
        @foreach($fonts as $font)
        @font-face {
            font-family: '{{ $font['name'] }}';
            src: url("/storage/{{$font['path']}}") format('truetype');
        }

        @endforeach
        body {
            margin: 0;
        }

        .card-inner {
            width: 6cm;
            height: 9cm;
            margin: 0.25cm 0.25cm;
            border: 1px solid #324376;
            text-align: center;
            box-shadow: inset 0 0 0 4px #324376;
            outline: 2px solid #324376;
            outline-offset: -8px;
        }

        .bold-text {
            font-family: 'Font Times New Roman', serif; /* Sử dụng phông chữ Times New Roman Bold */
        }

        .normal-text {
            font-family: 'Font Times New Roman', serif; /* Sử dụng phông chữ Times New Roman Bold */
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            color: #E40613;
            margin-top: 30px;
        }

        .image-cover {
            margin: 19px auto 19px auto;
            width: 3cm; /* Kích thước khung ảnh */
            height: 4cm; /* Kích thước khung ảnh */
            justify-content: center;
            font-size: 13px;
        }

        .border-image {
            border: 1px dashed #E5E5E5;
        }

        .image {
            width: 3cm; /* Kích thước khung ảnh */
            height: 4cm; /* Kích thước khung ảnh */
            object-fit: cover; /* Cắt ảnh sao cho luôn lấp đầy container */
            object-position: center; /* Đảm bảo cắt ở giữa ảnh */
        }

        .header {
            margin-top: 20px;
            font-size: 14px;
            color: #000000;
            font-weight: 700;
        }

        .header-back {
            margin-top: 13px;
            font-size: 10px;
            color: #000000;
            font-weight: bold;
            height: 18px;
        }

        .title-back {
            font-size: 14px;
            font-weight: bold;
            color: #E40613;
            margin-top: 22px;
        }

        .footer {
            margin-top: 35px;
            font-size: 15px;
            text-align: center;
        }

        .footer-back {
            margin-top: 10px;
            font-size: 13px;
            text-align: center;
        }

        .info {
            font-size: 12px;
            margin-top: 15px;
            margin-left: 12px;
            line-height: 16px;
            text-align: left;
            height: 150px;
            width: 91%;
        }

        .location {
            margin-top: 5px;
            margin-left: 36px;
            text-align: center;
            font-size: 13px;
            line-height: 11px;
            height: 67px;
        }

        .signature {
            margin: 2px auto;
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

        .page {
            padding-top: 2px;
            height: 29.660cm;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            column-gap: 0.1cm;
            row-gap: 0;
        }
        .small-font {
            font-size: 10px;
        }
    </style>

</head>
<body style="margin-left: 0.3cm;">
@for ($iGroup = 0; $iGroup < $total_group; $iGroup++)
    <div class="page">
        @for($i= 1; $i <= count($group_font_size_cards[$iGroup]); $i++)
                <?php
                $data_font_size_cards = $group_font_size_cards[$iGroup][$i - 1];
                ?>
            <div class="card" style="border: 1px solid black; width: 6.5cm; height: 9.5cm;">
                @if(!($group_font_size_cards[$iGroup][$i - 1]['is_fake'] ?? false))
                    <div class="card-inner">
                        <div class="header">
                            <div class="bold-text">CHI NHÁNH LUYỆN ĐỒNG</div>
                            <div class="bold-text">LÀO CAI - VIMICO</div>
                        </div>
                        <div class="title bold-text">THẺ AN TOÀN LAO ĐỘNG</div>
                            <?php $image = $data_font_size_cards['image'] ?? null ?>
                        <div class="image-cover {{!$image ? 'border-image' : null}}">
                            @if($image)
                                <img class="image" src="data:image/png;base64,{{$image}}" alt="image">
                            @else
                                <div class="image normal-text" style="margin-top: 35px">Ảnh 3x4 <br> (đóng dấu giáp lai)
                                </div>
                            @endif
                        </div>
                        <div class="footer normal-text">
                            Số: {{$data_font_size_cards['certificate_id'] ?? null}}
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
            <div class="card" style="border: 1px solid black; width: 6.5cm; height: 9.5cm">
                @if(!($group_back_size_cards[$iGroup][$i - 1]['is_fake'] ?? false))
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
                            <div class="normal-text job"> <span style="font-size: 12px">Công
                                việc:</span> {{$data_back_size_cards['job'] ?? null}}</div>
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
                            <div style="margin-top: 7px"><strong class="bold-text">GIÁM ĐỐC</strong></div>
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
                @endif
            </div>
        @endfor
    </div>
@endfor
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var textContents = document.querySelectorAll('.job');

        textContents.forEach(function(element) {
            var lineHeight = parseInt(window.getComputedStyle(element).lineHeight);
            var maxHeight = lineHeight * 3; // 3 lines limit
            console.log(element.scrollHeight, maxHeight);
            if (element.scrollHeight > maxHeight) {
                element.classList.add('small-font');
            }
        });
    });
</script>
</body>
</html>
