<?php

namespace App\Core;


use Mockery\CountValidator\Exception;

class ExtensionsCollector
{
    protected $extensionsList;
    protected $eventListeners;

    protected $resolversImplementations = [
        'RouteResolver' => '',
        'EntityResolver' => '',
        'ResponseResolver' => '',
    ];


    public function __construct()
    {

    }

    /**
     * Returns array with extensions names, registered in the system
     *
     * @return []
     */
    public function getExtensionsList()
    {
        if (null == $this->extensionsList) {
            $this->extensionsList = $this->collectExtensionsList();
        }

        return $this->extensionsList;
    }

    /**
     * Returns array with event listeners, registered in the system
     *
     * @return []
     */
    public function getEventListeners()
    {
        if (null == $this->eventListeners) {
            $this->eventListeners = $this->collectEventListeners();
        }
        return $this->eventListeners;
    }

    public function getResolverImplementation($resolverName)
    {
        $resolverImplementation = '';
        if (in_array($resolverName, array_keys($this->resolversImplementations))) {
            $resolverImplementation = isset($this->resolversImplementations[$resolverName]) ?: '';

            // If there's no implementation registered - maybe it's time to collect the implementations

            if (empty($resolverImplementation)) {
                $resolverImplementation = $this->collectResolversImplementations()[$resolverName];
            }

            // If the implementation is still empty that's really bad

            if (empty($resolverImplementation)) {
                // FIXME: maybe try to register a default implementation
                throw new Exception("An implementation for resolver '$resolverName' is missing in the system");
            }
        }

        return $resolverImplementation;
    }

    public function setResolverImplementation($implementation)
    {
        $this->resolversImplementations[$implementation['resolver']] = $implementation['class'];
    }

    /**
     * Checks the system for the registered extensions and return a list of the extensions names
     *
     * @return []
     */
    protected function collectExtensionsList()
    {
        //TODO

        return $this->extensionsList;
    }

    /**
     * Checks all extensions for the registered event listeners and returns a list of listeners
     *
     * @return []
     */
    protected function collectEventListeners()
    {
        // TODO

        /*
         The listeners list should have the following structure
        [
            'App\Events\RouteResolverRegisteredAfter' => [
                'App\Listeners\RouteResolverRegister'
            ],
        ];
        */

        foreach ($this->getExtensionsList() as $extension) {

        }

        return $this->eventListeners;
    }

    /**
     * Collects all resolvers implementations from the registered extensions
     *
     * @return array
     */
    protected function collectResolversImplementations()
    {
        // TODO

        foreach ($this->getExtensionsList() as $extension) {

        }
        return $this->resolversImplementations;
    }
}