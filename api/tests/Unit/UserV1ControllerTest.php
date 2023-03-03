<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\Weather\Weather;
use App\Services\Weather\WeatherProvider;
use Carbon\Carbon;
use Mockery\MockInterface;
use Tests\ApiBaseTest;

/**
 * Class UserV1ControllerTest
 *
 * @package Tests\Unit
 */
class UserV1ControllerTest extends ApiBaseTest
{
    public function test_getAllUsers()
    {
        User::factory(3)->create();

        $res = $this->getJson('/v1/users');
        $res->assertOk();
        $res->assertJsonIsArray();
        $res->assertJsonCount(3);
        $res->assertJsonStructure([
            0 => [
                'id',
                'name',
                'latitude',
                'lastWeatherUpdate',
                'weatherUpdates',
            ]
        ]);
    }

    public function test_refreshWeather()
    {
        $dummyWeather = (new Weather())
            ->setProvider('mock')
            ->setTime(Carbon::create(2023, 03, 03, 01, 01, 01))
            ->setLabel(Weather::CONDITION_SNOW)
            ->setDescription('Test description')
            ->setLatitude(1)
            ->setLongitude(2)
            ->setTemperature(10, Weather::TEMP_UNIT_CELSIUS)
            ->setWindSpeed(20, Weather::WIND_SPEED_UNIT_KMPH)
            ->setWindDirection(30);

        $mockWeatherProvider = $this->mock(WeatherProvider::class, function (MockInterface $mock) use ($dummyWeather) {
            $mock->shouldReceive('getWeather')
                ->once()
                ->andReturn($dummyWeather);
        });

        $user = User::factory(1)->createOne();

        $res = $this->postJson('/v1/users/' . $user->id . '/refresh-weather');
        $res->assertOk();
        $res->assertExactJson(['message' => 'success']);

        $res = $this->getJson('/v1/users/' . $user->id);
        $res->assertOk();
        $res->assertJsonIsArray('weatherUpdates');

        $weatherUpdate = $res->json('weatherUpdates.0');

        $this->assertEquals($dummyWeather->getProvider(), $weatherUpdate['provider']);
        $this->assertEquals($dummyWeather->getTime()->unix(), $weatherUpdate['time']);
        $this->assertEquals($dummyWeather->getLabel(), $weatherUpdate['label']);
        $this->assertEquals($dummyWeather->getDescription(), $weatherUpdate['description']);
        $this->assertEquals($dummyWeather->getTemperature(), $weatherUpdate['temperature']);
        $this->assertEquals($dummyWeather->getTemperatureUnit(), $weatherUpdate['temperature_unit']);
        $this->assertEquals($dummyWeather->getWindSpeed(), $weatherUpdate['wind_speed']);
        $this->assertEquals($dummyWeather->getWindSpeedUnit(), $weatherUpdate['wind_speed_unit']);
        $this->assertEquals($dummyWeather->getWindDirection(), $weatherUpdate['wind_direction']);
    }

    public function test_unitConversion()
    {
        $tempFromUnit        = Weather::TEMP_UNIT_CELSIUS;
        $tempTargetUnit      = Weather::TEMP_UNIT_FAHRENHEIT;
        $windSpeedFromUnit   = Weather::WIND_SPEED_UNIT_KMPH;
        $windSpeedTargetUnit = Weather::WIND_SPEED_UNIT_MPH;

        $dummyWeather = (new Weather())
            ->setProvider('mock')
            ->setTime(Carbon::create(2023, 03, 03, 01, 01, 01))
            ->setLabel(Weather::CONDITION_SNOW)
            ->setDescription('Test description')
            ->setLatitude(1)
            ->setLongitude(2)
            ->setTemperature(10, $tempFromUnit)
            ->setWindSpeed(20, $windSpeedFromUnit)
            ->setWindDirection(30);

        $mockWeatherProvider = $this->mock(WeatherProvider::class, function (MockInterface $mock) use ($dummyWeather) {
            $mock->shouldReceive('getWeather')
                ->once()
                ->andReturn($dummyWeather);
        });

        $user = User::factory(1)->createOne();

        $this->postJson('/v1/users/' . $user->id . '/refresh-weather');

        $res = $this->getJson('/v1/users/' . $user->id . '?temperature-unit=' . $tempTargetUnit . '&wind-speed-unit=' . $windSpeedTargetUnit);
        $res->assertOk();
        $res->assertJsonIsArray('weatherUpdates');

        $weatherUpdate         = $res->json('weatherUpdates.0');
        $expConvertedTemp      = Weather::convertTemperatureUnit($dummyWeather->getTemperature(), $tempFromUnit, $tempTargetUnit);
        $expConvertedWindSpeed = Weather::convertWindSpeedUnit($dummyWeather->getWindSpeed(), $windSpeedFromUnit, $windSpeedTargetUnit);

        $this->assertEquals($dummyWeather->getProvider(), $weatherUpdate['provider']);
        $this->assertEquals($dummyWeather->getTime()->unix(), $weatherUpdate['time']);
        $this->assertEquals($dummyWeather->getLabel(), $weatherUpdate['label']);
        $this->assertEquals($dummyWeather->getDescription(), $weatherUpdate['description']);
        $this->assertEquals($expConvertedTemp, $weatherUpdate['temperature']);
        $this->assertEquals($tempTargetUnit, $weatherUpdate['temperature_unit']);
        $this->assertEquals($expConvertedWindSpeed, $weatherUpdate['wind_speed']);
        $this->assertEquals($windSpeedTargetUnit, $weatherUpdate['wind_speed_unit']);
        $this->assertEquals($dummyWeather->getWindDirection(), $weatherUpdate['wind_direction']);
    }
}