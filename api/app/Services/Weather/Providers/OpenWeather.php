<?php

namespace App\Services\Weather\Providers;

use App\Services\Weather\Weather;
use App\Services\Weather\WeatherProvider;
use Carbon\Carbon;
use GuzzleHttp\Client;

/**
 * Class OpenWeather
 *
 * @package App\Services\Weather\Providers
 *
 * See documentation https://openweathermap.org/current#parameter
 */
class OpenWeather implements WeatherProvider
{
    /** @var array */
    private static $weatherCodes = [
        200 => [Weather::CONDITION_THUNDERSTORM, 'Thunderstorm with light rain'],
        201 => [Weather::CONDITION_THUNDERSTORM, 'Thunderstorm with rain'],
        202 => [Weather::CONDITION_THUNDERSTORM, 'Thunderstorm with heavy rain'],
        210 => [Weather::CONDITION_THUNDERSTORM, 'Light thunderstorm'],
        211 => [Weather::CONDITION_THUNDERSTORM, 'Thunderstorm'],
        212 => [Weather::CONDITION_THUNDERSTORM, 'Heavy thunderstorm'],
        221 => [Weather::CONDITION_THUNDERSTORM, 'Ragged thunderstorm'],
        230 => [Weather::CONDITION_THUNDERSTORM, 'Thunderstorm with light drizzle'],
        231 => [Weather::CONDITION_THUNDERSTORM, 'Thunderstorm with drizzle'],
        232 => [Weather::CONDITION_THUNDERSTORM, 'Thunderstorm with heavy drizzle'],
        300 => [Weather::CONDITION_DRIZZLE, 'Light intensity drizzle'],
        301 => [Weather::CONDITION_DRIZZLE, 'Drizzle'],
        302 => [Weather::CONDITION_DRIZZLE, 'Heavy intensity drizzle'],
        310 => [Weather::CONDITION_DRIZZLE, 'Light intensity drizzle rain'],
        311 => [Weather::CONDITION_DRIZZLE, 'Drizzle rain'],
        312 => [Weather::CONDITION_DRIZZLE, 'Heavy intensity drizzle rain'],
        313 => [Weather::CONDITION_DRIZZLE, 'Shower rain and drizzle'],
        314 => [Weather::CONDITION_DRIZZLE, 'Heavy shower rain and drizzle'],
        321 => [Weather::CONDITION_DRIZZLE, 'Shower drizzle'],
        500 => [Weather::CONDITION_RAIN, 'Light rain'],
        501 => [Weather::CONDITION_RAIN, 'Moderate rain'],
        502 => [Weather::CONDITION_RAIN, 'Heavy intensity rain'],
        503 => [Weather::CONDITION_RAIN, 'Very heavy rain'],
        504 => [Weather::CONDITION_RAIN, 'Extreme rain'],
        511 => [Weather::CONDITION_RAIN, 'Freezing rain'],
        520 => [Weather::CONDITION_RAIN_SHOWER, 'Light intensity shower rain'],
        521 => [Weather::CONDITION_RAIN_SHOWER, 'Shower rain'],
        522 => [Weather::CONDITION_RAIN_SHOWER, 'Heavy intensity shower rain'],
        531 => [Weather::CONDITION_RAIN_SHOWER, 'Ragged shower rain'],
        600 => [Weather::CONDITION_SNOW, 'Light snow'],
        601 => [Weather::CONDITION_SNOW, 'Snow'],
        602 => [Weather::CONDITION_SNOW, 'Heavy snow'],
        611 => [Weather::CONDITION_SNOW, 'Sleet'],
        612 => [Weather::CONDITION_SNOW, 'Light shower sleet'],
        613 => [Weather::CONDITION_SNOW, 'Shower sleet'],
        615 => [Weather::CONDITION_SNOW, 'Light rain and snow'],
        616 => [Weather::CONDITION_SNOW, 'Rain and snow'],
        620 => [Weather::CONDITION_SNOW_SHOWER, 'Light shower snow'],
        621 => [Weather::CONDITION_SNOW_SHOWER, 'Shower snow'],
        622 => [Weather::CONDITION_SNOW_SHOWER, 'Heavy shower snow'],
        701 => [Weather::CONDITION_ATMOSPHERE, 'Mist'],
        711 => [Weather::CONDITION_ATMOSPHERE, 'Smoke'],
        721 => [Weather::CONDITION_ATMOSPHERE, 'Haze'],
        731 => [Weather::CONDITION_ATMOSPHERE, 'Sand / dust whirl'],
        741 => [Weather::CONDITION_ATMOSPHERE, 'Fog'],
        751 => [Weather::CONDITION_ATMOSPHERE, 'Sand'],
        761 => [Weather::CONDITION_ATMOSPHERE, 'Dust'],
        762 => [Weather::CONDITION_ATMOSPHERE, 'Volcanic ash'],
        771 => [Weather::CONDITION_ATMOSPHERE, 'Squall'],
        781 => [Weather::CONDITION_ATMOSPHERE, 'Tornado'],
        800 => [Weather::CONDITION_CLEAR, 'Clear sky'],
        801 => [Weather::CONDITION_CLEAR, 'Few clouds'],
        802 => [Weather::CONDITION_CLEAR, 'Scattered clouds'],
        803 => [Weather::CONDITION_CLEAR, 'Broken clouds'],
        804 => [Weather::CONDITION_CLEAR, 'Overcast clouds'],
    ];

    const UNIT_METRIC   = 'metric';
    const UNIT_IMPERIAL = 'imperial';

    /** @var Client */
    private $client;
    /** @var string */
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client([
            'base_uri' => 'https://api.openweathermap.org/',
            'timeout'  => 500,
            'headers'  => [
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    /** {@inheritDoc} */
    public function getName(): string
    {
        return 'open-weather';
    }

    /** {@inheritDoc} */
    public function getWeather(float $latitude, float $longitude): Weather
    {
        $urlPath = '/data/2.5/weather?' . http_build_query([
                'lat'   => $latitude,
                'lon'   => $longitude,
                'appid' => $this->apiKey,
                'units' => self::UNIT_METRIC,
            ]);

        $response    = json_decode((string) $this->client->get($urlPath)->getBody(), true);
        $weather     = $response['weather'][0];
        $weatherCode = $weather['id'];

        if (!isset(self::$weatherCodes[$weatherCode])) {
            throw new \RuntimeException('Unknown openweather weather code: ' . $weatherCode);
        }

        $weatherLabel = self::$weatherCodes[$weatherCode];

        return (new Weather())
            ->setProvider('openweather')
            ->setLatitude($response['coord']['lat'])
            ->setLongitude($response['coord']['lon'])
            ->setLabel($weatherLabel[0])
            ->setDescription($weatherLabel[1])
            ->setTime(Carbon::createFromTimestamp($response['dt']))
            ->setTemperature($response['main']['temp'], Weather::TEMP_UNIT_CELSIUS)
            ->setWindSpeed($response['wind']['speed'], Weather::WIND_SPEED_UNIT_MPS)
            ->setWindDirection($response['wind']['deg']);
    }
}