<?php

namespace Plugins\Blog\Helper;

use Plugins\Cms\Model\BlocksCollection;
use \Plugins\Cms\Model\Page;

/**
 * @inheritdoc
 */
class Renderer extends \Plugins\Cms\Helper\Renderer
{
    /** @var  BlocksCollection */
    protected $listOfPosts;

    /**
     * Returns a limited count of posts
     *
     * @param int $from
     * @param int $to
     * @return array
     */
    public function getListOfPosts($from = 0, $to = 4)
    {
        if (!$this->listOfPosts) {
            return [];
        } else {
            return array_slice($this->listOfPosts->getBlocks(), $from, $to);
        }
    }

    /**
     * Collects list of posts for current page
     *
     * @param Page $post
     */
    public function collectListOfPosts(Page $post)
    {
        $this->listOfPosts = $post->getBlocksCollection();
    }
}