@extends('layouts.admin.layout')


@section('page_css')

@endsection

@section('content')
<div class="container-fluid">
    <div class="block-header">
        <h2>
            Edit Admin Profile
            <small>You can change your password, profile picture etc. here.</small>
        </h2>
    </div>
    <!-- Basic Validation -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>{{ $user->name }}'s Profile</h2>
                </div>
                <div class="body">
                    <form id="form_validation" class="demo-masked-input" method="POST">
                        <div class="form-group form-float">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">person</i>
                                </span>
                                <div class="form-line">
                                    <input type="text" class="form-control" placeholder="Name. Ex: Administrator" required value="{{ $user->name }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">email</i>
                                </span>
                                <div class="form-line">
                                    <input type="text" class="form-control email" placeholder="Email. Ex: example@example.com" required value="{{ $user->email }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="radio" name="gender" id="male" class="with-gap">
                            <label for="male">Male</label>

                            <input type="radio" name="gender" id="female" class="with-gap">
                            <label for="female" class="m-l-20">Female</label>

                            <input type="radio" name="gender" id="others" class="with-gap">
                            <label for="others" class="m-l-20">Others</label>
                        </div>
                        <div class="form-group form-float">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-line">
                                        <input type="password" class="form-control" name="password" required>
                                        <label class="form-label">Password</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-line">
                                        <input type="password" class="form-control" name="confirm_password" required>
                                        <label class="form-label">Confirm Password</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Validation -->
</div>
@endsection


@section('page_js')
<script type="text/javascript">
    var CSRF_TOKEN = "{{ csrf_token() }}";
    $(document).ready(function(e){
        console.log("Admin Edit Profile Page Loaded...");
        CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    });
</script>
@endsection