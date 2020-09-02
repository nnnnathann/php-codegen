<?php

namespace Acme\MyApi\Data;


final class CreatePostResult
{
    /** @var string */
    public $postId;

    /**
     * @param string $postId
     */
    public function __construct($postId)
    {
        $this->postId = $postId;
    }
}