<?php

namespace Acme\MyApi\Data;


class CreatePostResult
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

    public static function fromArray(array $data) : self
    {
        return new self(
            $data['postId']
        );
    }
}