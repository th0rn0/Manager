@extends ('layouts.borderless')

@section ('page_title', $event->display_name . ' - Lans in South Yorkshire')

@section ('content')
			
<div class="container">
	@foreach ($event->annoucements as $annoucement)
		<h3><center>{{ $annoucement->message }}</center></h3>
	@endforeach
	<!-- TIMETABLE -->
	@if (!$event->timetables->isEmpty())
		@foreach ($event->timetables as $timetable)
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
</div>
@endsection