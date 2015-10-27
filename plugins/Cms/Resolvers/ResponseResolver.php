<?php

namespace Plugins\Cms\Resolvers;

use App\Core\BalconInterface;
use App\Resolvers\ResponseResolverInterface;

class ResponseResolver implements ResponseResolverInterface
{
    /**
     * @var BalconInterface
     */
    protected $balcon;

    /**
     * @var string
     */
    protected $response;

    public function __construct(BalconInterface $balcon)
    {
        $this->balcon = $balcon;
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
        $this->setResponse('Hello world. Test passed');
    }
}