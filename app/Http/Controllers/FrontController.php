<?php

namespace App\Http\Controllers;

use App\Events\RouteResolverRegisterBefore;
use App\User;
use App\Http\Controllers\Controller;
use App\Events\RouteResolverRegisterAfter;
use App\Events\EntityResolverRegisterBefore;
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
        $balcon = $app->make('\App\Core\BalconInterface');

        event(new RouteResolverRegisterBefore($balcon));

        // Assign an implementation of the Router Resolver
        $app->bind(
            '\App\Resolvers\RouteResolverInterface',
            $balcon->getExtensionsContainer()->getResolverImplementation('RouteResolver')
        );

        event(new RouteResolverRegisterAfter($balcon));

        // Call Route Resolver
        $balcon->getRouteResolver()->process($page.$subpage);

        event(new EntityResolverRegisterBefore($balcon));

        // Assign an implementation of the Entity Resolver
        $app->bind(
            '\App\Resolvers\EntityResolverInterface',
            $balcon->getExtensionsContainer()->getResolverImplementation('EntityResolver')
        );

        // Call Entity Resolver
        $test = $balcon->getEntityResolver()->process();

        // Call Response resolver

        // Event: Response sent before

        // Send response

        return $test;
    }
}