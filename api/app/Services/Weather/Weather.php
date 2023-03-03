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
    const CONDITION_ATMOSPHERE   = 'ATMOSPHERE';
    const CONDITION_OTHER        = 'OTHER';

    const TEMP_UNIT_CELSIUS    = 'CELSIUS';
    const TEMP_UNIT_FAHRENHEIT = 'FAHRENHEIT';
    const TEMP_UNIT_KELVIN     = 'KELVIN';

    const WIND_SPEED_UNIT_KMPH = 'KMH';
    const WIND_SPEED_UNIT_MPH  = 'MPH';
    const WIND_SPEED_UNIT_MPS  = 'MS';

    /** @var string */
    private $provider;
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

    /** @return string */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * @param string $provider
     *
     * @return Weather
     */
    public function setProvider(string $provider): Weather
    {
        $this->provider = $provider;

        return $this;
    }

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
    public function jsonSerialize(): mixed
    {
        return [
            'provider'         => $this->provider,
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

    /**
     * @param float $temperature
     * @param string $fromUnit
     * @param string $targetUnit
     *
     * @return float
     */
    public static function convertTemperatureUnit(float $temperature, string $fromUnit, string $targetUnit): float
    {
        $conversion = [
            self::TEMP_UNIT_CELSIUS    => [
                self::TEMP_UNIT_CELSIUS    => function ($val) {
                    return $val;
                },
                self::TEMP_UNIT_FAHRENHEIT => function ($val) {
                    return ($val * 9 / 5) + 32;
                },
                self::TEMP_UNIT_KELVIN     => function ($val) {
                    return $val + 273.15;
                }
            ],
            self::TEMP_UNIT_FAHRENHEIT => [
                self::TEMP_UNIT_FAHRENHEIT => function ($val) {
                    return $val;
                },
                self::TEMP_UNIT_CELSIUS    => function ($val) {
                    return ($val - 32) * 5 / 9;
                },
                self::TEMP_UNIT_KELVIN     => function ($val) {
                    return (($val - 32) * 5 / 9) + 273.15;
                }
            ],
            self::TEMP_UNIT_KELVIN     => [
                self::TEMP_UNIT_KELVIN     => function ($val) {
                    return $val;
                },
                self::TEMP_UNIT_CELSIUS    => function ($val) {
                    return $val - 273.15;
                },
                self::TEMP_UNIT_FAHRENHEIT => function ($val) {
                    return (($val - 273.15) * 9 / 5) + 32;
                }
            ]
        ];

        $fromUnit   = strtoupper($fromUnit);
        $targetUnit = strtoupper($targetUnit);

        return $conversion[$fromUnit][$targetUnit]($temperature);
    }

    /**
     * @param float $windSpeed
     * @param string $fromUnit
     * @param string $targetUnit
     *
     * @return float
     */
    public static function convertWindSpeedUnit(float $windSpeed, string $fromUnit, string $targetUnit): float
    {
        $conversion = [
            self::WIND_SPEED_UNIT_KMPH => [
                self::WIND_SPEED_UNIT_KMPH => function ($val) {
                    return $val;
                },
                self::WIND_SPEED_UNIT_MPH  => function ($val) {
                    return $val / 1.609344;
                },
                self::WIND_SPEED_UNIT_MPS  => function ($val) {
                    return $val / 3.6;
                }
            ],
            self::WIND_SPEED_UNIT_MPH  => [
                self::WIND_SPEED_UNIT_MPH  => function ($val) {
                    return $val;
                },
                self::WIND_SPEED_UNIT_KMPH => function ($val) {
                    return $val * 1.609344;
                },
                self::WIND_SPEED_UNIT_MPS  => function ($val) {
                    return $val / 2.237;
                }
            ],
            self::WIND_SPEED_UNIT_MPS  => [
                self::WIND_SPEED_UNIT_MPS  => function ($val) {
                    return $val;
                },
                self::WIND_SPEED_UNIT_KMPH => function ($val) {
                    return $val * 3.6;
                },
                self::WIND_SPEED_UNIT_MPH  => function ($val) {
                    return $val * 2.237;
                }
            ]
        ];

        $fromUnit   = strtoupper($fromUnit);
        $targetUnit = strtoupper($targetUnit);

        return $conversion[$fromUnit][$targetUnit]($windSpeed);
    }
}