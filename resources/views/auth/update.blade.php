@extends('layouts.app')

@section('page-title')
{{___( "Update Account" )}}
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2" ng-controller="EditAccountCtrl">
            <div class="panel panel-default">
                <div class="panel-heading">{{___("Update Account")}}</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" onbeforesubmit="return false">

                    <div class="alert alert-warning" ng-show="working">{{___("Attempting to update your account. Please wait.")}}</div>
                    <div class="alert alert-danger" ng-show="failure" ng-bind="error"></div>
                    <div class="alert alert-success" ng-show="success">{{___("Your account was updated successfully.")}}</div>

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">{{___("Name")}}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" ng-model="account.name" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">{{___("E-Mail Address")}}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" ng-model="account.email" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-md-4 control-label">{{___("Current Password")}}</label>

                            <div class="col-md-6">
                                <input id="current_password" type="password" class="form-control" name="current_password" ng-model="account.current_password" required autofocus>
                            </div>
                        
                        </div>

                        <div class="alert-info-inverse alert">
                        {{___("Leave the following blank if you wish not to change your password.")}}
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-md-4 control-label">{{___("New Password")}}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" ng-model="account.password">

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">{{___("Confirm Password")}}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" ng-model="account.password2">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary" ng-click="update()">
                                    {{___("Update")}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="/js/controllers/EditAccountCtrl.js"></script>
@stop