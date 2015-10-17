<?php

namespace App\Resolvers;

class EntityResolver implements EntityResolverInterface
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function process()
    {
        return 'Test passed';
    }
}