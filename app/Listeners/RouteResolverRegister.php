<?php

namespace App\Listeners;

use App\Events\RouteResolverRegisteredAfter;

class RouteResolverRegister
{
    public function __construct()
    {

    }

    public function handle(RouteResolverRegisteredAfter $event)
    {
        /* Here is event handler */

        // Attempt to rebind
//        $event->app->bind('RouteResolver', function() {
//            return new AlternativeRouteResolver();
//        });
    }
}