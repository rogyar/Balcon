<?php
namespace App\Core;


interface PluginInterface
{
    /**
     * Returns plugin name
     *
     * @return string
     */
    public function getName();

    /**
     * Returns plugin description
     *
     * @return string
     */
    public function getDescription();


    /**
     * Returns list of the plugin events observers. Format should be the following:
     *  [
     *    'App\Events\Event' => [
     *      'SomePlugin\Listeners\SomeEventResolver'
     *    ],
     *  ]
     *
     * @return array;
     */
    public function getEvents();

}