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

    public function example()
    {
        $example = [];
        $randomCount = rand(1, 4);
        for ($i = 0; $i < $randomCount; $i++) {
            $example[] = $this->itemType->example();
        }
        return $example;
    }
}
