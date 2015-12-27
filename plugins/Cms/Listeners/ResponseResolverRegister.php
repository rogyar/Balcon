<?php

namespace Plugins\Cms\Listeners;

use App\Events\ResponseResolversRegister;
use Plugins\Cms\Config\Plugin;
use Plugins\Cms\Resolvers\ResponseResolver;

class ResponseResolverRegister
{
    public function handle(ResponseResolversRegister $event)
    {
        /** @var \App\Core\Balcon $balcon */
        $balcon = $event->getContext();
        $balcon->getExtensionsContainer()->addResponseResolver(new ResponseResolver($balcon, new Plugin()));
    }
}