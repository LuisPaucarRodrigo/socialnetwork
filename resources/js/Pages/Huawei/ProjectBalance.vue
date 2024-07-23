<template>

    <Head title="Gestion de Gastos" />
    <AuthenticatedLayout
      :redirectRoute="{ route: 'huawei.projects'}">
      <template #header>
        Resumen de Gastos e Ingresos
      </template>
      <br>
      <div class="flex flex-col space-y-24">
        <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
          <div class="w-full lg:w-1/2">
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
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">Gastos Adicionales</td>
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">S/. {{
          huawei_project.additional_cost_total.toFixed(2) }}</td>
                  </tr>
                  <tr class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">Materiales en Proyecto</td>
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">S/. {{
          huawei_project.materials_in_project.toFixed(2) }}</td>
                  </tr>
                  <tr class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">Materiales Liquidados</td>
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">S/. {{
          huawei_project.materials_liquidated.toFixed(2) }}</td>
                  </tr>
                  <tr class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">Equipos en Proyecto</td>
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">S/. {{
          huawei_project.equipments_in_project.toFixed(2) }}</td>
                  </tr>
                  <tr class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">Equipos Liquidados</td>
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">S/. {{
          huawei_project.equipments_liquidated.toFixed(2) }}</td>
                  </tr>
                  <tr class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">TOTAL</td>
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">S/. {{
          huawei_project.total_project_cost.toFixed(2) }}</td>
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
        <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
          <div class="w-full lg:w-1/2">
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
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">Monto Inicial</td>
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">S/. {{
          huawei_project.initial_amount }}.00</td>
                  </tr>
                  <tr class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">Total de Ingresos</td>
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">S/. {{
          huawei_project.total_earnings.toFixed(2) }}</td>
                  </tr>
                  <tr class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">Total de Gastos</td>
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">S/. {{
          huawei_project.total_project_cost.toFixed(2) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="flex flex-col items-center lg:w-1/2 mx-auto">
          <h2 class="text-lg font-semibold mb-4">Ingresos y Costos</h2>
          <div class="relative h-96 w-full">
            <canvas id="barChart"></canvas>
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
  import { Head } from '@inertiajs/vue3';

  const { huawei_project } = defineProps({
    huawei_project: Object
  });

  const chartInstance = ref(null);
  const updateChart = () => {
    const ctx = document.getElementById('pieChart').getContext('2d');
    if (chartInstance.value) {
      chartInstance.value.destroy();
    }

    const dataWithRemainingBudget = [huawei_project.additional_cost_total, huawei_project.materials_in_project, huawei_project.materials_liquidated, huawei_project.equipments_in_project, huawei_project.equipments_liquidated
    ];
    chartInstance.value = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: ['Gastos Adicionales', 'Materiales en Proyecto' , 'Materiales Liquidados', 'Equipos en Proyectos', 'Equipos Liquidados'],
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
    const barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Monto Inicial', 'Ingresos Totales', 'Gastos Totales'], // Etiquetas de los datos en el eje x
            datasets: [{
                label: 'Montos', // Etiqueta común para todos los datos
                data: [huawei_project.initial_amount, huawei_project.total_earnings, huawei_project.total_project_cost],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)', // Color para 'Monto Inicial'
                    'rgba(54, 162, 235, 0.2)', // Color para 'Ingresos Totales'
                    'rgba(255, 99, 132, 0.2)'  // Color para 'Gastos Totales'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)', // Color del borde para 'Monto Inicial'
                    'rgba(54, 162, 235, 1)', // Color del borde para 'Ingresos Totales'
                    'rgba(255, 99, 132, 1)'  // Color del borde para 'Gastos Totales'
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
