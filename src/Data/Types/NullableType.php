<?php

namespace CodeGen\Data\Types;

use CodeGen\Data\TypeValue;

class NullableType extends TypeValue
{
    private TypeValue $type;
    public function __construct(TypeValue $type)
    {
        $this->type = $type;
        parent::__construct('?' . $type->typeAsString);
    }

    public function example()
    {
        return (rand(0, 10) > 8) ? null : $this->type->example();
    }
}
