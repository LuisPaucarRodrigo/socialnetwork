<!-- src/components/ChildComponent.vue -->
<template>
    <div>
        <input v-model="internalValue" @input="updateValue" />
    </div>
</template>

<script setup>
import { ref, watch } from "vue";

const { modelValue } = defineProps({
    modelValue: String,
});

const emit = defineEmits(["update:modelValue"]);
const internalValue = ref(modelValue);

const updateValue = () => {
    emit("update:modelValue", internalValue.value);
};
</script>
