<?php

namespace STS\RouteContext;

use Closure;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;

class RouteContextServiceProvider extends ServiceProvider
{
    public function register()
    {
        Route::macro('with', function (array $context) {
            $this->context = $context;

            return $this;
        });

        Route::macro('context', function () {
            return (array)$this->context;
        });

        $this->app['router']->matched(function (RouteMatched $event) {
            foreach ($event->route->context() as $key => $value) {
                $event->route->setParameter($key, $value);

                if ($value instanceof Closure) {
                    $this->app['router']->bind($key, fn() => value($value));
                }
            }
        });
    }
}
