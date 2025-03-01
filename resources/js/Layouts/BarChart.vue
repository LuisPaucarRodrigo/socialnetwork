<script setup>
import { ref, onMounted } from "vue";
import {
    Chart,
    BarElement,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend,
    Title,
} from "chart.js";
import axios from "axios";

Chart.register(BarElement, CategoryScale, LinearScale, Tooltip, Legend, Title);

const { project_id } = defineProps({
    project_id: { type: String, required: true },
});

// Datos iniciales vacíos
const zones = ref([]);
const current = ref({ additional: [], fixed: [] });
const previous = ref({ additional: [], fixed: [] });
const years = ref({ additional: [], fixed: [] });

const staticColor = "#515fed";
const additionalColor = "#9b59b6";



const chartRef1 = ref(null);
let chartInstance1 = null;
const createChart1 = () => {
    if (chartRef1.value) {
    
        chartInstance1 = new Chart(chartRef1.value, {
            type: "bar",
            data: {
                labels: zones.value,
                datasets: [
                    {
                        label: "Costos Fijos",
                        data: current.value.fixed,
                        backgroundColor: staticColor,
                        borderRadius: 3,
                        stack: "Costos",
                    },
                    {
                        label: "Costos Variables",
                        data: current.value.additional,
                        backgroundColor: additionalColor,
                        borderRadius: 3,
                        stack: "Costos",
                    },
                ],
            },
            options: {
                indexAxis: "y",
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { beginAtZero: true, ticks: { font: { size: 14 } } },
                    y: { ticks: { font: { size: 14 } } },
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: { font: { size: 16 } },
                    },
                    title: {
                        display: true,
                        text: "Mes Actual",
                        font: { size: 18 },
                    },
                },
            },
        });
    }
};

const chartRef2 = ref(null);
let chartInstance2 = null;
const createChart2 = () => {
    if (chartRef2.value) {
        chartInstance2 = new Chart(chartRef2.value, {
            type: "bar",
            data: {
                labels: zones.value,
                datasets: [
                    {
                        label: "Costos Fijos",
                        data: previous.value.fixed,
                        backgroundColor: staticColor,
                        borderRadius: 3,
                        stack: "Costos",
                    },
                    {
                        label: "Costos Variables",
                        data: previous.value.additional,
                        backgroundColor: additionalColor,
                        borderRadius: 3,
                        stack: "Costos",
                    },
                ],
            },
            options: {
                indexAxis: "y",
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { beginAtZero: true, ticks: { font: { size: 14 } } },
                    y: { ticks: { font: { size: 14 } } },
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: { font: { size: 16 } },
                    },
                    title: {
                        display: true,
                        text: "Mes Anterior",
                        font: { size: 18 },
                    },
                },
            },
        });
    }
};

const chartRef3 = ref(null);
let chartInstance3 = null;
const createChart3 = () => {
    if (chartRef3.value) {
        chartInstance3 = new Chart(chartRef3.value, {
            type: "bar",
            data: {
                labels: zones.value,
                datasets: [
                    {
                        label: "Costos Fijos",
                        data: years.value.fixed,
                        backgroundColor: staticColor,
                        borderRadius: 3,
                        stack: "Costos",
                    },
                    {
                        label: "Costos Variables",
                        data: years.value.additional,
                        backgroundColor: additionalColor,
                        borderRadius: 3,
                        stack: "Costos",
                    },
                ],
            },
            options: {
                indexAxis: "y",
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { beginAtZero: true, ticks: { font: { size: 14 } } },
                    y: { ticks: { font: { size: 14 } } },
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: { font: { size: 16 } },
                    },
                    title: {
                        display: true,
                        text: "Anual",
                        font: { size: 18 },
                    },
                },
            },
        });
    }
};

// Función para obtener los datos y actualizar el gráfico
async function getZonesExpensesData() {
    const res = await axios.get(
        route("projectmanagement.pext.expense_dashboard_bar", { project_id })
    );
    console.log("dasd",res)
    zones.value = res.data.zones;
    current.value = res.data.current;
    previous.value = res.data.previous;
    years.value = res.data.years;

    if (chartInstance1) {
        chartInstance1.data.labels = zones.value;
        chartInstance1.data.datasets[0].data = current.value.fixed;
        chartInstance1.data.datasets[1].data = current.value.additional;
        chartInstance1.update();
    }
    if (chartInstance2) {
        chartInstance2.data.labels = zones.value;
        chartInstance2.data.datasets[0].data = previous.value.fixed;
        chartInstance2.data.datasets[1].data = previous.value.additional;
        chartInstance2.update();
    }
    if (chartInstance3) {
        chartInstance3.data.labels = zones.value;
        chartInstance3.data.datasets[0].data = years.value.fixed;
        chartInstance3.data.datasets[1].data = years.value.additional;
        chartInstance3.update();
    }
}

onMounted(() => {
    createChart1();
    createChart2();
    createChart3();
    getZonesExpensesData();
});
</script>

<template>
    <div class="w-full h-auto py-6 shadow-md rounded-lg border border-gray-200 bg-white bg-opacity-30">
        <div class="grid grid-cols-1 lg:grid-cols-3">
            <div class="flex flex-col items-center justify-center h-96">
                <canvas ref="chartRef1"></canvas>
            </div>
            <div class="flex flex-col items-center justify-center h-96">
                <canvas ref="chartRef2"></canvas>
            </div>
            <div class="flex flex-col items-center justify-center h-96">
                <canvas ref="chartRef3"></canvas>
            </div>
        </div>
    </div>
</template>
