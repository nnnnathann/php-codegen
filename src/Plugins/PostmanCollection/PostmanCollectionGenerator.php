<?php


namespace CodeGen\Plugins\PostmanCollection;


use CodeGen\Data\ServiceDefinition;
use CodeGen\GeneratorPluginInterface;

final class PostmanCollectionGenerator implements GeneratorPluginInterface
{

    private PostmanCollectionOptions $options;

    public function __construct(PostmanCollectionOptions $options)
    {
        $this->options = $options;
    }


    public function defaultDirectory(): string
    {
        return 'http-postman';
    }

    public function output(string $directory, ServiceDefinition $service)
    {
        $outputFile = $directory . '/' . $this->options->apiName . '.postman_collection.json';
        file_put_contents($outputFile, json_encode(new Postmanv21\Collection($this->options, $service), JSON_PRETTY_PRINT));
    }
}