<?php

namespace CodeGen\Data;

final class Event
{
    public string $topic;
    public string $name;
    public TypeValue $payload;
}
