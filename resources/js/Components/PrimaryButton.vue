<template>
    <button :type="type" :class="'rounded-md px-4 py-2 text-center text-sm text-white ' + customClass + customColor">
      <slot />
    </button>
  </template>
  
  <script setup>
  const props = defineProps({
    type: {
      type: String,
      default: 'submit',
    },
    customClass: {
      type: String,
      default: '',
    },
    customColor: {
      type: String,
      default: 'bg-indigo-600 hover:bg-indigo-500',
    },
  });
  </script>