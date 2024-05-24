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
        route: route('socialnetwork.sot')
    },
    {
        optionName: 'Área de Programación',
        route: route('socialnetwork.sot.programation')
    },
    {
        optionName: 'Área de Operaciones',
        route: route('socialnetwork.sot.operation')
    },
    {
        optionName: 'Área de Liquidación',
        route: route('socialnetwork.sot.liquidation')
    },
    {
        optionName: 'Área de Cobranza',
        route: '' //route('name.of.the.route')
    },
    {
        optionName: 'Área de Control',
        route: '' //route('name.of.the.route')
    },
]


const props = defineProps({
    currentSelect: {
        type: String,
        required: true
    }
});

const selectedOption = ref(props.currentSelect);

const ToRoute = () => {
    let currentOption = SotOptions.find(i=>i.optionName === selectedOption.value)
    if (currentOption?.route){
        router.visit(currentOption.route)
    } else {
        console.error('ruta no definida, define ps no seas');
    }
};

watch(() => props.currentSelect, (newVal) => {
    selectedOption.value = newVal;
});
</script>