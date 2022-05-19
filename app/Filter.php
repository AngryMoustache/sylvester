<?php

namespace App;

class Filter
{
    public string $key;
    public string $operator;
    public ?string $value;

    public function __construct(array $filter)
    {
        $this->key = $filter[0];
        $this->operator = $filter[1];
        $this->value = $filter[2] ?? null;
    }
}
