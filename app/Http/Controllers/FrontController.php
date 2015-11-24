<?php

namespace App\Http\Controllers;

use App\Events\RouteResolverRegisterBefore;
use App\User;
use App\Http\Controllers\Controller;
use App\Events\RouteResolverRegisterAfter;
use App\Events\EntityResolverRegisterBefore;
use App\Events\ResponseResolverRegisterBefore;
use App\Events\ResponseResolverRegisterAfter;
use Event;

class FrontController extends Controller
{
    public function frontRouter($page = '')
    {
        // FIXME: Do not process front route for static assets

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
        $balcon->getRouteResolver()->process($page);

        event(new EntityResolverRegisterBefore($balcon));

        // Assign an implementation of the Entity Resolver
        $app->bind(
            '\App\Resolvers\EntityResolverInterface',
            $balcon->getExtensionsContainer()->getResolverImplementation('EntityResolver')
        );

        // Call Entity Resolver
        $balcon->getEntityResolver()->process();

        event(new ResponseResolverRegisterBefore($balcon));

        $app->bind('\App\Resolvers\ResponseResolverInterface',
            $balcon->getExtensionsContainer()->getResolverImplementation('ResponseResolver')
        );

        event(new ResponseResolverRegisterAfter($balcon));

        $balcon->getResponseResolver()->process();

        // todo: event response send before

        return $balcon->getResponseResolver()->sendResponse();
    }
}