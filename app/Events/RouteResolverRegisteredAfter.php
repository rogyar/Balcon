<?php

namespace App\Events;

class RouteResolverRegisteredAfter extends Event
{
    public $app;

    public function __construct($app)
    {
        $this->app = $app;
    }
}
