<?php

namespace CodeGen\Plugins\PostmanCollection\Postmanv21;

use CodeGen\Data\Action;
use CodeGen\Data\ServiceDefinition;
use CodeGen\Data\TypeValue;
use CodeGen\Plugins\PostmanCollection\PostmanCollectionOptions;
use JsonSerializable;

final class Collection implements JsonSerializable
{
    private PostmanCollectionOptions $options;
    private ServiceDefinition $service;

    public function __construct(PostmanCollectionOptions $options, ServiceDefinition $service)
    {
        $this->options = $options;
        $this->service = $service;
    }

    public function jsonSerialize()
    {
        return [
            'info' => [
                'name' => $this->options->apiName,
                'description' => $this->options->apiDescription,
                'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
            ],
            'item' => $this->mapActionsToItems(),
        ];
    }

    private function mapActionsToItems()
    {
        return array_map([$this, 'mapActionToItem'], $this->service->actions);
    }

    private function mapActionToItem(Action $action)
    {
        return [
            'name' => $action->name,
            'request' => [
                'method' => 'POST',
                'header' => [
                    [
                        'key' => 'Content-Type',
                        'value' => 'application/json',
                        'type' => 'text',
                    ],
                ],
                'body' => [
                    'mode' => 'raw',
                    'raw' => $this->generateBody($action),
                    'options' => [
                        'raw' => [
                            'language' => 'json',
                        ],
                    ],
                ],
                'url' => [
                    'raw' => sprintf('{{%s}}', $this->options->endpointVariableName),
                    'host' => [
                        sprintf('{{%s}}', $this->options->endpointVariableName),
                    ],
                ],
            ],
            'response' => [],
        ];
    }

    private function generateBody(Action $action)
    {
        return json_encode([
            'commandName' => $action->name,
            'inputData' => $this->generateExampleArray($action->input),
        ], JSON_PRETTY_PRINT);
    }

    private function generateExampleArray(TypeValue $input)
    {
        return $input->example();
    }
}
