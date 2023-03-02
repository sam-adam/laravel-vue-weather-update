<?php

namespace App\Services\Weather;

use Carbon\Carbon;

/**
 * Class Weather
 *
 * @package App\Services\Weather
 */
class Weather implements \JsonSerializable
{
    const CONDITION_CLEAR        = 'CLEAR';
    const CONDITION_CLOUDY       = 'CLOUDY';
    const CONDITION_FOG          = 'FOG';
    const CONDITION_DRIZZLE      = 'DRIZZLE';
    const CONDITION_RAIN         = 'RAIN';
    const CONDITION_RAIN_SHOWER  = 'RAIN_SHOWER';
    const CONDITION_THUNDERSTORM = 'THUNDERSTORM';
    const CONDITION_SNOW         = 'SNOW';
    const CONDITION_SNOW_SHOWER  = 'SNOW_SHOWER';
    const CONDITION_OTHER        = 'OTHER';

    const TEMP_UNIT_CELSIUS    = 'CELSIUS';
    const TEMP_UNIT_FAHRENHEIT = 'FAHRENHEIT';

    const WIND_SPEED_UNIT_KMPH = 'KMH';
    const WIND_SPEED_UNIT_MPH  = 'MPH';
    const WIND_SPEED_UNIT_MPS  = 'MS';

    /** @var Carbon */
    private $time;
    /** @var float */
    private $latitude;
    /** @var float */
    private $longitude;
    /** @var string */
    private $label;
    /** @var string */
    private $description;
    /** @var float */
    private $temperature;
    /** @var string */
    private $temperatureUnit;
    /** @var float */
    private $windSpeed;
    /** @var string */
    private $windSpeedUnit;
    /** @var float */
    private $windDirection;

    /** @return Carbon */
    public function getTime(): Carbon
    {
        return $this->time;
    }

    /**
     * @param Carbon $time
     *
     * @return Weather
     */
    public function setTime(Carbon $time): Weather
    {
        $this->time = $time;

        return $this;
    }

    /** @return float */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     *
     * @return Weather
     */
    public function setLatitude(float $latitude): Weather
    {
        $this->latitude = $latitude;

        return $this;
    }

    /** @return float */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     *
     * @return Weather
     */
    public function setLongitude(float $longitude): Weather
    {
        $this->longitude = $longitude;

        return $this;
    }

    /** @return string */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return Weather
     */
    public function setLabel(string $label): Weather
    {
        $this->label = $label;

        return $this;
    }

    /** @return string */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Weather
     */
    public function setDescription(string $description): Weather
    {
        $this->description = $description;

        return $this;
    }

    /** @return float */
    public function getTemperature(): float
    {
        return $this->temperature;
    }

    /** @return string */
    public function getTemperatureUnit(): string
    {
        return $this->temperatureUnit;
    }

    /**
     * @param float $temperature
     * @param string $temperatureUnit
     *
     * @return Weather
     */
    public function setTemperature(float $temperature, string $temperatureUnit): Weather
    {
        $this->temperature     = $temperature;
        $this->temperatureUnit = $temperatureUnit;

        return $this;
    }

    /** @return float */
    public function getWindSpeed(): float
    {
        return $this->windSpeed;
    }

    /** @return string */
    public function getWindSpeedUnit(): string
    {
        return $this->windSpeedUnit;
    }

    /**
     * @param float $windSpeed
     * @param string $windSpeedUnit
     *
     * @return Weather
     */
    public function setWindSpeed(float $windSpeed, string $windSpeedUnit): Weather
    {
        $this->windSpeed     = $windSpeed;
        $this->windSpeedUnit = $windSpeedUnit;

        return $this;
    }

    /** @return float */
    public function getWindDirection(): float
    {
        return $this->windDirection;
    }

    /**
     * @param float $windDirection
     *
     * @return Weather
     */
    public function setWindDirection(float $windDirection): Weather
    {
        $this->windDirection = $windDirection;

        return $this;
    }

    /** @inheritDoc */
    public function jsonSerialize()
    {
        return [
            'time'             => $this->time->unix(),
            'latitude'         => $this->latitude,
            'longitude'        => $this->longitude,
            'label'            => $this->label,
            'description'      => $this->description,
            'temperature'      => $this->temperature,
            'temperature_unit' => $this->temperatureUnit,
            'wind_speed'       => $this->windSpeed,
            'wind_speed_unit'  => $this->windSpeedUnit,
            'wind_direction'   => $this->windDirection,
        ];
    }
}