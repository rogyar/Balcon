<?php

namespace App\Resolvers;

interface RouteResolverInterface
{
    public function process($route);

    public function getEntityResolver();

    public function setEntityResolver($resolverClassName);
}