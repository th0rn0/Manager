<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use GuzzleHttp\Client;
use Lanops\Challonge\Challonge;

class EventTournamentParticipant extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'event_tournament_participants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_participant_id', 
        'challonge_participant_id', 
        'event_tournament_team_id', 
        'event_tournament_id',
        'pug'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array(
        'created_at',
        'updated_at'
    );

    public static function boot()
    {
        parent::boot();
        self::created(function($model){
            if (
                (!isset($model->event_tournament_team_id) || trim($model->event_tournament_team_id) == '') &&
                (!$model->pug && $model->event_tournament_team_id == null) &&
                $model->eventTournament->format != 'list'
            ) {
                $challonge = new Challonge(env('CHALLONGE_API_KEY'));
                $tournament = $challonge->getTournament($model->eventTournament->challonge_tournament_id);
                if (!$response = $tournament->addParticipant(['participant[name]' => $model->eventParticipant->user->username])) {
                    $model->delete();
                    return false;
                }
                $model->challonge_participant_id = $response->id;
                $model->save();
            }
            return true;
        });
        self::deleting(function($model){
            if (!$model->pug && $model->event_tournament_team_id == null && $model->eventTournament->format != 'list') {
                $challonge = new Challonge(env('CHALLONGE_API_KEY'));
                $participant = $challonge->getParticipant($model->eventTournament->challonge_tournament_id, $model->challonge_participant_id);
                if (!$response = $participant->delete()) {
                    return false;
                }
            }
            if ($model->tournamentTeam && $model->tournamentTeam->tournamentParticipants->count() == 1) {
                if (!$model->tournamentTeam->delete()) {
                    return false;
                }
            }
            return true;
        });
    }

    /*
     * Relationships
     */
    public function eventTournament()
    {
        return $this->belongsTo('App\EventTournament');
    }
    public function eventParticipant()
    {
        return $this->belongsTo('App\EventParticipant');
    }
    public function tournamentTeam()
    {
        return $this->belongsTo('App\EventTournamentTeam', 'event_tournament_team_id');
    }
}