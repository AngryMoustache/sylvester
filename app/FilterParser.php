<?php

namespace App;

class FilterParser
{
    public function __construct(public $filters, public $data, public $weights)
    {
        $this->filters = $this->arrayToFilters($this->filters);
    }

    public function parse($filters = null)
    {
        foreach ($filters ?? $this->filters as $filter) {
            if (is_array($filter[0])) {
                $this->parse($filter);
            } else {
                foreach ($filter as $_filter) {
                    $this->data = $this->data->map(function ($result) use ($_filter) {
                        $result->has_passed ??= false;

                        $verdict = $result->parse($_filter);

                        if ($verdict) {
                            // Some verdicts will weigh heavier than others
                            // A 'false' verdict will translate into 0, therefore not adding anything
                            $result->weight += ($this->weights[$_filter->key] ?? 1) * ((int) $verdict);
                            $result->has_passed = true;
                        }

                        return $result;
                    });
                }

                // Filter and reset the items to filter again
                $this->data = $this->data
                    ->filter(fn ($item) => $item->has_passed)
                    ->map(function ($item) {
                        $item->has_passed = false;
                        return $item;
                    });
            }
        }

        return $this->data;
    }

    /**
     * Translate an array to an array with Filter objects
     * @param array $filters
     * @return array
     */
    private function arrayToFilters(array $filters)
    {
        foreach ($filters as $key => $value) {
            if (is_array($value[0])) {
                $filters[$key] = $this->arrayToFilters($value);
            } else {
                $filters[$key] = new Filter($value);
            }
        }

        return $filters;
    }
}
