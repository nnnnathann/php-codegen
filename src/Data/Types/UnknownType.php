<?php

namespace CodeGen\Data\Types;

use CodeGen\Data\TypeValue;

final class UnknownType extends TypeValue
{
    public function __construct()
    {
        parent::__construct('unknown');
    }
}
