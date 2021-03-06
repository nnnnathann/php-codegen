<?php

namespace Test\MyApi;

use CodeGen\Data\Action;
use CodeGen\Data\ServiceDefinition;
use CodeGen\Data\Types\ArrayType;
use CodeGen\Data\Types\FloatType;
use CodeGen\Data\Types\IntType;
use CodeGen\Data\Types\NullableType;
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
            'subtitle' => new NullableType(new StringType()),
            'body' => new StringType(),
            'tags' => new ArrayType(new StringType()),
            'user' => new ObjectType([
                'id' => new StringType(),
                'email' => new StringType()
            ])
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
            'version' => new IntType(),
            'url' => new StringType(),
            'timestamp' => new UnknownType(),
        ]);
    }
}
