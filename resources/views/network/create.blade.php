@extends('layouts.app')

@section('page-title')
{{ ___( "Create a Network" )}}
@stop

@section('styles')
  <link rel="stylesheet" href="/js/vendor/angular-timezone-selector/angular-timezone-selector.css">
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

				<div class="panel-body" ng-show="!newnetwork.url && !working">

					<form class="form" onbeforesubmit="return false">

						<div layout-gt-xs="row">

		          <md-input-container flex-gt-xs>

		            <timezone-selector ng-model="network.timezone"></timezone-selector>

		          </md-input-container>

		        </div>

		        <div layout-gt-xs="row">

		          <md-input-container flex-gt-xs>

		            <label>{{__( "Plan" )}}</label>
		            
		            <md-select ng-model="network.plan" required>
		            	<md-option value="Basic">{{___( "Basic" )}} - <em>{{___( "Free for beta period, until further notice." )}}</em></md-option>
		            </md-select>

		            <small class="help-block" ng-show="network.plan == 'Basic'">
		            	{{___( "Beta users will get a special discount when we start to charge for the service." )}}
		            </small>

		          </md-input-container>

		        </div>

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

				<div class="panel-body" ng-show="newnetwork.url && !working">

					<h3>{{___( "Congratulations! Your new network is ready." )}}</h3>

					<h4>{{___( "Click the following link to enter your network: " )}}<br /><br /><a href="@{{newnetwork.url}}">@{{newnetwork.url}}</a></h4>
				
				</div>

				<div class="panel-body" ng-show="working">
					<h4>{{___( "Creating network... Please wait." )}}</h4>
				</div>

			</div>
		</div>
	</div>
</div>
@endsection

@section('javascript')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.0/moment-timezone-with-data.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jstimezonedetect/1.0.6/jstz.min.js"></script>
  <script src="/js/controllers/CreateCtrl.js"></script>
@stop