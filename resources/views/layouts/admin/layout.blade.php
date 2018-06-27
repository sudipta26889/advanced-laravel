<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @include('layouts.admin.html.head')
</head>

<body class="theme-red">
    @include('layouts.admin.html.nav')

    <section class="content">
        @yield('content')
    </section>

    @include('layouts.admin.html.footer')
</body>

</html>