<?php

namespace App\Core;

interface BalconInterface
{
    function getRouteResolver();
    function setRouteResolver($routeResolver);
    function getEntityResolver();
    function setEntityResolver($entityResolver);
    function getEntity();
    function setEntity($entity);
    function getResponseResolver();
    function setResponseResolver($responseResolver);
    function getResponse();
    function setResponse($response);
}