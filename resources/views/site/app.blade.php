<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    @include('site.layouts.style')
</head>
<meta name="description" content="خرید اینترنتی لباس نوزاد را با فروشگاه آنلاین بی‌سی‌سی تجربه کنید. انواع لباس نوزاد دختر و پسر در طرح‌های شاد و رنگارنگ با کیفیت جهانی">
<body dir="rtl">
@include('site.layouts.header')
@include('site.layouts.nav')
<div id="loader" class="block-page">
    <span class='loading-spinner'></span>
</div>
<div class="container main-content" id="app">
    @yield('content')
</div>
@include('site.layouts.footer')
@include('site.layouts.scripts')
</body>
</html>
