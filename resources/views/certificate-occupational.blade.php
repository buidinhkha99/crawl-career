
    <!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Thẻ An Toàn Lao Động</title>
    <style>
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
            /*border: 3px solid #324376;*/
            /*padding: 1px;*/
        }

        .card-inner {
            width: 168px;
            height: 254px;
            border: 1px solid #324376;
            text-align: center;
        }

        .image {
            width: 170px;
            height: 256px;
        }

        .card-between {
            margin-right: 6px;
        }

        .column-gap {
            margin-bottom: 3px;
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

            <div
                class="{{$style}} {{!($group_font_size_cards[$iGroup][$i - 1]['is_fake'] ?? false) ? null : 'border-none'}}">
                @if(!($group_font_size_cards[$iGroup][$i - 1]['is_fake'] ?? false))
                    <img class="image" src="data:image/png;base64,{{$group_font_size_cards[$iGroup][$i - 1]['image_card']}}" alt="image">
                @endif
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
            <div
                class="{{$style}} {{!($group_back_size_cards[$iGroup][$i - 1]['is_fake'] ?? false) ? null : 'border-none'}}">
                @if(!($group_back_size_cards[$iGroup][$i - 1]['is_fake'] ?? false))
                    <img class="image" src="data:image/png;base64,{{$group_back_size_cards[$iGroup][$i - 1]['image_card']}}" alt="image">
                @endif
            </div>
        @endfor
    </div>
@endfor
</body>
</html>
