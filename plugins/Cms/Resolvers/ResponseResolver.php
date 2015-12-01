<?php

namespace Plugins\Cms\Resolvers;

use App\Core\BalconInterface;
use App\Core\EntityInterface;
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

    public function __construct(BalconInterface $balcon)
    {
        $this->balcon = $balcon;
        $this->setTemplatesProcessor(new TemplatesProcessor());
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
                $this->processCmsPage($entity)
            );
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
     * @return string
     * @throws \Exception
     */
    protected function processCmsPage(Page $page)
    {
        $dispatchedPage = $page->getDispatchedBlock();
        if ($dispatchedPage) {
            $view = $this->templatesProcessor->applyPageBlocksTemplates($dispatchedPage);
            $renderer = new Renderer($this->templatesProcessor->getResultViewParams());
            $renderer->setNavigationItems($page->getNavigationItems());
            $response = view($view, ['page' => $renderer])->render();

            return $response;
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
}