@extends ('layouts.default')

@section ('page_title', $event->display_name . ' - ' . $tournament->display_name)

@section ('content')
	<div class="container">

		<!-- HEADER -->
		<div class="row">
			<div class="jumbotron">
				<div class="row">
					<div class="col-xs-12">
						<div class="block">
							<h2>
								<span class="label label-success">{{ $tournament->status }}</span>
								@if (!$tournament->getParticipant($user->active_event_participant->id) && $tournament->status != 'COMPLETE')
									<span class="label label-danger">Not Signed up</span>
								@endif
								@if ($tournament->getParticipant($user->active_event_participant->id) && $tournament->status != 'COMPLETE')
									<span class="label label-success">Signed up</span>
								@endif
							</h2>
							<h2>
								{{ $tournament->name }}
							</h2>
							<h4>{{ $tournament->game }}</h4>
							<p>{{ $tournament->description }}</p>
							<dl>
								<dt>
									Team Sizes
								</dt>
								<dd>
									{{ $tournament->team_size }}
								</dd>
								<dt>
									Format:
								</dt>
								<dd>
									{{ $tournament->format }}
								</dd>
							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- MATCHES & STANDINGS -->
		<div class="row">

			@if ($tournament->status == 'LIVE' && isset($tournament_matches))
				@foreach ($tournament_matches as $round_number => $round)
					<div class="col-xs-2">
						<h3>Round {{ $round_number }}</h3>
						@foreach ($round as $match)
							@if ($match->player1_id)
								{{ ($tournament->getParticipantByChallongeId($match->player1_id))->eventParticipant->user->steamname }} 
							@else 
								TBC
							@endif 
							VS
							@if ($match->player2_id)
								{{ ($tournament->getParticipantByChallongeId($match->player2_id))->eventParticipant->user->steamname }}
							@else
								TBC
							@endif
						@endforeach
					</div>
				@endforeach
		 		@if ($tournament->team_size == '1v1')
					<div class="table-responsive">
						<table class="table">
							 <thead>
								<tr>
									<th>
										Player
									</th>
									<th>
										Win/Lose/Tie
									</th>
									<th>
										Points
									</th>
									<th>
										History
									</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($tournament_standings['final'] as $standings)
									<tr>
										<td>  
											{{ $standings['name'] }}
										</td>
										<td>
											{{ $standings['win'] }} / {{ $standings['lose'] }} / {{ $standings['tie'] }}
										</td>
										<td>
											{{ $standings['pts'] }}
										</td>
										<td>
											@foreach ($standings['history'] as $game)
												@if ($game == 'W') 
													<div class="col-xs-1">
														<span class="label label-success">W</span>
													</div>
												@endif
												@if ($game == 'L') 
													<div class="col-xs-1">
														<span class="label label-danger">L</span>
													</div>
												@endif
												@if ($game == 'T') 
													<div class="col-xs-1">
														<span class="label label-default">T</span>
													</div>
												@endif
											@endforeach
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				@endif
			 	@if ($tournament->status == 'LIVE' && isset($tournament->matches) && $tournament->team_size != '1v1')
					<div class="table-responsive">
						<table class="table">
							 <thead>
								<tr>
									<th>
										Teams
									</th>
									<th>
										Win/Lose/Tie
									</th>
									<th>
										Points
									</th>
									<th>
										History
									</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($tournament_standings['final'] as $standings)
									<tr>
										<td>  
											{{ $standings['name'] }}
										</td>
										<td>
											{{ $standings['win'] }} / {{ $standings['lose'] }} / {{ $standings['tie'] }}
										</td>
										<td>
											{{ $standings['pts'] }}
										</td>
										<td>
											@foreach ($standings['history'] as $game)
												@if ($game == 'W') 
													<div class="col-xs-1">
														<span class="label label-success">W</span>
													</div>
												@endif
												@if ($game == 'L') 
													<div class="col-xs-1">
														<span class="label label-danger">L</span>
													</div>
												@endif
												@if ($game == 'T') 
													<div class="col-xs-1">
														<span class="label label-default">T</span>
													</div>
												@endif
											@endforeach
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				@endif
			@endif

		</div>

		<div class="row">


			@if ($tournament->status == 'OPEN' && !$tournament->getParticipant($user->active_event_participant->id) && $tournament->team_size == '1v1')
				{{ Form::open(array('url'=>'/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/register', 'files' => true )) }}
					<input type="hidden" name="event_participant_id" value="{{ $user->active_event_participant->id }}">
					<button type="submit" class="btn btn-default">Signup</button>
				{{ Form::close() }}
			@endif
			@if ($tournament->status == 'OPEN' && !$tournament->getParticipant($user->active_event_participant->id) && $tournament->team_size != '1v1')
				{{ Form::open(array('url'=>'/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/register/pug', 'files' => true )) }}
					<input type="hidden" name="event_participant_id" value="{{ $user->active_event_participant->id }}">
					<button type="submit" class="btn btn-default">PUG</button>
				{{ Form::close() }}
				{{ Form::open(array('url'=>'/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/register/team', 'files' => true )) }}
					<div class="form-group">
						{{ Form::label('team_name','Team Name',array('id'=>'','class'=>'')) }}
						{{ Form::text('team_name', '',array('id'=>'team_name','class'=>'form-control', 'required' => 'required')) }}
					</div>
					<input type="hidden" name="event_participant_id" value="{{ $user->active_event_participant->id }}">
					<button type="submit" class="btn btn-default">Create Team</button>
				{{ Form::close() }}
			@endif
			@if ($tournament->status == 'OPEN' && $tournament->getParticipant($user->active_event_participant->id))
				{{ Form::open(array('url'=>'/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/register/remove', 'files' => true )) }}
					<input type="hidden" name="event_participant_id" value="{{ $user->active_event_participant->id }}">
					<button type="submit" class="btn btn-default">Remove Signup</button>
				{{ Form::close() }}
			@endif

			@if ($tournament->status == 'LIVE' && isset($tournament_matches))
				<iframe src="{{ env('CHALLONGE_URL') }}/{{ $tournament->challonge_tournament_url }}/module?multiplier=1.0&amp;match_width_multiplier=1.0&amp;show_final_results=0&amp;show_standings=0&amp;theme=1&amp;subdomain=" width="100%" height="480" frameborder="0" scrolling="auto" allowtransparency="true"></iframe>
			@endif

			@if ($tournament->status == 'COMPLETE' && isset($tournament->challonge_participants))
				<div class="alert alert-success text-center">
					@foreach ($tournament->challonge_participants as $challonge_participant)
						<h2>{{ Helpers::getChallongeRankFormat($challonge_participant->final_rank) }} - {{ $challonge_participant->name }}</h2>
					@endforeach
				</div>
			@endif
			@if ($tournament->team_size == '1v1')
				<div class="table-responsive">
					<table class="table">
						 <thead>
							<tr>
								<th>
									Name
								</th>
								<th>
									Seat
								</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($tournament->tournamentParticipants as $tournament_participant)
								<tr>
									<td>
										<p style="padding-top:7px;"><img class="img-rounded" style="max-width: 4%;" src="{{$tournament_participant->eventParticipant->user->avatar}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $tournament_participant->eventParticipant->user->steamname }}</p>
									</td>
									<td>
										<p style="padding-top:15px;">
											@if ($tournament_participant->eventParticipant->seat)
												{{ $tournament_participant->eventParticipant->seat->seat }}
											@else
												Not Seated
											@endif 
										</p>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			@endif
			@if ($tournament->team_size != '1v1')
				<div class="col-sm-6 col-xs-12">
					<h3>Pugs</h3>
					<div class="table-responsive">
						<table class="table">
							 <thead>
								<tr>
									<th>
										Player name
									</th>
									<th>
										Seat
									</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($tournament->tournamentParticipants as $tournament_participant)
									@if ($tournament_participant->pug)
										<tr>
											<td>
												<p>
													<img class="img-rounded" style="max-width: 6%;" src="{{ $tournament_participant->eventParticipant->user->avatar }}">
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $tournament_participant->eventParticipant->user->steamname }}
												</p>
											</td>
											<td>
												<p>
													@if ($tournament_participant->eventParticipant->seat)
														{{ $tournament_participant->eventParticipant->seat->seat }}
													@else
														Not Seated
													@endif 
												</p>
											</td>
										</tr>
									@endif
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-sm-6 col-xs-12">
					<h3>Teams</h3>
					<div class="table-responsive">
						<table class="table">
							 <thead>
								<tr>
									<th>
										Team Name
									</th>
									<th>
										
									</th>
									<th>
										
									</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($tournament->tournamentTeams as $tournament_team)
									<tr>
										<td>
											<h4>{{ $tournament_team->name }}</h4>
										</td>
										<td>
											<table class="table">
												@foreach ($tournament_team->tournamentParticipants as $participant)
													<tr>
														<td>  
															<img class="img-rounded" style="max-width: 8%;" src="{{ $participant->eventParticipant->user->avatar }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $participant->eventParticipant->user->steamname }}
														</td>
														<td>
															@if ($participant->eventParticipant->seat)
																{{ $participant->eventParticipant->seat->seat }}
															@else
																Not Seated
															@endif 
														</td>
													</tr>
												@endforeach
											</table>
										</td>
										<td>
											@if (!$tournament->getParticipant($user->active_event_participant->id))
												{{ Form::open(array('url'=>'/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/register', 'files' => true )) }}
													<input type="hidden" name="event_participant_id" value="{{ $user->active_event_participant->id }}">
													<input type="hidden" name="event_tournament_team_id" value="{{ $tournament_team->id }}">
													<button type="submit" name="action" value="sign_up" class="btn btn-default">Join Team</button>
												{{ Form::close() }}
											@endif
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			@endif
		</div>
	</div>


@endsection
