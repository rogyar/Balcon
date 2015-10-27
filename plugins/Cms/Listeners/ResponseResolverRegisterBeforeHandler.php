<?php

namespace Plugins\Cms\Listeners;

use App\Events\ResponseResolverRegisterBefore;

class ResponseResolverRegisterBeforeHandler
{
    public function handle(ResponseResolverRegisterBefore $event)
    {
        /** @var \App\Core\Balcon $balcon */
        $balcon = $event->getContext();
        $balcon->getExtensionsContainer()->setResolverImplementation([
            'resolver' => 'ResponseResolver',
            'class'    => 'Plugins\Cms\Resolvers\ResponseResolver'
        ]);
    }
}