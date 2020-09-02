<?php

namespace Acme\MyApi\Data;


final class CreatePostInput
{
    /** @var string */
    public $title;
    /** @var string */
    public $body;
    /** @var string[] */
    public $tags;

    /**
     * @param string $title
     * @param string $body
     * @param string[] $tags
     */
    public function __construct($title, $body, $tags)
    {
        $this->title = $title;
        $this->body = $body;
        $this->tags = $tags;
    }
}