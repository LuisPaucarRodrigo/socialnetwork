<template>
    <Head title="Inventario" />
    <AuthenticatedLayout>
        <template #header>
            Control de Inventario
        </template>
        <div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <Link :href="route('inventory.NetworkEquipment.index')">
                <button class="bg-white p-4 rounded-md shadow-md text-left h-full w-full">
                    <div>
                        <h3 class="text-lg font-semibold">Equipos de Red</h3>
                        <div class="mb-2">
                            <GlobeAltIcon class="h-6 w-6 text-blue-500" />
                        </div>
                    </div>
                </button>
                </Link>
                <Link :href="route('inventory.MobileDevices.index')">
                <button class="bg-white p-4 rounded-md shadow-md text-left h-full w-full">
                    <div>
                        <h3 class="text-lg font-semibold">Dispositivos Moviles</h3>
                        <div class="mb-2">
                            <DevicePhoneMobileIcon class="h-6 w-6 text-blue-500" />
                        </div>
                    </div>
                </button>
                </Link>
                <Link :href="route('inventory.ComponentsAndMaterials.index')">
                <button class="bg-white p-4 rounded-md shadow-md text-left h-full w-full">
                    <div>
                        <h3 class="text-lg font-semibold">Componentes y Materiales</h3>
                        <div class="mb-2">
                            <WrenchScrewdriverIcon class="h-6 w-6 text-blue-500" />
                        </div>
                    </div>
                </button>
                </Link>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { GlobeAltIcon, DevicePhoneMobileIcon, WrenchScrewdriverIcon } from '@heroicons/vue/24/solid'

</script>