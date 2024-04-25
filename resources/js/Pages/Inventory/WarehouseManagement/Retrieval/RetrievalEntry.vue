<template>

    <Head title="Gestion de Empleados" />
    <AuthenticatedLayout :redirectRoute="'warehouses.warehouses'">
        <template #header>
            Aprobacion Recupero
        </template>

        <div class="min-w-full rounded-lg shadow">
            <div class="mt-6 sm:flex sm:gap-4 sm:justify-end">
                <div class="flex items-center justify-end gap-x-6 w-full">
                    <PrimaryButton @click="boolean" type="button">
                        {{ entry_boolean == false ? "Sin Aprobar" : "Aprobados" }}
                    </PrimaryButton>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Codigo de Producto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Producto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cantidad
                            </th>
                            <th v-if="!entry_boolean"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="retrieval in retrievalEntry.data" :key="retrieval.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ retrieval.retrieval_entry.purchase_product.code }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ retrieval.retrieval_entry.purchase_product.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ retrieval.quantity }}</p>
                            </td>
                            <td v-if="!entry_boolean" class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
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
                <Pagination :links="retrievalEntry.links" />
            </div>
        </div>

        <ConfirmAcceptModal :confirmingaccept="showModalApprove" :itemType="'recuperacion de producto'" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmAcceptModal from '@/Components/ConfirmAcceptModal.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    retrievalEntry: Object,
    boolean: Boolean
})

const entry_boolean = ref(props.boolean);
const showModalApprove = ref(false);

function approve_retrieval(retrieval) {
    router.post(route('retrievalentry.approbe', { retrieval: retrieval }), {
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
        entry_boolean.value = false
        router.get(route('inventory.retrieval_entry.index'))
    } else {
        entry_boolean.value = true
        router.get(route('inventory.retrieval_entry.index', { boolean: entry_boolean.value }))
    }
}

</script>
