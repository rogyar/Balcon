<?php

namespace Plugins\Cms\Model;


use Mockery\CountValidator\Exception;

/**
 * Class Block
 * @package Plugins\Cms\Model
 *
 * Represents a page block
 */
class Block extends Mdfile
{
    /**
     * True if current block is accessable via URL
     * TODO: consider if this feature is really necessary
     * @var  bool
     */
    protected $isRoutable;
    /**
     *  True when current block has been already inserted in the content
     * @var bool
     */
    protected $isParsed = false;
    /**
     * Route for current block. Includes block's name
     * @var string
     */
    protected $route;
    /**
     * Collection of the child blocks
     * @var BlocksCollection
     */
    protected $children;

    /**
     * If true, the page link will be shown in the main
     * navigation menu
     * @var bool
     */
    protected $showInNavigation = false;

    /** @var  string */
    protected $body;
    /** @var  Block */
    protected $parent;
    /** @var  string */
    protected $template;
    /** @var  string */
    protected $name;
    /** @var  string */
    protected $sortOrderValue;

    /**
     * @param BlocksCollection $collection
     */
    public function setChildren(BlocksCollection $collection)
    {
        $this->children = $collection;
    }

    /**
     * @return BlocksCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getShowInNavigation()
    {
        return $this->showInNavigation;
    }

    /**
     * @return string
     */
    public function getSortOrderValue()
    {
        if (!$this->sortOrderValue) {
            $this->sortOrderValue = $this->getName();
        }
        return $this->sortOrderValue;
    }

    /**
     * @param string $sortOrderValue
     */
    public function setSortOrderValue($sortOrderValue)
    {
        $this->sortOrderValue = $sortOrderValue;
    }

    /**
     * Generates and returns current route
     *
     * @return string
     */
    public function getRoute()
    {
        if (!$this->route) {
            $block = $this;
            do {
                if (!$block) {
                    break;
                }
                $blockName = $block->getName();
                if ($this->route) {
                    $this->route = $blockName . '/' . $this->route;
                } else {
                    $this->route = $blockName;
                }
                $block = $block->getParent();
            } while (true);
        }

        return  '/' . $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @return Block
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Block $parent
     */
    public function setParent(Block $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return bool
     */
    public function getIsRoutable()
    {
        return $this->isRoutable;
    }

    /**
     * @param bool $isRoutable
     */
    public function setIsRoutable($isRoutable)
    {
        $this->isRoutable = $isRoutable;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        if (!$this->body) {
            $this->body = $this->getContent();
        }
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Return blocks body and exclude current block for
     * further rendering. Useful for child blocks rendering
     * inside of templates
     *
     * @return string
     */
    public function getBodyForInsertion()
    {
        $this->isParsed = true;
        return $this->getBody();
    }

    /**
     * @return bool
     */
    public function getIsParsed()
    {
        return $this->isParsed;
    }

    /**
     * Returns child block by it's name
     *
     * @param string $name
     * @return Block
     */
    public function getChild($name)
    {
        return $this->getChildren()->getBlock($name);
    }

    /**
     * Returns true if current block should be included
     * in main navigation
     *
     * @return bool
     */
    public function includeInNavigation()
    {
        if ($this->isRootBlock()) {
            $customParams = $this->getParams();
            if (!isset($customParams['exclude_from_navigation']) ||
                $customParams['exclude_from_navigation'] == false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns true if current block is page's root
     *
     * @return bool
     */
    protected function isRootBlock()
    {
        if (!$this->getParent()) {
            return true;
        }

        return false;
    }

}