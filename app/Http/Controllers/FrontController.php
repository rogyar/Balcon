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
        /** @var \App\Core\Balcon $balcon */
        $balcon = $app->make('Balcon');

        // Event: route resolver register before

        // Assign an implementation of the Router Resolver
        $app->bind(
            '\App\Resolvers\RouteResolverInterface',
            $balcon->getExtensionsContainer()->getResolverImplementation('RouteResolver')
        );

        event(new RouteResolverRegisteredAfter($balcon));

        // Call Route Resolver
        $balcon->getRouteResolver()->detectEntityType($page.$subpage);

        // Call Entity Resolver
        $test = $balcon->getEntityResolver()->process();

        // Call Response resolver

        // Event: Response sent before

        // Send response

        return $test;
    }
}