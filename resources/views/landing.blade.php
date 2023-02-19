<!DOCTYPE html>
<html data-theme="{{ $userPreferences['theme'] }}" lang="{{ $lang }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="{{ __('commons.2fauth_description') }}" lang="{{ $lang }}">
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

    <link href="{!! $subdirectory !!}{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <app></app>
    </div>
    <script type="text/javascript">
        var appSettings = {!! $appSettings !!};
        var appConfig = {!! $appConfig !!};
        var userPreferences = {!! $userPreferences->toJson() !!};
        var appVersion = '{{ config("2fauth.version") }}';
        var isDemoApp = {!! $isDemoApp !!};
        var isTestingApp = {!! $isTestingApp !!};
        var appLocales = {!! $locales !!};
    </script>
    <script src="{!! $subdirectory !!}{{ mix('js/manifest.js') }}"></script>
    <script src="{!! $subdirectory !!}{{ mix('js/vendor.js') }}"></script>
    <script src="{!! $subdirectory !!}{{ mix('js/app.js') }}"></script>
</body>
</html>