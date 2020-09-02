<?php

namespace Test\MyApi;

use Test\MyApi\Data\CreatePostRequest;
use Test\MyApi\Data\CreatePostResult;
use Test\MyApi\Data\PublishPostRequest;
use Test\MyApi\Data\PublishPostResult;

interface Blog
{
    public function createPost(CreatePostRequest $post) : CreatePostResult;

    public function publishPost(PublishPostRequest $request) : PublishPostResult;
}
