<?php

namespace Plugins\Cms\Resolvers;

use App\Core\BalconInterface;
use App\Core\EntityInterface;
use App\Resolvers\EntityResolverInterface;
use App\Resolvers\RouteResolverInterface;
use Plugins\Cms\Model\Page;

/**
 * Class RouteResolver
 * @package Plugins\Cms\Resolvers
 *
 * Processes the requested route
 */
class RouteResolver implements RouteResolverInterface
{
    /**
     * The entity instance that can process current route
     * @var EntityInterface
     */
    protected $entity;
    /** @var  BalconInterface */
    protected $balcon;
    /** @var  string */
    protected $route;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @param BalconInterface $balcon
     */
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
     * Processes the requested route. Registers current resolver if
     * the route can be processed by CMS module
     *
     * @param string $route
     */
    public function process($route)
    {
        /* Check if route resolver has not been registered yet */
        if (!$this->balcon->getRouteResolver()) {
            $this->setRoute($route);
            $this->setEntity(new Page($route));
            $this->getEntity()->process();
            /* Check if the route can be processed by CMS module.
               if yes - register current resolver */
            if ($this->getEntity()->isProcessed()) {
                $this->balcon->setRouteResolver($this);
            }
        }
    }
}