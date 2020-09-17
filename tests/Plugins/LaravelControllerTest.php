<?php

namespace Test\Plugins;

use CodeGen\Plugins\LaravelController\LaravelController;
use CodeGen\Plugins\LaravelController\LaravelControllerOptions;

final class LaravelControllerTest extends AbstractPluginTestCase
{
    public function testControllerGeneration()
    {
        $generator = new LaravelController(new LaravelControllerOptions());
        $this->validateOutput($generator, __DIR__ . '/LaravelControllerExpect');
    }
}
