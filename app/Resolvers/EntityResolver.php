<?php

namespace App\Resolvers;

use \App\Core\BalconInterface;

class EntityResolver implements EntityResolverInterface
{
    /**
     * @var BalconInterface
     */
    protected $balcon;

    public function __construct(BalconInterface $balcon)
    {
        $this->balcon = $balcon;
    }

    public function process()
    {
        return 'Test passed';
    }
}