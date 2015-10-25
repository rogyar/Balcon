<?php

namespace App\Core;

interface BalconInterface
{
    /**
     * @return \App\Resolvers\RouteResolverInterface
     */
    function getRouteResolver();

    function setRouteResolver(\App\Resolvers\RouteResolverInterface $routeResolver);

    /**
     * @return \App\Resolvers\EntityResolverInterface
     */
    function getEntityResolver();

    function setEntityResolver($entityResolver);
    function getEntity();
    function setEntity($entity);
    function getResponseResolver();
    function setResponseResolver($responseResolver);
    function getResponse();
    function setResponse($response);
}