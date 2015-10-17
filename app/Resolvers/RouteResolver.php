<?php

namespace App\Resolvers;

class RouteResolver implements RouteResolverInterface
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }
    public function detectEntityType($route)
    {
        // Detect entity type
         $this->registerEntityResolver();
    }

    public function registerEntityResolver()
    {
        $app = $this->app;
        $app->bind('EntityResolver', function($app) {
            return new EntityResolver($app);
        });

        // Event: entity resolver register after
    }
}