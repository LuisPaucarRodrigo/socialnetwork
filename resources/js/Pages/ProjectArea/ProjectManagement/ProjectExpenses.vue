<template>
    <Head title="Gestion de Gastos" />
    <AuthenticatedLayout>
        <template #header>
            Gastos
        </template>
        Presupuesto actual: S/. {{ current_budget }} <br>
        Presupuesto restante: S/. {{ remaining_budget - project.materials_costs }}
        <br>
        Total gastos : S/. {{ remaining_budget }}<br>
        Costo de Componentes y Materiales: S/. {{ project.materials_costs }}
        <br>
        <div>
            <canvas id="pieChart"></canvas>
        </div>
        <br>
        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
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
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { ref, onMounted } from 'vue';
import { Chart, registerables } from 'chart.js/auto';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    expenses: Object,
    project:Object,
    current_budget: Number,
    remaining_budget: Number
})

const updateChart = () => {
  const ctx = document.getElementById('pieChart').getContext('2d');

  // Verificar si el gráfico ya está creado
  if (chartInstance.value) {
    chartInstance.value.destroy();
  }

  const totalBudget = props.current_budget;

  // Calcular los porcentajes y obtener los montos
  const labels = props.expenses.data.map(expense => {
    const amount = expense.purchase_quotes?.amount || 0;
    const percentage = ((amount / totalBudget) * 100).toFixed(2);
    return `${expense.purchase_quotes?.provider || 'Sin proveedor'} (${percentage}%)`;
  });

  const amounts = props.expenses.data.map(expense => {
    return expense.purchase_quotes?.amount || 0;
  });

  // Agregar el presupuesto restante a la data
  const remainingBudget = props.remaining_budget;
  const dataWithRemainingBudget = [...amounts, remainingBudget];

  // Crear un nuevo gráfico con los datos actualizados
  chartInstance.value = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: [...labels, 'Presupuesto Restante'],
      datasets: [{
        data: dataWithRemainingBudget,
        backgroundColor: [...props.expenses.data.map(_ => getRandomColor()), '#808080'], // Color gris para el presupuesto restante
        hoverBackgroundColor: [...props.expenses.data.map(_ => getRandomColor()), '#808080'],
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
            return `${label}: S/. ${value}`;
          },
        },
      },
    },
  });
};

onMounted(() => {
  // Ejemplo de uso, asegúrate de pasar los datos reales desde tus props
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