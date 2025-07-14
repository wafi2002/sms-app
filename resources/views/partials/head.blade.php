<meta charset="utf-8" />
<meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />


@props(['pageTitle'])


<title>@yield('title') |
    {{ config('variables.Name') ? config('variables.Name') : 'Name' }} -
    {{ config('variables.Suffix') ? config('variables.Suffix') : 'Suffix' }}
</title>

<meta name="description" content="{{ config('variables.Description') ? config('variables.Description') : '' }}" />
<meta name="keywords" content="{{ config('variables.Keyword') ? config('variables.Keyword') : '' }}">
<!-- laravel CRUD token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Canonical SEO -->
<meta property="og:title" content="{{ config('variables.ogTitle') ? config('variables.ogTitle') : '' }}" />
<meta property="og:type" content="{{ config('variables.ogType') ? config('variables.ogType') : '' }}" />
<meta property="og:image" content="{{ config('variables.ogImage') ? config('variables.ogImage') : '' }}" />
<meta property="og:description"
    content="{{ config('variables.Name') ? config('variables.Name') : '' }}{{ config('variables.Description') ? config('variables.Description') : '' }}" />
<meta property="og:site_name" content="{{ config('variables.creatorName') ? config('variables.creatorName') : '' }}" />
<link rel="canonical" href="{{ config('variables.productPage') ? config('variables.productPage') : '' }}">
<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="{{ asset('assets/image/favicon/favicon.ico') }}" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<!-- Include Styles -->
@include('partials.styles')

@livewireStyles
