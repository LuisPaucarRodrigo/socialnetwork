<template>
    <div>
        <select v-model="selectedOption" @change="ToRoute"
            class="block pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
            <option v-for="item in SotOptions" :key="item.optionName">{{ item.optionName }}</option>
        </select>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const SotOptions = [
    {
        optionName: 'Proceso',
        route: route('cicsa.index', {type: props.type}),
    },
    {
        optionName: 'Asignación',
        route: route('assignation.index', {type: props.type}),
    },
    {
        optionName: 'Factibilidad PINT y PEXT',
        route: route('feasibilities.index', {type: props.type}),
    },
    {
        optionName: 'Materiales',
        route: route('material.index', {type: props.type}),
    },
    {
        optionName: 'Instalación PINT y PEXT',
        route: route('cicsa.installation.index', {type: props.type}),
    },
    {
        optionName: 'Orden de Compra',
        route: route('purchase.order.index', { type: props.type}),
    },
    {
        optionName: 'Validación de OC',
        route: route('cicsa.purchase_orders.validation', {type:props.type}),
    },
    {
        optionName: 'Orden de Servicio',
        route: route('cicsa.service_orders'),
    },
    {
        optionName: 'Cobranza',
        route: route('cicsa.charge_areas'),
    },
]


const props = defineProps({
    currentSelect: {
        type: String,
        required: true
    },
    type: Number
});

console.log(props.type)

const selectedOption = ref(props.currentSelect);

const ToRoute = () => {
    let currentOption = SotOptions.find(i=>i.optionName === selectedOption.value)
    if (currentOption?.route){
        router.visit(currentOption.route)
    } else {
        console.error('ruta no definida; define ps, no seas');
    }
};

watch(() => props.currentSelect, (newVal) => {
    selectedOption.value = newVal;
});
</script>