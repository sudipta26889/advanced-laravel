@extends('layouts.website.layout')


@section('page_css')
<style type="text/css">
    .content {
        text-align: center;
    }
</style>
@endsection


@section('content')
    <div class="title m-b-md">
        {{ $siteName }}
    </div>

    <div class="links">
        <a href="{{ route('api_page') }}">Api</a>
        @if(!Auth::check())
        <a href="{{ route('change_site', 1) }}">Prod Website</a>
        <a href="{{ route('change_site', 2) }}">Dev Website</a>
        @else
            @if($entity->id == 1)
            <a href="{{ route('change_site', 1) }}">Prod Website</a>
            @endif
            @if($entity->id == 2)
            <a href="{{ route('change_site', 2) }}">Dev Website</a>
            @endif
        @endif
    </div>
@endsection


@section('page_js')
    <script type="text/javascript">
        var CSRF_TOKEN = "{{ csrf_token() }}";
        $(document).ready(function(e){
            console.log("Landing Page Loaded...");
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        });
    </script>
@endsection