<?php

namespace Plugins\Cms\Resolvers;

use App\Core\BalconInterface;
use App\Resolvers\EntityResolverInterface;
use Plugins\Cms\Model\Page;
use App\Core\EntityInterface;

/**
 * Class EntityResolver
 * @package Plugins\Cms\Resolvers
 *
 * Handles an entity that connected to the requested route
 */
class EntityResolver implements EntityResolverInterface
{
    /** @var BalconInterface */
    protected $balcon;
    /** @var  EntityResolverInterface */
    protected $entity;

    public function __construct(BalconInterface $balcon)
    {
        $this->balcon = $balcon;
    }

    /**
     * @return EntityInterface
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param EntityInterface $entity
     */
    public function setEntity(EntityInterface $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Handles a page connected to the processes route
     */
    public function process()
    {
        // TODO: observer entity_process_before pass $this (?)
        $routeResolver = $this->balcon->getRouteResolver();
        $page = new Page($routeResolver->getRoute());
        $page->process();
        $this->setEntity($page);
    }
}