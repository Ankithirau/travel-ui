<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColoumnBusSchduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bus_schedule', function (Blueprint $table) {
            $table->bigInteger('route_id')->unsigned()->after('bus_id');
            $table->foreign('route_id')->references('id')->on('routes')
                    ->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bus_schedule', function (Blueprint $table) {
            $table->dropColumn(['route_id']);
        });
    }
}
