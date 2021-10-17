<?php

namespace App;

class View
{
    private string $template;

    public function getTemplate(): string
    {
        return $this->template;
    }

    private array $args;

    public function getArgs(): array
    {
        return $this->args;
    }

    public function __construct(string $template, array $args = [])
    {
        $this->template = $template;
        $this->args = $args;
    }
}
