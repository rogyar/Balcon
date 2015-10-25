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
        $balcon = $app->make('\App\Core\BalconInterface');

        // Event: route resolver register before

        // FIXME: temporary implementation registation. Should be done inside of an extension
        $balcon->getExtensionsContainer()->setResolverImplementation([
            'resolver' => 'RouteResolver',
            'class'    => '\App\Resolvers\RouteResolver'
        ]);

        // Assign an implementation of the Router Resolver
        $app->bind(
            '\App\Resolvers\RouteResolverInterface',
            $balcon->getExtensionsContainer()->getResolverImplementation('RouteResolver')
        );

        event(new RouteResolverRegisteredAfter($balcon));

        // Call Route Resolver
        $balcon->getRouteResolver()->detectEntityType($page.$subpage);


        // FIXME: temporary implementation registation. Should be done inside of an extension
        $balcon->getExtensionsContainer()->setResolverImplementation([
            'resolver' => 'EntityResolver',
            'class'    => '\App\Resolvers\EntityResolver'
        ]);

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