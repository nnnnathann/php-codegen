<?php

namespace CodeGen\Laravel\Data;


class CreatePostInput
{
    /** @var string */
    public $title;
    /** @var ?string */
    public $subtitle;
    /** @var string */
    public $body;
    /** @var string[] */
    public $tags;
    /** @var CreatePostInputUser */
    public $user;

    /**
     * @param string $title
     * @param ?string $subtitle
     * @param string $body
     * @param string[] $tags
     * @param CreatePostInputUser $user
     */
    public function __construct($title, $subtitle, $body, $tags, $user)
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->body = $body;
        $this->tags = $tags;
        $this->user = $user;
    }

    public static function fromArray(array $data) : self
    {
        return new self(
            $data['title'],
            $data['subtitle'],
            $data['body'],
            $data['tags'],
            $data['user']
        );
    }
}