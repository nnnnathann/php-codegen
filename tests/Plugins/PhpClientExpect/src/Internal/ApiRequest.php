<?php

namespace Acme\MyApi\Internal;

class ApiRequest
{
    /** @var string */
    public $url;
    /** @var string */
    public $commandName;
    /** @var mixed */
    public $inputData;

    public function __construct($url, $commandName, $inputData)
    {
        $this->url = $url;
        $this->commandName = $commandName;
        $this->inputData = $inputData;
    }

    public function toArray()
    {
        return [
            'url' => $this->url,
            'commandName' => $this->commandName,
            'inputData' => $this->inputData,
        ];
    }
}
