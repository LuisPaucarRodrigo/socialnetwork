<template>
    <div
        v-if="acExpensesAmounts.length > 0 || scExpensesAmounts.length > 0"
        class="flex flex-col sm:flex-row gap-6 justify-center items-center"
    >
        <!-- Pie Chart 1 -->
        <div class="sm:w-1/2 flex justify-center items-start overflow-auto">
            <div class="relative h-40 w-full">
                <canvas id="pieChart2" class="w-full"></canvas>
            </div>
        </div>

        <!-- Pie Chart 2 -->
        <div class="sm:w-1/2 flex justify-center items-start">
            <div class="relative h-40 w-full">
                <canvas id="pieChart3"></canvas>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Chart, registerables } from "chart.js/auto";
import { ref, onMounted } from "vue";

const { acExpensesAmounts, scExpensesAmounts } = defineProps({
    acExpensesAmounts: {
        type: Array,
        required: true,
    },
    scExpensesAmounts: {
        type: Array,
        required: true,
    },
});

// Pie Chart 1
const chartInstance2 = ref(null);
const updateChart2 = () => {
    const ctx = document.getElementById("pieChart2").getContext("2d");
    if (chartInstance2.value) {
        chartInstance2.value.destroy();
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

// Inicializa los gráficos
onMounted(() => {
    if (acExpensesAmounts.length > 0) updateChart2();
    if (scExpensesAmounts.length > 0) updateChart3();
});

// Registrar Chart.js
Chart.register(...registerables);
</script>
