<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTournamentScoresEventTournamentTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_tournament_teams', function (Blueprint $table) {
            $table->integer('final_rank')->after('event_tournament_team_id')->nullable();
            $table->string('final_history', 1000)->after('final_rank')->nullable();
            $table->string('final_ratio')->after('final_history')->nullable();
            $table->integer('final_score')->after('final_ratio')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_tournament_teams', function (Blueprint $table) {
            $table->dropColumn('final_rank');
            $table->dropColumn('final_history');
            $table->dropColumn('final_ratio');
            $table->dropColumn('final_score');
        });
    }
}
