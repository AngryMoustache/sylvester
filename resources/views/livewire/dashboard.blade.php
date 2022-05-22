<div>
    <h1>Usage</h1>
    <div class="w-2/3">
        <p>{{ $historyChart->flatten()->sum() }} total calls the past {{ $chartDays }} day(s)</p>
        <canvas id="usage-chart"></canvas>
    </div>

    <script>
        const labels = @json(collect($historyChart->first())->reverse()->keys())

        const data = {
            labels: labels,
            datasets: [
                @foreach ($historyChart as $type => $values)
                    {
                        label: @json($type),
                        backgroundColor: @json($colors[$loop->index] ?? $colors[0]),
                        borderColor: @json($colors[$loop->index] ?? $colors[0]),
                        data: @json($values),
                    },
                @endforeach
            ]
        }

        new Chart(document.getElementById('usage-chart'), {
            type: 'line',
            data: data,
        })
    </script>
</div>
