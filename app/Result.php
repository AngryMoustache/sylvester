<?php

namespace App;

use Illuminate\Support\Str;

class Result
{
    public string $item_id;
    public array $data;
    public array $result;
    public ?int $weight = null;

    public function __construct($item, $weights)
    {
        $this->item_id = (string) $item->item_id;
        $this->data = $item->data;
        $this->result = $item->result;
        $this->weights = $weights;
    }

    public function parse($key, $operator, $value = null)
    {
        $item = $this->data[$key] ?? null;
        $value = json_decode($value, true);

        if (is_string($item) && is_string($value)) {
            if ($operator === '=') {
                return ($item ?? '') === ($value ?? '');
            }

            if ($operator === 'LIKE') {
                return $value === '' || Str::contains($item ?? '', $value ?? '', true);
            }

            if (in_array($operator, ['>', '>=', '<', '<='])) {
                switch ($operator) {
                    case '>': return (int) $item > (int) $value;
                    case '>=': return (int) $item >= (int) $value;
                    case '<': return (int) $item < (int) $value;
                    case '<=': return (int) $item <= (int) $value;
                }
            }
        }

        if (is_array($item) && is_array($value)) {
            if ($operator === '=') {
                return array_diff($value, $item ?? []) === [];
            }

            if ($operator === 'LIKE') {
                return count(array_intersect($value, $item ?? [])) > 0;
            }
        }

        return false;
    }

    public function weigh($key, $verdict): void
    {
        if ($verdict === true) {
            $this->weight += ($this->weights[$key] ?? 1);
        }
    }
}