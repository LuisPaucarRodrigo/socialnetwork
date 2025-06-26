<template>
    <div class="flex h-screen bg-gray-100 font-roboto">
        <Navigation />

        <div class="z-10 flex flex-1 flex-col overflow-auto">
            <Header :redirectRoute="redirectRoute" />

            <main class=" flex-1 overflow-y-auto overflow-x-hidden bg-gray-100">
                <div class="container mx-auto px-6 py-4">
                    <div class="flex justify-between">
                        <h3 class="mb-4 text-3xl font-medium text-gray-700">
                            <slot name="header" />
                        </h3>
                        <div class="flex text-center bg-gray-100 rounded-lg gap-x-3 pb-5">
                            <slot name="header-right" />
                        </div>
                    </div>
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
<script setup>
import Header from '@/Layouts/Header.vue';
import Navigation from '@/Layouts/Navigation.vue';
import { usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import { watch } from 'vue';
import { appAuth } from '@/Store/auth';

const props = defineProps({
    redirectRoute: [String, Object],
})


onMounted(() => {
    initFlowbite();
})

const page = usePage();
watch(
    () => page.props.auth,
    () => {
        appAuth.role_id = page.props.auth?.user?.role_id || null;
        appAuth.functionalities = page.props.userFunctionalities || [];
    },
    { immediate: true }
);


</script>