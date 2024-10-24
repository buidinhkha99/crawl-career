@php
    $fonts = [
            ['name' => 'Font Times New Roman', 'path' => 'times.ttf'],
    ];
@endphp
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>

    <style type="text/css">
        @foreach($fonts as $font)
        @font-face {
            font-family: '{{ $font['name'] }}';
            src: url("/storage/{{$font['path']}}") format('truetype');
        }

        @endforeach
        body {
            margin: 0;
        }

        .page {
            margin-left: 0.5cm;
            margin-right: 0.5cm;
            margin-bottom: 2cm;
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 1cm;
            height: 29.7cm;
        }

        .card {
            border: 1px solid #000000;
            width: 20cm;
            height: 14cm
        }

        .card-inner {
            width: 19cm;
            height: 13cm;
            margin: 0.5cm;
            border: 1px solid #324376;
            background-color: #1b1e9d;
            font-size: 16px;
        }

        .title {
            margin-top: 200px;
        }

        .bold-text {
            font-family: 'Font Times New Roman', serif; /* Sử dụng phông chữ Times New Roman Bold */
        }

        .normal-text {
            font-family: 'Font Times New Roman', serif; /* Sử dụng phông chữ Times New Roman Bold */
        }

        .note {
            color: #FFFFFF;
            float: left;
            width: 45%;
            margin-right: 12px;
            line-height: 19px;
        }

        .note-back {
            width: 54%;
            color: #000000;
            float: left;
            margin-right: 12px;
            line-height: 19px;
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
            width: 41%;
            margin-right: 18px;
        }

        .photo-box {
            position: absolute;
            width: 55px;
            height: 83px;
            margin-right: 13px;
            letter-spacing: -0.1em;
        }

        .border-image {
            border: 1px solid #E5E5E5;
        }

        .photo-box span {
            text-align: center;
        }

        .signature {
            margin: auto;
            width: 50px;
            height: 29px;
            object-fit: contain;
        }

        .header {
            margin-top: 10px;
            font-size: 14px;
            text-align: center;
            margin-bottom: 12px;
        }

        .header-1 {
            margin-top: 10px;
            text-align: center;
            font-weight: 700;
            margin-bottom: 146px;
        }

        .header-2 {
            font-size: 15px;
            text-align: center;
            font-weight: 700;
        }

        .header-3 {
            font-size: 29px;
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
            width: 3cm; /* Kích thước khung ảnh */
            height: 4cm; /* Kích thước khung ảnh */
            justify-content: center;
            font-size: 12px;
            display: inline-block;
        }

        .image {
            width: 3cm; /* Kích thước khung ảnh */
            height: 4cm; /* Kích thước khung ảnh */
            object-fit: cover; /* Cắt ảnh sao cho luôn lấp đầy container */
            object-position: center; /* Đảm bảo cắt ở giữa ảnh */
        }

        .title-back {
            display: inline-block;
            height: 120px; /* Kích thước khung ảnh */
            line-height: 19px;
            margin-left: 2px;
        }

        .title-back-2 {
            margin-top: 10px;
            line-height: 15px;
            text-align: center;
            font-weight: bold;
        }

        .header-1-back {
            text-align: center;
            font-weight: 700;
        }

        .details {
            margin-top: 5px;
            margin-right: 12px;
            margin-left: 2px;
            line-height: 18px;
            height: 237px;
            max-height: 235px;
            overflow: hidden;
            font-size: 13px;
        }

        .footer {
            margin-top: 3px;
            float: left;
            margin-left: 120px;
            text-align: center;
            font-size: 13px;
        }

        .info {
            margin-top: 5px;
            line-height: 19px;
            font-size: 13px;
        }
    </style>

</head>
<body>
@for ($iGroup = 0; $iGroup < $total_group; $iGroup++)
    <div class="page">
        @for($i= 1; $i <= count($group_font_size_cards[$iGroup]); $i++)
            <div class="card">
                <div class="card-inner">
                    <div class="note font-time">
                        <div class="header font-mix bold-text"><strong>NHỮNG ĐIỀU CẦN LƯU Ý</strong></div>
                        <p>1- Xuất trình giấy khi được người có thẩm quyền yêu cầu.</p>
                        <p>2- Không được tẩy xóa, sửa chữa, tự ghi vào Giấy chứng nhận.</p>
                        <p>3- Không được cho người khác mượn.</p>
                        <p>4- Khi thất lạc phải báo ngay cho Tổ chức huấn luyện nơi cấp Giấy chứng nhận.</p>
                        <p>5- Trước khi Giấy chứng nhận huấn luyện hết hạn trong vòng 30 ngày, người được cấp phải
                            tham dự huấn luyện định kỳ để được cấp Giấy chứng nhận mới.
                            <br>
                            Đối tượng huấn luyện: {{$group_font_size_cards[$iGroup][$i -1]['group']}}
                        </p>
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
            </div>
        @endfor
    </div>

    <div class="page">
        @for($i= 1; $i <= count($group_back_size_cards[$iGroup]); $i++)
                <?php
                $image = $group_back_size_cards[$iGroup][$i - 1]['image'] ?? null;
                $data = $group_back_size_cards[$iGroup][$i - 1];
                ?>
            <div class="card">
                <div class="card-inner" style="background-color: #FFF7E9">
                    <div style="display: inline-block">
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
                                    <div style="margin-top: 20px">Số: {{$data['certificate_id']}}</div>
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
                                class="info">{!! $data['info_certificate'] ?? null !!}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
@endfor
</body>
</html>
