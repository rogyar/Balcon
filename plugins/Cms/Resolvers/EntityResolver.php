<?php

namespace Plugins\Cms\Resolvers;

use App\Core\BalconInterface;
use App\Resolvers\EntityResolverInterface;
use Plugins\Cms\Model\Page;

class EntityResolver implements EntityResolverInterface
{
    /**
     * @var BalconInterface
     */
    protected $balcon;

    protected $entity;

    public function __construct(BalconInterface $balcon)
    {
        $this->balcon = $balcon;
    }

    public function process()
    {
        $this->entity = new Page();
    }
}