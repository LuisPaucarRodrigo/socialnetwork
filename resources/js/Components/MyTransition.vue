<template>
    <transition
      enter-to-class="transition-all duration-300 ease-in-out"
      enter-from-class="max-h-0 opacity-25"
      leave-from-class="opacity-100 max-h-xl"
      leave-to-class="max-h-0 opacity-0"
    >
      <div v-show="transitiondemonstration">
        <ul class="overflow-hidden p-2 mx-4 mt-2 space-y-2 text-sm font-medium text-white bg-gray-700 bg-opacity-50 rounded-md shadow-inner" aria-label="submenu">
          <li class="px-2 py-1 transition-colors duration-150">
            <slot></slot>
          </li>
        </ul>
      </div>
    </transition>
  </template>
  
  <script>
  export default {
    name: 'MyTransition',
    props: {
      transitiondemonstration: Boolean, // Propiedad que indica si mostrar el componente
    },
  };
  </script>
  