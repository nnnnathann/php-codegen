<?php

namespace Test\Reflection;

use CodeGen\Data\ServiceDefinition;
use CodeGen\Reflection\TypeReflector;
use PHPUnit\Framework\TestCase;
use Test\MyApi\Blog;
use Test\MyApi\Expectations;

final class TypeReflectorTest extends TestCase
{
    private ServiceDefinition $expected;

    protected function setUp() : void
    {
        $this->expected = (new Expectations())->getExpectedDefinition();
    }

    public function testReflectInterface()
    {
        $reflector = new TypeReflector();
        $this->assertEquals($this->expected, $reflector->getServiceDefinition(Blog::class));
    }
}
