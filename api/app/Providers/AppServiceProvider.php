<?php

namespace App\Providers;

use App\Services\Weather\Providers\FallbackProvider;
use App\Services\Weather\Providers\LoggedProvider;
use App\Services\Weather\Providers\OpenMeteo;
use App\Services\Weather\Providers\OpenWeather;
use App\Services\Weather\WeatherProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(WeatherProvider::class, function (): WeatherProvider {
            $openMeteo   = new OpenMeteo();
            $openWeather = new OpenWeather(config('services.openweather.appid'));

            return (new FallbackProvider())
                ->addProvider(new LoggedProvider($openWeather))
                ->addProvider(new LoggedProvider($openMeteo));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
