<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @include('layouts.website.html.head')
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @include('layouts.website.html.header')
            <div id="app" class="content">
                @yield('content')
            </div>
        </div>
        @include('layouts.website.html.footer')
    </body>
</html>