@extends ('layouts.admin-default')

@section ('page_title', 'Settings')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Settings</h1>
		<ol class="breadcrumb">
			<li class="active">
				Settings
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-lg-6 col-xs-12">
		<!-- Name & Logo -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Name & Logo
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/settings/', 'onsubmit' => 'return ConfirmSubmit()', 'files' => 'true')) }}
					<div class="form-group">
						{{ Form::label('org_name','Name',array('id'=>'','class'=>'')) }}
						{{ Form::text('org_name', $settings->where('setting', 'org_name')->first()->value ,array('id'=>'','class'=>'form-control')) }}
					</div>
					<div class="form-group">
						@if (trim($settings->where('setting', 'org_logo')->first()->value) != '')
							<img class="img img-responsive" src="{{ $settings->where('setting', 'org_logo')->first()->value }}" />
						@endif
						{{ Form::label('org_logo','Logo',array('id'=>'','class'=>'')) }}
						{{ Form::file('org_logo',array('id'=>'','class'=>'form-control')) }}
					</div>
					 <div class="form-group">
						@if (trim($settings->where('setting', 'org_favicon')->first()->value) != '')
							<img class="img img-responsive" src="{{ $settings->where('setting', 'org_favicon')->first()->value }}" />
						@endif
						{{ Form::label('org_favicon','Favicon',array('id'=>'','class'=>'')) }}
						{{ Form::file('org_favicon',array('id'=>'','class'=>'form-control')) }}
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				{{ Form::close() }}
			</div>
		</div>
		<!-- Main -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Main
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th>Setting</th>
								<th>Value</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($settings as $setting)
								@if (
									$setting->setting != 'about_main' &&
									$setting->setting != 'about_short' &&
									$setting->setting != 'about_our_aim' &&
									$setting->setting != 'about_who' &&
									$setting->setting != 'terms_and_conditions' && 
									$setting->setting != 'org_name' && 
									$setting->setting != 'org_logo' &&
									$setting->setting != 'org_favicon' &&
									$setting->setting != 'currency' &&
									$setting->setting != 'social_facebook_page_access_token'
								)
									<tr>
										{{ Form::open(array('url'=>'/admin/settings/', 'onsubmit' => 'return ConfirmSubmit()')) }}
											<td>
												{{ ucwords(str_replace("_"," ",$setting->setting)) }}<br>
												@if ($setting->description != null)
													<small>{{ $setting->description }}</small>
												@endif
											</td>
											<td>
												{{ Form::text($setting->setting, $setting->value ,array('id'=>'setting','class'=>'form-control')) }}
											</td>
											<td>
												<button type="submit" class="btn btn-default btn-sm btn-block">Update</button>
											</td>
										{{ Form::close() }}
										{{ Form::open(array('url'=>'/admin/settings/', 'onsubmit' => 'return ConfirmDelete()')) }}
											<td width="15%">
												@if (!$setting->default)
													{{ Form::hidden('_method', 'DELETE') }}
													<button type="submit" class="btn btn-danger btn-sm btn-block">Delete</button>
												@endif
											</td>
										{{ Form::close() }}
									</tr>
								@endif
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- Terms & Conditions -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-info-circle fa-fw"></i> Terms and Conditions
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/settings/', 'onsubmit' => 'return ConfirmSubmit()')) }}
					<div class="form-group">
						{{ Form::label('registration_terms_and_conditions','Registration',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('registration_terms_and_conditions', $settings->where('setting', 'registration_terms_and_conditions')->first()->value ,array('id'=>'','class'=>'form-control wysiwyg-editor')) }}
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				{{ Form::close() }}
				<hr>
				{{ Form::open(array('url'=>'/admin/settings/', 'onsubmit' => 'return ConfirmSubmit()')) }}
					<div class="form-group">
						{{ Form::label('purchase_terms_and_conditions','Purchase',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('purchase_terms_and_conditions', $settings->where('setting', 'purchase_terms_and_conditions')->first()->value ,array('id'=>'','class'=>'form-control wysiwyg-editor')) }}
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				{{ Form::close() }}
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-xs-12">
		<!-- Payment Gateways -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-info-circle fa-fw"></i> Payment Gateways
			</div>
			<div class="panel-body">
				<div class="row">
					@foreach ($supportedPaymentGateways as $gateway)
						<div class="col-sm-6 col-xs-12">
							<h4>{{ ucfirst($gateway) }}</h4>
							@if (in_array($gateway, $activePaymentGateways))
								{{ Form::open(array('url'=>'/admin/settings/payments/' . $gateway . '/disable')) }}
									<button type="submit" class="btn btn-block btn-danger">Disable</button>
								{{ Form::close() }}
							@else
								{{ Form::open(array('url'=>'/admin/settings/payments/' . $gateway . '/enable')) }}
									<button type="submit" class="btn btn-block btn-success">Enable</button>
								{{ Form::close() }}
							@endif
						</div>
					@endforeach
				</div>
			</div>
		</div>
		<!-- Credit System -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-info-circle fa-fw"></i> Credit System
			</div>
			<div class="panel-body">
				<p>The Credit System is used to award participants with credit they can use to buy things from the shop and events. It can be award for buying tickets, attending events, participanting/winning tournaments or manually assigned.</p>
				@if ($isCreditEnabled)
					{{ Form::open(array('url'=>'/admin/settings/credit/disable')) }}
						<button type="submit" class="btn btn-block btn-danger">Disable</button>
					{{ Form::close() }}
				@else
					{{ Form::open(array('url'=>'/admin/settings/credit/enable')) }}
						<button type="submit" class="btn btn-block btn-success">Enable</button>
					{{ Form::close() }}
				@endif
			</div>
		</div>
		<!-- Social Media -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-info-circle fa-fw"></i> Social Media
			</div>
			<div class="panel-body">
				<p><small>Link Social Media your social media accounts to publish posts and pictures from the Lan Manager</small></p>
				<div class="row">
					<div class="col-xs-12 col-md-6">
						<h4>Facebook</h4>
						@if ($facebookCallback != null)
							<a href="{{ $facebookCallback }}">
								<button type="button" class="btn btn-block btn-success">Link Account</button>
							</a>
						@else
							{{ Form::open(array('url'=>'/admin/settings/unlink/facebook')) }}
								{{ Form::hidden('_method', 'DELETE') }}
								<button type="submit" class="btn btn-block btn-danger">Unlink Account</button>
							{{ Form::close() }}
						@endif
					</div>
					<div class="col-xs-12 col-md-6">
						<h4>Twitter <small>Coming soon</small></h4>
						{{ Form::open(array('url'=>'/admin/settings/link/twitter')) }}
							<button type="submit" class="btn btn-block btn-success" disabled>Link Account</button>
						{{ Form::close() }}
					</div>
				</div>
			</div>
		</div>
		<!-- About -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-info-circle fa-fw"></i> About
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/settings', 'onsubmit' => 'return ConfirmSubmit()')) }}
					<div class="form-group">
						{{ Form::label('about_main','Main',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('about_main', $settings->where('setting', 'about_main')->first()->value ,array('id'=>'','class'=>'form-control wysiwyg-editor')) }}
					</div>
					<div class="form-group">
						{{ Form::label('about_short','Short',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('about_short', $settings->where('setting', 'about_short')->first()->value ,array('id'=>'','class'=>'form-control wysiwyg-editor')) }}
					</div>
					<div class="form-group">
						{{ Form::label('about_our_aim','Our Aim',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('about_our_aim', $settings->where('setting', 'about_our_aim')->first()->value ,array('id'=>'','class'=>'form-control wysiwyg-editor')) }}
					</div>
					<div class="form-group">
						{{ Form::label('about_who','Who' ,array('id'=>'','class'=>'')) }}
						{{ Form::textarea('about_who', $settings->where('setting', 'about_who')->first()->value ,array('id'=>'','class'=>'form-control wysiwyg-editor')) }}
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				{{ Form::close() }}
			</div>
		</div>
		<!-- Currency -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-money fa-fw"></i> Currency
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/settings/', 'onsubmit' => 'return ConfirmSubmit()')) }}
					<h4>Currency</h4>
					<div class="form-group">
						{{ Form::label('currency','Currency',array('id'=>'','class'=>'')) }}
						{{ Form::select('currency', ['GBP' => 'GBP', 'USD' => 'USD', 'EUR' => 'EUR'], null, array('id'=>'venue','class'=>'form-control')) }}
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				{{ Form::close() }}
			</div>
		</div>
		 <!-- Misc -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Misc
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/settings/generate/qr', 'onsubmit' => 'return ConfirmSubmit()')) }}
					<button type="submit" class="btn btn-danger btn-sm btn-block">Re generate QR Codes</button>
				{{ Form::close() }}
				@foreach (config() as $config)
					{{ dd($config) }}
				@endforeach
			</div>  
		</div>
	</div>
</div>
 
@endsection