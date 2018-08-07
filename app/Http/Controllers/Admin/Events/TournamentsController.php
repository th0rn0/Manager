<?php

namespace App\Http\Controllers\Admin\Events;


use Input;
use DB;
use Auth;
use IGDB;
use Session;
use Storage;

use App\User;
use App\Event;
use App\EventParticipant;
use App\EventTournament;
use App\EventTournamentParticipant;
use App\EventTournamentTeam;
use App\EventTicket;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Reflex\Challonge\Challonge;

class TournamentsController extends Controller
{
	/**
	 * Show Tournaments Index Page
	 * @param  Event  $event
	 * @return View
	 */
	public function index(Event $event)
	{
		return view('admin.events.tournaments.index')->withEvent($event);
	}

	/**
	 * Show Tournaments Page
	 * @param  Event 			$event
	 * @param  EventTournament 	$tournament
	 * @return View
	 */
	public function show(Event $event, EventTournament $tournament)
	{
		return view('admin.events.tournaments.show')->withEvent($event)->withTournament($tournament);
	}
   
   	/**
   	 * Store Tournament to Database
   	 * @param  Event   $event
   	 * @param  Request $request
   	 * @return Redirect
   	 */
	public function store(Event $event, Request $request)
	{

		$rules = [
			'name'			=> 'required',
			'game'			=> 'required',
			'format'		=> 'required|in:single elimination,double elimination,round robin',
			'team_size'		=> 'required|in:1v1,2v2,3v3,4v4,5v5,6v6',
			'description'	=> 'required',
			'image'			=> 'image',
		];
		$messages = [
			'name.required'			=> 'Tournament name is required',
			'game.required'			=> 'Game is required',
			'format.required'		=> 'Format is required',
			'format.in'				=> 'Single Elimation, Double Elimination or Round Robin only',
			'team_size.required'	=> 'Team size is required',
			'team_size.in'			=> 'Team Size must be in format 1v1, 2v2, 3v3 etc',
			'description.required'	=> 'Description is required',
			'image.image'			=> 'Tournament image must be a Image'
		];
		$this->validate($request, $rules, $messages);

		$tournament_url = str_random(16);

		$tournament								= new EventTournament();

		$tournament->event_id					= $event->id;
		$tournament->challonge_tournament_url	= $tournament_url;
		$tournament->name						= $request->name;
		$tournament->game						= $request->game;
		$tournament->format						= $request->format;
		$tournament->team_size					= $request->team_size;
		$tournament->description				= $request->description;
		$tournament->allow_bronze				= ($request->allow_bronze ? true : false);
		$tournament->allow_player_teams			= ($request->allow_player_teams ? true : false);
		$tournament->status						= 'DRAFT';

		if ($request->file('image') !== NULL) {
			$tournament->game_cover_image_path = str_replace(
				'public/', 
				'/storage/', 
				Storage::put(
					'public/images/events/' . $event->slug . '/tournaments/' . $tournament->slug,
					$request->file('image')
				)
			);
		}

		if (!$tournament->save()) {
			Session::flash('message', 'Could not save Tournament!');
			return Redirect::to('admin/events/' . $event->slug . '/tournaments');
		}
		
		$challonge = new Challonge(env('CHALLONGE_API_KEY'));
		$params = [
		  'tournament[name]'					=> $request->name,
		  'tournament[tournament_type]'			=> strtolower($request->format),
		  'tournament[url]'						=> $tournament_url,
		  'tournament[subdomain]'				=> env('CHALLONGE_SUBDOMAIN'),
		  'tournament[hold_third_place_match]'	=> ($request->allow_bronze ? true : false),
		  'tournament[show_rounds]'				=> true,
		];

		if (!$response = $challonge->createTournament($params)) {
			$tournament->delete();
			Session::flash('message', 'Could not connect to Challonge. Please try again');
			return Redirect::to('admin/events/' . $event->slug . '/tournaments');
		}
		
		$tournament->challonge_tournament_id = $response->id;

		if (!$tournament->save()) {
			Session::flash('message', 'Cannot save Tournament!');
			return Redirect::to('admin/events/' . $event->slug . '/tournaments');
		}

		Session::flash('message', 'Successfully saved Tournament!');
		return Redirect::to('admin/events/' . $event->slug . '/tournaments');
	}
	
