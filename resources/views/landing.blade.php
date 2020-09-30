<!DOCTYPE html>
<html class="has-background-black-ter" lang="{{ option('lang', 'en') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0, shrink-to-fit=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="robots" content="noindex, nofollow">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>{{ config('app.name') }}</title>

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('favicon_lg.png') }}" />
    <link rel="apple-touch-icon-precomposed" href="{{ asset('favicon_lg.png') }}" />
    <link rel="manifest" href="/manifest.json">
    
    <link href=" {{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="has-text-lighter">
    <div id="app">
        <app></app>
    </div>
    <script type="text/javascript">
        var appSettings = {!! $appSettings !!};
        var appVersion = '{{ config("app.version") }}';
    </script>
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>