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
    /** @var CreatePostInputUser */
    public $user;

    /**
     * @param string $title
     * @param string $body
     * @param string[] $tags
     * @param CreatePostInputUser $user
     */
    public function __construct($title, $body, $tags, $user)
    {
        $this->title = $title;
        $this->body = $body;
        $this->tags = $tags;
        $this->user = $user;
    }
}