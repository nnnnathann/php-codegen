<?php


namespace CodeGen\Plugins\PostmanCollection;


final class PostmanCollectionOptions
{
    public string $apiName;
    public string $apiDescription;

    public function __construct(string $apiName, string $apiDescription)
    {
        $this->apiName = $apiName;
        $this->apiDescription = $apiDescription;
    }

}