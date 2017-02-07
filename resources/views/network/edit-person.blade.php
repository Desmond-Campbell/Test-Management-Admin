@extends('layouts.app')

@section('page-title')
{{ ___( "Edit Access" )}}
@stop

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-9 col-md-offset-1" ng-controller="EditAccessCtrl">

			<h1>{{___( "Edit Access" )}}</h1>

			<div class="alert alert-info-light">{{___( "You can edit permissions for people in your network, allowing them to manipulate other people, create and view projects. Permissions on each project are set from inside the project itself." )}}</div>

			<div class="panel panel-default push-down">

				<input type="hidden" id="network_id" value="{{$network->id}}" />
    		<input type="hidden" id="person_id" value="{{$person_id}}" />

    		<div class="padding border-bottom">
    			<h3 class="no-margins">@{{person.name}}</h3>
    			<button class="btn btn-danger push-right" style="margin-top:-33px;" ng-click="removePerson()"><i class="fa fa-ban"></i> &nbsp;{{___( "Remove" )}} @{{person.name}}</button>
    		</div>

				<div class="">

					<div layout="row">
						<div flex="20">

							<ul class="nav nav-stacked">
								<li role="presentation"><a href="/network/{{$network->id}}/people"><i class="fa fa-group"></i> &nbsp;{{___( "View Everyone" )}}</a></li>
								<li role="presentation"><a href="/network/{{$network->id}}/person/new"><i class="fa fa-user-plus"></i> &nbsp;{{___( "Add Someone" )}}</a></li>
							</ul>

							<hr />

							@include('layouts.networks-nav')
						</div>
						<div flex="70" class="padding border-left">

							<div class="padding">
								<div ng-show="dirty_permissions">
		              <button class="btn btn-success btn-sm" ng-click="savePermissions()">{{___( "Save Changes" )}}</button>
		              &nbsp;
		              <button class="btn btn-danger btn-sm" ng-click="getPerson()">{{___( "Undo" )}}</button><br /><br />
		            </div>

		            <fieldset class="demo-fieldset" >
		              <div layout="row" layout-wrap flex>
		                <div flex-xs flex="50" ng-show="permissions.length > 5">
		                  <md-checkbox aria-label="{{___( "Select All" )}}"
		                               ng-checked="isChecked()"
		                               md-indeterminate="isIndeterminate()"
		                               ng-click="toggleAll()">
		                    <span ng-show="isChecked()">{{___( "Clear Selection" )}}</span>
		                    <span ng-show="!isChecked()">{{___( "Select All" )}}</span>
		                  </md-checkbox>
		                </div>
		                <div class="demo-select-all-checkboxes perm-list-item" flex="100" ng-repeat="p in permissions">
		                  <md-checkbox ng-checked="exists(p, selected)" ng-click="toggle(p, selected)" aria-label=".">
		                   @{{ permission_info[p].name }} &nbsp; <span class="small-description">@{{ permission_info[p].description }}</span>
		                  </md-checkbox>
		                </div>
		              </div>
		            </fieldset>

		          </div>

						</div>
					
					</div>

				</div>

			</div>

		</div>
	</div>
</div>
@endsection

@section('javascript')
  <script src="/js/controllers/EditAccessCtrl.js"></script>
@stop