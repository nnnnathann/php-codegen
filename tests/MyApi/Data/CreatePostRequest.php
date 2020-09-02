<?php

namespace Test\MyApi\Data;

final class CreatePostRequest
{
    public string $title;
    public string $body;
    /** @var string[] */
    public array $tags;
}
