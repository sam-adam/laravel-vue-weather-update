<?php

namespace App\Services\Weather\Providers;

use App\Services\Weather\Weather;
use App\Services\Weather\WeatherProvider;

/**
 * Class LoggerProvider
 *
 * @package App\Services\Weather\Providers
 */
class LoggedProvider implements WeatherProvider
{
    /** @var WeatherProvider */
    private $provider;

    public function __construct(WeatherProvider $provider)
    {
        $this->provider = $provider;
    }

    /** {@inheritDoc} */
    public function getName(): string
    {
        return 'logged-' . $this->provider->getName();
    }

    /** {@inheritDoc} */
    public function getWeather(float $latitude, float $longitude): Weather
    {
        $startTime = microtime(true);

        try {
            $response = $this->provider->getWeather($latitude, $longitude);
            $endTime  = microtime(true);

            logger()->debug('Success get weather', [
                'provider'  => $this->provider->getName(),
                'latitude'  => $latitude,
                'longitude' => $longitude,
                'elapsed'   => $endTime - $startTime
            ]);

            return $response;
        } catch (\Throwable $ex) {
            $endTime = microtime(true);

            logger()->debug('Failed get weather', [
                'provider'  => $this->provider->getName(),
                'latitude'  => $latitude,
                'longitude' => $longitude,
                'elapsed'   => $endTime - $startTime
            ]);

            throw $ex;
        }
    }
}