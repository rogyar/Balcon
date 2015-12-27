<?php

namespace Plugins\Cms\Listeners;

use App\Events\ResponseRenderBefore;
use Plugins\Blog\Resolvers\ResponseResolver;
use Plugins\Cms\Resolvers\RouteResolver;

class ResponseRenderedBefore
{
    public function handle(ResponseRenderBefore $event)
    {
        /** @var \App\Core\Balcon $balcon */
        $balcon = $event->getContext();
        /** @var ResponseResolver $responseResolver */
        $responseResolver = $balcon->getResponseResolver();

        /* Check the current page is a blog page */
        if ($responseResolver->getPluginConfig()->getName() == 'Blog') {
            $page = $balcon->getRouteResolver()->getEntity();
            if ($responseResolver->checkPageIsBlogPost($page)) {
                // TODO: do the job here
            }
        }
    }
}