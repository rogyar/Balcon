<?php

namespace Plugins\Cms\Resolvers;


use Mockery\CountValidator\Exception;

class Block
{
    protected $name;
    protected $path;
    protected $template;
    protected $templateVariables;
    protected $isRoutable;
    protected $isParsed = false;

    protected $body;

    protected $route;

    /** @var  Block */
    protected $parent;

    /** @var  BlocksCollection */
    protected $children;


    public function setChildren(BlocksCollection $collection)
    {
        $this->children = $collection;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setName($name)
    {

    }

    /**
     * @return mixed
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
                $this->route = $blockName . '/' . $this->route;
                $block = $block->getParent();
            } while (true);
        }

        return  '/' . $this->route;
    }

    /**
     * @param mixed $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent(Block $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return mixed
     */
    public function getIsRoutable()
    {
        return $this->isRoutable;
    }

    /**
     * @param mixed $isRoutable
     */
    public function setIsRoutable($isRoutable)
    {
        $this->isRoutable = $isRoutable;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
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
     * @return mixed
     */
    public function getBodyForInsertion()
    {
        $this->isParsed = true;
        return $this->getBody();
    }

    /**
     * @return mixed
     */
    public function getIsParsed()
    {
        return $this->isParsed;
    }

}