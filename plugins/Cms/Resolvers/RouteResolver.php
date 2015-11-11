<?php

namespace Plugins\Cms\Resolvers;

use App\Core\BalconInterface;
use App\Resolvers\RouteResolverInterface;
use Plugins\Cms\Model\Page;

class RouteResolver implements RouteResolverInterface
{
    /** @var  BalconInterface */
    protected $balcon;

    /** @var  string */
    protected $entityResolver;

    /**
     * @param BalconInterface $balcon
     */
    public function __construct(BalconInterface $balcon)
    {
        $this->balcon = $balcon;
        $this->setEntityResolver('Plugins\Cms\Resolvers\EntityResolver'); // Default value
    }


    public function process($route)
    {
        $pageEntity = new Page();
        if ($pageEntity->handleRoute($route)) {
            $this->balcon->setEntity($pageEntity);
        }

        return $this->getEntityResolver();
    }

    public function getEntityResolver()
    {
        return $this->entityResolver;
    }

    public function setEntityResolver($resolverClassName)
    {
        $this->entityResolver = $resolverClassName;
    }
}