<?php


namespace CodeGen\Plugins\PostmanCollection;


final class PostmanCollectionOptions
{
    public string $apiName;
    public string $apiDescription;
    public string $endpointVariableName;

    public function __construct(string $apiName, string $apiDescription, string $endpointVariableName)
    {
        $this->apiName = $apiName;
        $this->apiDescription = $apiDescription;
        $this->endpointVariableName = $endpointVariableName;
    }

}