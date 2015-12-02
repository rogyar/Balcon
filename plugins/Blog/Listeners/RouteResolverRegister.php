<?php

namespace Plugins\Blog\Listeners;

use App\Events\RouteResolversRegister;
use Plugins\Blog\Resolvers\RouteResolver;

class RouteResolverRegister
{
    public function handle(RouteResolversRegister $event)
    {
        /** @var \App\Core\Balcon $balcon */
        $balcon = $event->getContext();
        $balcon->getExtensionsContainer()->addRouteResolver(new RouteResolver($balcon));
    }
}