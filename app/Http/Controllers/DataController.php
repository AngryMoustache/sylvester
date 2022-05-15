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
        ])->get()->mapWithKeys(function ($item) {
            return [$item->item_id => [
                'data' => $item->data,
                'result' => $item->result,
            ]];
        });

        $filters = $request->get('filters', []);

        foreach ($filters['string_contains'] ?? [] as $key => $value) {
            $data = $data->filter(function ($item) use ($key, $value) {
                $item = $item['data'];
                return $value === null || Str::contains($item[$key] ?? '', $value, true);
            });
        }

        foreach ($filters['string_is'] ?? [] as $key => $value) {
            $data = $data->filter(function ($item) use ($key, $value) {
                $item = $item['data'];
                return ($item[$key] ?? '') === $value;
            });
        }

        foreach ($filters['arrays_all'] ?? [] as $key => $value) {
            $data = $data->filter(function ($item) use ($key, $value) {
                $item = $item['data'];
                return array_diff($value, $item[$key] ?? []) === [];
            });
        }

        // foreach ($filters['arrays_some'] ?? [] as $key => $value) {
        //     $data = $data->filter(function ($item) use ($key, $value) {
        //         $item = $item['data'];
        //         dd($value, $item[$key]);
        //         return array_search($value, $item[$key] ?? []) === [];
        //     });
        // }

        return response()->json([
            'item_type' => $request->get('item_type'),
            'results_count' => $data->count(),
            'results' => $request->get('full_data', true)
                ? $data->pluck('result')
                : $data->keys(),
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
