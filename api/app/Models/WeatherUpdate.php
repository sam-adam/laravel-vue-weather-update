<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class WeatherUpdate
 *
 * @package App\Models
 *
 * @property User $user
 * @property int $user_id
 * @property string $provider
 * @property Carbon $time
 * @property float $latitude
 * @property float $longitude
 * @property string $label
 * @property string $description
 * @property float $temperature
 * @property string $temperature_unit
 * @property float $wind_speed
 * @property string $wind_speed_unit
 * @property float $wind_direction
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class WeatherUpdate extends Model
{
    /** @var array */
    protected $casts = [
        'time' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}