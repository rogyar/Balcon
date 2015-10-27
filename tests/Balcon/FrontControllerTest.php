<?php

use \App\Http\Controllers\FrontController;

class FrontControllerTest extends TestCase
{
    public function testBalconProviderIsCreated()
    {
        $frontController = new FrontController();
        $frontController->frontRouter('somepage', 'somesubpage');
        $app = app();

        /** @var \App\Core\Balcon $balcon */
        $balcon = $app->make('\App\Core\BalconInterface');
        $this->assertTrue(is_object($balcon->getRouteResolver()),
            "The main provider 'Balcon' has no route resolver"
        );
    }

    public function testPluginsFolderIsLoadedWithCoreCMSplugin()
    {
        $frontController = new FrontController();
        $frontController->frontRouter('somepage', 'somesubpage');
        $this->assertTrue(class_exists('\Plugins\Cms\Config\Plugin'),
            'Core CMS plugin is not loaded');
    }

    public function testExtensionWithSameNameThrowsException()
    {
        // TODO
    }
}
