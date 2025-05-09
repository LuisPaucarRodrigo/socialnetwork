<template>
    <div>
        <form @submit.prevent="submit">
            <div class="md:flex gap-5">
                <div class="md:w-1/2">
                    <h1>Dias de Jornada</h1>
                    <hr>
                    <dl class="flex justify-between lg:pr-4 sm:mb-0 px-4 py-3 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Laborados:</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">31</dd>
                    </dl>
                    <dl class="flex justify-between lg:pr-4 sm:mb-0 px-4 py-3 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Subsidiados:</dt>
                        <div class="flex gap-3">
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">0</dd>
                            <button @click="openModalSubsidized">
                                <PencilSquareIcon class="w-6 h-6 text-yellow-400" />
                            </button>
                        </div>
                    </dl>
                    <dl class="flex justify-between lg:pr-4 sm:mb-0 px-4 py-3 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">No laborados y no Subsidiados:</dt>
                        <div class="flex gap-3">
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">0</dd>
                            <button @click="openModalNotSubsidized()">
                                <PencilSquareIcon class="w-6 h-6 text-yellow-400" />
                            </button>
                        </div>
                    </dl>
                    <dl class="flex justify-between lg:pr-4 sm:mb-0 px-4 py-6 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Total:</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">31</dd>
                    </dl>
                </div>
                <div class="md:w-1/2">
                    <h1>Horas Laboradas</h1>
                    <hr>
                    <dl class="flex justify-between lg:pr-4 sm:mb-0 px-4 py-3 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Ordinarias(HHH:MM)</dt>
                        <div class="flex gap-3">
                            <input class="w-14 p-1 border border-gray-300 rounded" type="number"
                                v-model.number="form.regularHours" />
                            :
                            <input class="w-14 p-1 border border-gray-300 rounded" type="number"
                                v-model.number="form.regularMinutes" />
                        </div>
                    </dl>
                    <dl class="flex justify-between lg:pr-4 sm:mb-0 px-4 py-3 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">SobreTiempo(HHH:MM)</dt>
                        <div class="flex gap-3">
                            <input class="w-14 p-1 border border-gray-300 rounded" type="number"
                                v-model.number="form.overtimeHours" />
                            :
                            <input class="w-14 p-1 border border-gray-300 rounded" type="number"
                                v-model.number="form.overtimeMinutes" />
                        </div>
                    </dl>
                    <dl class="flex justify-between lg:pr-4 sm:mb-0 px-4 py-6 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Total:</dt>
                        <div class="flex gap-3">
                            <p class="w-14 p-1 border border-gray-300 rounded">{{ totalHours.hours }}</p>
                            :
                            <p class="w-14 p-1 border border-gray-300 rounded">{{ totalHours.minutes }}</p>
                        </div>
                    </dl>
                </div>
            </div>
            <div class="mt-6 flex gap-3 justify-end">
                <PrimaryButton type="submit">
                    Guardar
                </PrimaryButton>
            </div>
        </form>
    </div>
    <Subsidized :form="form" ref="subsidized" />
    <NotSubsidized :form="form" ref="notSubsidized" />
</template>
<script setup>
import { PencilSquareIcon } from '@heroicons/vue/24/outline';
import NotSubsidized from './NotSubsidized.vue';
import { ref, watch } from 'vue';
import Subsidized from './Subsidized.vue';
import { useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const { objectData } = defineProps({
    objectData: Object
});

const subsidized = ref(null)
const notSubsidized = ref(null)
const totalHours = ref({
    hours: 0,
    minutes: 0,
});
const form = useForm({
    regularHours: 0,
    regularMinutes: 0,
    overtimeHours: 0,
    overtimeMinutes: 0,
    subsidized: [],
    notSubsidized: []
})



watch(() => [
    form.regularHours,
    form.regularMinutes,
    form.overtimeHours,
    form.overtimeMinutes,
], totalSum)

function totalSum() {
    let totalMinutes = form.regularMinutes + form.overtimeMinutes;
    let totalHoursSum = form.regularHours + form.overtimeHours;

    if (totalMinutes >= 60) {
        const extraHours = Math.floor(totalMinutes / 60);
        totalMinutes = totalMinutes % 60;
        totalHoursSum += extraHours;
    }

    totalHours.value.hours = totalHoursSum;
    totalHours.value.minutes = totalMinutes;
}

function openModalSubsidized() {
    subsidized.value.toogleModal()
}

function openModalNotSubsidized() {
    notSubsidized.value.toogleModal()
}



</script>