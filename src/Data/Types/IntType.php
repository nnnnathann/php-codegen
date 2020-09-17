<?php

namespace CodeGen\Data\Types;

class IntType extends PrimitiveType
{
    public function __construct()
    {
        parent::__construct('int');
    }

    public function example()
    {
        return rand(-1000, 1000);
    }
}
