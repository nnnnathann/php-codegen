<?php

namespace CodeGen\Data\Types;

use CodeGen\Data\TypeValue;

class ObjectType extends TypeValue
{
    /** @var TypeValue[] keyed by property name string */
    public array $properties;

    /**
     * @param TypeValue[] $properties
     */
    public function __construct(array $properties)
    {
        parent::__construct('object');
        $this->properties = $properties;
    }
}
