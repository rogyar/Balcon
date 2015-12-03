<?php

namespace Plugins\Blog\Helper;

use Plugins\Cms\Model\BlocksCollection;
use Plugins\Cms\Model\Page;
use Plugins\Cms\Model\Block;

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

    public function getPostInfo(Block $post) {
        $customPostParameters = $post->getParams();
        return [
            'author' => '',
            'date' => '',
            'title' => $customPostParameters['title'],
        ];
    }

    /**
     * Returns preview of the blog post
     *
     * @param Block $post
     * @return string
     */
    public function getExcerpt(Block $post)
    {
        // TODO: replace by ability to set custom excerpt
        $excerpt = $postBody = $post->getBodyForInsertion();
        if (preg_match('/^.{1,260}\b/s', $postBody, $match))
        {
            $excerpt = $match[0];
        }

        return $excerpt;
    }
}