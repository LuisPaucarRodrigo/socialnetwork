<template>
    <div class="flex h-screen bg-gray-100 font-roboto">
        <Navigation />

        <div class="z-10 flex flex-1 flex-col overflow-auto">
            <Header :redirectRoute="redirectRoute" />

            <main class=" flex-1 overflow-y-auto overflow-x-hidden bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    <h3 class="mb-4 text-3xl font-medium text-gray-700">
                        <slot name="header" />
                    </h3>

                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
<script setup>
import Header from '@/Layouts/Header.vue';
import Navigation from '@/Layouts/Navigation.vue';

const props = defineProps({
    redirectRoute: [String, Object],
})
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'


onMounted(() => {
    initFlowbite();
})

</script>