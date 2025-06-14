<template>

    <Head title="Proyectos" />
    <AuthenticatedLayout :redirectRoute="'payroll.index'">
        <template #header>
            Nomina
        </template>
        <Toaster richColors />
        <div class="min-w-full">
            <div class="mt-6 flex items-center justify-start gap-x-6">
                <button @click="createOrEditModal" type="button"
                    class="whitespace-nowrap inline-flex items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white">
                    + Agregar
                </button>
            </div>
            <br>
            <PayrollTable :payrolls="payrolls" />
        </div>

        <PayrollForm :payrolls="payrolls" ref="payrollForm" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import PayrollTable from './components/PayrollTable.vue';
import PayrollForm from './components/PayrollForm.vue';
import { Toaster } from 'vue-sonner';

const { payroll } = defineProps({
    payroll: Object
})


const payrolls = ref(payroll);
const payrollForm = ref(null)

const createOrEditModal = () => {
    payrollForm.value.createOrEditModal()
};

</script>
