<?php

namespace App\Core;

interface BalconInterface
{
    /**
     * @return \App\Resolvers\RouteResolverInterface
     */
    function getRouteResolver();

    function setRouteResolver(\App\Resolvers\RouteResolverInterface $routeResolver);

    function getResponseResolver();
    function setResponseResolver($responseResolver);
}