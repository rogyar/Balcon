<?php

namespace Plugins\Blog\Resolvers;

use App\Resolvers\RouteResolverInterface;
use Plugins\Blog\Model\Post;

/**
 * Class RouteResolver
 * @package Plugins\Cms\Resolvers
 *
 * Processes the requested route
 */
class RouteResolver extends \Plugins\Cms\Resolvers\RouteResolver
    implements RouteResolverInterface
{
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
            /* Check if route can be processed by this extension */
            if (preg_match('/^(\/blog)(\/|$)/', $route)) {
                $this->setRoute($route);
                $this->setEntity(new Post($route));
                $this->getEntity()->process();
                /* Check if the route can be processed by CMS module.
                   if yes - register current resolver */
                if ($this->getEntity()->isProcessed()) {
                    $this->balcon->setRouteResolver($this);
                }
            }
        }
    }
}