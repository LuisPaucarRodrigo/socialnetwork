<template>
    <div :class="colSpan">
        <InputLabel :for="id" class="font-medium leading-6 text-gray-900">
            {{ label }}
        </InputLabel>
        <div class="mt-2">
            <select id="unit" v-model="material_item.unit" required
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                <option disabled value="">Seleccionar Unidad</option>
                <option>UNIDAD</option>
                <option>METROS</option>
                <option>CAJA</option>
                <option>GLB</option>
                <option>METROS 2</option>
                <option>METROS 3</option>
                <option>PIEZA</option>
                <option>LATA</option>
                <option>PAQUETE</option>
                <option>ROLLO</option>
            </select>
            <InputError :message="errorMessage" />
        </div>
    </div>
</template>

<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    id: String,
    label: String,
    type: {
        type: String,
        default: "text"
    },
    errorMessage: String,
    colSpan: {
        type: String,
        default: "sm:col-span-2"
    },
    autocomplete: {
        type: String,
        default: "off"
    }
});
const modelValue = defineModel('model_value');

</script>
