<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Noto+Sans+JP:wght@100&family=Open+Sans:wght@300;500&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
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

        .header {
            height: 100%;
        }

        .title {
            margin-top: 25px;
            font-size: 26px;
            text-align: center;
        }


        table {
            font-family: 'Roman New Times', sans;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
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
            /* margin-bottom: 30px; */
            padding-top: 30px;
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

        .italic {
            font-style: italic;
        }

        .height_td {
            height: 120px;
        }

        .paddingTop {
            padding-top: 30px;
        }

        .w-60 {
            width: 40%;
        }

        .paddingTop1 {
            padding-top: 90px;
        }

        .paddingTop2 {
            padding-top: 60px;
        }

        .uppercase {
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="mainMarginTop header">
            @if(isset($header['company_name']))
            <div style="float:left; width:50%; text-align:start">
                <p class="bold">{{$header['company_name']}}</p>
            </div>
            @endif
            @if(!$header['company_name'])
            <div style="float:left; width:50%; text-align:start">
                <p style="padding-left: 40px;">TỔNG CÔNG TY KHOÁNG SẢN - TKV</p>
                <p class="bold">CHI NHÁNH LUYỆN ĐỘNG LÀO CAI - VIMICO</p>
            </div>
            @endif
            <div style="float:right; width:50%; text-align:end" class="header-right">
                <p class="bold">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</p>
                <p class="bold" style="padding-right:50px;">Độc Lập - Tự Do - Hạnh Phúc</p>
                <p class="italic" style="font-size:14px">{{$header['place']}}, {{$header['date_time']}}</p>
            </div>
        </div>

        <div class="infoUser">
            @if(isset($title))
            <div class="header-left" style="flex:1;">
                <p class="bold uppercase" style="font-size: 32px;">{{$title}}</p>
            </div>
            @endif
            @if(!isset($title))
            <div class="header-left" style="flex:1;">
                <p class="bold uppercase" style="font-size: 32px;">Báo cáo</p>
            </div>
            @endif
        </div>

        <div class="info">
            @if(isset($table))
            <table>
                <tr>
                    @foreach($table['heading'] as $heading)
                        @if($heading == __('Signature'))
                            <th width="130px">{{$heading}}</th>
                        @else
                            <th>{{$heading}}</th>
                        @endif
                    @endforeach
                </tr>
                @foreach($table['data'] as $row)
                    <tr>
                        @foreach($row as $column)
                            <td>{{$column}}</td>
                        @endforeach
                    </tr>
                @endforeach
            </table>
            @endif

            @if(isset($note))
            <div class="infoUser marginTop">
                <div style="float: left; width: 8%;">
                    <ins class="bold" style=" font-style: italic;">Ghi chú:</ins>
                </div>
                <div style="float: right; width: 92%;">
                    <p>{{$note}}</p>
                </div>
            </div>
            @endif

        </div>


        <div class="marginTop">
            <div style="float:left; width:66%;" class="header-left">
                <div style="float:left; width:50%;" class="header-left">
                    <p class="bold">NGƯỜI LẬP</p>


                    @if(isset($footer) && isset($footer['reporter']))
                    <p class="header-left paddingTop1">{{$footer['reporter']}}</p>
                    @endif
                </div>


                <div style="float:right; width:50%;" class="header-right">
                    <p class="bold">PHÒNG ATMT</p>

                    @if(isset($footer) && isset($footer['represent']))
                    <p class="header-left paddingTop1">{{$footer['represent']}}</p>
                    @endif

                </div>
            </div>

            <div style="float:right; width:34%;" class="header-right">
                <p class="bold">KT. GIÁM ĐỐC</p>
                <p class="bold"> PHÓ GIÁM ĐỐC</p>

                @if(isset($footer) && isset($footer['verifier']))
                <p class="header-left paddingTop2">{{$footer['verifier']}}</p>
                @endif

            </div>

        </div>
    </div>
    <script type="module">
        import canvasFontsTimesNewRomanBoldItalic from 'https://cdn.skypack.dev/@canvas-fonts/times-new-roman-bold-italic';
    </script>
</body>

</html>
