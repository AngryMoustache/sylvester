<?php

namespace App;

class FilterParser
{
    public function __construct(
        public $filters,
        public $data,
        public $weights
    ) {}

    public function parse()
    {
        // TODO: DEEP NESTING (call $this->parse() recursively)

        foreach ($this->filters as $filters) {
            foreach ($filters as $filter) {
                $this->data = $this->data->map(function ($result) use ($filter) {
                    $result->has_passed ??= false;

                    $verdict = $result->parse(...$filter);

                    if ($verdict) {
                        // Some verdicts will weigh heavier than others
                        // A 'false' verdict will translate into 0, therefore not adding anything
                        $result->weight += ($this->weights[$filter[0]] ?? 1) * ((int) $verdict);
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

        return $this->data;
    }
}
