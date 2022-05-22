<?php

namespace App\Http\Livewire;

use App\Models\History;
use Livewire\Component;

class Dashboard extends Component
{
    public $history;

    public $chartDays = 14;

    public $colors = [
        'rgb(255, 99, 132)',
        'rgb(132, 99, 255)',
        'rgb(99, 132, 255)',
        'rgb(99, 255, 132)',
    ];

    public function mount()
    {
        $user = auth()->user();
        if (! $user) {
            die;
        }

        $history = History::whereUserId($user->id)->get()->map(function ($item) {
            $types = collect(json_decode($item->form_params)->item_type);

            return [
                'item_types' => $types->flatten()->unique()->toArray(),
                'called_at' => $item->created_at->format('d M Y'),
            ];
        });

        $this->history = [];
        $history->each(function ($day) {
            foreach ($day['item_types'] as $type) {
                $this->history[$type][$day['called_at']] ??= 0;
                $this->history[$type][$day['called_at']]++;
            }
        });
    }

    public function render()
    {
        $historyChart = [];
        for ($i = 0; $i < $this->chartDays; $i++) {
            $day = now()->subtract('day', $i)->format('d M Y');
            foreach (array_keys($this->history) as $type) {
                $historyChart[$type][$day] ??= $this->history[$type][$day] ?? 0;
            }
        }

        return view('livewire.dashboard', [
            'historyChart' => collect($historyChart)->reverse(),
        ]);
    }
}
