<template>
    <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#" @click="showFleetCars = !showFleetCars">
        <svg width="23px" height="23px" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
        :stroke="documentsCarToExpire.length + checkListCarToExpire.length + changelogAlarms.length > 0 ? 'red' : 'currentcolor'">
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1m1.5.5-1.5-.5M6.75 7.364V3h-3v18m3-13.636 10.5-3.819" />
        </svg>





        <span class="mx-3">A. de Habitaciones</span>
    </a>
    <MyTransition v-if="subModulePermission(submodules.rorenapprov_submodule, userSubModules)"
        :transitiondemonstration="showFleetCars">
        <div class="relative">
            <Link class="w-full" :href="route('room.rental.index.approvel')">Aprobación de Cambios</Link>
        </div>
    </MyTransition>
    <MyTransition v-if="subModulePermission(submodules.roomunit_submodule, userSubModules)"
        :transitiondemonstration="showFleetCars">
        <div class="relative">
            <Link class="w-full" :href="route('room.rental.index')">Habitaciones</Link>
            <div class="absolute top-0 right-0 flex justify-end gap-3">
                <button v-if="checkListCarToExpire.length > 0" @click="() => {
                    showCheckListCarToExpireAlarms = !showCheckListCarToExpireAlarms
                    showDocumentsCarToExpireAlarms = false
                }">
                    <span
                        class="cursor-pointer bg-indigo-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                        {{ checkListCarToExpire.length }}
                    </span>
                </button>
                <button v-if="documentsCarToExpire.length || changelogAlarms.length > 0" @click="() => {
                    showDocumentsCarToExpireAlarms = !showDocumentsCarToExpireAlarms
                    showCheckListCarToExpireAlarms = false
                }">
                    <span
                        class="cursor-pointer bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                        {{ documentsCarToExpire.length + changelogAlarms.length }}
                    </span>
                </button>
            </div>
        </div>
    </MyTransition>
    <template v-if="showDocumentsCarToExpireAlarms">
        <div class="mb-4">
            <MyTransition v-for="item, i in documentsCarToExpire" :key="i" class="ml-4"
                :transitiondemonstration="showDocumentsCarToExpireAlarms">
                <div class="w-full flex items-center">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-red-600 dark:text-red" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                        </svg>
                        <button @click="specificAlarm(item.id)">
                            <span>{{ item.plate }}</span>
                        </button>
                    </div>
                </div>
            </MyTransition>
            <MyTransition v-for="item, i in changelogAlarms" :key="i" class="ml-4"
                :transitiondemonstration="showDocumentsCarToExpireAlarms">
                <div class="w-full flex items-center">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-red-600 dark:text-red" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                        </svg>
                        <button @click="changelogAlarm(item.id)">
                            <span>{{ item.plate + " (RC)" }}</span>
                        </button>
                    </div>
                </div>
            </MyTransition>
        </div>
    </template>
    <template v-if="showCheckListCarToExpireAlarms">
        <div class="mb-4">
            <MyTransition v-for="item, i in checkListCarToExpire" :key="i" class="ml-4"
                :transitiondemonstration="showCheckListCarToExpireAlarms">
                <div class="w-full flex items-center">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-blue-600 dark:text-blue-600" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                        </svg>
                        <Link :href="route('room.rental.show_checklist', { car: item.id })">
                        <span>{{ item.plate }} - {{ item.days }}</span>
                        </Link>
                    </div>
                </div>
            </MyTransition>

        </div>
    </template>
    <Modal :show="showSpecificAlarm" maxWidth="sm" @close="openModalSpesificAlarm">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">Documentos a caducar</h2>
            <div class="my-5 flex space-x-5 justify-between" v-for="(value, key) in caducationsList" :key="key">
                <p>{{ key }} :</p>
                <p>{{ value }}</p>
            </div>
        </div>
    </Modal>
</template>
<script setup>
import Modal from '@/Components/Modal.vue';
import MyTransition from '@/Components/MyTransition.vue';
import { subModulePermission } from '@/utils/roles/roles';
import { Link, usePage } from '@inertiajs/vue3';
import { onMounted, ref, onUnmounted } from 'vue';

const { submodules } = usePage().props
const { userSubModules } = usePage().props.auth

const showFleetCars = ref(false)
const showDocumentsCarToExpireAlarms = ref(false)
const showCheckListCarToExpireAlarms = ref(false)
const documentsCarToExpire = ref([])
const checkListCarToExpire = ref([])
const caducationsList = ref(null)
const showSpecificAlarm = ref(false)
const changelogAlarms = ref([]);

let intervalId;
const fetchAllAlarms = () => {
    return Promise.all([
        fetchFleetCarCount(),
        fetchFleetCarCheckListCount(),
        getChangelogAlarms(),
    ]);
};
onMounted(() => {
    fetchAllAlarms();
    intervalId = setInterval(fetchAllAlarms, 60000);
});
onUnmounted(() => {
    clearInterval(intervalId);
});


async function fetchFleetCarCount() {
    try {
        const response = await axios.get(route('room.rental.alarms'));

        documentsCarToExpire.value = response.data.documentsCarToExpire;
    } catch (error) {
        console.error('Error al obtener el contador de carros:', error);
    }
}

async function fetchFleetCarCheckListCount() {
    try {
        const response = await axios.get(route('room.rental.checklist.alarms'));
        checkListCarToExpire.value = response.data;
    } catch (error) {
        console.error('Error al obtener el contador de carros:', error);
    }
}

async function specificAlarm(car_id) {
    try {
        const response = await axios.get(route('room.rental.specific.alarms', { car_id: car_id }));
        caducationsList.value = response.data;
        openModalSpesificAlarm()
    } catch (error) {
        console.error('Error al obtener los documentos:', error);
    }
}

function openModalSpesificAlarm() {
    showSpecificAlarm.value = !showSpecificAlarm.value
}

function changelogAlarm(id) {
    window.location.href = route('room.rental.index', { id: id });
}

async function getChangelogAlarms() {
    try {
        const response = await axios.get(route('room.rental.alarms.changelogs'));
        changelogAlarms.value = response.data.carsToExpire;
    } catch (error) {
        console.error('Error al obtener el contador de carros:', error);
    }
}

</script>

<!-- room.rental.specific.alarms -->