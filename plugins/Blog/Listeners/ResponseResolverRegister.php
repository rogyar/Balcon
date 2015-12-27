<?php

namespace Plugins\Blog\Listeners;

use App\Events\ResponseResolversRegister;
use Plugins\Blog\Config\Plugin;
use Plugins\Blog\Resolvers\ResponseResolver;

class ResponseResolverRegister
{
    public function handle(ResponseResolversRegister $event)
    {
        /** @var \App\Core\Balcon $balcon */
        $balcon = $event->getContext();
        $balcon->getExtensionsContainer()->addResponseResolver(new ResponseResolver($balcon, new Plugin()));
    }
}