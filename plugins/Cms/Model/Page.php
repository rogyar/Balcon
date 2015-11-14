<?php

namespace Plugins\Cms\Model;


use App\Core\EntityInterface;
use Mockery\CountValidator\Exception;
use Plugins\Cms\Helper\Filesystem;
use Plugins\Cms\Resolvers\Block;

class Page implements EntityInterface
{
    protected $route;
    protected $blocks;
    protected $dispatchedBlock;
    protected $isProcessed = false;

    public function __construct($route)
    {
        $filesystem = new Filesystem();
        $this->setRoute($route);

        // TODO: the pages can be loaded from cache here using filesystem factory
        $this->blocks = $filesystem->collectPages();
    }

    /**
     * Return true if the entity is processed.
     * If returns false - the further application execution flow
     * will return 404 page by default
     *
     * @return bool
     */
    public function isProcessed()
    {
        return $this->isProcessed;
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

    public function process()
    {
        $route = $this->getRoute();
        if (!$route) {
            /** @var  $block */
            foreach ($this->blocks as $block) {
                if ($block->getRoute == $route) {
                    $this->setDispatchedBlock($block);
                    $this->isProcessed = true;
                    return true;
                }
            }
        } else {
            throw new Exception('No route has been set for CMS Page entity');
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