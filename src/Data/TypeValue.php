<?php

namespace CodeGen\Data;

abstract class TypeValue
{
    public string $typeAsString;

    public function __construct(string $typeAsString)
    {
        $this->typeAsString = $typeAsString;
    }

    abstract public function example();
}
