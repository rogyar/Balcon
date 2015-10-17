<?php

namespace App\Resolvers;

interface RouteResolverInterface
{
    public function detectEntityType($route);

    public function registerEntityResolver();
}