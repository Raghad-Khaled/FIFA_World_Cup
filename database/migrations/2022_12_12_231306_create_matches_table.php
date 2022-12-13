<?php

use App\Models\Stadium;
use App\Models\Team;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('time');
            $table->foreignIdFor(Team::class, 'team1_id');
            $table->foreignIdFor(Team::class,  'team2_id');
            $table->foreign('team1_id')->references('id')->on('teams');
            $table->foreign('team2_id')->references('id')->on('teams');
            $table->string('referee');
            $table->string('lineman1');
            $table->string('lineman2');
            $table->foreignIdFor(Stadium::class);
            $table->foreign('stadium_id')->references('id')->on('stadiums');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matches');
    }
};
