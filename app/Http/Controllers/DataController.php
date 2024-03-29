<?php

namespace App\Http\Controllers;

use App\FilterParser;
use App\Models\Data;
use App\Result;
use Illuminate\Http\Request;

class DataController extends Controller
{
    /**
     * Filter and return data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filter(Request $request)
    {
        $fields = $request->get('fields', []);
        $weights = $request->get('weights', []);
        $orderBy = $request->get('orderBy', []);
        $filters = $request->get('filters', []);

        // Get the initial data and push them into the Result class
        $data = Data::where('user_id', $request->user()->id)
            ->whereIn('item_type', $request->get('item_type', []))
            ->get()
            ->mapWithKeys(fn ($item) => [$item->item_id => new Result($item)]);

        // Filter the data using the parser
        $results = (new FilterParser($filters, $data, $weights))
            ->parse()
            ->when($filters !== [], fn ($items) => $items->filter(fn ($item) => (bool) $item->weight))
            ->when($orderBy !== [], function ($collection) use ($orderBy) {
                // Sort the results based on given keys, if any
                foreach ($orderBy as $key => $direction) {
                    $collection = $collection->sortBy("data.${key}", SORT_REGULAR, $direction === 'desc');
                }

                return $collection;
            })
            ->sortByDesc('weight');

        // Select only the fields that are requested
        if ($fields !== []) {
            $results = $results->map(function ($result) use ($fields) {
                foreach ($fields as $key) {
                    $result->data[$key] ??= $result->{$key} ?? null;
                }

                return collect($result->data)->only($fields);
            });
        } else {
            $results = $results->pluck('data');
        }

        // Save to history
        $request->user()->history()->create([
            'form_params' => json_encode($request->all()),
            'result_count' => $results->count(),
        ]);

        // Return the results
        return response()->json([
            'item_type' => $request->get('item_type'),
            'results_count' => $results->count(),
            'results' => $results,
        ]);
    }

    /**
     * Store or update data.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        Data::updateOrCreate([
            'user_id' => $request->user()->id,
            'item_type' => $request->post('item_type'),
            'item_id' => $request->post('item_id'),
        ], [
            'data' => $request->post('data', []),
        ]);

        return response()->json([
            'message' => 'Data saved successfully',
        ]);
    }

    /**
     * Remove a piece of data.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $data = Data::where([
            'user_id' => $request->user()->id,
            'item_type' => $request->post('item_type'),
            'item_id' => $request->post('item_id'),
        ])->firstOrFail();

        $data->delete();

        return response()->json([
            'message' => 'Data deleted successfully',
        ]);
    }

    /**
     * Remove all data of a certain type.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function truncate(Request $request)
    {
        Data::where([
            'user_id' => $request->user()->id,
            'item_type' => $request->post('item_type'),
        ])->delete();

        return response()->json([
            'message' => 'Data truncated successfully',
        ]);
    }
}
