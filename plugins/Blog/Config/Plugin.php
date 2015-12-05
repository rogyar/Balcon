<?php
namespace Plugins\Blog\Config;

use \App\Core\PluginInterface;

class Plugin implements PluginInterface
{
    protected static $config = [
        'blogRootBlockName' => 'blog'
    ];

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
            'App\Events\ResponseResolversRegister' => [
                'Plugins\Blog\Listeners\ResponseResolverRegister'
            ]
        ];
    }

    /**
     * Returns value of the requested configuration parameter
     *
     * @param $paramName
     * @return string
     */
    public static function getConfig($paramName)
    {
        if (isset(self::$config[$paramName])) {
            return self::$config[$paramName];
        } else {
            return '';
        }
    }
}