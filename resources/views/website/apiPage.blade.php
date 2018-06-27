@extends('layouts.website.layout')


@section('page_css')

@endsection


@section('content')
<div class="container pageContainer">
    <passport-clients></passport-clients>
    <passport-authorized-clients></passport-authorized-clients>
    <passport-personal-access-tokens></passport-personal-access-tokens>
</div>
@endsection


@section('page_js')
    <script type="text/javascript">
        var CSRF_TOKEN = "{{ csrf_token() }}";
        $(document).ready(function(e){
            console.log("Api Page Loaded...");
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        });
    </script>
@endsection