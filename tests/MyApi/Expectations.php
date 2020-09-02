<?php

namespace Test\MyApi;

use CodeGen\Data\Action;
use CodeGen\Data\ServiceDefinition;
use CodeGen\Data\Types\ArrayType;
use CodeGen\Data\Types\NumberType;
use CodeGen\Data\Types\ObjectType;
use CodeGen\Data\Types\StringType;
use CodeGen\Data\Types\UnknownType;
use CodeGen\Data\TypeValue;

final class Expectations
{
    public function getExpectedDefinition()
    {
        return new ServiceDefinition([
            $this->getCreatePostAction(),
            $this->getPublishPostAction(),
        ]);
    }

    public function getCreatePostAction() : Action
    {
        return new Action('createPost', $this->getCreatePostInput(), $this->getCreatePostResult());
    }

    private function getCreatePostInput() : TypeValue
    {
        return new ObjectType([
            'title' => new StringType(),
            'body' => new StringType(),
            'tags' => new ArrayType(new StringType()),
        ]);
    }

    private function getCreatePostResult() : TypeValue
    {
        return new ObjectType([
            'postId' => new StringType(),
        ]);
    }

    public function getPublishPostAction() : Action
    {
        return new Action('publishPost', $this->getPublishPostInput(), $this->getPublishPostResult());
    }

    private function getPublishPostInput() : TypeValue
    {
        return new ObjectType([
            'postId' => new StringType(),
        ]);
    }

    private function getPublishPostResult() : TypeValue
    {
        return new ObjectType([
            'postId' => new StringType(),
            'version' => new NumberType(),
            'url' => new StringType(),
            'timestamp' => new UnknownType(),
        ]);
    }
}
