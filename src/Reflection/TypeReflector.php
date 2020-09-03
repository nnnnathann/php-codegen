<?php

namespace CodeGen\Reflection;

use CodeGen\Data\Action;
use CodeGen\Data\ServiceDefinition;
use CodeGen\Data\Types\ArrayType;
use CodeGen\Data\Types\NumberType;
use CodeGen\Data\Types\ObjectType;
use CodeGen\Data\Types\StringType;
use CodeGen\Data\Types\UnknownType;
use CodeGen\Data\Types\VoidType;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;
use ReflectionType;

class TypeReflector
{
    public function getServiceDefinition(string $className) : ServiceDefinition
    {
        $reflectionClass = new ReflectionClass($className);

        return new ServiceDefinition($this->getActions($reflectionClass));
    }

    private function getActions(ReflectionClass $reflectionClass) : array
    {
        return array_map([$this, 'mapMethodToAction'], $reflectionClass->getMethods());
    }

    private function mapMethodToAction(ReflectionMethod $method)
    {
        return new Action($method->getName(), $this->argumentType($method), $this->returnType($method));
    }

    private function argumentType(ReflectionMethod $method)
    {
        if ($method->getNumberOfParameters() === 0) {
            return new VoidType();
        }
        if ($method->getNumberOfParameters() > 1) {
            throw new InvalidArgumentException("invalid service method: {$method->getName()} is only allowed one parameter");
        }

        return $this->parameterType($method->getParameters()[0]);
    }

    private function returnType(ReflectionMethod $method)
    {
        if ($method->getReturnType() === null) {
            return new VoidType();
        }

        $hinted = $this->reflectionTypeType($method->getReturnType(), '');
        if (!$hinted instanceof UnknownType) {
            return $hinted;
        }

        return $this->returnDocType($method->getDocComment());
    }

    private function parameterType(ReflectionParameter $param)
    {
        if ($param->getType()) {
            return $this->structType($param->getClass());
        }

        return $this->docCommentType($param->getDeclaringFunction()->getDocComment(), $param->getName());
    }

    private function structType(ReflectionClass $class)
    {
        $props = $class->getProperties(ReflectionProperty::IS_PUBLIC);

        return new ObjectType(array_reduce($props, function ($carry, ReflectionProperty $curr) {
            return array_merge($carry, [$curr->getName() => $this->propertyType($curr)]);
        }, []));
    }

    private function propertyType(ReflectionProperty $prop)
    {
        return $this->reflectionTypeType($prop->getType(), $prop->getDocComment());
    }

    private function reflectionTypeType($type, ?string $docComment)
    {
        if ($type === null) {
            return new UnknownType();
        }
        if ($type->isBuiltin()) {
            return $this->builtInType($type, $docComment);
        }

        return $this->structType(new ReflectionClass($type->getName()));
    }

    private function builtInType(string $type, ?string $docComment)
    {
        switch ($type) {
            case 'string': return new StringType();
            case 'float':
            case 'int': return new NumberType();
            case 'array': return $this->docCommentType($docComment);
            default: return new UnknownType();
        }
    }

    private function returnDocType(string $docComment)
    {
        $matches = [];
        preg_match("/@return[\S]+([^\S]+)/", $docComment, $matches);
        if (count($matches) === 2) {
            return $this->stringType($matches[2]);
        }

        return new UnknownType();
    }

    private function docCommentType(string $docComment, ?string $name = '')
    {
        $matches = [];
        $nameMatch = $name ? '[\s]+\$?' . preg_quote($name, '/') : '';
        preg_match("/@(var|param|property)[\s]+([^\s]+)$nameMatch/", $docComment, $matches);
        if (count($matches) === 3) {
            return $this->stringType($matches[2]);
        }

        return new UnknownType();
    }

    private function stringType($typeString)
    {
        if (class_exists($typeString)) {
            return $this->structType(new ReflectionClass($typeString));
        }
        $arraySuffix = '/\[]$/';
        if (preg_match($arraySuffix, $typeString)) {
            return new ArrayType($this->stringType(preg_replace($arraySuffix, '', $typeString)));
        }

        return $this->builtInType($typeString, '');
    }
}
