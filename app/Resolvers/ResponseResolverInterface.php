<?php

namespace App\Resolvers;

interface ResponseResolverInterface
{
    public function process();

    /**
     * @return string
     */
    public function getResponse();

    /**
     * @param string $response
     * @return string
     */
    public function setResponse($response);
    public function sendResponse();
    public function renderResponse();
}