<template>

    <Head title="Vacaciones y Permisos" />
    <AuthenticatedLayout :redirectRoute="'management.vacation'">
        <template #header>
            Vacaciones y Permisos
        </template>
        <div class="mt-6 border-t border-gray-100">
            <div class="sm:flex lg:justify-between lg:gap-8">
                <div class="sm:w-1/2 lg:pr-4 sm:mb-0">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Empleado</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.employee.name
                            }}
                        </dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Tipo</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.type }}</dd>
                    </div>
                </div>
                <div v-if="type == 'Vacaciones'" class="sm:w-1/2 sm:pl-4">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Fecha Inicio</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ formattedDate(details.start_date) }}
                        </dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Fecha Culminacion</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ formattedDate(details.end_date) }}
                        </dd>
                    </div>
                </div>
                <div v-else class="sm:w-1/2 lg:pr-4 sm:mb-0">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Hora Inicio</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ details.start_permissions }}
                        </dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Hora Final</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ details.end_permissions }}
                        </dd>
                    </div>
                </div>
            </div>
            <div class="sm:flex lg:justify-between lg:gap-8">
                <div class="sm:w-1/2 lg:pr-4 sm:mb-0">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Razon</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.reason }}
                        </dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Estado</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.status }}
                        </dd>
                    </div>
                </div>

                <div v-if="details.status == 'Ausente'" class="sm:w-1/2 sm">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Razon de Falta</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{
        details.coment }}</dd>
                    </div>
                </div>
            </div>
            <div class="sm:flex lg:justify-between lg:gap-8">
                <div v-if="type == 'Permisos' && details.doc_permission" class="sm:w-1/2 lg:pr-4 sm:mb-0">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Documentacion</dt>
                        <button @click="openPreviewDocumentModal(details.id)"
                            class="flex items-center text-green-600 hover:underline">
                            Previsualizar
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6 text-teal-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

        </div>
        <div v-if="vacation" class="mt-6 flex items-center justify-end gap-x-6">
            <DangerButton @click="sendStatus(vacation.id, 'Rechazado')">
                Rechazar
            </DangerButton>
            <PrimaryButton @click="sendStatus(vacation.id, 'Aceptado')">
                Aceptar
            </PrimaryButton>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { formattedDate } from '@/utils/utils';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    details: Object,
    vacation: {
        type: Object,
        required: false
    }
});

const type = props.details ? props.details.type : props.vacation.type;
const documentToShow = ref(null);

function openPreviewDocumentModal(documentId) {
    documentToShow.value = documentId;
    const url = route('management.vacation.information.documents.show', { id: documentId });
    window.open(url, '_blank');
}

function sendStatus(id, status) {
    router.post(route('management.vacation.information.reviewed_decline'), { id: id, status: status }, {
        onSuccess: () => {
            router.visit(route('management.vacation'))
        },
    })
}

</script>