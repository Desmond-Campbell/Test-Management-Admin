@extends('layouts.app')

@section('page-title')
{{ ___( "Add Someone to" )}} {{$network->name}}
@stop

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-9 col-md-offset-1">

			<h1>{{___( "Add Someone to" )}} {{$network->name}}</h1>

			<h5>{{___( "Enter the email address below for the person you want to add." ) }}</h5><br />

			<div class="panel panel-default push-down" ng-controller="NewPersonCtrl">

				<input type="hidden" id="network_id" value="{{$network->id}}" />

				<div class="panel-body">

					<form class="form" onbeforesubmit="return false">

						<div layout-gt-xs="row">

		          <md-input-container flex-gt-xs>

		            <label>{{__( "Email Address" )}}</label>
		            <input class="md-block input-lg md-no-underline" 
		                  id="person-email"
		                  ng-model="person.email"
		                  required />

		          </md-input-container>

		        </div>

		        <button type="submit" class="btn btn-success" ng-click="save()">{{__( "Add" )}}</button> 

		      </form>

				</div>

			</div>

		</div>
	</div>
</div>
@endsection

@section('javascript')
  <script src="/js/controllers/NewPersonCtrl.js"></script>
@stop