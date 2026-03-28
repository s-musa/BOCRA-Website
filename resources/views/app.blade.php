<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      class="light-style layout-menu-fixed layout-compact"
      dir="ltr"
      data-theme="theme-default"
      data-style="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <title inertia>{{ config('app.name', 'Botswana Communications Regulatory Authority') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ $favicon ?? '' }}" type="image/x-icon"/>
    <link rel="shortcut icon" href="{{ $favicon ?? '' }}" type="image/x-icon"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Page CSS -->

    <style>
        :root {
            --ecbz-primary: {{ $customisation->primary_color ?? '#1285e5' }};
            --ecbz-primary-lt: {{ $customisation->primary_color_light ?? '#c2dcf5' }};
            --ecbz-secondary: {{ $customisation->secondary_color ?? '#ffd90d' }};
            --ecbz-secondary-lt: {{ $customisation->secondary_color_light ?? '#fff786' }};
            --ecbz-primary-hover: {{ $customisation->primary_color_light ?? '#c2dcf5' }};
            --ecbz-primary-active: {{ $customisation->primary_color ?? '#1285e5' }};
            --ecbz-primary-border: {{ $customisation->primary_color_light ?? '#c2dcf5' }};
            --ecbz-secondary-hover: {{ $customisation->secondary_color_light ?? '#fff786' }};
            --ecbz-secondary-active: {{ $customisation->secondary_color_light ?? '#fff786' }};
            --ecbz-secondary-border: {{ $customisation->secondary_color ?? '#ffd90d' }};
        }
    </style>

    <!-- Scripts -->
    @routes
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
    @inertia

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="{{ asset('assets/js/buttons.js') }}"></script>
</body>
</html>
