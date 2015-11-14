<?php

namespace Plugins\Cms\Resolvers;

use App\Core\BalconInterface;
use App\Resolvers\ResponseResolverInterface;
use Plugins\Cms\Model\Page;
use Plugins\Cms\Processors\TemplatesProcessor;


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

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }

    public function sendResponse()
    {
        return $this->getResponse();
    }

    public function process()
    {
        // TODO: observer response_process_before pass $this (?)
        $entityResolver = $this->balcon->getEntityResolver();
        $entity = $entityResolver->getEntity();
        if ($entity->isProcessed()) {
            if ($entity instanceof Page) {
                $this->processCmsPage($entity);
                $this->setResponse(
                    $this->templatesProcessor->getContent()
                );
            }
        } else {
            // TODO: process 404 response
        }
    }

    protected function processCmsPage(Page $page)
    {
        $dispatchedPage = $page->getDispatchedBlock();
        if ($dispatchedPage) {
            $this->setResponse(view(
                $this->templatesProcessor->applyBlocksTemplates($dispatchedPage),
                $this->templatesProcessor->getResultTemplateParams()
            ));
        } else {
            throw new \Exception("No CMS page has been dispatched");
        }
    }

    /**
     * @return mixed
     */
    public function getTemplatesProcessor()
    {
        return $this->templatesProcessor;
    }

    /**
     * @param mixed $templatesProcessor
     */
    public function setTemplatesProcessor($templatesProcessor)
    {
        $this->templatesProcessor = $templatesProcessor;
    }
}