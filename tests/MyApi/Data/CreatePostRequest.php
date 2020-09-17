<?php

namespace Test\MyApi\Data;

final class CreatePostRequest
{
    public string $title;
    public ?string $subtitle;
    public string $body;
    /** @var string[] */
    public array $tags;
    public User $user;
}
