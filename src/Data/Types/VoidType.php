<?php

namespace CodeGen\Data\Types;

use CodeGen\Data\TypeValue;

final class VoidType extends TypeValue
{
    public function __construct()
    {
        parent::__construct('void');
    }

    public function example()
    {
        return '';
    }
}
