@extends ('layouts.admin-default')

@section ('page_title', $venue->name)

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Venues</h1>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/venues">Venues</a>
			</li>
			<li class="active">
				{{ $venue->display_name }}
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-8">

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Settings
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/venues/' . $venue->slug, 'files' => 'true')) }}
					@if ($errors->any())
					  	<div class="alert alert-danger">
					        <ul>
					          	@foreach ($errors->all() as $error)
					            	<li>{{ $error }}</li>
					          	@endforeach
					        </ul>
					  	</div>
					@endif
					<div class="form-group">
						{{ Form::label('name','Venue Name',array('id'=>'','class'=>'')) }}
						{{ Form::text('name', $venue->display_name,array('id'=>'name','class'=>'form-control')) }}
					</div>
					<div class="row">
						<div class="col-lg-6 col-sm-12 form-group">
							{{ Form::label('address_1','Address Line 1',array('id'=>'','class'=>'')) }}
							{{ Form::text('address_1', $venue->address_1,array('id'=>'address_1','class'=>'form-control')) }}
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							{{ Form::label('address_2','Address Line 2',array('id'=>'','class'=>'')) }}
							{{ Form::text('address_2', $venue->address_2,array('id'=>'address_2','class'=>'form-control')) }}
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-sm-12 form-group">
							{{ Form::label('address_street','Address Street',array('id'=>'','class'=>'')) }}
							{{ Form::text('address_street', $venue->address_street,array('id'=>'address_street','class'=>'form-control')) }}
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							{{ Form::label('address_city','Address City',array('id'=>'','class'=>'')) }}
							{{ Form::text('address_city', $venue->address_city,array('id'=>'address_city','class'=>'form-control')) }}
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-sm-12 form-group">
							{{ Form::label('address_postcode','Address Postcode',array('id'=>'','class'=>'')) }}
							{{ Form::text('address_postcode', $venue->address_postcode,array('id'=>'address_postcode','class'=>'form-control')) }}
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							{{ Form::label('address_country','Address Country',array('id'=>'','class'=>'')) }}
							{{ Form::text('address_country', $venue->address_country,array('id'=>'address_country','class'=>'form-control')) }}
						</div>
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				{{ Form::close() }}
				<hr>
				{{ Form::open(array('url'=>'/admin/venues/' . $venue->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
					{{ Form::hidden('_method', 'DELETE') }}
					<button type="submit" class="btn btn-danger">Delete</button>
				{{ Form::close() }}
			</div>  
		</div>

	</div>
	<div class="col-lg-4">

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-plus fa-fw"></i> Add Images
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/venues/' . $venue->slug, 'files' => 'true')) }}
					<div class="form-group">
						{{ Form::label('images','Venue Images',array('id'=>'','class'=>'')) }}
						{{ Form::file('images[]',array('id'=>'images[]','class'=>'form-control', 'multiple'=>'multiple')) }}
					</div>
					<button type="submit" class="btn btn-default">Upload</button>
				{{ Form::close() }}
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-image fa-fw"></i> Images
			</div>
			<div class="panel-body">
				@foreach ($venue->images as $image)
					<div class="row">
						<div class="col-lg-12 col-sm-12">
							<img class="img img-responsive" src="{{ $image->path }}" />
						</div>
						<div class="col-lg-12 col-sm-12">
							<br>
							{{ Form::open(array('url'=>'/admin/venues/' . $venue->slug . '/' . $image->id, 'files' => 'true')) }}
								<div class="form-group">
									{{ Form::label('description','Description',array('id'=>'','class'=>'')) }}
									{{ Form::textarea('description', $image->description,array('id'=>'description','class'=>'form-control', 'rows'=>'1')) }}
								</div>
								<button type="submit" class="btn btn-default">Submit</button>
							{{ Form::close() }}
							<hr>
							{{ Form::open(array('url'=>'/admin/venues/' . $venue->slug . '/' . $image->id, 'onsubmit' => 'return ConfirmDelete()')) }}
								{{ Form::hidden('_method', 'DELETE') }}
								<button type="submit" class="btn btn-danger">Delete</button>
							{{ Form::close() }}
						</div>
					</div>
					<hr>
				@endforeach
			</div>
		</div>

	</div>
</div>

@endsection