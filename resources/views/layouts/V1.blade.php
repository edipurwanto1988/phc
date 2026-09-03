<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @php $dir = optional(current_bahasa())->direction ?? 'ltr'; @endphp dir="{{ $dir }}" class="light">
<head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KDQFLFMK');</script>
<!-- End Google Tag Manager -->
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-FH500THLZ9"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-FH500THLZ9');
</script>

@php
$favicon = \App\Models\Pengaturan::get('favicon');
$faviconUrl = public_image_url($favicon);
$faviconUrl = $faviconUrl ?: asset('favicon.ico');
$logo = \App\Models\Pengaturan::get('logo');
$logoUrl = public_image_url($logo);
$seoTitle = \App\Models\Pengaturan::get('meta_title') ?: config('app.name');
$seoDescription = \App\Models\Pengaturan::get('meta_description') ?: 'Fakultas Ilmu Komputer - Universitas Lancang Kuning';
$seoKeywords = \App\Models\Pengaturan::get('meta_keywords') ?: '';
$seoAuthor = \App\Models\Pengaturan::get('meta_author') ?: '';
$googleSearchConsole = \App\Models\Pengaturan::get('google_search_console') ?: null;
$bingWebmaster = \App\Models\Pengaturan::get('bing_webmaster') ?: null;
$imageDefault = \App\Models\Pengaturan::get('image_default');
$defaultImageUrl = public_image_url($imageDefault) ?: $logoUrl;
$ogImageUrl = $defaultImageUrl ? asset($defaultImageUrl) : '';
$twitterImageUrl = $defaultImageUrl ? asset($defaultImageUrl) : '';
@endphp
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="p:domain_verify" content="b7916750cf2e49978a722617b66e9357"/>
<title>@yield('title', $seoTitle)</title>
<meta name="description" content="@yield('meta_description', $seoDescription)">
<meta name="keywords" content="{{ $seoKeywords }}">
<meta name="author" content="{{ $seoAuthor }}">
<meta name="robots" content="index, follow">
<meta name="google" content="notranslate">
<meta property="og:title" content="@yield('og_title', $seoTitle)">
<meta property="og:description" content="@yield('og_description', $seoDescription)">
<meta property="og:image" content="@yield('og_image', $ogImageUrl)">
<meta property="og:url" content="@yield('og_url', url()->current())">
<meta property="og:type" content="@yield('og_type', 'website')">
<meta property="og:site_name" content="{{ $seoTitle }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('twitter_title', $seoTitle)">
<meta name="twitter:description" content="@yield('twitter_description', $seoDescription)">
<meta name="twitter:image" content="@yield('twitter_image', $twitterImageUrl)">
<meta name="googlebot" content="index, follow">
<meta name="bingbot" content="index, follow">
@if($googleSearchConsole)
<meta name="google-site-verification" content="{{ $googleSearchConsole }}"/>
@endif
@if($bingWebmaster)
<meta name="msvalidate.01" content="{{ $bingWebmaster }}"/>
@endif
<link rel="icon" href="{{ $faviconUrl }}">
<link rel="shortcut icon" href="{{ $faviconUrl }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=block" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('vendor/remixicon/remixicon.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
@if($dir === 'rtl')
<style>
    body { direction: rtl; text-align: right; }
    .swiper { direction: ltr; }
</style>
@endif
@stack('styles')
</head>
<body class="bg-background text-on-surface font-body-md overflow-x-hidden">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KDQFLFMK"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

@include('template.V1.partials.header')

<main>
    @yield('content')
</main>

@include('template.V1.partials.footer')

@include('template.V1.partials.floating-whatsapp')

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Fancybox.bind('[data-fancybox="gallery"]', {
            slidesPerPage: 1,
            infinite: true,
            showThumbs: true,
            thumb: {
                width: 80,
                height: 80
            }
        });
    });
</script>
@stack('scripts')
</body>
</html>
