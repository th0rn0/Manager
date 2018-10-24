@extends ('layouts.admin-default')

@section ('page_title', 'Venues')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Venues</h1>
		<ol class="breadcrumb">
			<li class="active">
				Venues
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-8">

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-th-list fa-fw"></i> Venues
			</div>
			<div class="panel-body">
				<div class="dataTable_wrapper">
					<table width="100%" class="table table-striped table-hover" id="dataTables-example">
						<thead>
							<tr>
								<th>Name</th>
								<th>Address</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($venues as $venue)
								<tr class="table-row" data-href="/admin/venues/{{ $venue->slug }}" class="odd gradeX">
									<td>{{ $venue->display_name }}</td>
									<td>
										<address>
										<strong>{{ $venue->address_1 }}</strong>,<br>
											@if ($venue->address_2){{ $venue->address_2 }},<br>@endif
											@if ($venue->address_street){{ $venue->address_street }},<br>@endif
											@if ($venue->address_city){{ $venue->address_city }},<br>@endif
											@if ($venue->address_postcode){{ $venue->address_postcode }},<br>@endif
											@if ($venue->address_country){{ $venue->address_country }}@endif
										</address>
									</td>
									<td width="15%">
										<a href="/admin/venues/{{ $venue->slug }}"><button type="button" class="btn btn-primary btn-sm btn-block">Edit</button></a>
									</td>
									<td width="15%">
										{{ Form::open(array('url'=>'/admin/venues/' . $venue->slug, 'onsubmit' => 'return ConfirmDelete()')) }}
											{{ Form::hidden('_method', 'DELETE') }}
											<button type="submit" class="btn btn-danger btn-sm btn-block">Delete</button>
										{{ Form::close() }}
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>  
		</div>

	</div>
	<div class="col-lg-4">

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-plus fa-fw"></i> Add Venue
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/venues', 'files' => 'true')) }}
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
						{{ Form::text('name', '',array('id'=>'name','class'=>'form-control')) }}
					</div>
					<div class="form-group">
						{{ Form::label('address_1','Address Line 1',array('id'=>'','class'=>'')) }}
						{{ Form::text('address_1', '',array('id'=>'address_1','class'=>'form-control')) }}
					</div>
					<div class="form-group">
						{{ Form::label('address_2','Address Line 2',array('id'=>'','class'=>'')) }}
						{{ Form::text('address_2', '',array('id'=>'address_2','class'=>'form-control')) }}
					</div>
					<div class="row">
						<div class="col-lg-6 col-sm-12 form-group">
							{{ Form::label('address_street','Address Street',array('id'=>'','class'=>'')) }}
							{{ Form::text('address_street', '',array('id'=>'address_street','class'=>'form-control')) }}
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							{{ Form::label('address_city','Address City',array('id'=>'','class'=>'')) }}
							{{ Form::text('address_city', '',array('id'=>'address_city','class'=>'form-control')) }}
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-sm-12 form-group">
							{{ Form::label('address_postcode','Address Postcode',array('id'=>'','class'=>'')) }}
							{{ Form::text('address_postcode', '',array('id'=>'address_postcode','class'=>'form-control')) }}
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							{{ Form::label('address_country','Address Country',array('id'=>'','class'=>'')) }}
							{{ Form::text('address_country', '',array('id'=>'address_country','class'=>'form-control')) }}
						</div>
					</div>
					<div class="form-group">
						{{ Form::label('images','Venue Images',array('id'=>'','class'=>'')) }}
						{{ Form::file('images[]',array('id'=>'images[]','class'=>'form-control', 'multiple'=>'multiple')) }}
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				{{ Form::close() }}
			</div>
		</div>

	</div>
</div>

@endsection