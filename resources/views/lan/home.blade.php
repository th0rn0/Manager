@extends ('layouts.default')

@section ('page_title', $event->display_name . ' - Lans in South Yorkshire')

@section ('content')
			
<div class="container">

	<div class="page-header">
		<h1>Welcome to {{ $event->display_name }}!</h1> 
	</div>
	<div class="text-center">
		<nav class="navbar navbar-default" style="z-index: 1;">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div id="navbar" class="navbar-collapse collapse" style="text-align:center;">
					<ul class="nav navbar-nav" style="display: inline-block; float: none;">
						<!--<li style="font-size:15px; font-weight:bold;"><a href="#food">Food Orders</a></li>-->
						<li style="font-size:15px; font-weight:bold;"><a href="#event">What's on</a></li>
						<li style="font-size:15px; font-weight:bold;"><a href="#seating">Seating</a></li>
						<li style="font-size:15px; font-weight:bold;"><a href="#attendees">Attendees</a></li>
						@if (!$event->tournaments->isEmpty())
							<li style="font-size:15px; font-weight:bold;"><a href="#tournaments">Tournaments</a></li>
						@endif
						<li style="font-size:15px; font-weight:bold;"><a href="#information">Essential Information</a></li>
					</ul>
				</div>
			</div>
		</nav>
	</div>
	<!-- SIGN IN TO EVENT -->
	@if (!$signed_in)
		Please Sign in at the main desk
	@endif

	<!-- EVENT SPONSORS -->
	@if (!$event->sponsors->isEmpty())
		<div class="page-header">
			<a name="sponsors"></a>
			<h3>{{ $event->display_name }} is sponsored by</h3>
		</div>
		@foreach ($event->sponsors as $sponsor)
			<a href="{{$sponsor->website}}">
				<img class="img-responsive img-rounded" src="{{ $sponsor->image_path }}"/>
			</a>
		@endforeach
	@endif

	<!-- ESSENTIAL INFORMATION -->
	<div class="row">
		<div class="col-lg-6 col-md-6 col-xs-12">
			<div class="page-header">
				<a name="information"></a>
				<h3>Essential Information</h3>
			</div>
			{!! $event->essential_info !!}
		</div>
		<div class="col-lg-6 col-md-6 col-xs-12">
			<div class="page-header">
				<a name="annoucements"></a>
				<h3>Annoucements</h3>
			</div>
			@if ($event->annoucements->isEmpty())
				<div class="alert alert-info"><strong>No Annoucements</strong></div>
			@else
				@foreach ($event->annoucements as $annoucement)
					<div class="alert alert-info">{{ $annoucement->message }}</div>
				@endforeach
			@endif
		</div>
	</div>
	
	<!-- TIMETABLE -->
	@if (!$event->timetables->isEmpty())
		<div class="page-header">
			<a name="timetable"></a>
			<h3>Timetable</h3>
		</div>
		@foreach ($event->timetables as $timetable)
			@if (strtoupper($timetable->status) == 'DRAFT')
				<h4>DRAFT</h4>
			@endif
			<h4>{{ $timetable->name }}</h4>
			<table class="table table-striped">
				<thead>
					<th>
						Time
					</th>
					<th>
						Game
					</th>
					<th>
						Description
					</th>
				</thead>
				<tbody>
					@foreach ($timetable->data as $slot)
						@if ($slot->name != NULL && $slot->desc != NULL)
							<tr>
								<td>
									{{ date("D", strtotime($slot->start_time)) }} - {{ date("H:i", strtotime($slot->start_time)) }}
								</td>
								<td>
									{{ $slot->name }}
								</td>
								<td>
									{{ $slot->desc }}
								</td>
							</tr>
						@endif
					@endforeach
				</tbody>
			</table>
		@endforeach
	@endif

	<!-- TOURNAMENTS -->
	@if (!$event->tournaments->isEmpty())
		<div class="page-header">
			<a name="tournaments"></a>
			<h3>Tournaments</h3>
		</div>
		<div class="row">
			@foreach ($event->tournaments as $tournament)
				@if ($tournament->status != 'DRAFT')
					<div class="col-xs-12 col-sm-6 col-md-3">
						<div class="thumbnail">
							@if ($tournament->game && $tournament->game->image_thumbnail_path)
								<a href="/events/{{ $event->slug }}/tournaments/{{ $tournament->slug }}">
									<img class="img img-responsive img-rounded" src="{{ $tournament->game->image_thumbnail_path }}" alt="{{ $tournament->game->name }}">
								</a>
							@endif
							<div class="caption">
								<h3>{{ $tournament->name }}</h3>
								<span class="small">
									@if ($tournament->status == 'COMPLETE')
										<span class="label label-success">Ended</span>
									@endif
									@if ($tournament->status == 'LIVE')
										<span class="label label-success">Live</span>
									@endif
									@if ($tournament->status != 'COMPLETE' && !$tournament->getParticipant($user->active_event_participant->id))
										<span class="label label-danger">Not Signed up</span>
									@endif
									@if ($tournament->status != 'COMPLETE' && $tournament->getParticipant($user->active_event_participant->id))
										<span class="label label-success">Signed up</span>
									@endif
								</span>
								<hr>
								@if ($tournament->status != 'COMPLETE')
									<dl>
										<dt>
											Team Sizes:
										</dt>
										<dd>
											{{ $tournament->team_size }}
										</dd>
										@if ($tournament->game)
											 <dt>
												Game:
											</dt>
											<dd>
												{{ $tournament->game->name }}
											</dd>
										@endif
										<dt>
											Format:
										</dt>
										<dd>
											{{ $tournament->format }}
										</dd>
									</dl>
								@endif
								<!-- // TODO - refactor & add order on rank-->
								@if ($tournament->status == 'COMPLETE' && $tournament->format != 'list')
									@php
										if ($tournament->team_size != '1v1') {
											$tournament_participants = $tournament->tournamentTeams;
										}
										if ($tournament->team_size == '1v1') {
											$tournament_participants = $tournament->tournamentParticipants;
										}
									@endphp
									@foreach ($tournament_participants as $tournament_participant)
										@if ($tournament_participant->final_rank == 1)
											@if ($tournament->team_size == '1v1')
												<h2>{{ Helpers::getChallongeRankFormat($tournament_participant->final_rank) }} - {{ $tournament_participant->eventParticipant->user->steamname }}</h2>
											@else
												<h2>{{ Helpers::getChallongeRankFormat($tournament_participant->final_rank) }} - {{ $tournament_participant->name }}</h2>
											@endif
										@endif
										@if ($tournament_participant->final_rank == 2)
											@if ($tournament->team_size == '1v1')
												<h3>{{ Helpers::getChallongeRankFormat($tournament_participant->final_rank) }} - {{ $tournament_participant->eventParticipant->user->steamname }}</h3>
											@else
												<h3>{{ Helpers::getChallongeRankFormat($tournament_participant->final_rank) }} - {{ $tournament_participant->name }}</h3>
											@endif
										@endif
										@if ($tournament_participant->final_rank != 2 && $tournament_participant->final_rank != 1)
											@if ($tournament->team_size == '1v1')
												<h4>{{ Helpers::getChallongeRankFormat($tournament_participant->final_rank) }} - {{ $tournament_participant->eventParticipant->user->steamname }}</h4>
											@else
												<h4>{{ Helpers::getChallongeRankFormat($tournament_participant->final_rank) }} - {{ $tournament_participant->name }}</h4>
											@endif
										@endif
									@endforeach
									<h4>Signups Closed</h4>
								@endif
								<strong>
									{{ $tournament->tournamentParticipants->count() }} Signups
								</strong>
								<hr>
								<p><a href="/events/{{ $event->slug }}/tournaments/{{ $tournament->slug }}" class="btn btn-primary btn-block" role="button">View</a></p>
							</div>
						</div>
					</div>
				@endif
			@endforeach
		</div>
	@endif

	<!-- ATTENDEES -->
	<div class="page-header">
		<a name="attendees"></a>
		<h3>Attendees</h3>
	</div>
	<table class="table table-striped">
		<thead>
			<th width="7%">
			</th>
			<th>
				Steam Name
			</th>
			<th>
				Name
			</th>
			<th>
				Seat
			</th>
		</thead>
		<tbody>
			@foreach ($event->eventParticipants as $participant)
			<tr>
				<td>
					<img class="img-responsive img-rounded" style="max-width: 70%;" src="{{ $participant->user->avatar }}">
				</td>
				<td style="vertical-align: middle;">
					{{ $participant->user->steamname }}
				</td>
				<td style="vertical-align: middle;">
					{{ $participant->user->firstname }}
				</td>
				<td style="vertical-align: middle;">
					@if ($participant->seat)
						{{ $participant->seat->seat }}
					@else
						Not Seated
					@endif 
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	
	<!-- SEATING -->
	@if (!$event->seatingPlans->isEmpty())
		<div class="page-header">
			<a name="seating"></a>
			<h3>Seating Plans <small>- unseatedtickets / total seatable tickets remaining</small></h3>
		</div>
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			@foreach ($event->seatingPlans as $seating_plan)
				<div class="panel panel-default">
					<div class="panel-heading" role="tab" id="headingOne">
						<h4 class="panel-title">
							<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_{{ $seating_plan->slug }}" aria-expanded="true" aria-controls="collapse_{{ $seating_plan->slug }}">
								{{ $seating_plan->name }} <small>- Number of seated seats here</small>
							</a>
						</h4>
					</div>
					<div id="collapse_{{ $seating_plan->slug }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collaspe_{{ $seating_plan->slug }}">
						<div class="panel-body">
							<div class="table-responsive text-center">
								<table class="table">
									<thead>
										<tr>
										<?php
											$headers = explode(',', $seating_plan->headers);
											$headers = array_combine(range(1, count($headers)), $headers);
										?>
										@for ($column = 1; $column <= $seating_plan->columns; $column++)
											<th class="text-center"><h4><strong>ROW {{ucwords($headers[$column])}}</strong></h4></th>
										@endfor
										</tr>
									 </thead>
									<tbody>
										@for ($row = $seating_plan->rows; $row > 0; $row--)
											<tr>
												@for ($column = 1; $column <= $seating_plan->columns; $column++)
													<td style="padding-top:14px;">
														@if ($event->getSeat($seating_plan->id, ucwords($headers[$column]) . $row))
															@if ($seating_plan->locked)
																<button class="btn btn-success btn-sm" disabled>
																	{{ ucwords($headers[$column]) . $row }} - {{ $event->getSeat($seating_plan->id, ucwords($headers[$column] . $row))->eventParticipant->user->steamname }}
																</button>
															@else
																<button class="btn btn-success btn-sm">
																	{{ ucwords($headers[$column]) . $row }} - {{ $event->getSeat($seating_plan->id, ucwords($headers[$column] . $row))->eventParticipant->user->steamname }}
																</button>
															@endif
														@else
															@if ($seating_plan->locked)
																<button class="btn btn-primary btn-sm" disabled>
																	{{ ucwords($headers[$column]) . $row }} - Empty
																</button>
															@else
																@if (Auth::user() && $event->getEventParticipant())
																	<button 
																		class="btn btn-primary btn-sm"
																		onclick="pickSeat(
																			'{{ $seating_plan->id }}',
																			'{{ ucwords($headers[$column]) . $row }}'
																		)"
																		data-toggle="modal"
																		data-target="#pickSeatModal"
																	>
																		{{ ucwords($headers[$column]) . $row }} - Empty
																	</button>
																@else
																	<button class="btn btn-primary btn-sm">
																		{{ ucwords($headers[$column]) . $row }} - Empty
																	</button>
																@endif
															@endif
														@endif
													</td>
												@endfor
											</tr>
										@endfor
									</tbody>
								</table>
								@if ($seating_plan->locked)
									<p class="text-center"><strong>NOTE: Seating Plan is currently locked!</strong></p>
								@endif
							</div>
							<hr>
							<div class="row" style="display: flex; align-items: center;">
								<div class="col-xs-12 col-md-8">
									<img class="img-responsive" src="{{$seating_plan->image_path}}"/>
								</div>
								<div class="col-xs-12 col-md-4">
									<h5>Your Seats</h5>
									@if ($ticket_flag)
										@foreach ($user->eventParticipation as $participant) 
											@if ($participant->seat && $participant->seat->event_seating_plan_id == $seating_plan->id) 
												{{ Form::open(array('url'=>'/events/' . $event->slug . '/seating/' . $seating_plan->slug)) }}
													{{ Form::hidden('_method', 'DELETE') }}
													{{ Form::hidden('user_id', $user->id, array('id'=>'user_id','class'=>'form-control')) }} 
													{{ Form::hidden('participant_id', $participant->id, array('id'=>'participant_id','class'=>'form-control')) }} 
													{{ Form::hidden('seat_number', $participant->seat->seat, array('id'=>'seat_number','class'=>'form-control')) }} 
													<h5>
														<button class="btn btn-success btn-block"> 
														{{ $participant->seat->seat }} - Remove
														</button>
													</h5>
												{{ Form::close() }} 
											@endif
										@endforeach
									@elseif(Auth::user())
										<div class="alert alert-info">
											<h5>Please Purchase a ticket</h5>
										</div>
									@else
										<div class="alert alert-info">
											<h5>Please Log in to Purchase a ticket</h5>
										</div>
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
			@endforeach
		</div>
	@endif

	<!-- Image Uploader -->
	<div class="page-header" hidden>
		<a name="image_uploader"></a>
		<h3>Image Uploader</h3>
	</div>
	<div class="row" hidden>
	</div>
	
</div>

@endsection