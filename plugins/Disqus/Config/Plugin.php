<?php
namespace Plugins\Disqus\Config;

use \App\Core\PluginInterface;

class Plugin implements PluginInterface
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Disqus';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'Blog Disqus comments integration';
    }

    /**
     * @inheritdoc
     */
    public function getEvents()
    {
        return [
            'App\Events\ResponseRenderBefore' => [
                'Plugins\Disqus\Listeners\ResponseRenderedBefore'
            ]
        ];
    }
}