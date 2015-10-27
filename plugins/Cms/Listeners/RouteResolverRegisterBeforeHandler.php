<?php

namespace Plugins\Cms\Listeners;

use App\Events\RouteResolverRegisterBefore;

class RouteResolverRegisterBeforeHandler
{
    public function handle(RouteResolverRegisterBefore $event)
    {
        /** @var \App\Core\Balcon $balcon */
        $balcon = $event->getContext();
        $balcon->getExtensionsContainer()->setResolverImplementation([
            'resolver' => 'RouteResolver',
            'class'    => 'Plugins\Cms\Resolvers\RouteResolver'
        ]);
    }
}