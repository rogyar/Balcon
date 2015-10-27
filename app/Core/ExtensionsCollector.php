<?php

namespace App\Core;


use Mockery\CountValidator\Exception;

class ExtensionsCollector
{
    const PLUGINS_NAMESPACE = 'Plugins\\';

    protected $pluginsDir;
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
     * @return array
     */
    public function getExtensionsList()
    {
        if (null == $this->extensionsList) {
            $this->extensionsList = $this->collectExtensions();
        }

        return $this->extensionsList;
    }

    /**
     * Returns array with event listeners, registered in the system
     *
     * @return array
     */
    public function getEventListeners()
    {
        if (null == $this->eventListeners) {
            $this->eventListeners = $this->collectEventListeners();
        }
        return $this->eventListeners;
    }

    /**
     * Returns a registered implementation class name for the requested resolver
     *
     * @param $resolverName
     * @return string
     */
    public function getResolverImplementation($resolverName)
    {
        $resolverImplementation = '';
        if (in_array($resolverName, array_keys($this->resolversImplementations))) {
            $resolverImplementation = isset($this->resolversImplementations[$resolverName]) ?
                $this->resolversImplementations[$resolverName] : '';

            // If there's no implementation registered - maybe it's time to collect the implementations
            if (empty($resolverImplementation)) {
                $resolverImplementation = $this->collectResolversImplementations()[$resolverName];
            }
        }

        // If the implementation is still empty that's really bad
        if (empty($resolverImplementation)) {
            // FIXME: maybe try to register a default implementation
            throw new Exception("An implementation for resolver '$resolverName' is missing in the system");
        }

        return $resolverImplementation;
    }

    public function setResolverImplementation($implementation)
    {
        $this->resolversImplementations[$implementation['resolver']] = $implementation['class'];
    }

    /**
     * Returns path to the plugins directory
     *
     * @return string
     */
    public function getPluginsDir()
    {
        if (empty($this->pluginsDir)) {
            $appDir = dirname(dirname(__FILE__));
            $this->pluginsDir = dirname($appDir) . '/plugins/';
        }

        return $this->pluginsDir;
    }

    /**
     * Returns plugin's bootstrap file class from it's directory name
     *
     * @param string $pluginDirectoryName
     * @return string
     */
    protected function getPluginBootstrapClassName($pluginDirectoryName)
    {
        return self::PLUGINS_NAMESPACE . $pluginDirectoryName . '\Config\Plugin';
    }

    /**
     * Checks the system for the registered extensions and return a list
     *
     * @return array
     */
    protected function collectExtensions()
    {
        $this->extensionsList = [];
        $pluginsFolderContents = scandir($this->getPluginsDir());
        $excludedDirectoriesNames = ['..', '.'];
        foreach ($pluginsFolderContents as $pluginsFolderItem) {
            if (is_dir($this->getPluginsDir() . $pluginsFolderItem) && !in_array($pluginsFolderItem, $excludedDirectoriesNames) ) {
                $extensionBootstrapClass = $this->getPluginBootstrapClassName($pluginsFolderItem);
                /** @var \App\Core\PluginInterface $extensionBootstrap */
                $extensionBootstrap = new $extensionBootstrapClass;
                $extensionName = $extensionBootstrap->getName();

                /* Throw exception if an extension with the same name has been previously registered */
                if (isset($this->extensionsList[$extensionName])) {
                    throw new Exception("The extension with name $extensionName has been already regisered");
                }
                $this->extensionsList[$extensionName] = $extensionBootstrap;
            }
        }

        return $this->extensionsList;
    }

    /**
     * Checks all extensions for the registered event listeners and returns a list of listeners
     *
     * @return array
     */
    protected function collectEventListeners()
    {
        $this->eventListeners = [];
        /** @var \App\Core\PluginInterface $extension */
        foreach ($this->getExtensionsList() as $extension) {
            $extensionEvents = $extension->getEvents();

            if (count($extensionEvents) > 0) {
                $this->eventListeners = array_merge($this->eventListeners, $extensionEvents);
            }
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
        // FIXME: resolvers will not be collected. Will be injected via events observers instead

        foreach ($this->getExtensionsList() as $extension) {

        }

        return $this->resolversImplementations;
    }



}