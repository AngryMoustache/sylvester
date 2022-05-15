<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DataController extends Controller
{
    /**
     * Filter and return data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filter(Request $request)
    {
        $data = Data::where([
            'user_id' => $request->user()->id,
            'item_type' => $request->get('item_type'),
        ])->pluck('data', 'item_id');

        $filters = $request->get('filters', []);

        foreach ($filters['string_contains'] ?? [] as $key => $value) {
            $data = $data->filter(function ($item) use ($key, $value) {
                return $value === null || Str::contains($item[$key] ?? '', $value, true);
            });
        }

        foreach ($filters['string_is'] ?? [] as $key => $value) {
            $data = $data->filter(function ($item) use ($key, $value) {
                return ($item[$key] ?? '') === $value;
            });
        }

        foreach ($filters['arrays_all'] ?? [] as $key => $value) {
            $data = $data->filter(function ($item) use ($key, $value) {
                return array_diff($value, $item[$key] ?? []) === [];
            });
        }

        return response()->json([
            'item_type' => $request->get('item_type'),
            'results_count' => $data->count(),
            'results' => $request->get('full_data', true) ? $data : $data->keys(),
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
            'data' => collect($request->post('data', []))->except('item_id', 'item_type'),
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
