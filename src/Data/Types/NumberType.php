<?php

namespace CodeGen\Data\Types;

class NumberType extends PrimitiveType
{
    public function __construct()
    {
        parent::__construct('number');
    }
}
