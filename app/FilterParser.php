<?php

namespace App;

class FilterParser
{
    public function __construct(public $filters, public $data) {}

    public function parse()
    {
        // TODO: DEEP NESTING (call $this->parse() recursively)

        foreach ($this->filters as $filters) {
            foreach ($filters as $filter) {
                $this->data = $this->data->map(function ($result) use ($filter) {
                    $result->has_passed ??= false;

                    $verdict = $result->parse(...$filter);
                    $result->weigh($filter[0], $verdict);

                    if ($verdict) {
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
