<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <x-wire-card>
        <p class="text-sm font-semibold text-gray-500">
            Total de Ventas del mes
        </p>

        <p class="mt-2 text-3xl font-bold text-gray-950">
            $ {{ number_format($data['sales_month'] ?? 1, 0, ',', '.') }}
        </p>
    </x-wire-card>


    <x-wire-card>
        <p class="text-sm font-semibold text-gray-500">
            Total de Compras del mes
        </p>

        <p class="mt-2 text-3xl font-bold text-gray-950">
            $ {{ number_format($data['purchases_month'] ?? 1, 0, ',', '.') }}
        </p>
    </x-wire-card>


    <x-wire-card>
        <p class="text-sm font-semibold text-gray-500">
            Total de Productos
        </p>

        <p class="mt-2 text-3xl font-bold text-gray-950">
            {{ number_format($data['total_products'] ?? 8, 0, ',', '.') }}
        </p>
    </x-wire-card>


    <x-wire-card>
        <p class="text-sm font-semibold text-gray-500">
            Stock Actual
        </p>

        <p class="mt-2 text-3xl font-bold text-gray-950">
            {{ number_format($data['total_stock'] ?? 20, 0, ',', '.') }}
        </p>
    </x-wire-card>

</div>
<div class="grid grid-cols-1 gap-6">

    <x-wire-card>

        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900">
                Ventas y Compras
            </h3>

            <p class="text-sm text-gray-500">
                Últimos 6 meses
            </p>
        </div>

        <div class="w-full">
            <canvas id="salesPurchasesChart"></canvas>
        </div>

    </x-wire-card>

</div>
@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const monthlyData = @json($data['monthly'] ?? []);

        const labels = monthlyData.map(item => item.month);

        const sales = monthlyData.map(item => Number(item.sales));

        const purchases = monthlyData.map(item => Number(item.purchases));

        const ctx = document
            .getElementById('salesPurchasesChart')
            .getContext('2d');

        new Chart(ctx, {

            type: 'line',

            data: {
                labels: labels,

                datasets: [
                    {
                        label: 'Ventas',
                        data: sales,
                        tension: 0.4,
                        borderWidth: 2,
                        fill: false
                    },
                    {
                        label: 'Compras',
                        data: purchases,
                        tension: 0.4,
                        borderWidth: 2,
                        fill: false
                    }
                ]
            },

            options: {

                responsive: true,

                plugins: {
                    legend: {
                        position: 'top'
                    }
                },

                scales: {

                    y: {
                        beginAtZero: true,

                        ticks: {
                            callback: function(value) {
                                return '$ ' +
                                    new Intl.NumberFormat('es-CO')
                                    .format(value);
                            }
                        }
                    }

                }

            }

        });

    });
</script>

@endpush
