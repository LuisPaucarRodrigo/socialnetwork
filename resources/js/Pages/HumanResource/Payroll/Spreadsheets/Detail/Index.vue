<template>
    <Head title="Informacion Personal" />
    <AuthenticatedLayout
        :redirectRoute="{
            route: 'spreadsheets.index',
            params: { payroll_id: payroll_detail.payroll_id },
        }"
    >
        <Toaster richColors />
        <div>
            <div
                class="flex flex-1 items-center justify-center sm:items-stretch"
            >
                <div class="sm:ml-6 sm:block mb-6">
                    <div class="flex space-x-4">
                        <button
                            @click="changeComponent('WorkerData')"
                            :class="clasesDinamic.WorkerData"
                            class="text-gray-400 rounded-md px-3 py-2 text-sm font-medium"
                        >
                            Datos del Trabajador
                        </button>
                        <button
                            @click="changeComponent('WorkSchedule')"
                            :class="clasesDinamic.WorkSchedule"
                            class="text-gray-400 rounded-md px-3 py-2 text-sm font-medium"
                            :objectData="objectData"
                        >
                            Jornada Laboral
                        </button>
                        <button
                            @click="changeComponent('MonetaryIncome')"
                            :objectData="objectData"
                            :class="clasesDinamic.MonetaryIncome"
                            class="text-gray-400 rounded-md px-3 py-2 text-sm font-medium"
                        >
                            Ingresos
                        </button>
                        <button
                            @click="changeComponent('MonetaryDiscounts')"
                            :objectData="objectData"
                            :class="clasesDinamic.MonetaryDiscounts"
                            class="text-gray-400 rounded-md px-3 py-2 text-sm font-medium"
                        >
                            Descuentos
                        </button>
                        <button
                            @click="changeComponent('TaxesAndContributions')"
                            :objectData="objectData"
                            :class="clasesDinamic.TaxesAndContributions"
                            class="text-gray-400 rounded-md px-3 py-2 text-sm font-medium"
                        >
                            Tributos y Aportes
                        </button>
                    </div>
                </div>
            </div>
            <component :is="componentDinamic" :objectData="objectData" />
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, markRaw } from "vue";
import WorkerData from "./components/WorkerData.vue";
import WorkSchedule from "./components/WorkSchedule.vue";
import MonetaryIncome from "./components/MonetaryIncome.vue";
import MonetaryDiscounts from "./components/MonetaryDiscounts.vue";
import TaxesAndContributions from "./components/TaxesAndContributions.vue";
import { Toaster } from "vue-sonner";

const { payroll_detail, employee_id } = defineProps({
    payroll_detail: Object,
    employee_id: String,
});

const objectData = {
    payroll_detail: payroll_detail,
    employee_id: employee_id,
};

const componentDinamic = ref(markRaw(WorkerData));
const clasesDinamic = ref({
    WorkerData: "text-white bg-gray-900",
    WorkSchedule: "hover:bg-gray-700 hover:text-white",
    MonetaryIncome: "hover:bg-gray-700 hover:text-white",
    MonetaryDiscounts: "hover:bg-gray-700 hover:text-white",
    TaxesAndContributions: "hover:bg-gray-700 hover:text-white",
});

function changeComponent(component) {
    // Reinicia todas las clases
    clasesDinamic.value = {
        WorkerData: "hover:bg-gray-700 hover:text-white",
        WorkSchedule: "hover:bg-gray-700 hover:text-white",
        MonetaryIncome: "hover:bg-gray-700 hover:text-white",
        MonetaryDiscounts: "hover:bg-gray-700 hover:text-white",
        TaxesAndContributions: "hover:bg-gray-700 hover:text-white",
    };

    clasesDinamic.value[component] = "text-white bg-gray-900";

    const componentMap = {
        WorkerData: markRaw(WorkerData),
        WorkSchedule: markRaw(WorkSchedule),
        MonetaryIncome: markRaw(MonetaryIncome),
        MonetaryDiscounts: markRaw(MonetaryDiscounts),
        TaxesAndContributions: markRaw(TaxesAndContributions),
    };

    componentDinamic.value = componentMap[component];
}
</script>
