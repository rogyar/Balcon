<?php

namespace Plugins\Cms\Resolvers;

use App\Core\BalconInterface;
use App\Core\EntityInterface;
use App\Core\PluginInterface;
use App\Resolvers\ResponseResolverInterface;
use Plugins\Cms\Helper\Renderer;
use Plugins\Cms\Model\Page;
use Plugins\Cms\Processors\TemplatesProcessor;

/**
 * Class ResponseResolver
 * @package Plugins\Cms\Resolvers
 *
 * Processes response for the handled entity
 */
class ResponseResolver implements ResponseResolverInterface
{
    /**
     * @var BalconInterface
     */
    protected $balcon;

    /**
     * @var  TemplatesProcessor
     */
    protected $templatesProcessor;

    /**
     * @var string
     */
    protected $response;

    /**
     * @var string
     */
    protected $rawView;

    /**
     * @var Renderer
     */
    protected $renderer;

    /**
     * @var PluginInterface
     */
    protected $pluginConfig;

    public function __construct(BalconInterface $balcon, PluginInterface $plugin)
    {
        $this->balcon = $balcon;
        $this->pluginConfig = $plugin;
    }


    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param string $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function sendResponse()
    {
        return $this->getResponse();
    }

    /**
     * @return string
     */
    public function getRawView()
    {
        return $this->rawView;
    }

    /**
     * @param string $rawView
     */
    public function setRawView($rawView)
    {
        $this->rawView = $rawView;
    }

    /**
     * @return Renderer
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param Renderer $renderer
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
    }



    /**
     * Renders raw blade template code stored in $rawView
     * using $renderer as a helper with a set of methods
     * available inside of the blate template
     *
     * @return string
     */
    public function renderResponse()
    {
        $this->setResponse(view($this->getRawView(), ['page' => $this->getRenderer()])->render());
        return $this->sendResponse();
    }

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
            $this->processCmsPage($entity);
        }
    }

    /**
     * Returns true if current resolver can process the request
     *
     * @param EntityInterface $entity
     * @return bool
     */
    protected function requestCanBeHandled(EntityInterface $entity)
    {
        return (!$this->balcon->getResponseResolver() && $entity instanceof Page && $entity->isProcessed());
    }

    /**
     * Processes response for the handled CMS page
     *
     * @param Page $page
     * @throws \Exception
     */
    protected function processCmsPage(Page $page)
    {
        $dispatchedPage = $page->getDispatchedBlock();
        if ($dispatchedPage) {
            $this->setTemplatesProcessor(new TemplatesProcessor('default.blade.php'));
            $this->rawView = $this->templatesProcessor->applyPageBlocksTemplates($dispatchedPage);
            $this->renderer = new Renderer($this->templatesProcessor->getResultViewParams(), $this->getPluginConfig());
            $this->renderer->setNavigationItems($page->getNavigationItems());

        } else {
            throw new \Exception("No CMS page has been dispatched");
        }
    }

    /**
     * @return TemplatesProcessor
     */
    public function getTemplatesProcessor()
    {
        return $this->templatesProcessor;
    }

    /**
     * @param TemplatesProcessor $templatesProcessor
     */
    public function setTemplatesProcessor(TemplatesProcessor $templatesProcessor)
    {
        $this->templatesProcessor = $templatesProcessor;
    }

    /**
     * @return PluginInterface
     */
    public function getPluginConfig()
    {
        return $this->pluginConfig;
    }
}