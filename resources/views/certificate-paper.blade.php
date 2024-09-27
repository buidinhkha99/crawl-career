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
    </style>
</head>
<body>

</body>
</html>
