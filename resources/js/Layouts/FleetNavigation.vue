<template v-if="hasPermission('CarManager')|| hasPermission('Car')">
    <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#" @click="showFleetCars = !showFleetCars">
        <svg width="23px" height="23px" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke-width="1.5" :stroke="documentsCarToExpire.length > 0 ? 'red' : 'currentcolor'">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
        </svg>
        <span class="mx-3">Flota de Vehiculos</span>
    </a>
    <MyTransition v-if="hasPermission('CarManager')" :transitiondemonstration="showFleetCars">
        <div class="relative">
            <Link class="w-full" :href="route('fleet.cars.index.approvel')">Aprobaciòn de Cambios</Link>
        </div>
    </MyTransition>
    <MyTransition :transitiondemonstration="showFleetCars">
        <div class="relative">
            <Link class="w-full" :href="route('fleet.cars.index')">UM</Link>
            <button v-if="documentsCarToExpire.length > 0"
                @click="showDocumentsCarToExpireAlarms = !showDocumentsCarToExpireAlarms">
                <span
                    class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                    {{ documentsCarToExpire.length }}
                </span>
            </button>
        </div>
    </MyTransition>
</template>
<script setup>
import MyTransition from '@/Components/MyTransition.vue';
import { Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const { userPermissions } = defineProps({
    userPermissions: Array
})

const showFleetCars = ref(false)
const showDocumentsCarToExpireAlarms = ref(false)
const documentsCarToExpire = ref([])

function hasPermission(permission) {
    return userPermissions.includes(permission)
}

onMounted(() => {
    if (hasPermission('CarManager') || hasPermission('Car')) {
        fetchFleetCarCount();
        setInterval(fetchFleetCarCount, 60000);
    }
});

async function fetchFleetCarCount() {
    try {
        const response = await axios.get(route('fleet.cars.alarms'));
        documentsCarToExpire.value = response.data.documentsCarToExpire;
    } catch (error) {
        console.error('Error al obtener el contador de carros:', error);
    }
}
</script>