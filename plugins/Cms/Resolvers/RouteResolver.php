<?php

namespace Plugins\Cms\Resolvers;

use App\Core\BalconInterface;
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
    /** @var  BalconInterface */
    protected $balcon;
    /** @var  string */
    protected $entityResolver;
    /** @var  string */
    protected $route;
    /** @var bool */
    protected $routeDispatched = false;

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
     * Processes the requested route. If the requested route has not been
     * dispatched in an observer - assign Cms Page entity resolver for
     * the further processing
     *
     * @param string $route
     * @return EntityResolverInterface
     */
    public function process($route)
    {
        $this->setRoute($route);
        // TODO: observer route_process_before pass $this

        // If the route has nod been dispatched - try to use standard route from the CMS plugin
        if (!$this->routeDispatched) {
            $this->setEntityResolver('Plugins\Cms\Resolvers\EntityResolver'); // Default value
        }

        return $this->getEntityResolver();
    }

    /**
     * @return EntityResolverInterface
     */
    public function getEntityResolver()
    {
        return $this->entityResolver;
    }

    /**
     * @param string $resolverClassName
     */
    public function setEntityResolver($resolverClassName)
    {
        $this->entityResolver = $resolverClassName;
    }
}