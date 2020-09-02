<?php

namespace CodeGen\Data\Types;

use CodeGen\Data\TypeValue;

final class ArrayType extends TypeValue
{
    public TypeValue $itemType;

    /**
     * @param TypeValue $itemType
     */
    public function __construct(TypeValue $itemType)
    {
        $this->itemType = $itemType;
        parent::__construct('array');
    }
}
