<?php

namespace CodeGen\Data;

final class Action
{
    public string $name;
    public TypeValue $input;
    public TypeValue $result;

    public function __construct(string $name, TypeValue $input, TypeValue $result)
    {
        $this->name = $name;
        $this->input = $input;
        $this->result = $result;
    }
}
