<?php

namespace App\Core;

interface EntityInterface
{
    function process();
    function isProcessed();
}