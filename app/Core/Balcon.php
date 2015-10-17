<?php

namespace App\Core;

class Balcon implements BalconInterface
{
    protected $app;
    protected $routeResolver;
    protected $entityResolver;
    protected $entity;
    protected $responseResolver;
    protected $response;


    public function __construct($app)
    {
        $this->app = $app;
    }

    function getRouteResolver()
    {
        if (!$this->routeResolver) {
            $this->routeResolver = $this->app->make('RouteResolver');
        }
        return $this->routeResolver;
    }

    function setRouteResolver($routeResolver)
    {
        $this->routeResolver = $routeResolver;
    }

    function getEntityResolver()
    {
        if (!$this->entityResolver) {
            $this->entityResolver = $this->app->make('EntityResolver');
        }
        return $this->entityResolver;
    }

    function setEntityResolver($entityResolver)
    {
        $this->entityResolver = $entityResolver;
    }

    function getEntity()
    {
        return $this->entity;
    }

    function setEntity($entity)
    {
        $this->entity = $entity;
    }

    function getResponseResolver()
    {
        return $this->responseResolver;
    }

    function setResponseResolver($responseResolver)
    {
        $this->responseResolver = $responseResolver;
    }

    function getResponse()
    {
        return $this->response;
    }

    function setResponse($response)
    {
        $this->response = $response;
    }
}