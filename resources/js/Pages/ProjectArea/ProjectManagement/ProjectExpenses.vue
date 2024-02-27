<template>
  <Head title="Gestion de Gastos" />
  <AuthenticatedLayout>
    <template #header>
      Gastos
    </template>
    Presupuesto actual: S/. {{ current_budget }} <br>
    Presupuesto restante: S/. {{ remainingBudget.toFixed(2) }}
    <br>
    <br>
    Total gastos en activos: S/. {{ project.total_assigned_resources_costs.toFixed(2) }}<br>
    Total gastos en productos: S/. {{ project.total_product_costs_with_liquidation.toFixed(2) }}<br>
    Gastos adicionales: S/. {{ additionalCosts }}
    <br>
    <br>
    <div>
      <canvas id="pieChart"></canvas>
    </div>
    <br>
    <div v-if="false" class="inline-block min-w-full overflow-hidden rounded-lg shadow">
      <table class="w-full whitespace-no-wrap">
        <thead>
          <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              Proveedor
            </th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              Descripcion
            </th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              Monto
            </th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              Estado de Compra
            </th>

          </tr>
        </thead>
        <tbody>
          <tr v-for="expense in expenses.data" :key="expense.id" class="text-gray-700">
            <template v-if="expense.id != null">
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                <p class="text-gray-900 whitespace-no-wrap">{{ expense.purchase_quotes[0]?.provider }}</p>
              </td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                <p class="text-gray-900 whitespace-no-wrap">{{ expense.product_description }}</p>
              </td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                <p class="text-gray-900 whitespace-no-wrap">{{ expense.purchase_quotes[0].amount }}</p>
              </td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                <p class="text-gray-900 whitespace-no-wrap">{{ expense.purchase_quotes[0]?.purchase_order?.state }}</p>
              </td>
            </template>
          </tr>
        </tbody>
      </table>

      <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
        <pagination :links="expenses.links" />
      </div>
    </div>
    <div class="mt-6 flex items-center justify-between gap-x-6">
      <a :href="route('projectmanagement.purchases_request.index', { id: project.id })"
        class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
        Atras
      </a>
    </div>
  </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { ref, onMounted } from 'vue';
import { Chart, registerables } from 'chart.js/auto';
import { Head } from '@inertiajs/vue3';

const { project, current_budget, remaining_budget, additionalCosts } = defineProps({
  project: Object,
  current_budget: Number,
  remaining_budget: Number,
  additionalCosts: Number
})


const expenses = { data: [], links: [] }

const remainingBudget = current_budget - project.total_resources_costs_with_liquidation - project.total_product_costs_with_liquidation - project.total_employee_costs - additionalCosts;


const updateChart = () => {
  const ctx = document.getElementById('pieChart').getContext('2d');

  // Verificar si el gráfico ya está creado
  if (chartInstance.value) {
    chartInstance.value.destroy();
  }

  const totalBudget = current_budget;

  // Calcular los porcentajes y obtener los montos

  // Agregar el presupuesto restante y los costos adicionales a la data\


  const dataWithRemainingBudget = [remainingBudget, additionalCosts, project.total_assigned_resources_costs, project.total_product_costs_with_liquidation];

  // Crear un nuevo gráfico con los datos actualizados
  chartInstance.value = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: ['Presupuesto Restante', 'Costos Adicionales', 'Costos de Activos', 'Costos de Productos'],
      datasets: [{
        data: dataWithRemainingBudget,
        backgroundColor: [getRandomColor(), getRandomColor(), getRandomColor(), getRandomColor()],
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
});

Chart.register(...registerables);

const chartOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
});

const chartInstance = ref(null);

const getRandomColor = () => {
  const letters = '0123456789ABCDEF';
  let color = '#';
  for (let i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
};

</script>