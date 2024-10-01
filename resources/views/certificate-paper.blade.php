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
            height: auto;
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
            width: 85px; /* Kích thước khung ảnh */
            height: 113px; /* Kích thước khung ảnh */
        }

        .title-back {
            display: inline-block;
            height: 120px; /* Kích thước khung ảnh */
            line-height: 13px;
        }

        .header-1-back {
            font-size: 9px;
            text-align: center;
            font-weight: 700;
        }

        .details {
            margin-top: -20px;
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
                class="card-background-font {{ $i % 2 == 0 ? "card" : "card card-bottom"}} {{!($group_back_size_cards[$iGroup][$i - 1]['is_fake'] ?? false) ? null : 'backgroup-none'}}">
                @if(!($group_back_size_cards[$iGroup][$i - 1]['is_fake'] ?? false))
                    <div class="note font-time">
                        <div class="header font-mix"><strong>NHỮNG ĐIỀU CẦN LƯU Ý</strong></div>
                        <div>1- Xuất trình giấy khi được người có thẩm quyền yêu cầu.</div>
                        <div>2- Không được tẩy xóa, sửa chữa, tự ghi vào Giấy chứng nhận.</div>
                        <div>3- Không được cho người khác mượn.</div>
                        <div>4- Khi thất lạc divhải báo ngay cho Tổ chức huấn luyện nơi cấdiv Giấy chứng nhận.</div>
                        <div>5- Trước khi Giấy chứng nhận huấn luyện hết hạn trong vòng 30 ngày, người được cấp phải
                            tham
                            dự huấn luyện định kỳ để được cấp Giấy chứng nhận mới.
                        </div>
                    </div>
                    <div class="certificate">
                        <div class="header-1 font-mix">
                            <div>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
                            <div>Độc lập - Tự do - Hạnh phúc</div>
                            <div class="underline"></div>
                        </div>

                        <div class="title">
                            <div class="header-3 font-mix">GIẤY CHỨNG NHẬN</div>
                            <div class="header-2 font-mix">HUẤN LUYỆN AN TOÀN, VỆ SINH LAO ĐỘNG</div>
                        </div>
                    </div>
                @endif
            </div>
        @endfor
    </div>

    <div class="{{$iGroup + 1 < $total_group ? 'page-break' : null}} page-after">
        @for($i= 1; $i <= count($group_back_size_cards[$iGroup]); $i++)
            <div
                class="card-background-back {{ $i % 2 == 0 ? "card" : "card card-bottom"}} {{!($group_back_size_cards[$iGroup][$i - 1]['is_fake'] ?? false) ? null : 'backgroup-none'}}">
                @if(!($group_back_size_cards[$iGroup][$i - 1]['is_fake'] ?? false))
                    <div class="note-back">
                        <div>
                            {{--                        <div class="image-cover {{!$image ? 'border-image' : null}}">--}}
                            <div class="image-cover border-image">
                                {{--                            @if($image)--}}
                                {{--                                <img class="image" src="data:image/png;base64,{{$image}}" alt="image">--}}
                                {{--                            @else--}}
                                {{--                                <div style="margin-top: 35px">Ảnh 3x4 <br> (đóng dấu giáp lai)</div>--}}
                                {{--                            @endif--}}
                                <div style="margin: 35px 14px">Ảnh 3x4 (đóng dấu giáp lai)</div>
                            </div>
                            <div class="title-back header-1-back font-mix">
                                <div>GIẤY CHỨNG NHẬN HUẤN LUYỆN</div>
                                <div>AN TOÀN, VỆ SINH LAO ĐỘNG</div>
                                <div>Số: 02/GCN-ATLĐ</div>
                            </div>
                        </div>
                        <div class="details font-time">
                            <div>1. Họ và tên: <strong class="font-mix">Nguyễn Thị Mừng</strong></div>
                            <div>2. Nam/Nữ: Nữ</div>
                            <div>3. Ngày, tháng, năm sinh: 08/06/1981</div>
                            <div>4. Quốc tịch: Việt Nam; Số CMND/Căn cước công dân/hộ chiếu: 0989328012</div>
                            <div>5. Chức vụ: Công nhân lấy mẫu, phân tích quặng và sản phẩm luyện kim.</div>
                            <div>6. Đơn vị công tác: Chi nhánh Luyện đồng Lào Cai - VIMICO</div>
                            <div>7. Hoàn thành khóa huấn luyện: Ngày 23 tháng 10 năm 2024 đến ngày 28 tháng 10 năm 2024</div>
                            <div>8. Kết quả đạt loại: Giỏi</div>
                            <div>9. Giấy chứng nhận có giá trị 2 năm: Từ ngày 30 tháng 10 năm 2024 đến ngày 30 tháng 10 năm 2026</div>
                        </div>
{{--                        <div class="footer">--}}
{{--                            <p>Lào Cai, ngày 30 tháng 10 năm 2024</p>--}}
{{--                            <p>Giám đốc</p>--}}
{{--                            <div class="signature">--}}
{{--                                <p><strong>Hoàng Ngọc Minh</strong></p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                    <div class="certificate-back">
                                                <h1>NỘI DUNG HUẤN LUYỆN</h1>
                        {{--                        <ul>--}}
                        {{--                            <li>+ Kiến thức cơ bản về an toàn, vệ sinh lao động;</li>--}}
                        {{--                            <li>+ Nội dung liên quan đến quyền và nghĩa vụ của người lao động;</li>--}}
                        {{--                            <li>+ Chính sách liên quan đến an toàn, vệ sinh lao động đối với người lao động;</li>--}}
                        {{--                            <li>+ Kiến thức cơ bản về yếu tố nguy hiểm, có hại tại nơi làm việc và phương pháp cải thiện--}}
                        {{--                                điều kiện lao động;--}}
                        {{--                            </li>--}}
                        {{--                            <li>+ Chức năng, nhiệm vụ của mạng lưới an toàn, vệ sinh viên;</li>--}}
                        {{--                            <li>+ Văn hóa an toàn trong sản xuất, kinh doanh;</li>--}}
                        {{--                            <li>+ Nội quy an toàn, vệ sinh lao động, biển báo, biển chỉ dẫn an toàn, vệ sinh lao động và--}}
                        {{--                                sử dụng các thiết bị an toàn, phương tiện bảo vệ cá nhân, nghiệp vụ, kỹ năng sơ cứu tai--}}
                        {{--                                nạn lao động, phòng chống bệnh nghề nghiệp;--}}
                        {{--                            </li>--}}
                        {{--                            <li>+ Nội dung huấn luyện trực tiếp tại nơi làm việc: Huấn luyện về quy trình làm việc và--}}
                        {{--                                yêu cầu cụ thể về an toàn, vệ sinh lao động nơi làm việc.--}}
                        {{--                            </li>--}}
                        {{--                        </ul>--}}

                    </div>
                @endif
            </div>
        @endfor
    </div>
@endfor
</body>
</html>
