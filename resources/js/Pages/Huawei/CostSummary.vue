<template>

    <Head title="Gestion de Gastos" />
    <AuthenticatedLayout
      :redirectRoute="{ route: 'huawei.projects' }">
      <template #header>
        Resumen de Gastos
      </template>
      <div class="flex gap-2 mb-5">
        <Link :href="route('huawei.projects.additionalcosts', {huawei_project: huawei_project.id})" type="button" class="hidden sm:block rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 whitespace-nowrap">
        Costos Variables
      </Link>
      <Link :href="route('huawei.projects.staticcosts', {huawei_project: huawei_project.id})" type="button" class="hidden sm:block rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 whitespace-nowrap">
        Costos FIjos
      </Link>
      </div>
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
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">Total gastos en planilla</td>
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">S/. {{
          huawei_project.total_employee_costs.toFixed(2) }}</td>
                  </tr>
                  <tr class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">Total gastos en planilla (essalud)</td>
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">S/. {{
          huawei_project.total_essalud_employee_cost.toFixed(2) }}</td>
                  </tr>
                  <tr class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">Costos Fijos</td>
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">S/. {{
          staticCosts.toFixed(2) }}</td>
                  </tr>
                  <tr class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">Costos Variables</td>
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">S/. {{
          additionalCosts.toFixed(2) }}</td>
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
        <div v-if="staticCosts>0" class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
          <div class="w-full lg:w-1/2">
            <div class="overflow-x-auto ring-1 ring-gray-200">
              <table class="w-full whitespace-no-wrap">
                <thead>
                  <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <th
                      class="border-b-2 border-gray-200 bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Costos Fijos</th>
                    <th
                      class="border-b-2 border-gray-200 bg-gray-100 px-3 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Monto</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, i) in scExpensesAmounts" class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">{{ item.expense_type }}</td>
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">S/. {{
          item.total_amount.toFixed(2) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="lg:w-1/2">
            <div class="relative h-96">
              <canvas id="pieChart2"></canvas>
            </div>
          </div>
        </div>
        <div v-if="additionalCosts>0" class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
          <div class="w-full lg:w-1/2">
            <div class="overflow-x-auto ring-1 ring-gray-200">
              <table class="w-full whitespace-no-wrap">
                <thead>
                  <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <th
                      class="border-b-2 border-gray-200 bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Costos Variables</th>
                    <th
                      class="border-b-2 border-gray-200 bg-gray-100 px-3 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Monto</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, i) in acExpensesAmounts" class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">{{ item.expense_type }}</td>
                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">S/. {{
          item.total_amount.toFixed(2) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="lg:w-1/2">
            <div class="relative h-96">
              <canvas id="pieChart3"></canvas>
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

  const { huawei_project, additionalCosts, staticCosts, acExpensesAmounts, scExpensesAmounts } = defineProps({
    huawei_project: Object,
    current_budget: Number,
    additionalCosts: Number,
    staticCosts: Number,
    acExpensesAmounts: Array,
    scExpensesAmounts: Array,
  });


  const chartInstance = ref(null);
  const updateChart = () => {
    const ctx = document.getElementById('pieChart').getContext('2d');
    if (chartInstance.value) {
      chartInstance.value.destroy();
    }
    const dataWithRemainingBudget = [huawei_project.total_employee_costs, huawei_project.total_essalud_employee_cost, staticCosts, additionalCosts];
    chartInstance.value = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: ['Total de Planilla', 'Total de Essalud','Costos Fijos' , 'Costos Variables'],
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



  const chartInstance2 = ref(null);
  const updateChart2 = () => {
    const ctx = document.getElementById('pieChart2').getContext('2d');
    if (chartInstance2.value) {
      chartInstance2.value.destroy();
    }
    const dataWithRemainingBudget = scExpensesAmounts.map(item=>item.total_amount);
    chartInstance2.value = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: scExpensesAmounts.map(item=>item.expense_type),
        datasets: [{
          data: dataWithRemainingBudget,
          backgroundColor: Array(scExpensesAmounts.length).fill().map(() => getRandomColor()),
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


  const chartInstance3 = ref(null);
  const updateChart3 = () => {
    const ctx = document.getElementById('pieChart3').getContext('2d');
    if (chartInstance3.value) {
      chartInstance3.value.destroy();
    }
    const dataWithRemainingBudget = acExpensesAmounts.map(item=>item.total_amount);
    chartInstance3.value = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: acExpensesAmounts.map(item=>item.expense_type),
        datasets: [{
          data: dataWithRemainingBudget,
          backgroundColor: Array(acExpensesAmounts.length).fill().map(() => getRandomColor()),
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


  onMounted(() => {
    updateChart();
    if (staticCosts>0){
      updateChart2();
    }
    if (additionalCosts>0){
      updateChart3();
    }
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
