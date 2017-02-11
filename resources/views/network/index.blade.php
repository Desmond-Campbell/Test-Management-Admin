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
				<big>{{___( "A network is the space you create for your company or organisation. Inside your network, you can add people and projects, and manage tests for individual projects." ) }}</big>
			</div>

			<div class="panel panel-default">

				<div class="padding">

					@if ( !$networks )
					<h3 class="no-margin-top">{{___( "You are currently not a part of any network." )}}</h3>

					<p><a href="/create-network">{{___( "Create a network" )}}</a></p>

					@else

					<div layout="row">
						
						<div flex="80">

						<a href="/create-network" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;{{___( "Create a Network" )}}</a>

							<br /><br />

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
									<td align="right">@if ( $n->is_owner ) <a href="/network/{{$n->id}}/edit"><i class="fa fa-gear"></i> &nbsp;{{___( "Manage" )}}</a>  &nbsp; &nbsp; @endif @if ( $n->is_owner || pass( 'people.view_people', $n->id ) ) <a href="/network/{{$n->id}}/people"><i class="fa fa-group"></i> &nbsp;{{___( "People" )}}</a> @endif &nbsp; &nbsp; <a href="{{$n->url}}"><i class="fa fa-paper-plane"></i> &nbsp;{{___( "Visit" )}}</a></td>
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
