<template>

    <Head title="Gestion de Empleados" />
    <AuthenticatedLayout :redirectRoute="'retrievalEntry.index'">
        <template #header>
            Ingresos
        </template>

        <div class="min-w-full rounded-lg shadow">
            <div class="mt-6 sm:flex sm:gap-4 sm:justify-end">
                <div class="flex items-center justify-between gap-x-6 w-full">
                    <button @click="boolean" type="button"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                        {{ boolean == false ? "Aprobados" : "Sin Aprobar" }}
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Proyecto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Producto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cantidad
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="retrieval in retrievalEntry.data" :key="retrieval.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ retrieval.project_entry.project.preproject.code }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ retrieval.lastname }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ retrieval.quantity }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <button type="button" @click="approve_retrieval(retrieval.id)"
                                    class="text-blue-900 whitespace-no-wrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="retrievalEntry.links" />
            </div>
        </div>

        <ConfirmAcceptModal :confirmingaccept="showModalApprove" itemtype="recuperacion de producto" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmAcceptModal from '@/Components/ConfirmAcceptModal.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    retrievalEntry: Object,
    boolean: Boolean
})

const showModalApprove = ref(false);

function approve_retrieval(retrieval) {
    router.get(route('retrievalEntry.approbe', { retrieval: retrieval }), {
        onSuccess: () => {
            showModalApprove.value = true
            setTimeout(() => {
                showModalApprove.value = false;
                router.visit(route('retrievalEntry.index'))
            }, 2000);
        },
    })
}

function boolean() {
    if (props.boolean == true) {
        router.get(route('retrievalEntry.index'))
    } else {
        router.get(route('retrievalEntry.index', { retrieval: props.boolean }))
    }
}

</script>
