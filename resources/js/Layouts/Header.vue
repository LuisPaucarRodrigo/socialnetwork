<template>
  <header class="flex items-center justify-between border-b-4 border-indigo-600 bg-white px-6 py-4">
    <div class="flex items-center">
      <button @click="$page.props.showingMobileMenu = !$page.props.showingMobileMenu"
        class="text-gray-500 focus:outline-none">
        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" />
        </svg>
      </button>
      <button v-if="redirectRoute" @click="()=> router.visit(getRoute())"
        class="ml-4 text-gray-500 focus:outline-none">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </button>
      <a v-else href="javascript:window.history.back()"
        class="ml-4 text-gray-500 focus:outline-none">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </a>
    </div>
    <div class="flex items-center">
      <dropdown>
        <template #trigger>
          <button @click="dropdownOpen = !dropdownOpen" class="relative block overflow-hidden">
            {{ $page.props.auth?.user?.name }}
          </button>
        </template>

        <template #content>
          <dropdown-link :href="route('profile.edit')">
            Perfil
          </dropdown-link>
          <dropdown-link class="w-full text-left" :href="route('logout')" method="post" as="button">
            Cerrar sesión
          </dropdown-link>
        </template>
      </dropdown>
    </div>
  </header>
</template>
<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  redirectRoute: [String, Object]
})

const getRoute = () => {
  if (typeof props.redirectRoute === 'string') {
    return route(props.redirectRoute);
  } else {
    return route(props.redirectRoute.route, props.redirectRoute.params);
  }
};
</script>
  