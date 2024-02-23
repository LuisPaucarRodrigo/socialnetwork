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
                            Valor
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Valor_seg
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="pension in pensions" :key="pension.id" class="text-gray-700">
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ pension.type }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <TextInput type="text" id="values" :value="pension.values" :disabled="pension.type == 'ONP'"
                                @change="handleInputChange(pension.id, $event)"
                                class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-4 sm:mt-0 border-gray-300 bg-transparent focus:outline-none focus:border-indigo-500" />
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <TextInput type="text" id="values" :value="pension.values_seg" :disabled="pension.type == 'ONP'"
                                @change="handleInputChangeSeg(pension.id, $event)"
                                class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-4 sm:mt-0 border-gray-300 bg-transparent focus:outline-none focus:border-indigo-500" />
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


</script>
