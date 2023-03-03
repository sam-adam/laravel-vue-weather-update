<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('weather_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('provider');
            $table->dateTime('time');
            $table->float('latitude');
            $table->float('longitude');
            $table->string('label');
            $table->text('description');
            $table->float('temperature');
            $table->string('temperature_unit');
            $table->float('wind_speed');
            $table->string('wind_speed_unit');
            $table->float('wind_direction');
            $table->timestamps();
        });


        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('last_weather_update_id')->nullable();
            $table->dateTime('last_weather_update_at')->nullable();

            $table->index(['last_weather_update_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_updates');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_weather_update_id');
            $table->dropColumn('last_weather_update_at');
        });
    }
};
