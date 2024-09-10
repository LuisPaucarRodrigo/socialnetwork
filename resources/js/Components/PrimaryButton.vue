<template>
    <button :type="type" :class="'rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 ' + customClass">
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
  });
  </script>