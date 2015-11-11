<?php

namespace Plugins\Cms\Resolvers;


class BlocksCollection
{
    protected $blocks;

    /**
     * @return mixed
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * @param mixed $blocks
     */
    public function setBlocks($blocks)
    {
        $this->blocks = $blocks;
    }

    public function addBlock(Block $block)
    {
        $this->blocks[] = $block;
    }

}