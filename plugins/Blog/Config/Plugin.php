<?php
namespace Plugins\Blog\Config;

use \App\Core\PluginInterface;

class Plugin implements PluginInterface
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Blog';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'Balcon core Blog extension';
    }

    /**
     * @inheritdoc
     */
    public function getEvents()
    {
        return [
            'App\Events\RouteResolversRegister' => [
                'Plugins\Blog\Listeners\RouteResolverRegister'
            ]
        ];
    }
}