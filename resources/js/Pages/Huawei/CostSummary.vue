<template>
    <Head title="Gestion de Gastos" />
    <AuthenticatedLayout
        :redirectRoute="{
            route: 'huawei.projects',
            params: { status: 1, prefix: 'Claro' },
        }"
    >
        <template #header> Resumen de Gastos del Proyecto - {{ huawei_project.name + ' | ' + huawei_project.code }} </template>
        <div class="flex gap-2 mb-5">
            <Link
                :href="route('huawei.projects.additionalcosts', { huawei_project: huawei_project.id, mode: 1 })"
                type="button"
                class="rounded-md bg-gray-500 px-4 py-2 text-center text-sm text-white hover:bg-gray-400 whitespace-nowrap"
            >
                Gastos Fijos
            </Link>
            <Link
                :href="route('huawei.projects.additionalcosts', {huawei_project: huawei_project.id})"
                type="button"
                class="rounded-md bg-gray-500 px-4 py-2 text-center text-sm text-white hover:bg-gray-400 whitespace-nowrap"
            >
                Gastos Variables
            </Link>
        </div>
        <div class="flex flex-col space-y-24">
            <div
                class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0"
            >
                <div class="w-full lg:w-1/2">
                    <h3 class="text-lg font-semibold mb-5">Gastos Fijos</h3>

                    <div class="overflow-x-auto ring-1 ring-gray-200">
                        <table class="w-full whitespace-no-wrap">
                            <thead>
                                <tr
                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500"
                                >
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                                    >
                                        Tipo de Gasto
                                    </th>

                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-3 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-600"
                                    >
                                        Monto
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template
                                    v-for="expense in scExpensesAmounts"
                                    :key="expense.expense_type"
                                >
                                    <tr class="text-gray-700">
                                        <td
                                            class="border-b border-gray-200 bg-white px-3 py-3 text-sm"
                                        >
                                            {{ expense.expense_type }}
                                        </td>
                                        <td
                                            class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right"
                                        >
                                            S/.
                                            {{
                                                expense.total_amount.toFixed(2)
                                            }}
                                        </td>
                                    </tr>
                                </template>
                                <!-- Fila del total -->
                                <tr class="text-gray-700 bg-blue-400">
                                    <td
                                        class="border-b border-gray-200 px-3 py-3 font-black text-black text-sm"
                                    >
                                        TOTAL GASTOS FIJOS
                                    </td>
                                    <td
                                        class="border-b border-gray-200 px-3 py-3 font-black text-black text-sm whitespace-nowrap text-right"
                                    >
                                        S/.
                                        {{
                                            scExpensesAmounts
                                                .reduce(
                                                    (total, expense) =>
                                                        total +
                                                        expense.total_amount,
                                                    0
                                                )
                                                .toFixed(2)
                                        }}
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

            <div
                class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0"
            >
                <div class="w-full lg:w-1/2">
                    <h3 class="text-lg font-semibold mb-5">Gastos Variables</h3>
                    <div class="overflow-x-auto ring-1 ring-gray-200">
                        <table class="w-full whitespace-no-wrap">
                            <thead>
                                <tr
                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500"
                                >
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                                    >
                                        Tipo de Gasto
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-3 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-600"
                                    >
                                        Monto
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template
                                    v-for="expense in acExpensesAmounts"
                                    :key="expense.expense_type"
                                >
                                    <tr class="text-gray-700">
                                        <td
                                            class="border-b border-gray-200 bg-white px-3 py-3 text-sm"
                                        >
                                            {{ expense.expense_type }}
                                        </td>
                                        <td
                                            class="border-b border-gray-200 bg-white px-3 py-3 text-sm whitespace-nowrap text-right"
                                        >
                                            S/.
                                            {{
                                                expense.total_amount.toFixed(2)
                                            }}
                                        </td>
                                    </tr>
                                </template>
                                <!-- Fila del total -->
                                <tr class="text-gray-700 bg-blue-400">
                                    <td
                                        class="border-b border-gray-200 px-3 py-3 font-black text-black text-sm"
                                    >
                                        TOTAL GASTOS VARIABLES
                                    </td>
                                    <td
                                        class="border-b border-gray-200 px-3 py-3 font-black text-black text-sm whitespace-nowrap text-right"
                                    >
                                        S/.
                                        {{
                                            acExpensesAmounts
                                                .reduce(
                                                    (total, expense) =>
                                                        total +
                                                        expense.total_amount,
                                                    0
                                                )
                                                .toFixed(2)
                                        }}
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
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, onMounted } from "vue";
import { Chart, registerables } from "chart.js/auto";
import { Head, Link } from "@inertiajs/vue3";

