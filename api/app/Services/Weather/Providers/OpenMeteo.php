<?php

namespace App\Services\Weather\Providers;

use App\Services\Weather\Weather;
use App\Services\Weather\WeatherProvider;
use Carbon\Carbon;
use GuzzleHttp\Client;

/**
 * Class OpenMeteo
 *
 * @package App\Services\Weather\Providers
 *
 * See documentation and samples here https://open-meteo.com/en/docs#api_form
 */
class OpenMeteo implements WeatherProvider
{
    /** @var array */
    private static $weatherCodes = [
        0  => [Weather::CONDITION_CLEAR, 'Clear sky'],
        1  => [Weather::CONDITION_CLOUDY, 'Mainly clear'],
        2  => [Weather::CONDITION_CLOUDY, 'Partly cloudy'],
        3  => [Weather::CONDITION_CLOUDY, 'Overcast'],
        45 => [Weather::CONDITION_FOG, 'Fog'],
        48 => [Weather::CONDITION_FOG, 'Depositing rime fog'],
        51 => [Weather::CONDITION_DRIZZLE, 'Light drizzle'],
        53 => [Weather::CONDITION_DRIZZLE, 'Moderate drizzle'],
        55 => [Weather::CONDITION_DRIZZLE, 'Intense drizzle'],
        56 => [Weather::CONDITION_DRIZZLE, 'Light freezing drizzle'],
        57 => [Weather::CONDITION_DRIZZLE, 'Intense freezing drizzle'],
        61 => [Weather::CONDITION_RAIN, 'Slight rain'],
        63 => [Weather::CONDITION_RAIN, 'Moderate rain'],
        65 => [Weather::CONDITION_RAIN, 'Intense rain'],
        71 => [Weather::CONDITION_SNOW, 'Slight snow'],
        73 => [Weather::CONDITION_SNOW, 'Moderate snow'],
        75 => [Weather::CONDITION_SNOW, 'Intense snow'],
        77 => [Weather::CONDITION_SNOW, 'Snow grains'],
        80 => [Weather::CONDITION_RAIN_SHOWER, 'Slight rain showers'],
        81 => [Weather::CONDITION_RAIN_SHOWER, 'Moderate rain showers'],
        82 => [Weather::CONDITION_RAIN_SHOWER, 'Violent rain showers'],
        85 => [Weather::CONDITION_SNOW_SHOWER, 'Slight snow shower'],
        86 => [Weather::CONDITION_SNOW_SHOWER, 'Heavy snow shower'],
        95 => [Weather::CONDITION_THUNDERSTORM, 'Thunderstorm'],
        96 => [Weather::CONDITION_THUNDERSTORM, 'Thunderstorm with slight hail'],
        99 => [Weather::CONDITION_THUNDERSTORM, 'Thunderstorm with heavy hail'],
    ];

    /** @var Client */
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.open-meteo.com/',
            'timeout'  => 500,
            'headers'  => [
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    /** {@inheritDoc} */
    public function getWeather(float $latitude, float $longitude): Weather
    {
        $tempUnit      = Weather::TEMP_UNIT_CELSIUS;
        $windSpeedUnit = Weather::WIND_SPEED_UNIT_KMPH;
        $urlPath       = '/v1/forecast?' . http_build_query([
                'latitude'         => $latitude,
                'longitude'        => $longitude,
                'hourly'           => 'temperature_2m',
                'current_weather'  => 'true',
                'temperature_unit' => strtolower($tempUnit),
                'windspeed_unit'   => strtolower($windSpeedUnit),
            ]);

        $response    = json_decode((string) $this->client->get($urlPath)->getBody(), true);
        $weatherCode = $response['current_weather']['weathercode'];

        if (!isset(self::$weatherCodes[$weatherCode])) {
            throw new \RuntimeException('Unknown openmeteo weather code: ' . $weatherCode);
        }

        $weatherLabel = self::$weatherCodes[$weatherCode];

        return (new Weather())
            ->setLatitude($response['latitude'])
            ->setLongitude($response['longitude'])
            ->setLabel($weatherLabel[0])
            ->setDescription($weatherLabel[1])
            ->setTime(Carbon::parse($response['current_weather']['time']))
            ->setTemperature($response['current_weather']['temperature'], $tempUnit)
            ->setWindSpeed($response['current_weather']['windspeed'], $windSpeedUnit)
            ->setWindDirection($response['current_weather']['winddirection']);
    }
}