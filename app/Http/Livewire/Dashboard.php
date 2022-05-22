<?php

namespace App\Http\Livewire;

use App\Models\History;
use Livewire\Component;

class Dashboard extends Component
{
    public $history;

    public $chartDays = 14;

    public function mount()
    {
        $user = auth()->user();
        if (! $user) {
            die;
        }

        $this->history = History::whereUserId($user->id)->get()
            ->map(function ($item) {
                $types = collect(json_decode($item->form_params)->item_type);

                return [
                    'results' => $item->result_count,
                    'filters' => $item->form_params,
                    'item_type' => $types->flatten()->join(', ', ' and '),
                    'called_at' => $item->created_at->format('d M Y'),
                ];
            })
            ->groupBy('called_at')
            ->toArray();
    }

    public function render()
    {
        $historyChart = collect();
        for ($i = 0; $i < $this->chartDays; $i++) {
            $day = now()->subtract('day', $i)->format('d M Y');
            $historyChart->put($day, count($this->history[$day] ?? []));
        }

        return view('livewire.dashboard', [
            'historyChart' => $historyChart->reverse(),
        ]);
    }
}
