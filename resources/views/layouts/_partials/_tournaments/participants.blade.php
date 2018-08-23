<!-- All Participants -->
<h3>All Participants</h3>
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
				@if($tournament->team_size != '1v1')
					<th>
						PUG
					</th>
					<th>
						Team
					</th>
				@endif
			</tr>
		</thead>
		<tbody>
			@foreach ($tournament->tournamentParticipants as $tournament_participant)
				<tr>
					<td>
						<p style="padding-top:7px;">
							<img class="img-rounded" style="max-width: 4%;" src="{{$tournament_participant->eventParticipant->user->avatar}}">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $tournament_participant->eventParticipant->user->steamname }}
							<small> - {{ $tournament_participant->eventParticipant->user->username }}</small>
						</p>
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
					@if ($tournament->team_size != '1v1')
						<td>
							@if($tournament_participant->pug)
								Yes
							@else
								No
							@endif
						</td>                        
						<td>
							{{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/tournaments/' . $tournament->slug . '/participants/' . $tournament_participant->id  . '/team')) }}
								@if ($tournament->status == 'LIVE' || $tournament->status == 'COMPLETE')
									{{ $tournament_participant->tournamentTeam->name }}
								@else
									<div class="form-group">
										{{ Form::select('event_tournament_team_id', [0 => 'None'] + $tournament->getTeams(), $tournament_participant->event_tournament_team_id, array('id'=>'name','class'=>'form-control')) }}
									</div>
								@endif
								@if ($tournament->status == 'LIVE' || $tournament->status == 'COMPLETE')
									<button type="submit" class="btn btn-default" disabled>Update</button>  
								@else
									<button type="submit" class="btn btn-default">Update</button>  
								@endif
							{{ Form::close() }}
						</td>
					@endif
				</tr>
			@endforeach
		</tbody>
	</table>
</div>
<!-- Teams -->
@if ($tournament->team_size != '1v1')
	<h3>Teams</h3>
	<div class="table-responsive">
		<table class="table">
			 <thead>
				<tr>
					<th>
						Name
					</th>
					<th>
						Roster
					</th>
					
				</tr>
			</thead>
			<tbody>
				@foreach ($tournament->tournamentTeams as $tournament_team)
					<tr>
						<td width="50%">
							<h4>{{ $tournament_team->name }}</h4>
						</td>
						<td>
							@if ($tournament_team->tournamentParticipants)
								@foreach ($tournament_team->tournamentParticipants as $participant)
									<img class="img-rounded" style="max-width: 8%;" src="{{ $participant->eventParticipant->user->avatar }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $participant->eventParticipant->user->steamname }}
									<span class="pull-right">
										@if ($participant->eventParticipant->seat)
											{{ $participant->eventParticipant->seat->seat }}
										@else
											Not Seated
										@endif
									</span>
									<br><br>
								@endforeach
							@else
								No one yet
							@endif
						</td>
						
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<h3>PUGs</h3>
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
@endif