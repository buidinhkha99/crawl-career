@php
    $fonts = [
            ['name' => 'Font SVN-Times New Roman', 'path' => 'fonts/SVN-Times New Roman.ttf'],
            ['name' => 'Font Times New Roman Bold', 'path' => 'fonts/times new roman bold.ttf'],
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
            font-family: 'Font SVN-Times New Roman', serif; /* Sử dụng phông chữ Times New Roman Bold */
        }

        .bold-text {
            font-family: 'Font Times New Roman Bold', 'Font SVN-Times New Roman', serif; /* Sử dụng phông chữ Times New Roman Bold */
        }

        .normal-text {
            font-family: 'Font SVN-Times New Roman', serif; /* Sử dụng phông chữ Times New Roman Bold */
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
            margin-top: 12px;
            display: flex;
            background-color: #1b1e9d;
        }

        .card-background-back {
            background-color: #FFF7E9;
            color: #000000;
            width: 538px;
            height: 368px;
            padding: 0 6px 12px 6px;
            display: flex;
        }

        .card-bottom {
            margin-bottom: 45px;
            /*margin-top: -40px;*/
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
            font-size: 12px;
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
            width: 85px; /* Kích thước khung ảnh */
            height: 113px; /* Kích thước khung ảnh */
            justify-content: center;
            font-size: 12px;
            display: inline-block;
            margin-left: -6px;
        }

        .image {
            width: 85px; /* Kích thước khung ảnh */
            height: 113px; /* Kích thước khung ảnh */
        }

        .title-back {
            display: inline-block;
            height: 120px; /* Kích thước khung ảnh */
            line-height: 13px;
            margin-left: 2px;
        }

        .title-back-2 {
            margin-top: 5px;
            font-size: 12px;
            line-height: 15px;
            text-align: center;
            font-weight: bold;
        }

        .header-1-back {
            font-size: 10px;
            text-align: center;
            font-weight: 700;
        }

        .details {
            margin-top: 5px;
            margin-right: 12px;
            margin-left: 2px;
            font-size: 8px;
            line-height: 10px;
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
                    <div class="note font-time">
                        <div class="header font-mix bold-text"><strong>NHỮNG ĐIỀU CẦN LƯU Ý</strong></div>
                        <div>1- Xuất trình giấy khi được người có thẩm quyền yêu cầu.</div>
                        <div>2- Không được tẩy xóa, sửa chữa, tự ghi vào Giấy chứng nhận.</div>
                        <div>3- Không được cho người khác mượn.</div>
                        <div>4- Khi thất lạc phải báo ngay cho Tổ chức huấn luyện nơi cấp Giấy chứng nhận.</div>
                        <div>5- Trước khi Giấy chứng nhận huấn luyện hết hạn trong vòng 30 ngày, người được cấp phải
                            tham dự huấn luyện định kỳ để được cấp Giấy chứng nhận mới.
                        </div>
                    </div>
                    <div class="certificate">
                        <div class="header-1 bold-text">
                            <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
                            <div>Độc lập - Tự do - Hạnh phúc</div>
                            <div class="underline"></div>
                        </div>

                        <div class="title">
                            <div class="header-3 font-mix">GIẤY CHỨNG NHẬN</div>
                            <div class="header-2 font-mix">HUẤN LUYỆN AN TOÀN, VỆ SINH LAO ĐỘNG</div>
                        </div>
                    </div>
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
                class="{{ $i % 2 == 0 ? "card-background-back" : "card-background-back card-bottom"}}">
                    <div class="note-back">
                        <div style="display: flex; margin-top: 10px">
                            <div class="image-cover {{!$image ? 'border-image' : null}}">
                                @if($image)
                                    <img class="image" src="data:image/png;base64,{{$image}}" alt="image">
                                @else
                                    <div style="margin: 35px 14px">Ảnh 3x4 (đóng dấu giáp lai)</div>
                                @endif
                            </div>
                            <div class="title-back header-1-back font-mix">
                                <div>GIẤY CHỨNG NHẬN HUẤN LUYỆN</div>
                                <div>AN TOÀN, VỆ SINH LAO ĐỘNG</div>
                                <div>Số: {{$data['certificate_id']}}</div>
                            </div>
                        </div>
                        <div class="details font-time">
                            <div>1. Họ và tên: <strong class="font-mix">{{$data['name']}}</strong></div>
                            <div>2. Nam/Nữ: {{$data['gender']}}</div>
                            <div>3. Ngày, tháng, năm sinh: {{$data['dob']}}</div>
                            <div>4. Quốc tịch: {{$data['nationality']}}; Số CMND/Căn cước công dân/hộ chiếu: {{$data['cccd']}}</div>
                            <div>5. Chức vụ: {{$data['position']}}</div>
                            <div>6. Đơn vị công tác: {{$data['work_unit']}}</div>
                            <div>7. Đã hoàn thành khóa huấn luyện an toàn, vệ sinh lao động được tổ chức từ {{$data['complete_from']}} đến {{$data['complete_to']}}
                            </div>
                            <div>8. Kết quả đạt loại: {{$data['result']}}</div>
                            <div>9. Giấy chứng nhận có giá trị {{$data['year_effect']}} năm. Từ {{$data['effective_from']}} đến {{$data['effective_to']}}</div>
                        </div>
                        <div class="footer font-mix">
                            <div>{{$data['place'] ?? null}}, {{$data['create_at'] ?? null}}</div>
                            <div><strong>Giám đốc</strong></div>
                            <img class="signature"
                                 src="data:image/png;base64,{{$data['signature_photo'] ?? null}}">
                            <div><strong>{{$data['director_name'] ?? null}}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="certificate-back">
                        <div class="title-back-2 font-dejavu">NỘI DUNG HUẤN LUYỆN</div>
                        <div
                            class="info font-time">{!! $data['info_certificate'] ?? null !!}</div>
                    </div>
            </div>
        @endfor
    </div>
@endfor
</body>
</html>
