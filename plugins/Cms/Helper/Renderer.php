<?php

namespace Plugins\Cms\Helper;

/**
 * Class Renderer
 * @package Plugins\Cms\Helper
 *
 * Contains methods for rendering different components
 * methods allowed directly from the blade template
 */
class Renderer
{
    /** @var  array */
    protected $pageParameters;
    /** @var  array */
    protected $navigationItems;

    /**
     * Renderer constructor.
     * @param $pageParams
     */
    public function __construct($pageParams)
    {
        $this->pageParameters = $pageParams;
    }

    /**
     * @return array
     */
    public function getPageParameters()
    {
        return $this->pageParameters;
    }

    /**
     * @return array
     */
    public function getNavigationItems()
    {
        return $this->navigationItems;
    }

    /**
     * @param array $navigationItems
     */
    public function setNavigationItems($navigationItems)
    {
        $this->navigationItems = $this->validateNavigationItems($navigationItems);
    }

    /**
     * Renders a specified page parameter
     * Returns empty string if the parameter does not set
     *
     * @param $paramName
     * @return string
     */
    public function renderParam($paramName)
    {
        // TODO: add ability to call nested parameters

        if (isset($this->pageParameters[$paramName])) {
            return $this->pageParameters[$paramName];
        } else {

            /* Check if custom block parameter exists */

            if (isset($this->pageParameters['customParams'][$paramName])) {
                return $this->pageParameters['customParams'][$paramName];
            }
        }
        return '';
    }

    /**
     * Validates navigation items array and
     * returns array with correct values.
     *
     * @param array $navigationItems
     * @return array
     */
    protected function validateNavigationItems($navigationItems)
    {
        $validatedNavigationItems = [];
        foreach ($navigationItems as $navigationItem) {
            if (isset($navigationItem['name']) && isset($navigationItem['route'])) {
                $validatedNavigationItems[] = $navigationItem;
            }
        }

        return $validatedNavigationItems;
    }
}