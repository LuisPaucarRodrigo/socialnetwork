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
            </dl>
            <div class="md:w-1/2 flex items-start justify-center md:justify-start md:ml-4">
                <img class="max-w-1/2 md:max-w-full h-full object-contain self-start md:self-center"
                    :src="details.purchase_image" alt="Imagen de Cotización">
            </div>
        </div>
        <button @click="sendReply('Aceptado')" type="button"
            class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
            Aceptar
        </button>
        <button @click="check" type="button"
            class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
            Rechazar
        </button>
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

const props = defineProps({
    details: Object
});

const confirmOrden = ref(false);
const comentOrden = ref(null);

const form = useForm({
    response: '',
    state: ''
});

const check = () => {
    confirmOrden.value = true;
    nextTick(() => comentOrden.value.focus());
};

const sendReply = (state) => {
    form.state = state
    form.put(route('managementexpense.reviewed',props.details.id,form), {
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
</script>