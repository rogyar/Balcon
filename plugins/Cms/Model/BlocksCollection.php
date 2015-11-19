<?php

namespace Plugins\Cms\Model;

/**
 * Class BlocksCollection
 * @package Plugins\Cms\Model
 *
 * Represents blocks collection
 */
class BlocksCollection
{
    /** @var  array */
    protected $blocks;

    /**
     * @return array
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * @param array $blocks
     */
    public function setBlocks($blocks)
    {
        $this->blocks = $blocks;
    }

    /**
     * Adds block to the blocks collection
     *
     * @param Block $block
     */
    public function addBlock(Block $block)
    {
        $this->blocks[] = $block;
    }

    /**
     * Searches for a block by it's name in the blocks collection
     * if the requested block does not exist - returns empty block
     *
     * @param $name
     * @return Block
     */
    public function getBlock($name)
    {
        /** @var Block $block */
        foreach ($this->blocks as $block) {
            if ($block->getName() == $name) {
                return $block;
            }
        }

        return new Block();
    }

}