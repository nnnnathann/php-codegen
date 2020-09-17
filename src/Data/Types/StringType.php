<?php

namespace CodeGen\Data\Types;

class StringType extends PrimitiveType
{
    const EXAMPLES = [
        'Example String Value: This is an example string value',
        'Example String Value: A little bit longer than your average string, but not by much!',
        'Example',
    ];

    public function __construct()
    {
        parent::__construct('string');
    }

    public function example()
    {
        return self::EXAMPLES[array_rand(self::EXAMPLES)];
    }
}
