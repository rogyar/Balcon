<?php

namespace Plugins\Cms\Helper;


use Plugins\Cms\Resolvers\Block;
use Plugins\Cms\Resolvers\BlocksCollection;

class Filesystem
{
    protected $pagesDir;

    /**
     * Returns path to the pages directory
     *
     * @return string
     */
    public function getPagesDir()
    {
        if (empty($this->pagesDir)) {
            $appDir = dirname(dirname(__FILE__));
            $this->pagesDir = dirname($appDir) . '/pages/';
        }

        return $this->pagesDir;
    }

    public function collectPages()
    {
        $rootContents = new \DirectoryIterator($this->getPagesDir());
        $pagesCollection = new BlocksCollection();
        foreach ($rootContents as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }
            if ($fileInfo->isDir()) {
                $this->collectBlocks($pagesCollection, null, $fileInfo->getPath());
            }
        }

        return $pagesCollection;
    }

    protected function collectBlocks(BlocksCollection $collection, Block $parent, $path)
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
                $this->collectBlocks($block->getChildren(), $block, $fileInfo->getPath());
            }
            if ($fileInfo->getExtension() == 'md') {
                // TODO: check if all names will be compatible with URLs
                $block->setName($fileInfo->getBasename());
            }
        }
        $collection->addBlock($block);
    }
}