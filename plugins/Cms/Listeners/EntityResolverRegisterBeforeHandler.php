<?php

namespace Plugins\Cms\Listeners;

use App\Events\EntityResolverRegisterBefore;

class EntityResolverRegisterBeforeHandler
{
    public function handle(EntityResolverRegisterBefore $event)
    {
        /** @var \App\Core\Balcon $balcon */
        $balcon = $event->getContext();
        $balcon->getExtensionsContainer()->setResolverImplementation([
            'resolver' => 'EntityResolver',
            'class'    => 'Plugins\Cms\Resolvers\EntityResolver'
        ]);
    }
}