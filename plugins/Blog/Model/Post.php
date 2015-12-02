<?php

namespace Plugins\Blog\Model;


use App\Core\EntityInterface;
use Plugins\Cms\Model\Page;

class Post extends Page implements EntityInterface
{
    /** @var   */
    protected $childPosts;
}