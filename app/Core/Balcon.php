<?php

namespace App\Core;

use App\Resolvers\RouteResolverFactory;

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
        return $this->routeResolver;
    }

    public function setRouteResolver(\App\Resolvers\RouteResolverInterface $routeResolver)
    {
        $this->routeResolver = $routeResolver;
    }

    public function getResponseResolver()
    {
        return $this->responseResolver;
    }

    public function setResponseResolver($responseResolver)
    {
        $this->responseResolver = $responseResolver;
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