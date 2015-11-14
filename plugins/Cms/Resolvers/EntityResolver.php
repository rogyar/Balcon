<?php

namespace Plugins\Cms\Resolvers;

use App\Core\BalconInterface;
use App\Resolvers\EntityResolverInterface;
use Plugins\Cms\Model\Page;
use App\Core\EntityInterface;

class EntityResolver implements EntityResolverInterface
{
    /**
     * @var BalconInterface
     */
    protected $balcon;

    protected $entity;

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     */
    public function setEntity(EntityInterface $entity)
    {
        $this->entity = $entity;
    }

    public function __construct(BalconInterface $balcon)
    {
        $this->balcon = $balcon;
    }

    public function process()
    {
        // TODO: observer entity_process_before pass $this (?)
        $routeResolver = $this->balcon->getRouteResolver();
        $page = new Page($routeResolver->getRoute());
        $page->process();
        $this->setEntity($page);
    }
}