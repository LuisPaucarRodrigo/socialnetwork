<template>
    <Head title="Gestion Pension" />
    <AuthenticatedLayout :redirectRoute="'spreadsheets.index'"> 
        <template #header>
            Gestion de Sistema de Pension
        </template>
        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
            <table class="w-full whitespace-no-wrap md:overflow-x-hidden">
                <thead>
                    <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Tipo
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Valor//Pension
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Valor_seg//Salud
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="pension in pensions" :key="pension.id" class="text-gray-700">
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ pension.type }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <TextInput type="number" id="values" :value="pension.values"
                                @change="handleInputChange(pension.id, $event)" />
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <TextInput type="number" id="values" :value="pension.values_seg" :disabled="pension.type === 'ONP'"
                                @change="handleInputChangeSeg(pension.id, $event)" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow mt-6">
            <table class="w-full whitespace-no-wrap md:overflow-x-hidden">
                <thead>
                    <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Tipo
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            SCTR Pension
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            SCTR Salud
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="sctr in sctrs" :key="sctr.id" class="text-gray-700">
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-nowrap">SCTR</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <TextInput type="number" id="values" :value="sctr.sctr_p"
                                @change="handleInputChangeSctr_p($event.target.value)" />
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <TextInput type="number" id="values" :value="sctr.sctr_s"
                                @change="handleInputChangeSctr_s($event.target.value)" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, router } from '@inertiajs/vue3';


const props = defineProps({
    pensions: {
        type: Object,
        required: true,
    },
    sctrs: {
        type: Array,
        required: true
    }
});

const handleInputChange = (pensionId, event) => {
    const newValue = event.target.value;
    router.put(route('pension_system.update', { id: pensionId }), {
        value: newValue
    })
}

const handleInputChangeSeg = (pensionId, event) => {
    const newValue = event.target.value;
    router.put(route('pension_system_seg.update', { id: pensionId }), {
        value: newValue
    })
}

const handleInputChangeSctr_p = (event) => {
    router.post(route('sctr_p.update'), {
        value: event
    })
}

const handleInputChangeSctr_s = (event) => {
    router.post(route('sctr_s.update'), {
        value: event
    })
}


</script>
