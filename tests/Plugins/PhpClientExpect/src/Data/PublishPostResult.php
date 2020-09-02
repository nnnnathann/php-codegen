<?php

namespace Acme\MyApi\Data;


final class PublishPostResult
{
    /** @var string */
    public $postId;
    /** @var int|float */
    public $version;
    /** @var string */
    public $url;
    /** @var mixed */
    public $timestamp;

    /**
     * @param string $postId
     * @param int|float $version
     * @param string $url
     * @param mixed $timestamp
     */
    public function __construct($postId, $version, $url, $timestamp)
    {
        $this->postId = $postId;
        $this->version = $version;
        $this->url = $url;
        $this->timestamp = $timestamp;
    }
}