<?php

namespace App\Resolvers;

interface ResponseResolverInterface
{
    public function process();
    public function getResponse();
    public function setResponse($response);
    public function sendResponse();
}