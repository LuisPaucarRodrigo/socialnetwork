<script setup>
import { ref, onMounted } from "vue";
import {
    Chart,
    BarElement,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend,
} from "chart.js";

Chart.register(BarElement, CategoryScale, LinearScale, Tooltip, Legend);

const { project_id } = defineProps({ project_id: Number });

const chartRef = ref(null);
let chartInstance = null;

// Datos iniciales vacíos
const labels = ref([]);
const data = ref([]);

const createGradient = (ctx, chartArea) => {
    const { left, right, top, bottom } = chartArea;
    const width = right - left;
    const height = bottom - top;

    const gradient = ctx.createLinearGradient(0, top, width, bottom);
    gradient.addColorStop(0, "#515fed"); // Azul en la parte superior
    gradient.addColorStop(1, "#9b59b6"); // Morado en la parte inferior

    return gradient;
};
// Función para crear el gráfico vacío
const createChart = () => {
    if (chartRef.value) {
        chartInstance = new Chart(chartRef.value, {
            type: "bar",
            data: {
                labels: labels.value,
                datasets: [
                    {
                        label: "Utilidad",
                        data: data.value,
                        backgroundColor: (context) => {
                            const chart = context.chart;
                            if (!chart.chartArea) return "#5eace5"; // Color base si aún no hay área definida
                            return createGradient(chart.ctx, chart.chartArea);
                        },
                        borderRadius: 2,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { font: { size: 14 } },
                    },
                    x: {
                        ticks: { font: { size: 14 } },
                    },
                },
                plugins: { legend: { display: false } },
            },
        });
    }
};

// Función para actualizar el gráfico cuando lleguen los datos
async function getLastUtilities() {
    const res = await axios.get(
        route("projectmanagement.last12.utilities", { project_id })
    );

    // Actualiza labels y data
    labels.value = res.data.months;
    data.value = res.data.utilities;

    // Si el gráfico ya está creado, actualiza los datos
    if (chartInstance) {
        chartInstance.data.labels = labels.value;
        chartInstance.data.datasets[0].data = data.value;
        chartInstance.update(); // 🔥 Refresca el gráfico con los nuevos datos
    }
}

onMounted(() => {
    createChart(); // Crea el gráfico vacío
    getLastUtilities(); // Obtiene los datos y los actualiza
});
</script>

<template>
    <div class="overflow-auto p-4">
        <h4 class="text-2xl font-medium text-gray-700 text-center">
            Utilidad en S/. (sin igv)
        </h4>
        <div class="w-auto h-96">
            <canvas ref="chartRef"></canvas>
        </div>
    </div>
</template>
