@if (isset($seo))
    <meta name="description" content="{{ $seo['description'] }}"/>
    <meta name="keywords" content="{{ $seo['keywords'] }}"/>
    <title>{{ $seo['title'] }}</title>
@endif