@extends('layouts.app')

@section('page-title')
{{ ___( "Networks" )}}
@stop

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-9 col-md-offset-1">

			<h1>{{___( "Networks" )}}</h1>

			<div class="alert alert-info-light">
				{{___( "A network is the space you create for your company or organisation. Inside your network, you can add people and projects, and manage tests for individual projects." ) }}
			</div>

			<div class="panel panel-default">

				<div class="panel-body">

					@if ( !$networks )
					<h3 class="no-margin-top">{{___( "You are currently not a part of any network." )}}</h3>

					<p><a href="/create-network">{{___( "Create a network" )}}</a></p>

					@else

					<div layout="row">
						<div flex="20">
							<ul class="list-group">
								<li class="list-group-item"><a href="/networks">View Networks</a></li>
								<li class="list-group-item"><a href="/create-network">Create Network</a></li>
							</ul>
						</div>
						<div flex="5"></div>
						<div flex="70">

							<table class="table">

								<tr>

									<th>{{( "Network Name" )}}</th>
									<th>{{( "Membership Type" )}}</th>
									<th>&nbsp;</th>

								</tr>

							@foreach ( $networks as $n )

								<tr>
									<td>{{$n->name}}</td>
									@if ( $n->is_owner ) <td><strong>{{___( "Owner" )}}</strong></td>
									@else <td>{{___( "Member" )}}</td> @endif
									<td>@if ( $n->owner ) <a href="/network/{{$n->id}}">{{___( "Manage" )}}</a> @endif <a href="{{$n->url}}">{{___( "Visit" )}}</a></td>
								</tr>

							@endforeach

							</table>

						</div>
					</div>

					@endif

				</div>
			</div>
		</div>
	</div>
</div>
@endsection
