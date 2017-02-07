@extends('layouts.app')

@section('page-title')
{{ ___( "Access Denied" )}}
@stop

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-9 col-md-offset-1">

			<h1>{{___( "Access Denied" )}}</h1>

			<br />

			<div class="alert alert-danger">
				<h4>{{___( "Sorry, you're not allowed to access this page." ) }}</h4>
			</div>
			
		</div>
	</div>
</div>
@endsection
