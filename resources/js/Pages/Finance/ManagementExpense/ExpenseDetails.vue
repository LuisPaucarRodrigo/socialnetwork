<template>
    <Head title="Solicitudes" />
    <AuthenticatedLayout>
        <template #header>
            Orden de Gasto
        </template>
        <div class="mt-6 border-t border-gray-100">
            <dl class="divide-y divide-gray-100">
                <div v-if="details.purchasing_requests.project" class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Proyecto</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{
                        details.purchasing_requests.project.name }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Solicitud</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{
                        details.purchasing_requests.title }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Descripcion de Solicitud</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{
                        details.purchasing_requests.product_description }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Proveedor</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.provider }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Cotizacion</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.response }}
                    </dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Monto</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.amount }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Fecha Limite</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.quote_deadline }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Archivo Adjunto</dt>
                    <button @click="openPreviewDocumentModal(details.id)"
                        class="flex items-center text-green-600 hover:underline mb-5">
                        Previsualizar
                        <EyeIcon class="h-4 w-4 ml-1" />
                    </button>
                </div>
            </dl>
        </div>
        <div v-if="details.purchasing_requests.state == 'En Progreso'" class="flex gap-2">
            <button @click="sendReply('Aceptado')" type="button"
                class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                Aceptar
            </button>
            <button @click="sendReply('Rechazado')" type="button"
                class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                Rechazar
            </button>
        </div>
        <teleport to="body">
            <div v-if="isPreviewDocumentModalOpen" class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="absolute inset-0 bg-gray-800 opacity-75" @click="closePreviewDocumentModal"></div>
                <div class="flex items-center justify-center h-full w-3/4">
                    <div class="bg-white p-5 rounded-md relative w-full h-3/5">
                        <button @click="closePreviewDocumentModal"
                            class="close-button absolute top-0 right-0 mt-2 mr-2">&#10006;</button>
                        <iframe :src="getDocumentUrl(documentToShow)" class="w-full h-full"></iframe>
                    </div>
                </div>
            </div>
        </teleport>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { EyeIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    details: Object
});

const form = useForm({
    response: '',
    state: '',
    purchase_quote_id: props.details.id
});

const sendReply = (state) => {
    form.state = state
    form.put(route('managementexpense.reviewed', { id: props.details.id }), form, {
        preserveScroll: true,
        onFinish: () => form.reset(),
    });

};

function getDocumentUrl(documentId) {
    return route('purchasesrequest.show', { id: documentId });
}

const documentToShow = ref(null);
const isPreviewDocumentModalOpen = ref(false);

const closePreviewDocumentModal = () => {
    isPreviewDocumentModalOpen.value = false;
};

function openPreviewDocumentModal(documentId) {
    documentToShow.value = documentId;
    isPreviewDocumentModalOpen.value = true;
}
</script>