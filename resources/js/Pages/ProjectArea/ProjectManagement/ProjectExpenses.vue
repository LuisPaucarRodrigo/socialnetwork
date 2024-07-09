<template>
  <Head title="Gestion de Gastos" />
  <AuthenticatedLayout :redirectRoute="{ route: 'projectmanagement.purchases_request.index', params: { id: project.id  } }">
    <template #header>
      Resumen de Gastos
    </template>
    <br>
    <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
      <div class="w-full">
        <p>Presupuesto actual: S/. {{ current_budget.toFixed(2) }}</p>
        <p>Presupuesto restante: S/. {{ project.remaining_budget.toFixed(2) }}</p>
        <br>
        <p>Total gastos en servicios: S/. {{ project.total_services_cost.toFixed(2) }}</p>
        <p>Total gastos en productos: S/. {{ project.total_products_cost.toFixed(2) }}</p>
        <p>Total gastos en planilla: S/. {{ project.total_employee_costs.reduce((a, item) => item.total_payroll + a, 0).toFixed(2) }}</p>
        <p>Total gastos en planilla (essalud): S/. {{ project.total_employee_costs.reduce((a, item) => item.essalud + a, 0).toFixed(2) }}</p>
        <br>
        <p>Gastos Fijos: S/. {{ staticCosts.toFixed(2) }}</p>
        <p>Gastos Variables: S/. {{ additionalCosts.toFixed(2) }}</p>
      </div>
      <div class="w-full lg:w-1/2">
        <div class="relative h-96">
          <canvas id="pieChart"></canvas>
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

const { project, current_budget, additionalCosts, staticCosts } = defineProps({
  project: Object,
  current_budget: Number,
  additionalCosts: Number,
  staticCosts: Number,
});

const chartInstance = ref(null);

const updateChart = () => {
  const ctx = document.getElementById('pieChart').getContext('2d');

  if (chartInstance.value) {
    chartInstance.value.destroy();
  }

  const dataWithRemainingBudget = [
    project.remaining_budget < 0 ? 0 : project.remaining_budget,
    additionalCosts,
    staticCosts,
    project.total_products_cost,
    project.total_services_cost,
    project.total_employee_costs.reduce((a, item) => item.total_payroll + a, 0).toFixed(2),
    project.total_employee_costs.reduce((a, item) => item.essalud + a, 0).toFixed(2)
  ];

  chartInstance.value = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: [
        'Presupuesto Restante',
        'Gastos Variables',
        'Gastos Fijos',
        'Costos de Productos',
        'Costos de Servicios',
        'Planilla',
        'Planilla Essalud'
      ],
      datasets: [{
        data: dataWithRemainingBudget,
        backgroundColor: Array(7).fill().map(() => getRandomColor()),
      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        tooltip: {
          callbacks: {
            label: (tooltipItem) => {
              const label = tooltipItem.label || '';
              const value = tooltipItem.raw;
              return `${label}: S/. ${value.toFixed(2)}`;
            },
          },
        },
      },
    },
  });
};

onMounted(() => {
  updateChart();
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
