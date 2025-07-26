<template>
    <div :class="colSpan">
        <InputLabel :for="id" class="font-medium leading-6 text-gray-900">
            {{ label }}
        </InputLabel>
        <div class="mt-2">
            <TextInput :type="type" v-model="modelValue" :id="id" :autocomplete="autocomplete" v-bind="$attrs" />
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
        default: "sm:col-span-6"
    },
    autocomplete: {
        type: String,
        default: "off"
    }
});
const modelValue = defineModel('model_value');

</script>
