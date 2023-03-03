<?php

namespace App\Services\Weather\Providers;

use App\Services\Weather\Weather;
use App\Services\Weather\WeatherProvider;
use function Termwind\ValueObjects\p;

/**
 * Class FallbackProvider
 *
 * @package App\Services\Weather\Providers
 */
class FallbackProvider implements WeatherProvider
{
    /** @var array */
    private $providers = [];

    /**
     * @param WeatherProvider $provider
     *
     * @return FallbackProvider
     */
    public function addProvider(WeatherProvider $provider): FallbackProvider
    {
        $this->providers[] = $provider;

        return $this;
    }

    /** {@inheritDoc} */
    public function getName(): string
    {
        return 'fallback';
    }

    /** {@inheritDoc} */
    public function getWeather(float $latitude, float $longitude): Weather
    {
        /** @var WeatherProvider $provider */
        foreach ($this->providers as $provider) {
            try {
                return $provider->getWeather($latitude, $longitude);
            } catch (\Exception $ex) {
                logger()->error('Failed to get weather', [
                    'provider'  => $provider->getName(),
                    'exception' => $ex
                ]);
            }
        }

        throw new \RuntimeException('Failed to get weather, all providers failed');
    }
}