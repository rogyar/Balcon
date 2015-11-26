<?php

namespace App\Resolvers;

use App\Core\EntityInterface;

interface RouteResolverInterface
{
    /**
     * @param string $route
     */
    public function process($route);

    /**
     * @return string
     */
    public function getRoute();

    /**
     * @param string $route
     */
    public function setRoute($route);

    /**
     * @return EntityInterface
     */
    public function getEntity();

    /**
     * @param EntityInterface $entity
     */
    public function setEntity(EntityInterface $entity);
}