	/**
	 * Update Tournament
	 * @param  Event           $event
	 * @param  EventTournament $tournament
	 * @param  Request         $request
	 * @return Redirect
	 */
	public function update(Event $event, EventTournament $tournament, Request $request)
	{
		$rules = [
			'name'			=> 'filled',
			'status'		=> 'in:DRAFT,OPEN,CLOSED,LIVE,COMPLETE',
			'description'	=> 'filled',
		];
		$messages = [
			'name.filled'			=> 'Tournament name cannot be empty',
			'status.in'				=> 'Status must be DRAFT, OPEN, CLOSED, LIVE or COMPLETE',
			'description.filled'	=> 'Description cannot be empty',
		];
		$this->validate($request, $rules, $messages);

		if (isset($request->status) && $request->status != $tournament->status) {
			if (!$tournament->setStatus($request->status)) {
				Session::flash('message', 'Tournament status cannot be updated!');
				return Redirect::back();
			}
		}

		$tournament->name			= $request->name;
		$tournament->description	= $request->description;

		$challonge = new Challonge(env('CHALLONGE_API_KEY'));
		$challonge_tournament = $challonge->getTournament($tournament->challonge_tournament_id);
		$params = [
		  'tournament[name]'					=> $request->name
		];

		if (!$response = $challonge_tournament->update($params)) {
			Session::flash('message', 'Could not connect to Challonge. Please try again');
			return Redirect::back();
		}
		
		if (!$tournament->save()) {
			session::flash('alert-danger', 'Cannot update Tournament!');
			return Redirect::back();
		}

		session::flash('alert-success', 'Successfully updated Tournament!');
		return Redirect::back();
	}

	/**
	 * Delete Tournament from Database
	 * @param  Event           $event
	 * @param  EventTournament $tournament
	 * @return Redirect
	 */
	public function destroy(Event $event, EventTournament $tournament)
	{
		if (!$tournament->delete()) {
			Session::flash('alert-danger', 'Cannot delete Tournament!');
			return Redirect::to('admin/events/' . $event->slug . '/tournaments');
		}

		Session::flash('alert-success', 'Successfully deleted Tournament!');
		return Redirect::to('admin/events/' . $event->slug . '/tournaments');
	}

	/**
	 * Start Tournament
	 * @param  Event           $event
	 * @param  EventTournament $tournament
	 * @return Redirect
	 */
	public function start(Event $event, EventTournament $tournament)
	{
		if ($tournament->tournamentParticipants->count() < 2) {
			Session::flash('alert-danger', 'Tournament doesnt have enough participants');
			return Redirect::back();
		}

		if ($tournament->status == 'LIVE' || $tournament->status == 'COMPLETED') {
			Session::flash('alert-danger', 'Tournament is already live or completed');
			return Redirect::back();
		}

		if (!$tournament->tournamentTeams->isEmpty()) {
			foreach ($tournament->tournamentTeams as $team) {
				if ($team->tournamentParticipants->isEmpty()) {
					 if (!$team->delete()) {
						Session::flash('message', 'Error connecting to Challonge!');
						return Redirect::to('admin/events/' . $event->slug . '/tournaments');
					}
				}
			}
		}

		if (!$tournament->setStatus('LIVE')) {
			Session::flash('alert-danger', 'Cannot start Tournament!');
			return Redirect::to('admin/events/' . $event->slug . '/tournaments/');
		}

		Session::flash('alert-success', 'Tournament Started!');
		return Redirect::to('admin/events/' . $event->slug . '/tournaments/');
	}

	/**
	 * Finalize Tournament
	 * @param  Event           $event
	 * @param  EventTournament $tournament
	 * @return Redirect
	 */
	public function finalize(Event $event, EventTournament $tournament)
	{
		if (!$tournament->setStatus('COMPLETE')) {
			Session::flash('alert-danger', 'Cannot finalize. Tournament is still live!');
			return Redirect::to('admin/events/' . $event->slug . '/tournaments');
		}

		Session::flash('alert-success', 'Tournament Finalized!');
		return Redirect::to('admin/events/' . $event->slug . '/tournaments/' . $tournament->slug);
	}

	/**
	 * Update Participant Team
	 * @param  Event                      $event 
	 * @param  EventTournament            $tournament
	 * @param  EventTournamentParticipant $participant
	 * @param  Request                    $request
	 * @return Redirect
	 */
	public function updateParticipantTeam(Event $event, EventTournament $tournament, EventTournamentParticipant $participant, Request $request)
	{
		$rules = [
			'event_tournament_team_id'	=> 'required'
		];
		$messages = [
			'event_tournament_team_id|required'	=> 'A Team ID is required.'
		];
		$this->validate($request, $rules, $messages);

		$participant->event_tournament_team_id = $request->event_tournament_team_id;
		
		if (!$participant->save()) {
			Session::flash('alert-danger', 'Cannot update Participant!');
			return Redirect::back();
		}

		Session::flash('alert-success', 'Successfully updated Participant!');
		return Redirect::to('admin/events/' . $event->slug . '/tournaments/' . $tournament->slug);
	}
}

