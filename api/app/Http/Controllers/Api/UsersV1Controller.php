<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\WeatherUpdate;
use App\Services\Weather\WeatherProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class UsersV1Controller
 *
 * @package App\Http\Controllers\Api
 */
class UsersV1Controller extends Controller
{
    /** @var WeatherProvider */
    private $weatherProvider;

    public function __construct(WeatherProvider $weatherProvider)
    {
        $this->weatherProvider = $weatherProvider;
    }

    public function index()
    {
        $users = User::with('lastWeatherUpdate')->get();

        return response()->json(
            UserResource::collection($users)
        );
    }

    public function show($id)
    {
        $user = User::with('weatherUpdates')->findOrFail($id);

        return response()->json(
            new UserResource($user)
        );
    }

    public function refreshWeather($id)
    {
        /** @var User $user */
        $user    = User::findOrFail($id);
        $weather = $this->weatherProvider->getWeather($user->latitude, $user->longitude);

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

        return response()->json([
            'message' => 'success'
        ]);
    }
}