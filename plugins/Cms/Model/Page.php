<?php

namespace Plugins\Cms\Model;


use App\Core\EntityInterface;
use Plugins\Cms\Helper\Filesystem;
use Plugins\Cms\Resolvers\Block;

class Page implements EntityInterface
{
    protected $route;
    protected $blocks;
    protected $dispatchedBlock;

    public function __construct()
    {
        $filesystem = new Filesystem();

        // TODO: the pages can be loaded from cache here using filesystem factory
        $this->blocks = $filesystem->collectPages();
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param mixed $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    public function handleRoute($route)
    {
        /** @var  $block */
        foreach ($this->blocks as $block) {
            if ($block->getRoute == $route) {
                $this->setDispatchedBlock($block);
                return $block;
            }
        }

        return false;
    }

    public function getDispatchedBlock()
    {
        return $this->dispatchedBlock;
    }

    public function setDispatchedBlock(Block $block)
    {
        $this->dispatchedBlock = $block;
    }

}