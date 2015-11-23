<?php

namespace Plugins\Cms\Helper;


use Plugins\Cms\Model\Block;
use Plugins\Cms\Model\BlocksCollection;

/**
 * Class Filesystem
 * @package Plugins\Cms\Helper
 *
 * Helper class that handles pages placed in the file system
 */
class Filesystem
{
    /**
     * Path for the root directory with pages
     * @var string
     */
    protected $pagesDir;

    /**
     * @return string
     */
    public function getPagesDir()
    {
        // TODO: get value from config
        if (empty($this->pagesDir)) {
            $pluginDir = dirname(dirname(__FILE__));
            $this->pagesDir = dirname(dirname($pluginDir)) . '/pages/';
        }

        return $this->pagesDir;
    }

    /**
     * Collects all root pages and their child blocks
     *
     * @return BlocksCollection
     */
    public function collectPages()
    {
        $rootContents = new \DirectoryIterator($this->getPagesDir());
        $pagesCollection = new BlocksCollection();
        foreach ($rootContents as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }
            if ($fileInfo->isDir()) {
                $this->collectBlocks(
                    $pagesCollection,
                    $fileInfo->getPath() . '/' . $fileInfo->getFilename());
            }
        }

        return $pagesCollection;
    }

    /**
     * Collects child blocks of the parent block
     *
     * @param BlocksCollection $collection
     * @param $path
     * @param Block|null $parent
     */
    protected function collectBlocks(BlocksCollection $collection, $path, Block $parent = null)
    {
        $pageContents = new \DirectoryIterator($path);
        $block = new Block();
        $block->setParent($parent);
        $block->setChildren(new BlocksCollection());
        $block->setPath($path);
        foreach ($pageContents as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }
            if ($fileInfo->isDir()) {
                $this->collectBlocks(
                    $block->getChildren(),
                    $fileInfo->getPath()  . '/' .  $fileInfo->getFilename(),
                    $block
                );
            }
            if ($fileInfo->getExtension() == 'md') {
                // TODO: check if all names will be compatible with URLs
                $block->setFilename($fileInfo->getBasename());
                $basename = substr($fileInfo->getBasename(), 0, (strlen($fileInfo->getBasename())- 3));
                $block->setName($basename);
            }
        }
        $collection->addBlock($block);
    }
}