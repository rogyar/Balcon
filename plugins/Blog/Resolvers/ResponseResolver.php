<?php

namespace Plugins\Blog\Resolvers;

use App\Core\BalconInterface;
use App\Core\EntityInterface;
use App\Resolvers\ResponseResolverInterface;
use Plugins\Cms\Helper\Renderer;
use Plugins\Blog\Config\Plugin;
use Plugins\Blog\Helper\Renderer as BlogIndexRenderer;
use Plugins\Cms\Model\Page;
use Plugins\Cms\Processors\TemplatesProcessor;

/**
 * Class ResponseResolver
 * @package Plugins\Cms\Resolvers
 *
 * Processes response for the handled entity
 */
class ResponseResolver extends \Plugins\Cms\Resolvers\ResponseResolver
    implements ResponseResolverInterface
{
    /**
     * Processes a response for the handled Entity (CMS page by default)
     *
     * @throws \Exception
     */
    public function process()
    {
        $routeResolver = $this->balcon->getRouteResolver();
        /** @var Page $entity */
        $entity = $routeResolver->getEntity();
        if ($this->requestCanBeHandled($entity)) {
            /* Register current resolver as response resolver */
            $this->balcon->setResponseResolver($this);

            $this->setResponse(
                $this->processBlogPage($entity)
            );
        }
    }

    /**
     * Processes response for the handled Blog page
     *
     * @param Page $page
     * @return string
     * @throws \Exception
     */
    protected function processBlogPage(Page $page)
    {
        $dispatchedPage = $page->getDispatchedBlock();
        if ($dispatchedPage) {

            /* Check the route to identify the blog page type index or post */
            // TODO: implement as a separate routine that has a list of routes for listofposts page
            $pageIsBlogpost = (count(explode('/', $page->getRoute())) > 1);
            $defaultTemplate = ($pageIsBlogpost)? 'blogpost.blade.php' : 'blog.blade.php';
            $this->setTemplatesProcessor(new TemplatesProcessor($defaultTemplate));

            $view = $this->templatesProcessor->applyPageBlocksTemplates($dispatchedPage);
            if ($pageIsBlogpost) {
                $renderer = new Renderer($this->templatesProcessor->getResultViewParams());
            } else {
                $renderer = new BlogIndexRenderer($this->templatesProcessor->getResultViewParams());
                $renderer->collectListOfPosts($page);
            }

            $renderer->setNavigationItems($page->getNavigationItems());
            $response = view($view, ['page' => $renderer])->render();

            return $response;
        } else {
            throw new \Exception("No CMS page has been dispatched");
        }
    }

    /**
     * @inheritdoc
     */
    protected function requestCanBeHandled(EntityInterface $entity)
    {
        $blogRoute = Plugin::getConfig('blogRootBlockName');
        if ($entity instanceof Page && (preg_match("/^($blogRoute)(\/|\$)/", $entity->getRoute()))) {
            return parent::requestCanBeHandled($entity);
        } else {
            return false;
        }
    }
}