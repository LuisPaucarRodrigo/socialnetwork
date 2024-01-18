<template>
    <Head title="Solicitudes" />
    <AuthenticatedLayout>
        <template #header>
            Orden de Gasto
        </template>
        <div class="mt-6 border-t border-gray-100 flex flex-col md:flex-row">
            <dl class="divide-y divide-gray-100 md:w-1/2 md:mr-4">
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
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
                <button @click="openPreviewDocumentModal(details.id)" class="flex items-center text-green-600 hover:underline mb-5">
                    Previsualizar <EyeIcon class="h-4 w-4 ml-1" />
                </button>
            </dl>
        
        </div>


        <div v-if="details.purchasing_requests.state == 'En Progreso'" class="flex gap-2">
            <button @click="sendReply('Aceptado')" type="button"
                class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                Aceptar
            </button>
            <button @click="check" type="button"
                class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                Rechazar
            </button>
        </div>
        <Modal :show="confirmOrden" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Enviar un Comentario
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Agregar una razon en caso se rechaze la solicitud.
                </p>
                <div class="mt-6">
                    <InputLabel for="coment" value="Coment" class="sr-only" />
                    <TextInput id="coment" ref="comentOrden" v-model="form.response" type="text" class="mt-1 block w-3/4"
                        placeholder="Coment" @keyup.enter="sendReply" />
                    <InputError :message="form.errors.response" class="mt-2" />
                </div>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>
                    <PrimaryButton class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                        @click="sendReply('Rechazado')">
                        Validar
                    </PrimaryButton>
                </div>
            </div>
        </Modal>

        <teleport to="body">
            <div v-if="isPreviewDocumentModalOpen" class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="absolute inset-0 bg-gray-800 opacity-75" @click="closePreviewDocumentModal"></div>
                <div class="flex items-center justify-center h-full w-3/4">
                <div class="bg-white p-5 rounded-md relative w-full h-3/5" >
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
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';
import { EyeIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    details: Object
});

const confirmOrden = ref(false);
const comentOrden = ref(null);

const form = useForm({
    response: '',
    state: '',
});

const check = () => {
    confirmOrden.value = true;
    nextTick(() => comentOrden.value.focus());
};

const sendReply = (state) => {
    form.state = state
    form.put(route('managementexpense.reviewed',props.details.purchasing_requests.id,form), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => comentOrden.value.focus(),
        onFinish: () => form.reset(),
    }); 
};

const closeModal = () => {
    confirmOrden.value = false;
    form.reset();
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