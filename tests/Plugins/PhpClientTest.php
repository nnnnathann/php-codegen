<?php

namespace Test\Plugins;

use CodeGen\Plugins\PhpClient\PhpClientGenerator;
use CodeGen\Plugins\PhpClient\PhpClientOptions;

final class PhpClientTest extends AbstractPluginTestCase
{
    public function testClientGeneration()
    {
        $generator = new PhpClientGenerator(new PhpClientOptions('acme/my-api-sdk-php', 'Acme\\MyApi'));
        $this->validateOutput($generator, __DIR__ . '/PhpClientExpect');
    }
}
