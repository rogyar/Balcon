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
        return 'Cms';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'Balcon core Content Management extension';
    }

    /**
     * @inheritdoc
     */
    public function getEvents()
    {
        return [
            'App\Events\RouteResolversRegister' => [
                'Plugins\Cms\Listeners\RouteResolverRegister'
            ],
            'App\Events\ResponseResolversRegister' => [
                'Plugins\Cms\Listeners\ResponseResolverRegister'
            ]
        ];
    }
}