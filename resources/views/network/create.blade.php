@extends('layouts.app')

@section('page-title')
{{ ___( "Create a Network" )}}
@stop

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-9 col-md-offset-1">

			<h1>{{___( "Create a Network" )}}</h1>

			<div class="alert alert-info-light">
				{{___( "A network is the space you create for your company or organisation. Inside your network, you can add people and projects, and manage tests for individual projects." ) }}
			</div>

			<div class="panel panel-default" ng-controller="CreateCtrl">

				<div class="panel-body" ng-show="!newnetwork.url">

					<form class="form" onbeforesubmit="return false">

		        <div layout-gt-xs="row">

		          <md-input-container flex-gt-xs>

		            <label>{{__( "Network Name" )}}</label>
		            <input class="md-block input-lg md-no-underline" 
		                  id="network-name"
		                  ng-model="network.name"
		                  maxlength="72"
		                  required />

		          </md-input-container>

		        </div>

		        <div layout-gt-xs="row">

		          <md-input-container flex-gt-xs>

		            <label>{{__( "Domain" )}}</label>
		            <input class="md-block input-lg md-no-underline" 
		                  id="network-domain"
		                  ng-model="network.domain"
		                  maxlength="64"
		                  ng-change="slugifyDomain()"
		                   />

		          </md-input-container>

		        </div>

		        <h5>{{___( "You will access your network using" )}} http://<strong>@{{network.domain}}</strong>.{{env('APP_DOMAIN')}}/</h5><br />

		        <button type="submit" class="btn btn-success" ng-click="save()">{{__( "Create" )}}</button> 

		      </form>

				</div>

				<div class="panel-body" ng-show="newnetwork.url">

					<h3>{{___( "Congratulations! Your new network is ready." )}}</h3>

					<h5>{{___( "Link: " )}}<a href="@{{newnetwork.url}}">@{{newnetwork.url}}</a></h5>
				
				</div>

			</div>
		</div>
	</div>
</div>
@endsection
