<?php

namespace App\Core;

interface EntityInterface
{
    function process();
    /**
     * @return bool
     */
    function isProcessed();
}