<?php

namespace Plugins\Blog\Helper;

use Plugins\Cms\Model\BlocksCollection;
use Plugins\Cms\Model\Page;
use Plugins\Cms\Model\Block;
use Plugins\Blog\Config\Plugin;

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
        $pagesCollection = $post->getBlocksCollection();
        $blogRootBlockName = $this->plugin->getConfigValue('blogRootBlockName');
        $blogPostsCollection = $pagesCollection->getBlock($blogRootBlockName)
            ->getChildren();
        $this->listOfPosts = $this->sortPosts($blogPostsCollection);
    }

    public function getPostInfo(Block $post) {
        $customPostParameters = $post->getParams();
        return [
            'author' => '',
            'date' => '',
            'title' => $customPostParameters['title'],
            'url' => $post->getRoute()
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

    /**
     * Sort posts by 'post_date' parameter provided in
     * the .md file headers. If 'post_date' parameter is not provided
     * the file last modification time will be used for sorting
     *
     * @param BlocksCollection $postsCollection
     * @return BlocksCollection $postsCollection
     */
    protected function sortPosts(BlocksCollection $postsCollection)
    {
        $sortedBlocks = [];
        /** @var Block $block */
        foreach ($postsCollection->getBlocks() as $block) {
            $customBlockParams = $block->getParams();
            if (isset($customBlockParams['post_date'])) {
                $postTimestamp = strtotime($customBlockParams['post_date']);
                $sortedBlocks[(int)$postTimestamp] = $block;
            } else {
                $postDate = $block->getFileAttrs()['updated_at'];
                $sortedBlocks[strtotime($postDate)] = $block;
            }
        }
        if (count($sortedBlocks) > 0) {
            ksort($sortedBlocks);
            $postsCollection->setBlocks($sortedBlocks);
        }

        return $postsCollection;
    }
}