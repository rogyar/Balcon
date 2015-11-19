<?php

namespace Plugins\Cms\Resolvers;

use App\Core\BalconInterface;
use App\Resolvers\ResponseResolverInterface;
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
        // TODO: observer response_process_before pass $this (?)
        $entityResolver = $this->balcon->getEntityResolver();
        $entity = $entityResolver->getEntity();
        if ($entity->isProcessed()) {
            if ($entity instanceof Page) {
                $this->setResponse(
                    $this->processCmsPage($entity)
                );
            }
        } else {
            // TODO: process 404 response
        }
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
            $response = view(
                $view,
                $this->templatesProcessor->getResultViewParams()
            )->render();

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