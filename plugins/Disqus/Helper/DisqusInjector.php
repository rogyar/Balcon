<?php

namespace Plugins\Disqus\Helper;


use App\Core\PluginInterface;
use Plugins\Blog\Resolvers\ResponseResolver;
use Plugins\Cms\Model\Page;
use App\Resolvers\ResponseResolverInterface;

class DisqusInjector
{
    /** @var  ResponseResolver */
    protected $responseResolver;
    /** @var  Page  */
    protected $page;

    public function __construct(ResponseResolverInterface $responseResolver, Page $page)
    {
        $this->responseResolver = $responseResolver;
        $this->page = $page;
    }

    public function injectDisqusComments()
    {
        /* Add additional necessary page parameters */
        $disqusParams = [
            'pageUrl' => $this->page->getRoute(),
            'pageId' => $this->page->getRoute(),
        ];

        $existingParameters = $this->responseResolver->getRenderer()->getPageParameters();
        $this->responseResolver->getRenderer()->setPageParameters(
            array_merge($existingParameters, $disqusParams)
        );
    }
}