<?php

namespace Plugins\Cms\Model;


use App\Core\EntityInterface;
use Mockery\CountValidator\Exception;
use Plugins\Cms\Helper\Filesystem;
use Plugins\Cms\Model\Block;

/**
 * Class Page
 * @package Plugins\Cms\Model
 *
 * Represents a page entity with child blocks
 * TODO: that's an exact place where you can optimise something if got bored
 */
class Page implements EntityInterface
{
    /**
     * Block that matches current route
     * @var Block
     */
    protected $dispatchedBlock;
    /**
     * True if a page that matches current route has been found
     * @var bool
     */
    protected $isProcessed = false;
    /** @var string */
    protected $route;
    /** @var BlocksCollection  */
    protected $blocksCollection;
    /** @var  array */
    protected $navigationItems = [];


    public function __construct($route)
    {
        $filesystem = new Filesystem();
        $this->setRoute($route);

        // TODO: the pages can be loaded from cache here using filesystem factory
        $this->blocksCollection = $filesystem->collectPages();
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
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @return \Plugins\Cms\Model\Block
     */
    public function getDispatchedBlock()
    {
        return $this->dispatchedBlock;
    }

    /**
     * @param \Plugins\Cms\Model\Block $block
     */
    public function setDispatchedBlock(Block $block)
    {
        $this->dispatchedBlock = $block;
    }

    /**
     * @return BlocksCollection
     */
    public function getBlocksCollection()
    {
        return $this->blocksCollection;
    }

    /**
     * @param BlocksCollection $blocksCollection
     */
    public function setBlocksCollection($blocksCollection)
    {
        $this->blocksCollection = $blocksCollection;
    }


    /**
     * Searches for a page that matches current route
     * Returns true if the page has been found
     *
     * @return bool
     */
    public function process()
    {
        $route = "/{$this->getRoute()}";
        if ($route) {
            /** @var  Block $block */
            foreach ($this->blocksCollection->getBlocks() as $block) {
                $this->routeDispatch($block, $route);
                if ($this->getDispatchedBlock()) {
                    break;
                }
            }
        } else {
            throw new Exception('No route has been set for CMS Page entity');
        }

        return false;
    }

    /**
     * Goes recursively trough blocks and attempts to dispatch
     * the specified route
     *
     * @param \Plugins\Cms\Model\Block $block
     * @param string $route
     * @return bool
     */
    protected function routeDispatch(Block $block, $route)
    {
        if (!$this->getDispatchedBlock()) {
            if ($block->getRoute() == $route) { // TODO: check if block is routable
                $this->setDispatchedBlock($block);
                $this->isProcessed = true;
                return true;
            } elseif (count($block->getChildren()->getBlocks()) > 0) { // Go trough child blocks
                /** @var Block $childBlock */
                foreach ($block->getChildren()->getBlocks() as $childBlock) {
                    $this->routeDispatch($childBlock, $route);
                    if ($this->getDispatchedBlock()) {
                        break;
                    }
                }
            }
        }
        return true;
    }

    /**
     * Collects and returns all navigation links
     *
     * @return array
     */
    public function getNavigationItems()
    {
        if (0 == count($this->navigationItems)) {
            /** @var Block $block */
            foreach ($this->blocksCollection->getBlocks() as $block) {
                if ($block->includeInNavigation()) {
                    $this->navigationItems[$block->getSortOrderValue()] = [
                        'name' => isset($block->getParams()['navigation_title'])?
                            $block->getParams()['navigation_title'] : '',
                        'route' => $block->getRoute()
                    ];
                }
            }
        }
        ksort($this->navigationItems);
        return $this->navigationItems;
    }
}