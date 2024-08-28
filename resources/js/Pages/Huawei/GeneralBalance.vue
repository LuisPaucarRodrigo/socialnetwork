<template>

    <Head title="Gestion de Gastos" />
    <AuthenticatedLayout>
      <template #header>
        Resumen de Gastos e Ingresos
      </template>
      <div class="flex gap-2 mb-5">
        <Link :href="route('huawei.generalbalance.costs.summary')" type="button" class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 whitespace-nowrap">
            Gastos
        </Link>
        <Link :href="route('huawei.generalbalance.earnings')" type="button" class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 whitespace-nowrap">
            Ingresos
        </Link>
      </div>
      <div class="flex flex-col space-y-24">
        <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
            <div class="w-full lg:w-1/2">
                <h3 class="text-lg font-semibold mb-5">Resumen de Gastos e Ingresos</h3>
            <div class="overflow-x-auto ring-1 ring-gray-200">
              <table class="w-full whitespace-no-wrap">
                <thead>
                  <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <th
                      class="border-b-2 border-gray-200 bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Nombre</th>
                    <th
                      class="border-b-2 border-gray-200 bg-gray-100 px-3 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Monto</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">Total de Ingresos</td>
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">S/. {{
          props.total_earnings.toFixed(2) }}</td>
                  </tr>
                  <tr class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">Total de Gastos</td>
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">S/. {{
          (props.total_variable_costs + props.total_static_costs).toFixed(2) }}</td>
                  </tr>
                  <tr :class="{
                        'text-gray-700': true,
                        'bg-red-500': props.total_earnings - (props.total_static_costs + props.total_variable_costs) < 0,
                        'bg-green-500': props.total_earnings - (props.total_static_costs + props.total_variable_costs) > 0,
                        'bg-gray-100': props.total_earnings - (props.total_static_costs + props.total_variable_costs) === 0
                    }">
                        <td class="border-b border-gray-200 px-3 py-3 text-sm font-black text-black">Estado Actual</td>
                        <td class="border-b border-gray-200 px-3 py-3 text-sm font-black text-black whitespace-nowrap text-right">
                            S/. {{ (props.total_earnings - (props.total_static_costs + props.total_variable_costs)).toFixed(2) }}
                        </td>
                    </tr>

                </tbody>
              </table>
            </div>
          </div>
          <div class="w-full lg:w-1/2 flex flex-col items-center">
          <h3 class="text-lg font-semibold mb-5 lg:hidden">Resumen de Gastos e Ingresos</h3>
          <div class="relative h-96 w-full">
            <canvas id="barChart"></canvas>
          </div>
        </div>
        </div>

        <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
          <div class="w-full lg:w-1/2">
            <h3 class="text-lg font-semibold mb-5">Resumen de Gastos</h3>
            <div class="overflow-x-auto ring-1 ring-gray-200">
              <table class="w-full whitespace-no-wrap">
                <thead>
                  <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <th
                      class="border-b-2 border-gray-200 bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Nombre</th>
                    <th
                      class="border-b-2 border-gray-200 bg-gray-100 px-3 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Monto</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">Gastos Variables</td>
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">S/. {{
          props.total_variable_costs.toFixed(2) }}</td>
                  </tr>
                  <tr class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">Gastos Fijos</td>
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">S/. {{
          props.total_static_costs.toFixed(2) }}</td>
                  </tr>
                  <tr class="text-gray-700 bg-blue-400">
                    <td class="border-b border-gray-200 px-3 py-3 text-sm font-black text-black">TOTAL</td>
                    <td class="border-b border-gray-200 px-3 py-3 text-black font-black text-sm whitespace-nowrap text-right">S/. {{
          (props.total_static_costs + props.total_variable_costs).toFixed(2) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="lg:w-1/2">
            <div class="relative h-96">
              <canvas id="pieChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>

  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { ref, onMounted } from 'vue';
  import { Chart, registerables } from 'chart.js/auto';
  import { Head, Link } from '@inertiajs/vue3';

  const props = defineProps({
    total_variable_costs: Number,
    total_static_costs: Number,
    total_earnings: Number
  });

  const chartInstance = ref(null);
  const updateChart = () => {
    const ctx = document.getElementById('pieChart').getContext('2d');
    if (chartInstance.value) {
      chartInstance.value.destroy();
    }

    const dataWithRemainingBudget = [props.total_variable_costs, props.total_static_costs];
    chartInstance.value = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: ['Gastos Variables', 'Gastos Fijos'],
        datasets: [{
          data: dataWithRemainingBudget,
          backgroundColor: Array(7).fill().map(() => getRandomColor()),
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        tooltips: {
          callbacks: {
            label: (tooltipItem, data) => {
              const dataset = data.datasets[tooltipItem.datasetIndex];
              const label = data.labels[tooltipItem.index] || '';
              const value = dataset.data[tooltipItem.index];
              return `${label}: S/. ${value.toFixed(2)}`;
            },
          },
        },

      },
    });
  };

  const updateBarChart = () => {
    const ctx = document.getElementById('barChart').getContext('2d');
    const total_costs = props.total_static_costs + props.total_variable_costs;
    const barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Ingresos Totales', 'Gastos Totales'], // Etiquetas de los datos en el eje x
            datasets: [{
                label: 'Montos', // Etiqueta común para todos los datos
                data: [props.total_earnings, total_costs],
                backgroundColor: [
                'rgba(75, 192, 75, 0.2)',   // Verde para 'Total de Ingresos'
                'rgba(255, 99, 71, 0.2)'    // Rojo más intenso para 'Total de Gastos'
                ],
                borderColor: [
                'rgba(75, 192, 75, 1)',   // Verde para 'Total de Ingresos'
                'rgba(255, 99, 71, 1)'    // Rojo más intenso para 'Total de Gastos'
                ],
                borderWidth: 2 // Grosor de las barras
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: '' // Título del eje X
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: '' // Título del eje Y
                    }
                }
            },
            plugins: {
                legend: {
                    display: false,
                    position: 'top', // Posición de la leyenda del gráfico
                    padding: {
                        top: 20 // Espacio entre la leyenda y el gráfico
                    }
                },
                tooltip: {
                    callbacks: {
                        label: (tooltipItem) => {
                            const label = tooltipItem.dataset.label || '';
                            const value = tooltipItem.raw;
                            return `${label}: S/. ${parseFloat(value).toFixed(2)}`;
                        }
                    }
                }
            }
        }
    });
};

  onMounted(() => {
    updateChart();
    updateBarChart();
  });

  Chart.register(...registerables);

  const getRandomColor = () => {
    const letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
      color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
  };

  </script>
