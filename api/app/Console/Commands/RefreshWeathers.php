<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\WeatherUpdate;
use App\Services\Weather\WeatherProvider;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RefreshWeathers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh-weathers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(WeatherProvider $weatherProvider): void
    {
        $users = User::all();

        /** @var User $user */
        foreach ($users as $user) {
            $this->info('User: #' . $user->id . ' - ' . $user->name);
            $this->info('Coordinate: ' . $user->latitude . ' - ' . $user->longitude);

            try {
                $weather = $weatherProvider->getWeather($user->latitude, $user->longitude);

                DB::transaction(function () use ($user, $weather) {
                    $newWeatherUpdate                   = new WeatherUpdate();
                    $newWeatherUpdate->provider         = $weather->getProvider();
                    $newWeatherUpdate->latitude         = $weather->getLatitude();
                    $newWeatherUpdate->longitude        = $weather->getLongitude();
                    $newWeatherUpdate->time             = $weather->getTime();
                    $newWeatherUpdate->label            = $weather->getLabel();
                    $newWeatherUpdate->description      = $weather->getDescription();
                    $newWeatherUpdate->temperature      = $weather->getTemperature();
                    $newWeatherUpdate->temperature_unit = $weather->getTemperatureUnit();
                    $newWeatherUpdate->wind_speed       = $weather->getWindSpeed();
                    $newWeatherUpdate->wind_speed_unit  = $weather->getWindSpeedUnit();
                    $newWeatherUpdate->wind_direction   = $weather->getWindDirection();
                    $newWeatherUpdate->user()->associate($user);
                    $newWeatherUpdate->saveOrFail();

                    $user->last_weather_update_id = $newWeatherUpdate->id;
                    $user->last_weather_update_at = Carbon::now();
                    $user->saveOrFail();
                });
            } catch (\Exception $ex) {
                $this->error('Failed to get weather: ' . $ex->getMessage());
            }
        }
    }
}
