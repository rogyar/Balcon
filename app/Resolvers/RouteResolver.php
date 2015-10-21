<?php

namespace App\Resolvers;

class RouteResolver implements RouteResolverInterface
{
    /** @var  \App\Core\Balcon */
    protected $balcon;

    /**
     * @param \App\Core\Balcon $balcon
     */
    public function __construct($balcon)
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