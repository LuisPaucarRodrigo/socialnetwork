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
        optionName: 'Control de Empleados',
        route: route('controlEmployees.index'),
    },
    {
        optionName: 'Documentacion Fija',
        route: route('controlEmployees.fixed.documentation.index'),
    },
    {
        optionName: 'Documentacion de Ingreso',
        route: route('controlEmployees.entry.document.index'),
    },
    {
        optionName: 'Control de Emision de Documentacion',
        route: route('controlEmployees.issuance.documentation.index'),
    }
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
        console.error('ruta no definida; define ps, no seas');
    }
};

watch(() => props.currentSelect, (newVal) => {
    selectedOption.value = newVal;
});
</script>