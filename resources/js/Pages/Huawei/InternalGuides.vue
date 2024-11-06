<template>

    <Head title="Guías Internas" />

    <AuthenticatedLayout :redirectRoute="'huawei.titles'">
        <template #header>
            Guías de Huawei
        </template>
        <div class="mt-6 flex items-center justify-between gap-x-6">
            <button @click="add_code" type="button"
                class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                + Agregar
            </button>
        </div>

        <div class="overflow-x-auto rounded-lg shadow mt-2">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th
                            class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Código
                        </th>
                        <th
                            class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Fecha de Emisión
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in internal_guides.data" :key="item.id" class="text-gray-700">
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap text-center">{{ item.code }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap text-center">{{ formattedDate(item.created_at) }}</p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <div class="flex justify-center space-x-3">
                                <button @click="openPreviewDocumentModal(item.id)"
                                    class="flex items-center text-green-600 hover:underline">
                                    <EyeIcon class="h-5 w-5 ml-1" />
                                </button>
                                <button type="button" @click="confirmDeleteCode(item.id)"
                                    class="text-red-600 whitespace-no-wrap">
                                    <TrashIcon class="h-5 w-5 ml-1" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
            <pagination :links="props.internal_guides.links" />
        </div>

        <Modal :show="create_code">
    <div class="p-6">
        <h2 class="text-base font-medium leading-7 text-gray-900">Crear Guía</h2>
        <form @submit.prevent="submit">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <InputLabel for="file" class="font-medium leading-6 text-gray-900">Archivo</InputLabel>
                                <div class="mt-2">
                                    <InputFile type="file" accept="xls,xlsx" v-model="form.file" id="file"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.file" />
                                </div>
                            </div>
                        <div>
                            <InputLabel for="emission_date" class="font-medium leading-6 text-gray-900 mt-3">Fecha de Emisión</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.emission_date" id="emission_date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.emission_date" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="transfer_date" class="font-medium leading-6 text-gray-900 mt-3">Fecha de Traslado</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.transfer_date" id="transfer_date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.transfer_date" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="start_point" class="font-medium leading-6 text-gray-900 mt-3">Punto de Partida</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.start_point" id="start_point"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.start_point" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="end_point" class="font-medium leading-6 text-gray-900 mt-3">Punto de Llegada</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.end_point" id="end_point"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.end_point" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="addresee" class="font-medium leading-6 text-gray-900 mt-3">Destinatario</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.addresee" id="addresee"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.addresee" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="ruc" class="font-medium leading-6 text-gray-900 mt-3">R.U.C.</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.ruc" id="ruc"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.ruc" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="transport_unit" class="font-medium leading-6 text-gray-900 mt-3">Unidad de Transporte</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.transport_unit" id="transport_unit"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.transport_unit" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="brand" class="font-medium leading-6 text-gray-900 mt-3">Marca</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.brand" id="brand"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.brand" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="plate" class="font-medium leading-6 text-gray-900 mt-3">Placa</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.plate" id="plate"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.plate" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="license" class="font-medium leading-6 text-gray-900 mt-3">N° de Licencia</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.license" id="license"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.license" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="transport_company" class="font-medium leading-6 text-gray-900 mt-3">Empresa de Transporte</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.transport_company" id="transport_company"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.transport_company" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="inscrip_const" class="font-medium leading-6 text-gray-900 mt-3">Const. de Inscrip.</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.inscrip_const" id="inscrip_const"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.inscrip_const" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="name" class="font-medium leading-6 text-gray-900 mt-3">Nombre</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.name" id="name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.name" />
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3 justify-end">
                        <SecondaryButton @click="close_add_code"> Cancelar
                        </SecondaryButton>
                        <button type="submit" :class="{ 'opacity-25': form.processing }"
                                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</Modal>


        <ConfirmCreateModal :confirmingcreation="showModal" itemType="Código" />
        <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Código" :deleteFunction="deleteCode"
            @closeModal="closeModalDoc" />
        <ErrorOperationModal :showError="errorModal" :title="'Error'" :message="errorMessage" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import InputFile from '@/Components/InputFile.vue';
import { TrashIcon, EyeIcon } from '@heroicons/vue/24/outline';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { formattedDate } from '@/utils/utils'
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';

const create_code = ref(false);
const showModal = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);
const errorModal = ref(false);
const errorMessage = ref('');

const props = defineProps({
    internal_guides: Object,
    userPermissions:Array
})

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission)
}

const add_code = () => {
    create_code.value = true;
}

const close_add_code = () => {
    form.reset();
    create_code.value = false;
}

const form = useForm({
    emission_date: '',
    transfer_date: '',
    start_point: '',
    end_point: '',
    addresee: '',
    ruc: '',
    transport_unit: '',
    brand:'',
    plate: '',
    license: '',
    transport_company: '',
    inscrip_const: '',
    name: '',
    file: null
});

const submit = async () => {
    await axios.post(route('huawei.internalguides.store'), form, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
    .then(res => {
        if (res.data.file_error){
            errorMessage.value = res.data.file_error;
            errorModal.value = true;
            setTimeout(() => {
                errorModal.value = false;
                errorMessage.value = '';
            }, 3000);
        }else{
            close_add_code();
            form.reset();
            showModal.value = true;
            openPreviewDocumentModal(res.data.guide_id);
            setTimeout(() => {
                showModal.value = false;
                router.visit(route('huawei.internalguides'));
            }, 2000);
        }
    })
    .catch(error => {
        const fieldMessages = {
                emission_date: 'La fecha de emisión es obligatoria',
                transfer_date: 'La fecha de traslado es obligatoria'
            };

        const errorMessages = Object.entries(error.response.data.errors)
            .map(([field, messages]) => {
                return fieldMessages[field] || messages[0];
            })
            .join(' y ');

        errorMessage.value = errorMessages;
        errorModal.value = true;
        setTimeout(() => {
            errorModal.value = false;
            errorMessage.value = '';
        }, 3000);
    });
};

const confirmDeleteCode = (codeId) => {
    docToDelete.value = codeId;
    confirmingDocDeletion.value = true;
};

const closeModalDoc = () => {
    confirmingDocDeletion.value = false;
};

const deleteCode = () => {
    const docId = docToDelete.value;
    if (docId) {
        router.delete(route('huawei.internalguides.delete', { id: docId }), {
            onSuccess: () => {
                closeModalDoc(),
                    router.visit(route('huawei.internalguides'))
            }
        });
    }
};

function openPreviewDocumentModal(documentId) {
  const routeToShow = route('huawei.internalguides.show', { id: documentId });
  window.open(routeToShow, '_blank');
}

</script>
