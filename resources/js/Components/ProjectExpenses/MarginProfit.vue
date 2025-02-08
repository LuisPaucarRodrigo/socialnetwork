<script setup>
import { ref, onMounted } from "vue";
import { Chart, ArcElement, Tooltip, Legend } from "chart.js";

Chart.register(ArcElement, Tooltip, Legend);

// Datos del gráfico

const { expensesVariables } = defineProps({
    expensesVariables: { type: Object, required: true },
});

function getChartOptions(value) {
    const totalDognut = 100;
    const data = [value, totalDognut - value];
    const percentage = (value / totalDognut) * 100;
    const centerLabel =
        percentage % 1 === 0 ? `${percentage}%` : `${percentage.toFixed(1)}%`;

    const options = {
        responsive: true,
        maintainAspectRatio: false,
        cutout: "60%",
        rotation: -135,
        circumference: 270,
        plugins: {
            legend: { display: false },
            tooltip: { enabled: false },
        },
    };
    const plugins = [
        {
            id: "centerText",
            beforeDraw(chart) {
                const { width, height, ctx } = chart;
                ctx.restore();
                const fontSize = (height / 100).toFixed(0);
                ctx.font = `${fontSize}em sans-serif`;
                ctx.textBaseline = "middle";
                ctx.textAlign = "center";
                ctx.fillStyle = "#333";
                const text = centerLabel;
                const x = width / 2;
                const y = (5 * height) / 9;
                ctx.fillText(text, x, y);
                ctx.save();
            },
        },
    ];
    return {
        data,
        options,
        plugins,
    };
}


const grossProfit = getChartOptions(
    (expensesVariables.incomeTaxes 
        - expensesVariables.additionalCosts) *100
        /expensesVariables.incomeTaxes)
const operatingProfit = getChartOptions(
    (expensesVariables.incomeTaxes 
        - expensesVariables.additionalCosts 
        - expensesVariables.staticCosts) *100
        /expensesVariables.incomeTaxes)
const netProfit = getChartOptions(
    (expensesVariables.incomeNoTaxes 
        - expensesVariables.additionalCosts 
        - expensesVariables.staticCosts) *100
        /expensesVariables.incomeTaxes)


const chartRef3 = ref(null);
const createChart3 = () => {
    if (chartRef3.value) {
        new Chart(chartRef3.value, {
            type: "doughnut",
            data: {
                datasets: [
                    {
                        data: grossProfit.data,
                        backgroundColor: ["#8a71e7", "#c4bae9"],
                    },
                ],
            },
            options: grossProfit.options,
            plugins: grossProfit.plugins,
        });
    }
};
const chartRef2 = ref(null);
const createChart2 = () => {
    if (chartRef3.value) {
        new Chart(chartRef2.value, {
            type: "doughnut",
            data: {
                datasets: [
                    {
                        data: operatingProfit.data,
                        backgroundColor: ["#7a5ce8", "#c4bae9"],
                    },
                ],
            },
            options: operatingProfit.options,
            plugins: operatingProfit.plugins,
        });
    }
};
const chartRef1 = ref(null);
const createChart1 = () => {
    if (chartRef1.value) {
        new Chart(chartRef1.value, {
            type: "doughnut",
            data: {
                datasets: [
                    {
                        data: netProfit.data,
                        backgroundColor: ["#5027e6", "#c4bae9"],
                    },
                ],
            },
            options: netProfit.options,
            plugins: netProfit.plugins,
        });
    }
};

onMounted(() => {
    createChart3();
    createChart2();
    createChart1();
});
</script>

<template>
    <div
        class="w-full h-auto py-6 shadow-md rounded-lg border border-gray-200 bg-white bg-opacity-30"
    >
        <div class="grid grid-cols-1 lg:grid-cols-3">
            <div class="flex flex-col items-center justify-center">
                <div class="text-center">
                    <h4 class="text-xl font-medium text-gray-700">
                        Beneficio Neto
                    </h4>
                    <span>(Beneficio Operativo - IGV)</span>
                </div>
                <div class="flex items-center justify-center">
                    <canvas ref="chartRef1" class="w-52 h-52"></canvas>
                </div>
            </div>
            <div class="flex flex-col items-center justify-center">
                <div class="text-center">
                    <h4 class="text-xl font-medium text-gray-700">
                        Beneficio Operativo
                    </h4>
                    <span>(Beneficio Bruto - CF)</span>
                </div>
                <div class="flex items-center justify-center">
                    <canvas ref="chartRef2" class="w-52 h-52"></canvas>
                </div>
            </div>
            <div class="flex flex-col items-center justify-center">
                <div class="text-center">
                    <h4 class="text-xl font-medium text-gray-700">
                        Beneficio Bruto
                    </h4>
                    <span>(Ingreso con IGV - CV)</span>
                </div>
                <div class="flex items-center justify-center">
                    <canvas ref="chartRef3" class="w-52 h-52"></canvas>
                </div>
            </div>
        </div>
        <div class="text-sm w-full text-gray-600 text-center p-2">
            <span>** Cálculo realizado en base al INGRESO CON IGV **</span>
        </div>
    </div>

</template>
