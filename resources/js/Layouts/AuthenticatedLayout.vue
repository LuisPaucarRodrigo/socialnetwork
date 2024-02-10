<template>
    <div class="flex h-screen bg-gray-100 font-roboto">
        <Navigation />

        <div class="flex flex-1 flex-col overflow-auto">
            <Header />

            <main class="flex-1 overflow-y-auto overflow-x-auto bg-gray-100">
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
// import { onMounted } from 'vue'; 

// onMounted(() => {
//     const timestamp = new Date().getTime(); // Obtenemos el tiempo actual en milisegundos
//     const url = new URL(window.location.href); // Obtenemos la URL actual del navegador
//     url.searchParams.set('timestamp', timestamp); // Añadimos un parámetro llamado 'timestamp' con el valor de la marca de tiempo
//     window.history.replaceState({}, '', url); // Actualizamos la URL en el historial del navegador
// });
</script>