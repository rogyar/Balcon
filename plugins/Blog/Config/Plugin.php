<?php
namespace Plugins\Blog\Config;

use \App\Core\PluginInterface;

class Plugin implements PluginInterface
{
    protected $config = [
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
    public function getConfigValue($paramName)
    {
        if (isset($this->config[$paramName])) {
            return $this->config[$paramName];
        } else {
            return '';
        }
    }
}