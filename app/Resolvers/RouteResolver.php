<?php

namespace App\Resolvers;

use \App\Core\BalconInterface;

class RouteResolver implements RouteResolverInterface
{
    /** @var  BalconInterface */
    protected $balcon;

    /**
     * @param BalconInterface $balcon
     */
    public function __construct(BalconInterface $balcon)
    {
        $this->balcon = $balcon;
    }

    public function detectEntityType($route)
    {
        // Detect entity type
         $this->registerEntityResolver();
    }

    public function registerEntityResolver()
    {
        //$app = $this->balcon;
        // FIXME: do not bind anything, put a new implementation instead

        //$app->bind('EntityResolver', function($app) {
        //    return new EntityResolver($app);
        //});

        $this->balcon->getExtensionsContainer()->setResolverImplementation([
            'resolver' => 'resolver name',
            'class' => 'class reference'
        ]);

        // Event: entity resolver register after
    }
}