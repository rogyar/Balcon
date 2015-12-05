<?php

namespace App\Core;


use App\Resolvers\ResponseResolverInterface;
use App\Resolvers\RouteResolverInterface;
use Mockery\CountValidator\Exception;

class ExtensionsCollector
{
    const PLUGINS_NAMESPACE = 'Plugins\\';

    protected $pluginsDir;
    protected $extensionsList;
    protected $eventListeners;

    protected $routeResolversCollection = [];
    protected $responseResolversCollection = [];

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

    public function addRouteResolver(RouteResolverInterface $routeResolver)
    {
        $this->routeResolversCollection[] = $routeResolver;
    }

    public function addResponseResolver(ResponseResolverInterface $responseResolver)
    {
        $this->responseResolversCollection[] = $responseResolver;
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
        // TODO: use DirectoryIterator
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
                // FIXME: array merge by keys
                $this->eventListeners = array_merge_recursive($this->eventListeners, $extensionEvents);
            }
        }

        return $this->eventListeners;
    }

    /**
     * @return array
     */
    public function getResponseResolversCollection()
    {
        return $this->responseResolversCollection;
    }

    /**
     * @return array
     */
    public function getRouteResolversCollection()
    {
        return $this->routeResolversCollection;
    }


}