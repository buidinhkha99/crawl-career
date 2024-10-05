<!doctype html>
<html lang="{{ app()->getLocale() }}">
@php
    $listLabelAnswer = ['A', 'B', 'C', 'D', 'E', 'F', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L']
@endphp
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <style>
        html,
        body {
            background-color: #fff;
            font-family: 'Roman New Times', sans;
            font-weight: 400;
            height: 100vh;
            margin: 0;
            font-size: 16px;
            color: black;
            padding: 10px 0px 10px 30px;
        }

        .container {
            align-items: center;
            padding: 0px 50px;
        }

        .main {
            margin-top: 30px;
            padding: 110px 12px;
        }

        .main__result--title {
            font-weight: 700;
        }

        .main_result_table_title {
            margin-top: 0;
            font-weight: 900;
        }

        .main__result--answer {
            display: flex;
            flex-direction: row;
            align-items: center;
            padding-left: 20px;
        }

        .result {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 50%;
            gap: 40px;
        }

        .achievements {
            color: #23A538;
            font-weight: 700;
            font-size: 36px;
        }

        .fail {
            margin-top: 10px;
            color: red;
            font-weight: 700;
            font-size: 30px;
        }

        .infoUser {
            display: flex;
            flex-direction: row;
            align-items: end;
            gap: 60px;
            width: 100%;
        }

        .infoUser__img {
            max-width: 180px;
            max-height: 180px;
            padding-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            text-align: center;
        }

        tr.info {
            height: 32px;
        }

        tr {
            height: 50px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .mainMarginTop {
            margin-bottom: 30px;
        }

        .marginTop {
            margin-top: 30px;
        }

        .borderNone {
            border: none
        }

        .w-35 {
            width: 30%;
        }

        .bold {
            font-weight: 700;
        }

        .header-left {
            text-align: center;
        }

        .header-right {
            text-align: center;
        }

        .height_td {
            height: 120px;
        }

        .w-60 {
            width: 40%;
        }
        .title-detail-exam {
            margin-top: 20px;
        }

        .question {
            font-weight: bold;
        }
        .answer-list {
            list-style-type: none;
            padding-left: 0;
        }
        .answer-list li {
            margin-bottom: 10px;
        }

        .answer {
            display: inline-block;
            font-weight: bold;
            margin-right: 8px;
        }

        .answered {
            display: inline-block;
            width: 24px;
            height: 24px;
            border: 2px solid black;
            border-radius: 50%;
            text-align: center;
            line-height: 24px;
            font-weight: bold;
        }

        .checkmark {
            color: green;
            font-size: 24px;
            display: inline;
            margin-right: 13px;
        }

        .borderless-table {
            border: none; /* Loại bỏ đường viền của bảng */
            border-collapse: collapse; /* Đảm bảo không có khoảng trống giữa các ô */
        }

        .borderless-table tr {
            height: auto;
        }
        .borderless-table th,
        .borderless-table td {
            border: none; /* Loại bỏ đường viền của các ô trong bảng */
            text-align: left;
            padding-top: 9px;
            vertical-align: top;
        }

    </style>
</head>

<body>
<div class="container">
    <div class="header mainMarginTop">
        <div style="float:left; width:50%;" class="header-left">
            <p>TỔNG CÔNG TY KHOÁNG SẢN - TKV</p>
            <p class="bold">CHI NHÁNH LUYỆN ĐỘNG LÀO CAI - VIMICO</p>
        </div>

        <div style="float:right; width:50%;" class="header-right">
            <p class="bold">BÀI KIỂM TRA AT - VSLĐ</p>
            <p>Ngày thi: {{$created_at}}</p>
            <p>Thời gian làm bài: {{$duration}}</p>
            <p>Đề thi: {{ $quiz_name }}</p>

        </div>
    </div>
    <div class="main">
        <div class="infoUser marginTop">

            <div style="float: left; width: 15%;">
                <img src="{{$user_info['avatar']}}" alt="img user" class="infoUser__img" />
            </div>
            <div style="width: 85%;">
                <p class="main__result--title borderNone" style=" width: 85%;float:right">Thông tin thí sinh</p>
                <table style=" width: 85%;float:right" class="borderNone">

                    <tr class="info">
                        <td class="borderNone w-35">Họ tên:</td>
                        <td class="borderNone"><b>{{$user_info['full_name']}}</b></td>
                    </tr>
                    <tr class="info">
                        <td class="borderNone w-35">Mã nhân viên:</td>
                        <td class="borderNone"><b>{{$user_info['identification_number']}}</b></td>
                    </tr>
                    <tr class="info">
                        <td class="borderNone w-35">Ngày sinh:</td>
                        <td class="borderNone"><b>{{$user_info['date_of_birth']}}</b></td>
                    </tr>
                    {{--                        <tr class="info">--}}
                    {{--                            <td class="borderNone w-35">Nhóm huấn luyện:</td>--}}
                    {{--                            <td class="borderNone"><b>{{$user_info['coaching_team']}}</b> </td>--}}
                    {{--                        </tr>--}}
                    <tr class="info">
                        <td class="borderNone w-35">Đơn vị công tác:</td>
                        <td class="borderNone"><b>{{$user_info['work_unit']}}</b></td>
                    </tr>

                    <tr class="info">
                        <td class="borderNone w-35">Vị trí làm việc:</td>
                        <td class="borderNone"><b>{{$user_info['working_position']}}</b></td>
                    </tr>
                </table>
            </div>
        </div>

        <table class="borderNone marginTop">
            <thead>
            <tr>
                <th style="padding: 10px 0px;">
                    <div style="padding-bottom: 6px;">Chữ ký thí sinh</div>
                    <em style="font-weight: normal; font-size:12px">(ký, ghi rõ họ tên)</em>
                </th>
                <th style="padding: 10px 0px;">
                    <div style="padding-bottom: 6px;">Cán bộ coi thi 1</div>
                    <i style="font-weight: normal; font-size:12px">(ký, ghi rõ họ tên)</i>
                </th>
                <th style="padding: 10px 0px;">
                    <div style="padding-bottom: 6px;">Cán bộ coi thi 2</div>
                    <i style="font-weight: normal; font-size:12px">(ký, ghi rõ họ tên)</i>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="height_td"></td>
                <td class="height_td"></td>
                <td class="height_td"></td>
            </tr>

            </tbody>
        </table>

        <div class="marginTop">
            <div class="main__result">
                <p class="main__result--title"><b>Kết quả thi</b></p>
                <div class="main__result--answer">
                    <table style="width: 70%; float: left" class="borderNone">
                        <tr class="info">
                            <td class="borderNone w-60">Số câu trả lời ĐÚNG:</td>
                            <td class="borderNone"><b>{{$exam_result['right_answers']}}</b></td>
                        </tr>
                        <tr class="info">
                            <td class="borderNone w-60">Số câu trả lời SAI:</td>
                            <td class="borderNone"><b>{{$exam_result['wrong_answers']}}</b></td>
                        </tr>
                        <tr class="info">
                            <td class="borderNone w-60">Số câu CHƯA TRẢ LỜI:</td>
                            <td class="borderNone"><b>{{$exam_result['unanswered']}}</b></td>
                        </tr>
                    </table>
                    <div class="result" style="width: 30%; float: left;">
                        <div style="text-align: center">KẾT QUẢ: {{$exam_result['score']}}/10</div>
                        @if($exam_result['is_passed'])
                            <div style="text-align: center" class="achievements">ĐẠT</div>
                        @else
                            <div style="text-align: center" class="fail">KHÔNG ĐẠT</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="marginTop">
                <p class="main_result_table_title"><b>Bảng kết quả thi</b></p>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th style="width: 100px">Câu hỏi</th>
                        @for ($i = 1; $i <= count($examination); $i++)
                            <th style="width: 45px">{{ $i }}</th>
                        @endfor
                    </tr>
                    </thead>
                    <tbody>
                    @for($indexAnswer = 0; $indexAnswer <= $max_answer - 1; $indexAnswer++)
                        <tr style="height: 35px;">
                            <td><strong>{{$listLabelAnswer[$indexAnswer] ?? ''}}</strong></td>
                            @foreach ($examination as $question)
                                <td>{{ $question['index_answered'] == $indexAnswer ? '●' : ''}}</td>
                            @endforeach
                        </tr>
                    @endfor
                    <tr style="height: 35px;">
                        <td><strong>Đáp án</strong></td>
                        @foreach ($examination as $question)
                            <td><strong>{{ $listLabelAnswer[$question['index_correct_answer']] ?? ''}}</strong></td>
                        @endforeach
                    </tr>
                    </tbody>
                </table>

                <p class="title-detail-exam"><b>Chi tiết bài làm</b></p>
                @foreach($examination as $exam)
                    <div class="question-block">
                        <div style="margin-top: 20px;">
                            <strong style="display: inline; margin-right: 5px">Câu {{$exam['order']}}: </strong> {!! str_replace('<p>', '<p style="display: inline;">' ,$exam['question_content']) !!}
                        </div>
                        <table class="borderless-table">
                            @foreach ($exam['answers'] as $keyAnswer => $answerQuestion)
                                <tr>
                                    <td style="width: 25px; padding-top: 2px">
                                        @if($answerQuestion['is_correct'])
                                            <span class="checkmark">✓</span>
                                        @endif
                                    </td>
                                    <td style="width: 25px" >
                                        <span class="{{ $answerQuestion['is_choose'] ? 'answered' : 'answer' }}" style="display: inline;">{{$listLabelAnswer[$keyAnswer]}}</span>
                                    </td>
                                    <td>
                                        {!! str_replace('<p>', '<p style="display: inline;">' ,$answerQuestion['data']) !!}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @endforeach
            </div>

            <div class="infoUser marginTop">
                <div style="float: left; width: 8%;">
                    <ins class="bold" style=" font-style: italic;">Lưu ý:</ins>
                </div>
                <div style="float: right; width: 92%;">
                    <p>- Số thứ tự các câu trả lời trong bảng kết quả thi ứng với số thứ tự các câu trắc nghiệm trong đề thi.</p>
                    <p>- Bài thi hợp lệ: Là bài có đầy đủ họ tên, chữ ký của học viên, có đầy đủ chữ ký của cán bộ coi thi.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module">
    import canvasFontsTimesNewRomanBoldItalic from 'https://cdn.skypack.dev/@canvas-fonts/times-new-roman-bold-italic';
</script>
</body>

</html>
