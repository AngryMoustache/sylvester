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
        $weights = $request->get('weights', []);
        $orderBy = $request->get('orderBy', []);

        $data = Data::where('user_id', $request->user()->id)
            ->whereIn('item_type', $request->get('item_type', []))
            ->get()
            ->mapWithKeys(fn ($item) => [
                $item->item_id => new Result($item)
            ]);

        $filters = $request->get('filters', []);
        info(json_encode($request->all()));

        $results = (new FilterParser($filters, $data, $weights))
            ->parse()
            ->filter(fn ($item) => (bool) $item->weight)
            ->when($orderBy !== [], function ($collection) use ($orderBy) {
                foreach ($orderBy as $key => $direction) {
                    $collection = $collection->sortBy("data.${key}", SORT_REGULAR, $direction === 'desc');
                }

                return $collection;
            })
            ->sortByDesc('weight')
            ->pluck('result');

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
            'result' => $request->post('result', []),
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
}
