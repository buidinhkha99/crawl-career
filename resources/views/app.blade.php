<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="{{ \App\Models\Setting::get('favicon', '') }}">
        {!! \App\Models\Setting::get('fonts', '') !!}

        {!! SEO::generate(true) !!}

        {!! \App\Models\Customization::get('Header Script') !!}

        <!-- Scripts -->
        @viteReactRefresh
        @vite(['resources/js/app.jsx'])
        @inertiaHead
        <style>
            body {
                font-family: {{ \App\Models\Setting::get('font_name', '') }};
                color: {{ \App\Models\Setting::get('font_color', '') }};
                margin:0px;
            }
        </style>
    </head>
    <body>
        <!-- <h1 style="position: absolute; margin-left: 100%">{{ $h1 }}</h1> -->
        @inertia

        {!! \App\Models\Customization::get('Body Script') !!}
        <link rel="stylesheet" href="/assets/custom.css">
        <script defer src="/assets/custom.js"></script>
    </body>
</html>
