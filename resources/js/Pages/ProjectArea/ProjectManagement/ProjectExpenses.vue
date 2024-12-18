<template>

  <Head title="Gestion de Gastos" />
  <AuthenticatedLayout
    :redirectRoute="{ route: 'projectmanagement.purchases_request.index', params: { id: project.id } }">
    <template #header>
      Resumen de Gastos {{ project.description }}
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
              <tbody class="tabular-nums">
                <tr class="text-gray-700">
                  <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">Presupuesto actual</td>
                  <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">
                    S/. {{ current_budget.toFixed(2) }}
                  </td>
                </tr>
                <tr class="text-gray-700">
                  <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">Presupuesto restante</td>
                  <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">
                    S/. {{ project.remaining_budget.toFixed(2) }}</td>
                </tr>
                <tr class="text-gray-700">
                  <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">Total gastos en servicios</td>
                  <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">S/. {{
                    project.total_services_cost.toFixed(2) }}</td>
                </tr>
                <tr class="text-gray-700">
                  <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">Total gastos en productos</td>
                  <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">S/.
                    {{ project.total_products_cost.toFixed(2) }}</td>
                </tr>
                <tr class="text-gray-700">
                  <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">
                    <div class="flex gap-3 justify-between">
                      <p>
                        MOD Operativo
                      </p>
                      <button @click="openDetailsModal({
                        title: 'MOD Operativo',
                        detArray: operativeMod
                      })" type="button" class="text-green-500 hover:text-green-300">
                        <InformationCircleIcon class="w-5 h-5 " />
                      </button>
                    </div>
                  </td>
                  <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">
                    S/.{{ project.total_employee_costs.reduce((a, item) => item.total_payroll + a, 0).toFixed(2) }}
                  </td>
                </tr>
                <tr class="text-gray-700">
                  <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">Costos Fijos</td>
                  <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">
                    S/. {{ staticCosts.toFixed(2) }}</td>
                </tr>
                <tr class="text-gray-700">
                  <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">Costos Variables</td>
                  <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right">
                    S/. {{ additionalCosts.toFixed(2) }}</td>
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
                    Costos Fijos</th>
                  <th
                    class="border-b-2 border-gray-200 bg-gray-100 px-3 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-600">
                    Monto</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, i) in scExpensesAmounts" class="text-gray-700">
                  <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">
                    <div class="flex gap-3 justify-between">
                      <p :class="[
                        { 'text-red-700': scExpensesThatDontCount.includes(item.expense_type) }
                      ]">
                        {{ item.expense_type }}
                      </p>
                      <button @click="prevOpenModal({
                        spMod: 'static',
                        expType: item.expense_type,
                        project_id: project.id
                      })" type="button" class="text-green-500 hover:text-green-300">
                        <InformationCircleIcon class="w-5 h-5 " />
                      </button>
                    </div>
                  </td>
                  <td
                    class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right tabular-nums">
                    S/. {{
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
      <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
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
                  <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">
                    <div class="flex gap-3 justify-between">
                      <p>
                        {{ item.expense_type }}
                      </p>
                      <button @click="prevOpenModal({
                        spMod: 'additional',
                        expType: item.expense_type,
                        project_id: project.id
                      })" type="button" class="text-green-500 hover:text-green-300">
                        <InformationCircleIcon class="w-5 h-5 " />
                      </button>
                    </div>
                  </td>
                  <td
                    class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right tabular-nums">
                    S/. {{ item.total_amount.toFixed(2) }}
                  </td>
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

    <Modal :show="showDetailsModal" @close="closeDetailsModal">
      <div class="p-6">
        <table class="w-full whitespace-nowrap rounded-md shadow-sm">
          <thead>
            <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                {{ detailsStructure.title }}
              </th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-3 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-600">
                Monto
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, i) in detailsStructure.detArray" class="text-gray-700" :key="i">
              <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">{{ item.spentName }}</td>
              <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right tabular-nums">
                S/. {{ item.amount.toFixed(2) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </Modal>


  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted } from 'vue';
import { Chart, registerables } from 'chart.js/auto';
import { Head } from '@inertiajs/vue3';
import { InformationCircleIcon } from "@heroicons/vue/24/outline";
import Modal from '@/Components/Modal.vue';

const {
  project,
  current_budget,
  additionalCosts,
  staticCosts,
  acExpensesAmounts,
  scExpensesAmounts
} = defineProps({
  project: Object,
  current_budget: Number,
  additionalCosts: Number,
  staticCosts: Number,
  acExpensesAmounts: Array,
  scExpensesAmounts: Array,
  scExpensesThatDontCount: Array
});


let operativeMod = [
  { spentName: 'Planilla', amount: project.total_employee_costs.reduce((a, item) => item.total_payroll + a, 0), },
  { spentName: 'Essalud', amount: project.total_employee_costs.reduce((a, item) => item.essalud + a, 0) },
  // { spentName : 'Viáticos', amount: 0 },
]

const chartInstance = ref(null);
const updateChart = () => {
  const ctx = document.getElementById('pieChart').getContext('2d');
  if (chartInstance.value) {
    chartInstance.value.destroy();
  }
  const dataWithRemainingBudget = [
    project.remaining_budget < 0
      ? 0
      : project.remaining_budget,
    staticCosts,
    additionalCosts,
    project.total_products_cost,
    project.total_services_cost,
    operativeMod.reduce((a, b) => (a + b.amount), 0).toFixed(2)
  ];

  chartInstance.value = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: [
        'Presupuesto Restante',
        'Costos Fijos',
        'Costos Variables',
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
  const dataWithRemainingBudget = scExpensesAmounts.map(item => item.total_amount);
  chartInstance2.value = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: scExpensesAmounts.map(item => item.expense_type),
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
  const dataWithRemainingBudget = acExpensesAmounts.map(item => item.total_amount);
  chartInstance3.value = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: acExpensesAmounts.map(item => item.expense_type),
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
  if (staticCosts > 0) {
    updateChart2();
  }
  if (additionalCosts > 0) {
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


//Details modal 

const showDetailsModal = ref(false)
const detailsStructure = ref({})
const openDetailsModal = (details) => {
  showDetailsModal.value = true
  detailsStructure.value = details
}
const closeDetailsModal = () => {
  showDetailsModal.value = false
  detailsStructure.value = {}
}


const prevOpenModal = async (form) => {
  const det = await fetchExpenseTypeData(form)
  openDetailsModal({ title: form.expType, detArray: det })
}


async function fetchExpenseTypeData(form) {
  try {
    const res = await axios.post(route('project.expenses.zones.details'), form)
    return res.data
  } catch (e) {
    console.log(e)
  }
}





</script>
