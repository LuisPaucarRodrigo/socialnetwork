<template>
    <input
      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
      :value="formattedModelValue"
      @input="handleInput"
      ref="input"
      step="any"
      />
  </template>
  
  <script setup>
  import { onMounted, ref, computed } from 'vue';
  
  const props = defineProps(['modelValue', 'toUppercase']);
  const emits = defineEmits(['update:modelValue']);
  
  const input = ref(null);
  
  onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
      input.value.focus();
    }
  });
  
  const formattedModelValue = computed(() => {
    return props.toUppercase ? String(props.modelValue).toUpperCase() : props.modelValue;
  });
  
  const handleInput = (event) => {
    const formattedValue = props.toUppercase ? String(event.target.value).toUpperCase() : event.target.value;
    emits('update:modelValue', formattedValue);
  };
  </script>
  