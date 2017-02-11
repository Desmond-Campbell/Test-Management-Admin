@extends('layouts.app')

@section('page-title')
{{ ___( "People" )}}
@stop

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-9 col-md-offset-1">

			<h1>{{___( "People" )}}</h1>

			<div class="alert alert-info-light">
				{{___( "You can manage the people in your network here. You need to give them permissions at this level to do certain things, like viewing or creating projects." ) }}
			</div>

			<div class="panel panel-default">

				<div class="">

					<div layout="row">
						<div flex="20">
							@include('layouts.networks-nav')
						</div>
						<div flex="70" class="padding border-left">

							@if ( pass( 'people.create_person', $network->id ) ) <a href="/network/{{$network->id}}/person/new" class="btn btn-success">{{___( "Add a Person" )}} &nbsp;<i class="fa fa-user-plus"></i></a> @else <a href="javascript:;" class="btn btn-default" title="{{___("You do not have permission to add people.")}}" disabled="disabled">{{___( "Add a Person" )}} &nbsp;<i class="fa fa-user-plus"></i></a>  @endif <br /><br />

							<table class="table">

								<tr>

									<th>{{( "Name" )}}</th>
									<th>{{( "Membership Type" )}}</th>
									<th>&nbsp;</th>

								</tr>

							@foreach ( $people as $p )

								<tr>
									<td>{{$p->name}}</td>
									@if ( $p->is_owner ) <td><strong>{{___( "Owner" )}}</strong></td>
									@else <td>{{___( "Member" )}}</td> @endif
									<td><a href="/network/{{$network->id}}/person/{{$p->id}}/edit"><i class="fa fa-lock"></i> &nbsp;{{___( "Edit Access" )}}</a></td>
								</tr>

							@endforeach

							</table>

						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
@endsection