const { acExpensesAmounts, scExpensesAmounts, huawei_project } = defineProps({
    acExpensesAmounts: {
        type: Object,
        required: true,
    },
    scExpensesAmounts: {
        type: Object,
        required: true,
    },
    huawei_project: Object
});


const chartInstance2 = ref(null);
const updateChart2 = () => {
    const ctx = document.getElementById("pieChart2").getContext("2d");
    if (chartInstance3.value) {
        chartInstance3.value.destroy();
    }
    const sortedData = scExpensesAmounts
        .map((item) => ({
            expense_type: item.expense_type,
            total_amount: item.total_amount,
        }))
        .sort((a, b) => b.total_amount - a.total_amount);

    const data = sortedData.map((item) => item.total_amount);
    const labels = sortedData.map((item) => item.expense_type);
    const backgroundColors = Array(sortedData.length)
        .fill()
        .map(() => getRandomColor());

    chartInstance2.value = new Chart(ctx, {
        type: "pie",
        data: {
            labels: labels,
            datasets: [
                {
                    data: data,
                    backgroundColor: backgroundColors,
                    borderColor: "#ffffff", // Borde blanco en los segmentos del gráfico
                    borderWidth: 2,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "top",
                    labels: {
                        boxWidth: 20,
                        padding: 10,
                        font: {
                            size: 12,
                            color: "#7a7a7c", // Texto gris
                        },
                        usePointStyle: false, // Deshabilita los círculos
                        pointStyle: "rect", // Asegura que sean rectángulos
                        generateLabels: (chart) => {
                            return chart.data.labels.map((label, index) => ({
                                text: label,
                                fillStyle:
                                    chart.data.datasets[0].backgroundColor[
                                        index
                                    ],
                                hidden: chart.getDatasetMeta(0).data[index]
                                    .hidden,
                                strokeStyle: "#ffffff", // Borde blanco en la leyenda
                                lineWidth: 2,
                            }));
                        },
                    },
                },
            },
        },
    });
};

// Pie Chart 2
const chartInstance3 = ref(null);
const updateChart3 = () => {
    const ctx = document.getElementById("pieChart3").getContext("2d");
    if (chartInstance3.value) {
        chartInstance3.value.destroy();
    }
    const sortedData = acExpensesAmounts
        .map((item) => ({
            expense_type: item.expense_type,
            total_amount: item.total_amount,
        }))
        .sort((a, b) => b.total_amount - a.total_amount);

    const data = sortedData.map((item) => item.total_amount);
    const labels = sortedData.map((item) => item.expense_type);
    const backgroundColors = Array(sortedData.length)
        .fill()
        .map(() => getRandomColor());

    chartInstance3.value = new Chart(ctx, {
        type: "pie",
        data: {
            labels: labels,
            datasets: [
                {
                    data: data,
                    backgroundColor: backgroundColors,
                    borderColor: "#ffffff", // Borde blanco en los segmentos del gráfico
                    borderWidth: 2,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "top",
                    labels: {
                        boxWidth: 20,
                        padding: 10,
                        font: {
                            size: 12,
                            color: "#6b7280", // Texto gris
                        },
                        usePointStyle: false, // Deshabilita los círculos
                        pointStyle: "rect", // Asegura que sean rectángulos
                        generateLabels: (chart) => {
                            return chart.data.labels.map((label, index) => ({
                                text: label,
                                fillStyle:
                                    chart.data.datasets[0].backgroundColor[
                                        index
                                    ],
                                hidden: chart.getDatasetMeta(0).data[index]
                                    .hidden,
                                strokeStyle: "#ffffff", // Borde blanco en la leyenda
                                lineWidth: 2,
                            }));
                        },
                    },
                },
            },
        },
    });
};

const getRandomColor = () => {
    const letters = "0123456789ABCDEF";
    let color = "#";
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
};

onMounted(() => {
    if (acExpensesAmounts.length > 0) updateChart2();
    if (scExpensesAmounts.length > 0) updateChart3();
});

Chart.register(...registerables);

</script>
