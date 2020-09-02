<?php

namespace CodeGen\Data;

final class ServiceDefinition
{
    /** @var Action[] */
    public array $actions;

    /**
     * @param Action[] $actions
     */
    public function __construct($actions)
    {
        $this->actions = $actions;
    }

    public function getTemplateData()
    {
        return [
            'actions' => $this->actions,
        ];
    }
}
