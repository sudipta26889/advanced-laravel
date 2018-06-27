<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', env('APP_NAME')) }}</title>

<!-- Styles -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet"> 
<link href="{{ asset('website/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('website/css/style.css') }}" rel="stylesheet">

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">


<!-- Fonts -->
<link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

@yield('page_css')