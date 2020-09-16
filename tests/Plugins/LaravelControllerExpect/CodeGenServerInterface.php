<?php

namespace CodeGen\Laravel;

use CodeGen\Laravel\Data\CreatePostInput;
use CodeGen\Laravel\Data\CreatePostResult;
use CodeGen\Laravel\Data\PublishPostInput;
use CodeGen\Laravel\Data\PublishPostResult;


interface CodeGenServerInterface
{
    public function createPost(CreatePostInput $input) : CreatePostResult;

    public function publishPost(PublishPostInput $input) : PublishPostResult;
}