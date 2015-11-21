<?php

namespace Plugins\Cms\Model;


use Mockery\CountValidator\Exception;

/**
 * Class Block
 * @package Plugins\Cms\Model
 *
 * Represents a page block
 */
class Block
{
    /**
     * Absolute path to the block's md file
     * @var  string
     */
    protected $path;
    /**
     * True if current block is accessable via URL
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
     * A set of parameters that will be passed to the template
     * Actual only for the root block
     * @var array
     */
    protected $blockParams;

    /** @var  string */
    protected $body;
    /** @var  Block */
    protected $parent;
    /** @var  string */
    protected $template;
    /** @var  string */
    protected $name;

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
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
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
     * @return array
     */
    public function getBlockParams()
    {
        return $this->blockParams;
    }

    /**
     * @param array $blockParams
     */
    public function setBlockParams($blockParams)
    {
        $this->blockParams = $blockParams;
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
                if (empty($blockName)) {
                    throw new Exception("The block in {$this->getPath()} does not have .md file inside");
                }
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
        // FIXME: temporary solution
        $this->body = file_get_contents($this->getPath() . '/' . $this->getName() . '.md');
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

}