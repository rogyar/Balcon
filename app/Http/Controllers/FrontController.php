<?php

namespace App\Http\Controllers;

use App\Resolvers\ResponseResolverInterface;
use App\Resolvers\RouteResolverInterface;
use App\User;
use App\Http\Controllers\Controller;
use App\Events\RouteResolversRegister;
use App\Events\ResponseResolversRegister;
use Illuminate\Support\Facades\Response;
use Event;

class FrontController extends Controller
{
    public function frontRouter($route = '')
    {
        // FIXME: Do not process front route for static assets

        $app = app();
        /** @var \App\Core\Balcon $balcon */
        $balcon = $app->make('\App\Core\BalconInterface');

        /* Register extensions */
        $extensionsContainer = $balcon->getExtensionsContainer();

        /* Register route resolvers from all extensions */
        event(new RouteResolversRegister($balcon));

        /* Process all route resolvers */
        /** @var RouteResolverInterface $routeResolver */
        foreach ($extensionsContainer->getRouteResolversCollection() as $routeResolver)
        {
            $routeResolver->process($route);
        }

        if (!$balcon->getRouteResolver()) {
            return Response::view('errors.404', array(), 404);
        }

        /* Register response resolvers from all extensions */
        event(new ResponseResolversRegister($balcon));

        /* Process all response resolvers */
        /** @var ResponseResolverInterface $responseResolver */
        foreach ($extensionsContainer->getResponseResolversCollection() as $responseResolver)
        {
            $responseResolver->process();
        }

        return $balcon->getResponseResolver()->sendResponse();
    }
}