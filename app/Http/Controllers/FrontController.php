<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use App\Resolvers\RouteResolver;
use App\Events\RouteResolverRegisteredAfter;
use Event;

class FrontController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @return Response
     */
    public function frontRouter($page = '', $subpage = '')
    {
        $app = app();
        $app->bind('RouteResolver', function($app) {
            return new RouteResolver($app);
        });

        // Event: route resolver register after
        event(new RouteResolverRegisteredAfter($app));

        // Call Route Resolver
        $app->make('Balcon')->getRouteResolver()->detectEntityType($page.$subpage);

        // Call Entity Resolver
        $test = $app->make('Balcon')->getEntityResolver()->process();

        // Call Response resolver

        // Event: Response sent before

        // Send response


        return $test;
    }
}