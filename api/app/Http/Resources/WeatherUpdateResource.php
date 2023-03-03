<?php

namespace App\Http\Resources;

use App\Services\Weather\Weather;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeatherUpdateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $temperatureUnit = $request->get('temperature-unit', $this->temperature_unit);
        $windSpeedUnit   = $request->get('wind-speed-unit', $this->wind_speed_unit);

        return [
            'id'               => $this->id,
            'provider'         => $this->provider,
            'label'            => $this->label,
            'time'             => $this->time->unix(),
            'description'      => $this->description,
            'latitude'         => $this->latitude,
            'longitude'        => $this->longitude,
            'temperature'      => Weather::convertTemperatureUnit($this->temperature, $this->temperature_unit, $temperatureUnit),
            'temperature_unit' => $temperatureUnit,
            'wind_speed'       => Weather::convertWindSpeedUnit($this->wind_speed, $this->wind_speed_unit, $windSpeedUnit),
            'wind_speed_unit'  => $windSpeedUnit,
            'wind_direction'   => $this->wind_direction,
        ];
    }
}
