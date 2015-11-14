<?php

namespace Plugins\Cms\Resolvers;

use App\Core\BalconInterface;
use App\Resolvers\RouteResolverInterface;
use Plugins\Cms\Model\Page;

class RouteResolver implements RouteResolverInterface
{
    /** @var  BalconInterface */
    protected $balcon;

    /** @var  string */
    protected $entityResolver;
    protected $route;

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param mixed $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }
    protected $routeDispatched = false;

    /**
     * @param BalconInterface $balcon
     */
    public function __construct(BalconInterface $balcon)
    {
        $this->balcon = $balcon;
    }


    public function process($route)
    {
        $this->setRoute($route);
        // TODO: observer route_process_before pass $this

        // If the route has nod been dispatched - try to use standard route from the CMS plugin
        if (!$this->routeDispatched) {
            $this->setEntityResolver('Plugins\Cms\Resolvers\EntityResolver'); // Default value
            //$pageEntity = new Page();
            //$pageEntity->setRoute($route);
            //$this->balcon->setEntity($pageEntity);

        }

        return $this->getEntityResolver();
    }

    public function getEntityResolver()
    {
        return $this->entityResolver;
    }

    public function setEntityResolver($resolverClassName)
    {
        $this->entityResolver = $resolverClassName;
    }
}