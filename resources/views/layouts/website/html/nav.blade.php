<?php 
    $user = null; 
    $currentRouteName = \Route::getCurrentRoute()->getName();
?>
@if(Auth::check())
  <?php $user = Auth::user(); ?>
@endif
@if (Route::has('login'))
    <div class="top-right links">
        <a href="{{ route('landing') }}">Home</a>
        <a href="{{ route('switch_website') }}">Switch</a>
        @auth
            <a href="{{ route('admin::dashboard') }}">Dashboard</a>
            <a href="{{ route('api_page') }}">API</a>
            <a href="{{ route('logout') }}">Logout</a>
        @else
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        @endauth
    </div>
@endif