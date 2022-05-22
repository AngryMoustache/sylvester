<div>
    <h1>Usage</h1>
    <div class="w-1/2">
        <p>{{ $historyChart->sum() }} total calls the past {{ $chartDays }} day(s)</p>
        <canvas id="usage-chart"></canvas>
    </div>

    <script>
        const labels = @json($historyChart->keys())

        const data = {
            labels: labels,
            datasets: [
                {
                    label: 'API Usage',
                    backgroundColor: 'rgb(255, 99, 132)',
                    borderColor: 'rgb(255, 99, 132)',
                    data: @json($historyChart->values()),
                }
            ]
        }

        const config = {
            type: 'line',
            data: data,
            options: {}
        }

        new Chart(document.getElementById('usage-chart'), config)
    </script>
</div>
