<template>
    <input
      class="block mt-1 w-full rounded-md form-input focus:border-indigo-600"
      :value="formattedModelValue"
      @input="handleInput"
      ref="input"
    />
  </template>
  
  <script setup>
  import { onMounted, ref, computed, defineProps, defineEmits } from 'vue';
  
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
  