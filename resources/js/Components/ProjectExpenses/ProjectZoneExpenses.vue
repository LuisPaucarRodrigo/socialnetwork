<script setup>
import { ref, onMounted } from "vue";
import { Chart, BarElement, CategoryScale, LinearScale, Tooltip, Legend, Title } from "chart.js";
import axios from "axios";

Chart.register(BarElement, CategoryScale, LinearScale, Tooltip, Legend, Title);


const { project_id } = defineProps({
    project_id: { type: Number, required: true },
});



// Datos iniciales vacíos
const zones = ref([]);
const current = ref({additionals:[], statics:[]})
const previous = ref({additionals:[], statics:[]})
const years = ref({additionals:[], statics:[]})




// Función para crear el gráfico vacío



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
                        data: current.value.statics,
                        backgroundColor: "#5eace5", 
                        borderRadius: 6,
                    },
                    {
                        label: "Costos Variables",
                        data: current.value.additionals,
                        backgroundColor: "#f79b34", 
                        borderRadius: 6,
                    },
                ],
            },
            options: {
                indexAxis: "y",
                responsive: true,
                maintainAspectRatio: false,
                scales: { 
                    x: { beginAtZero: true,ticks: { font: { size: 14 } } },
                    y: { ticks: { font: { size: 14 } } }
                },
                plugins: { 
                    legend: { 
                        display: true, 
                        labels: { font: { size: 16 } } 
                    },
                    title: {
                        display: true,
                        text: "Mes Actual",
                        font: { size: 18 }
                    }
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
                        data: previous.value.statics,
                        backgroundColor: "#5eace5", 
                        borderRadius: 6,
                    },
                    {
                        label: "Costos Variables",
                        data: previous.value.additionals,
                        backgroundColor: "#f79b34", 
                        borderRadius: 6,
                    },
                ],
            },
            options: {
                indexAxis: "y",
                responsive: true,
                maintainAspectRatio: false,
                scales: { 
                    x: { beginAtZero: true,ticks: { font: { size: 14 } } },
                    y: { ticks: { font: { size: 14 } } }
                },
                plugins: { 
                    legend: { 
                        display: true, 
                        labels: { font: { size: 16 } } 
                    },
                    title: {
                        display: true,
                        text: "Mes Anterior",
                        font: { size: 18 }
                    }
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
                        data: years.value.statics,
                        backgroundColor: "#5eace5", 
                        borderRadius: 6,
                    },
                    {
                        label: "Costos Variables",
                        data: years.value.additionals,
                        backgroundColor: "#f79b34", 
                        borderRadius: 6,
                    },
                ],
            },
            options: {
                indexAxis: "y",
                responsive: true,
                maintainAspectRatio: false,
                scales: { 
                    x: { beginAtZero: true,ticks: { font: { size: 14 } } },
                    y: { ticks: { font: { size: 14 } } }
                },
                plugins: { 
                    legend: { 
                        display: true, 
                        labels: { font: { size: 16 } } 
                    },
                    title: {
                        display: true,
                        text: "Anual",
                        font: { size: 18 }
                    }
                },
            },
        });
    }
};

// Función para obtener los datos y actualizar el gráfico
async function getZonesExpensesData() {
    const res = await axios.get(route("projectmanagement.zoneexpenses.chart", {project_id}));

    zones.value = res.data.zones; 
    current.value = res.data.current;
    previous.value = res.data.previous;
    years.value = res.data.years;


    if (chartInstance1) {
        chartInstance1.data.labels = zones.value;
        chartInstance1.data.datasets[0].data = current.value.statics;
        chartInstance1.data.datasets[1].data = current.value.additionals;
        chartInstance1.update(); 
    }
    if (chartInstance2) {
        chartInstance2.data.labels = zones.value;
        chartInstance2.data.datasets[0].data = previous.value.statics;
        chartInstance2.data.datasets[1].data = previous.value.additionals;
        chartInstance2.update(); 
    }
    if (chartInstance3) {
        chartInstance3.data.labels = zones.value;
        chartInstance3.data.datasets[0].data = years.value.statics;
        chartInstance3.data.datasets[1].data = years.value.additionals;
        chartInstance3.update(); 
    }
}

onMounted(() => {
    createChart1();
    createChart2();
    createChart3();
    getZonesExpensesData(); // Obtiene los datos y los actualiza
});
</script>

<template>
    <div class="w-full h-96">
        <canvas ref="chartRef"></canvas>
    </div>
</template>
