<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * @package App\Models
 *
 * @property Collection $weatherUpdates
 * @property WeatherUpdate|null $lastWeatherUpdate
 * @property int $id
 * @property string $name
 * @property float $latitude
 * @property float $longitude
 * @property int|null $last_weather_update_id
 * @property Carbon|null $last_weather_update_at
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at'      => 'datetime',
        'last_weather_update_at' => 'datetime',
    ];

    public function weatherUpdates(): HasMany
    {
        return $this->hasMany(WeatherUpdate::class)->orderByDesc('id');
    }

    public function lastWeatherUpdate(): HasOne
    {
        return $this->hasOne(WeatherUpdate::class, 'id', 'last_weather_update_id');
    }
}
