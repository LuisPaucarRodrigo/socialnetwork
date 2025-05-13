<template>
    <template v-if="!isLoading">
        <div
            class="flex flex-col sm:flex-row gap-6 justify-center items-center"
        >
            <!-- Pie Chart 1 -->
            <div
                v-if="acExpensesAmounts.length > 0"
                class="sm:w-1/2 flex justify-center items-start"
            >
                <div class="relative h-36 w-full">
                    <canvas id="pieChart2" class="w-full"></canvas>
                </div>
            </div>
            <div
                v-else
                class="sm:w-1/2 flex justify-center items-center italic text-gray-500 h-36"
            >
                No hay gastos variables
            </div>

            <!-- Pie Chart 2 -->
            <div
                v-if="scExpensesAmounts.length > 0"
                class="sm:w-1/2 flex justify-center items-start"
            >
                <div class="relative h-36 w-full">
                    <canvas id="pieChart3"></canvas>
                </div>
            </div>
            <div
                v-else
                class="sm:w-1/2 flex justify-center items-center italic text-gray-500 h-36"
            >
                No hay gastos fijos
            </div>
        </div>
    </template>
    <!-- Skeleton de gráficos de pastel -->
    <!-- Skeleton de gráficos con labels -->
    <!-- Skeleton de gráficos con labels centrados verticalmente -->
    <div
        v-if="isLoading"
        class="flex flex-col sm:flex-row gap-6 justify-center items-center animate-pulse"
    >
        <!-- Sección 1 -->
        <div class="sm:w-1/2 flex justify-between items-center gap-4 w-full">
            <!-- Labels simulados -->
            <div class="flex flex-col gap-2 w-1/2 items-start">
                <div
                    class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-3/4"
                ></div>
                <div
                    class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-1/2"
                ></div>
                <div
                    class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-2/3"
                ></div>
            </div>
            <!-- Gráfico simulado -->
            <div class="flex justify-center items-center w-1/2">
                <div
                    class="rounded-full bg-gray-300 dark:bg-gray-700 w-36 h-36"
                ></div>
            </div>
        </div>

        <!-- Sección 2 -->
        <div class="sm:w-1/2 flex justify-between items-center gap-4 w-full">
            <!-- Labels simulados -->
            <div class="flex flex-col gap-2 w-1/2 items-start">
                <div
                    class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-3/4"
                ></div>
                <div
                    class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-1/2"
                ></div>
                <div
                    class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-2/3"
                ></div>
            </div>
            <!-- Gráfico simulado -->
            <div class="flex justify-center items-center w-1/2">
                <div
                    class="rounded-full bg-gray-300 dark:bg-gray-700 w-36 h-36"
                ></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Chart, registerables } from "chart.js/auto";
import { ref, onMounted, watch } from "vue";
import axios from "axios";

const { project_id, reload_flag } = defineProps({
    project_id: Number,
    reload_flag: Boolean,
});
const isLoading = ref(false);
const acExpensesAmounts = ref([]);
const scExpensesAmounts = ref([]);

// Pie Chart 1
const chartInstance2 = ref(null);
const updateChart2 = () => {
    const ctx = document.getElementById("pieChart2").getContext("2d");
    if (chartInstance2.value) {
        chartInstance2.value.destroy();
    }
    const sortedData = acExpensesAmounts.value
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
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "left",
                    fullWidth: true,
                    labels: {
                        boxWidth: 20,
                        padding: 2,
                        font: {
                            size: 12,
                        },
                        overflow: "visible",
                        generateLabels: (chart) => {
                            const data = chart.data.datasets[0].data;
                            const labels = chart.data.labels;
                            return labels.map((label, index) => {
                                return {
                                    text: `${label} - ${data[index].toFixed(
                                        2
                                    )}`,
                                    fillStyle:
                                        chart.data.datasets[0].backgroundColor[
                                            index
                                        ],
                                    hidden: chart.getDatasetMeta(0).data[index]
                                        .hidden,
                                };
                            });
                        },
                    },
                },
            },
            tooltip: {
                callbacks: {
                    label: (tooltipItem) => {
                        const dataset = tooltipItem.dataset;
                        const label =
                            dataset.labels[tooltipItem.dataIndex] || "";
                        const value = dataset.data[tooltipItem.dataIndex];
                        return `${label}: ${value.toFixed(2)}`;
                    },
                },
            },
        },
        plugins: [
            {
                id: "legendMaxWidth",
                afterInit(chart, args, options) {
                    const fitValue = chart.legend.fit;
                    chart.legend.fit = function fit() {
                        fitValue.bind(chart.legend)();
                        let width = (this.width += 100);
                        return width;
                    };
                },
            },
        ],
    });
};

// Pie Chart 2
const chartInstance3 = ref(null);
const updateChart3 = () => {
    const ctx = document.getElementById("pieChart3").getContext("2d");
    if (chartInstance3.value) {
        chartInstance3.value.destroy();
    }
    const sortedData = scExpensesAmounts.value
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
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: "left",

                    labels: {
                        boxWidth: 20,
                        padding: 2,
                        font: {
                            size: 12,
                        },
                        overflow: "visible",
                        generateLabels: (chart) => {
                            // Personaliza las etiquetas para incluir el valor
                            const data = chart.data.datasets[0].data;
                            const labels = chart.data.labels;
                            return labels.map((label, index) => {
                                return {
                                    text: `${label} - ${data[index].toFixed(
                                        2
                                    )}`, // Muestra el valor junto con el nombre de la etiqueta
                                    fillStyle:
                                        chart.data.datasets[0].backgroundColor[
                                            index
                                        ],
                                    hidden: chart.getDatasetMeta(0).data[index]
                                        .hidden,
                                };
                            });
                        },
                    },
                },
            },
            tooltip: {
                callbacks: {
                    label: (tooltipItem) => {
                        const dataset = tooltipItem.dataset;
                        const label =
                            dataset.labels[tooltipItem.dataIndex] || "";
                        const value = dataset.data[tooltipItem.dataIndex];
                        return `${label}: ${value.toFixed(2)}`;
                    },
                },
            },
        },
        plugins: [
            {
                id: "legendMaxWidth",
                afterInit(chart, args, options) {
                    const fitValue = chart.legend.fit;
                    chart.legend.fit = function fit() {
                        fitValue.bind(chart.legend)();
                        let width = (this.width += 100);
                        return width;
                    };
                },
            },
        ],
    });
};

// Colores aleatorios
const getRandomColor = () => {
    const letters = "0123456789ABCDEF";
    let color = "#";
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
};

async function getExpensesFromAdditionalProjects() {
    isLoading.value = true;
    const res = await axios.get(
        route("projectmanagement.projectadditional.expenses", { project_id })
    );
    isLoading.value = false;
    if (res.status === 200) {
        acExpensesAmounts.value = res.data.acExpensesAmounts;
        scExpensesAmounts.value = res.data.scExpensesAmounts;
    }
}

async function loadCharts() {
    await getExpensesFromAdditionalProjects();
    if (acExpensesAmounts.value.length > 0) updateChart2();
    if (scExpensesAmounts.value.length > 0) updateChart3();
}

// Inicializa los gráficos
onMounted(() => {
    loadCharts();
});

watch(
    () => reload_flag,
    () => loadCharts()
);

// Registrar Chart.js
Chart.register(...registerables);
</script>
