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
    <title>Giấy Chứng Nhận</title>
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
            width: 538px;
            height: 368px;
            padding: 12px 6px 12px 6px;
        }

        .card-background-font {
            background-color: #1b1e9d;
        }

        .card-background-back {
            background-color: #FFF7E9;
            color: #000000;
            width: 538px;
            height: 368px;
            padding: 0 6px 12px 6px;
        }

        .card-bottom {
            margin-bottom: 45px;
            margin-top: -40px;
        }

        .note {
            color: #FFFFFF;
            float: left;
            width: 45%;
            margin-right: 12px;
            line-height: 15px;
        }

        .note-back {
            color: #000000;
            float: left;
            width: 260px;
            margin-right: 12px;
            line-height: 15px;
        }

        .certificate {
            color: #FFFF27;
            float: right;
            width: 50%;
            max-height: 195px;
            margin-right: 18px;
        }

        .certificate-back {
            color: #000000;
            float: right;
            width: 260px;
            max-height: 195px;
            margin-right: 18px;
        }

        .photo-box {
            position: absolute;
            width: 55px;
            height: 83px;
            margin-right: 13px;
            font-size: 12px;
            letter-spacing: -0.1em;
        }

        .border-image {
            border: 1px solid #E5E5E5;
        }

        .photo-box span {
            font-size: 12px;
            text-align: center;
        }

        .signature {
            margin: auto;
            width: 50px;
            height: 29px;
            object-fit: contain;
        }

        .font-time {
            font-family: 'Font1', sans-serif;
            font-size: 12px;
        }

        .font-dejavu {
            font-family: 'DejaVu Sans', sans-serif;
        }

        .font-mix {
            font-family: 'Font1', 'DejaVu Sans', sans-serif;
        }

        .header {
            font-size: 14px;
            text-align: center;
            margin-bottom: 12px;
        }

        .header-1 {
            font-size: 11px;
            text-align: center;
            font-weight: 700;
            margin-bottom: 146px;
        }

        .header-2 {
            font-size: 11px;
            text-align: center;
            font-weight: 700;
        }

        .title {
            position: fixed;
        }

        .header-3 {
            font-size: 20px;
            text-align: center;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .underline {
            width: 120px;
            margin: 5px auto;
            height: 0.01em;
            background-color: #FFFF27;
        }

        .image-cover {
            font-family: 'Font1', sans-serif;
            width: 85px; /* Kích thước khung ảnh */
            height: 113px; /* Kích thước khung ảnh */
            justify-content: center;
            font-size: 12px;
            display: inline-block;
            margin-left: -6px;
            margin-top: 40px;
        }

        .image {
            width: 538px;
            height: 368px;
        }

        .title-back {
            display: inline-block;
            height: 120px; /* Kích thước khung ảnh */
            line-height: 13px;
        }

        .title-back-2 {
            margin-top: 5px;
            font-size: 12px;
            line-height: 15px;
            text-align: center;
            font-weight: bold;
        }

        .header-1-back {
            font-size: 9px;
            text-align: center;
            font-weight: 700;
        }

        .details {
            margin-top: -20px;
            margin-right: 12px;
            margin-left: 2px;
            font-size: 8px;
            line-height: 9px;
            height: 155px;
        }

        .footer {
            margin-top: 3px;
            font-size: 8px;
            line-height: 10px;
            float: left;
            margin-left: 120px;
            text-align: center;
        }

        .info {
            margin-top: 5px;
            font-size: 8px;
            line-height: 10px;
        }
    </style>
</head>
<body>
@for ($iGroup = 0; $iGroup < $total_group; $iGroup++)
    <div class="page-break page-after">
        @for($i= 1; $i <= count($group_font_size_cards[$iGroup]); $i++)
            <div
                class="card-background-font {{ $i % 2 == 0 ? "card" : "card card-bottom"}} {{!($group_font_size_cards[$iGroup][$i - 1]['is_fake'] ?? false) ? null : 'backgroup-none'}}">
                @if(!($group_font_size_cards[$iGroup][$i - 1]['is_fake'] ?? false))
                    <img class="image" src="data:image/png;base64,{{$group_font_size_cards[$iGroup][$i - 1]['image_card']}}" alt="image">
                @endif
            </div>
        @endfor
    </div>

    <div class="{{$iGroup + 1 < $total_group ? 'page-break' : null}} page-after">
        @for($i= 1; $i <= count($group_back_size_cards[$iGroup]); $i++)
                <?php
                    $image = $group_back_size_cards[$iGroup][$i - 1]['image'] ?? null;
                    $data = $group_back_size_cards[$iGroup][$i - 1];
                ?>
            <div
                class="{{ $i % 2 == 0 ? "card-background-back" : "card-background-back card-bottom"}} {{!($data['is_fake'] ?? false) ? null : 'backgroup-none'}}">
                @if(!($data['is_fake'] ?? false))
                    <img class="image" src="data:image/png;base64,{{$group_back_size_cards[$iGroup][$i - 1]['image_card']}}" alt="image">
                @endif
            </div>
        @endfor
    </div>
@endfor
</body>
</html>
