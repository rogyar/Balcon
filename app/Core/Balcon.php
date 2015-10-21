<?php

namespace App\Core;

class Balcon implements BalconInterface
{
    /** @var  \Illuminate\Foundation\Application */
    protected $app;
    protected $routeResolver;
    protected $entityResolver;
    protected $entity;
    protected $responseResolver;
    protected $response;
    protected $extenssionsContainer;


    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * @inheritdoc
     */
    public function getRouteResolver()
    {
        if (!$this->routeResolver) {
            /* FIXME: instantiate a new class and inject '\App\Resolvers\RouteResolverInterface' there
               Supposed that we already have an implementation assigned to the interface
            */

            $this->routeResolver = $this->app->make('\App\Resolvers\RouteResolverInterface');
        }
        return $this->routeResolver;
    }

    public function setRouteResolver(\App\Resolvers\RouteResolverInterface $routeResolver)
    {
        $this->routeResolver = $routeResolver;
    }

    public function getEntityResolver()
    {
        if (!$this->entityResolver) {
            $this->entityResolver = $this->app->make('EntityResolver');
        }
        return $this->entityResolver;
    }

    public function setEntityResolver($entityResolver)
    {
        $this->entityResolver = $entityResolver;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function getResponseResolver()
    {
        return $this->responseResolver;
    }

    public function setResponseResolver($responseResolver)
    {
        $this->responseResolver = $responseResolver;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return \App\Core\ExtensionsCollector
     */
    public function getExtensionsContainer()
    {
        if (null == $this->extenssionsContainer) {
            $this->extenssionsContainer = $this->collectExtensions();
        }

        return $this->extenssionsContainer;
    }

    /**
     * @return \App\Core\ExtensionsCollector
     */
    protected function collectExtensions()
    {
        $this->extenssionsContainer = new ExtensionsCollector();

        return $this->extenssionsContainer;
    }
}