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
                if ($block->getRoute() == $route) { // TODO: check if block is routable
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
                    $this->navigationItems[] = [
                        'name' => isset($block->getParams()['name'])? $block->getParams()['name'] : '',
                        'route' => $block->getRoute()
                    ];
                }
            }
        }

        return $this->navigationItems;
    }
}