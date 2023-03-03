<?php

namespace Tests\Unit;

use App\Services\Weather\Weather;
use PHPUnit\Framework\TestCase;

class WeatherTest extends TestCase
{
    private $tempConversions = [
        [Weather::TEMP_UNIT_CELSIUS, Weather::TEMP_UNIT_CELSIUS, 10, 10, 0],
        [Weather::TEMP_UNIT_CELSIUS, Weather::TEMP_UNIT_FAHRENHEIT, 10, 50, 0],
        [Weather::TEMP_UNIT_CELSIUS, Weather::TEMP_UNIT_KELVIN, 10, 283.15, 2],
        [Weather::TEMP_UNIT_FAHRENHEIT, Weather::TEMP_UNIT_FAHRENHEIT, 10, 10, 0],
        [Weather::TEMP_UNIT_FAHRENHEIT, Weather::TEMP_UNIT_CELSIUS, 10, -12.2222, 4],
        [Weather::TEMP_UNIT_FAHRENHEIT, Weather::TEMP_UNIT_KELVIN, 10, 260.928, 3],
        [Weather::TEMP_UNIT_KELVIN, Weather::TEMP_UNIT_KELVIN, 10, 10, 0],
        [Weather::TEMP_UNIT_KELVIN, Weather::TEMP_UNIT_CELSIUS, 10, -263.15, 2],
        [Weather::TEMP_UNIT_KELVIN, Weather::TEMP_UNIT_FAHRENHEIT, 10, -441.67, 2],
    ];

    private $windSpeedConversions = [
        [Weather::WIND_SPEED_UNIT_KMPH, Weather::WIND_SPEED_UNIT_KMPH, 10, 10, 0],
        [Weather::WIND_SPEED_UNIT_KMPH, Weather::WIND_SPEED_UNIT_MPH, 10, 6.21, 2],
        [Weather::WIND_SPEED_UNIT_KMPH, Weather::WIND_SPEED_UNIT_MPS, 10, 2.78, 2],
        [Weather::WIND_SPEED_UNIT_MPH, Weather::WIND_SPEED_UNIT_MPH, 10, 10, 0],
        [Weather::WIND_SPEED_UNIT_MPH, Weather::WIND_SPEED_UNIT_KMPH, 10, 16.0934, 4],
        [Weather::WIND_SPEED_UNIT_MPH, Weather::WIND_SPEED_UNIT_MPS, 10, 4.47, 2],
        [Weather::WIND_SPEED_UNIT_MPS, Weather::WIND_SPEED_UNIT_MPS, 10, 10, 0],
        [Weather::WIND_SPEED_UNIT_MPS, Weather::WIND_SPEED_UNIT_KMPH, 10, 36, 0],
        [Weather::WIND_SPEED_UNIT_MPS, Weather::WIND_SPEED_UNIT_MPH, 10, 22.37, 2],
    ];

    public function test_temperatureConversions(): void
    {
        foreach ($this->tempConversions as $case) {
            $from      = $case[0];
            $target    = $case[1];
            $value     = $case[2];
            $expected  = $case[3];
            $precision = $case[4];

            $weather = new Weather();
            $weather->setTemperature($value, $from);

            $this->assertEquals($expected, round($weather->convertTemperatureUnit($target), $precision));
        }
    }

    public function test_windSpeedConversions(): void
    {
        foreach ($this->windSpeedConversions as $case) {
            $from      = $case[0];
            $target    = $case[1];
            $value     = $case[2];
            $expected  = $case[3];
            $precision = $case[4];

            $weather = new Weather();
            $weather->setWindSpeed($value, $from);

            $this->assertEquals($expected, round($weather->convertWindSpeedUnit($target), $precision));
        }
    }
}
