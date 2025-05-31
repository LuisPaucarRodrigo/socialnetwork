<template>
    <div>
        <h1>DATOS DE SEGURIDAD SOCIAL</h1>
        <h3>Periodo Laboral</h3>
        <div>
            <div class="sm:flex lg:pr-4 sm:mb-0">
                <dl class="sm:w-1/2 px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Fecha de Inicio:</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ worker.contract.hire_date }}</dd>
                </dl>
                <dl class="sm:w-1/2 px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Fecha de Fin:</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ worker.contract.fired_date }}</dd>
                </dl>
            </div>
            <div class="sm:flex lg:pr-4 sm:mb-0">
                <dl class="sm:w-1/2 px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Tipo de Trabajador:</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">Empleado</dd>
                </dl>
                <dl class="sm:w-1/2 px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Situación:</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ worker.contract.fired_date ? "Subsidio" : "Activo" }}</dd>
                </dl>
            </div>
            <div class="sm:flex lg:pr-4 sm:mb-0">
                <dl class="sm:w-1/2 px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Regimen de Salud:</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">ESSALUD REGULAR</dd>
                </dl>
                <dl class="sm:w-1/2 px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Regimen Pensionario:</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ worker.contract.pension_type }}</dd>
                </dl>
            </div>
            <div class="sm:flex lg:pr-4 sm:mb-0">
                <dl class="sm:w-1/2 px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Aporta a EsSalud:</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">Si</dd>
                </dl>
            </div>
        </div>
    </div>
</template>
<script setup>
import { onMounted, ref } from 'vue';

const { objectData } = defineProps({
    objectData: Object
});

const worker = ref({
    id: null,
    name: null,
    contract: {
        employee_id: null,
        hire_date: null,
        fire_date: null,
        pension_type: null
    }
});

onMounted(async () => {
    let url = route('index.worker.data', { employee_id: objectData.employee_id })
    try {
        const response = await axios.get(url);
        worker.value = response.data;
    } catch (error) {
        console.error('Error al cargar datos:', error);
    }
});

</script>