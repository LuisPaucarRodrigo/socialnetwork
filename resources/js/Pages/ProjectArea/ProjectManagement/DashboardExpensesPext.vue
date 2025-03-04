<template>
    <AuthenticatedLayout>
        <div class="flex flex-col space-y-16">
            <BarChart :project_id="project_id" />
            <div v-if="expenses.additional.length > 0"
                class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                <div class="w-full lg:w-1/2">
                    <div class="overflow-x-auto ring-1 ring-gray-200">
                        <table class="w-full whitespace-no-wrap">
                            <thead>
                                <tr
                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Costos Adicionales
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-3 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Monto
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, i) in expenses.additional" class="text-gray-700">
                                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">
                                        <div class="flex gap-3 justify-between">
                                            <p>
                                                {{ item.expense_type }}
                                            </p>
                                            <button @click="
                                                prevOpenModal({
                                                    spMod: 'static',
                                                    expType:
                                                        item.expense_type,
                                                    project_id: project.id,
                                                })
                                                " type="button" class="text-green-500 hover:text-green-300">
                                                <InformationCircleIcon class="w-5 h-5" />
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
                        <canvas id="pieChart2"></canvas>
                    </div>
                </div>
            </div>

            <div v-if="expenses.fixed.length > 0" class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                <div class="w-full lg:w-1/2">
                    <div class="overflow-x-auto ring-1 ring-gray-200">
                        <table class="w-full whitespace-no-wrap">
                            <thead>
                                <tr
                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Costos Fijos
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-3 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Monto
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, i) in expenses.fixed" class="text-gray-700">
                                    <td class="border-b border-gray-200 bg-white px-3 py-3 text-sm">
                                        <div class="flex gap-3 justify-between">
                                            <p>
                                                {{ item.expense_type }}
                                            </p>
                                            <button @click="
                                                prevOpenModal({
                                                    spMod: 'fixed',
                                                    expType:
                                                        item.expense_type,
                                                    project_id: project.id,
                                                })
                                                " type="button" class="text-green-500 hover:text-green-300">
                                                <InformationCircleIcon class="w-5 h-5" />
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
                    <div class="relative h-[420px]">
                        <canvas id="pieChart3"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BarChart from '@/Layouts/BarChart.vue';
// import { InformationCircleIcon } from "@heroicons/vue/24/outline";

import { Chart, registerables } from 'chart.js/auto';
import { onMounted, ref } from 'vue';

const { expenses, project_id } = defineProps({
    expenses: Object,
    project_id: String
})

const chartInstance2 = ref(null);
const updateChart2 = () => {
    const ctx = document.getElementById('pieChart2').getContext('2d');
    if (chartInstance2.value) {
        chartInstance2.value.destroy();
    }
    const dataWithRemainingBudget = expenses.additional.map(item => item.total_amount);
    chartInstance2.value = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: expenses.additional.map(item => item.expense_type),
            datasets: [{
                data: dataWithRemainingBudget,
                backgroundColor: Array(expenses.additional.length).fill().map(() => getRandomColor()),
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
    const dataWithRemainingBudget = expenses.fixed.map(item => item.total_amount);
    chartInstance3.value = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: expenses.fixed.map(item => item.expense_type),
            datasets: [{
                data: dataWithRemainingBudget,
                backgroundColor: Array(expenses.fixed.length).fill().map(() => getRandomColor()),
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
Chart.register(...registerables);

const getRandomColor = () => {
    const letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
};

onMounted(() => {
    if (expenses.additional.length > 0) {
        updateChart2();
    }

    if (expenses.fixed.length > 0) {
        updateChart3();
    }
});
</script>