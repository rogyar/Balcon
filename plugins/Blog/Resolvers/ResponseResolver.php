<?php

namespace Plugins\Blog\Resolvers;

use App\Core\EntityInterface;
use App\Resolvers\ResponseResolverInterface;
use Plugins\Blog\Config\Plugin;
use Plugins\Blog\Helper\Renderer;
use Plugins\Cms\Model\Page;
use Plugins\Cms\Processors\TemplatesProcessor;
use Plugins\Cms\Model\Block;

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
            $this->processBlogPage($entity);
        }
    }

    public function checkPageIsBlogPost(Page $page)
    {
        // TODO: implement as a separate routine that has a list of routes for listofposts page
        return (count(explode('/', $page->getRoute())) > 1);
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
            $pageIsBlogpost = $this->checkPageIsBlogPost($page);
            $defaultTemplate = ($pageIsBlogpost)? 'blogpost.blade.php' : 'blog.blade.php';
            $this->setTemplatesProcessor(new TemplatesProcessor($defaultTemplate));

            $this->rawView = $this->templatesProcessor->applyPageBlocksTemplates($dispatchedPage);
            $this->renderer = new Renderer($this->templatesProcessor->getResultViewParams(), $this->getPluginConfig());
            if (!$pageIsBlogpost) {
                $this->renderer->collectListOfPosts($page);
            }

            $this->renderer->setNavigationItems($page->getNavigationItems());
        } else {
            throw new \Exception("No CMS page has been dispatched");
        }
    }

    /**
     * @inheritdoc
     */
    protected function requestCanBeHandled(EntityInterface $entity)
    {
        $blogRoute = $this->pluginConfig->getConfigValue('blogRootBlockName');
        if ($entity instanceof Page && (preg_match("/^($blogRoute)(\/|\$)/", $entity->getRoute()))) {
            return parent::requestCanBeHandled($entity);
        } else {
            return false;
        }
    }
}