<?php
namespace Plugins\Cms\Config;

use \App\Core\PluginInterface;

class Plugin implements PluginInterface
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function getEvents()
    {
        return [];
    }
}