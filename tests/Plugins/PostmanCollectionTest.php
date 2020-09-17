<?php

namespace Test\Plugins;

use CodeGen\Plugins\LaravelController\LaravelController;
use CodeGen\Plugins\LaravelController\LaravelControllerOptions;
use CodeGen\Plugins\PostmanCollection\PostmanCollectionGenerator;
use CodeGen\Plugins\PostmanCollection\PostmanCollectionOptions;

final class PostmanCollectionTest extends AbstractPluginTestCase
{
    public function testCollectionGeneration()
    {
        $generator = new PostmanCollectionGenerator(
            new PostmanCollectionOptions('MyApi', 'My Api Description')
        );
        $outputDir = $this->writeOutput($generator, __DIR__ . '/PostmanCollectionExpect');
        $expected = json_decode(file_get_contents(__DIR__ . '/PostmanCollectionExpect/MyApi.postman_collection.json'), true);
        $contents = json_decode(file_get_contents($outputDir . '/MyApi.postman_collection.json'), true);
        $this->assertEquals($expected['info'], $contents['info']);
        foreach ($expected['item'] as $i => $expectedItem) {
            $actualItem = $contents['item'][$i];
            $this->assertEquals($this->normalizeItem($expectedItem), $this->normalizeItem($actualItem));
        }
    }

    private function normalizeItem($item)
    {
        $body = json_decode($item['request']['body']['raw'], true);
        $item['request']['body']['raw'] = array_walk_recursive($body, function ($value) {
            return gettype($value);
        });
        return $item;
    }
}
