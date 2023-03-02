<?php

namespace App\Services\Weather;

/**
 * Interface WeatherProvider
 *
 * @package App\Services\Weather
 */
interface WeatherProvider
{
    /**
     * Get weather based on a single coordinate
     *
     * @param float $latitude
     * @param float $longitude
     *
     * @return Weather|null
     */
    public function getWeather(float $latitude, float $longitude): Weather;
}