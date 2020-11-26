@extends('default.layout')
@section('content')

<div class="row">
	<div class="container">

		@if(session()->has('message'))
		<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
			<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
		</div>
		@endif

		
		<div class="col s12">
			<h4 class="center-align">Nova Devolução</h4>
			<form method="post" enctype="multipart/form-data" action="/devolucao/new">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="col s10">
					<div class="file-field input-field">
						<div class="btn red">
							<span>XML</span>
							<input accept=".xml" name="file" type="file">
						</div>
						<div class="file-path-wrapper">
							<input class="file-path validate" type="text">
						</div>
					</div>
				</div>
				<div class="s2">
					<input class="btn-large red" type="submit" value="OK">
				</div>
			</form>
		</div>
	</div>

</div>
@endsection	