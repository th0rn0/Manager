<!DOCTYPE html>
<html lang="en" class="full-height">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/png" sizes="32x32" href="{{ Settings::getOrgFavicon() }}">
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css' />
		<link href="/css/app.css?v={{ Helpers::getCssVersion() }}" rel=stylesheet />
		
		{!! Analytics::render() !!}
		
		<title>
			@hasSection ('page_title')
				@yield ('page_title') | {{ Settings::getOrgName() }}
			@else
				{{ Settings::getOrgTagline() }} | {{ Settings::getOrgName() }}
			@endif
		</title>
	</head>
	<body class="full-height">
		@include ('layouts._partials.navigation')
		<div class="container" style="margin-top:30px;">
			<div class='row'>
				@foreach (['danger', 'warning', 'success', 'info'] as $msg)
					@if (Session::has('alert-' . $msg))
						<div class="col-xs-12" style="margin-top:30px; margin-bottom:-40px;">
							<p class="alert alert-{{ $msg }}">
								<b>{{ Session::get('alert-' . $msg) }}</b> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							</p>
						</div>
					@endif
				@endforeach
				@if (isset($errors) && $errors->any())
					<div class="col-xs-12" style="margin-top:30px; margin-bottom:-40px;">
						<div class="alert alert-danger">
							<ul class="list-unstyled">
								@foreach ($errors->all() as $error)
									<li><strong>{{ $error }}</strong></li>
								@endforeach
							</ul>
						</div>
					</div>
				@endif
			</div>
		</div>
		@yield ('content')
		<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
		<br>
		<div class="stats  section-padding">
			<div class="container">
				<div class="row">
					<div class="col-md-4  text-center">
						<div class="stats-number">
							{{ Helpers::getEventTotal() }}
						</div>
						<hr />
						<div class="stats-title">
							EVENTs we've hosted
						</div>
					</div>

					<div class="col-md-4  text-center">
						<div class="stats-number">
							{{ Helpers::getEventParticipantTotal() }}
						</div>
						<hr />
						<div class="stats-title">
							PLAYERs we've entertained
						</div>
					</div>

					<div class="col-md-4  text-center">
						<div class="stats-number">
							A LOT
						</div>
						<hr />
						<div class="stats-title">
							{{ Settings::getFrontpageAlotTagline() }}
						</div>
					</div>
				</div>
			</div>
		</div>
		<footer class="footer">
			<div class="container">
				<div class="hidden-xs hidden-sm">
					<br><br>
				</div>
				<div class="col-lg-4 hidden-md hidden-sm hidden-xs">
					<img class="img-responsive" src="{{ Settings::getOrgLogo() }}">
				</div>
				<div class="col-lg-8 col-sm-12 col-md-12 text-center">
					<div class="col-lg-6 col-md-6">
						<h2 class="">Links</h2>
						<p class=" hidden"><a href="/contact">Contact Us</a></p>
						<p class=""><a href="/news">News</a></p>
						<p class=""><a href="/terms">Terms & Conditions</a></p>
						<p class=""><a href="/about">About Us</a></p>
						<p class=""><a href="/polls">Polls</a></p>
						<p class=" hidden">Lan Guide</p>
					</div>
					<div class="col-lg-6 col-md-6">
						<h2 class="">Connect</h2>
						@if (Settings::getFacebookLink() != "")
							<p class=""><a target="_blank" href="{{ Settings::getFacebookLink() }}">Facebook</a></p>
						@endif
						@if (Settings::getDiscordLink() != "")
							<p class=""><a target="_blank" href="{{ Settings::getDiscordLink() }}">Discord</a></p>
						@endif
						@if (Settings::getDiscordLink() != "")
							<p class=""><a target="_blank" href="{{ Settings::getSteamLink() }}">Steam</a></p>
						@endif
					</div>
					<div class="col-lg-12">
						<p class="">© {{ Settings::getOrgName() }} {{ date("Y") }}. All rights reserved.</p>
					</div>
				</div>
				<div class="col-lg-12 text-center">
					<p class="">Powered By <a href="https://eventula.com">Eventula Event Manager</a></p>
				</div>
			</div>
		</footer>
	</body>
</html>
