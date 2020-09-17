<?php

namespace CodeGen\Data\Types;

class FloatType extends PrimitiveType
{
    public function __construct()
    {
        parent::__construct('float');
    }

    public function example()
    {
        return rand(-1000, 1000) / 1000;
    }
}
