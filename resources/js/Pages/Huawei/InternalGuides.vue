<template>
    <Head title="Guías Internas" />

    <AuthenticatedLayout :redirectRoute="'huawei.titles'">
        <template #header> Guías de Huawei </template>
        <div class="mt-6 flex items-center justify-between gap-x-6">
            <button
                @click="add_code"
                type="button"
                class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500"
            >
                + Agregar
            </button>
        </div>

        <div class="overflow-x-auto rounded-lg shadow mt-2">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500"
                    >
                        <th
                            class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Código
                        </th>
                        <th
                            class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Fecha de Emisión
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                        ></th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="item in internal_guides.data"
                        :key="item.id"
                        class="text-gray-700"
                    >
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            <p
                                class="text-gray-900 whitespace-no-wrap text-center"
                            >
                                {{ item.code }}
                            </p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            <p
                                class="text-gray-900 whitespace-no-wrap text-center"
                            >
                                {{ formattedDate(item.created_at) }}
                            </p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            <div class="flex justify-center space-x-3">
                                <button
                                    @click="openPreviewDocumentModal(item.id)"
                                    class="flex items-center text-green-600 hover:underline"
                                >
                                    <EyeIcon class="h-5 w-5 ml-1" />
                                </button>
                                <button
                                    type="button"
                                    @click="confirmDeleteCode(item.id)"
                                    class="text-red-600 whitespace-no-wrap"
                                >
                                    <TrashIcon class="h-5 w-5 ml-1" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div
            class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between"
        >
            <pagination :links="props.internal_guides.links" />
        </div>

        <Modal :show="create_code">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Crear Guía
                </h2>
                <form @submit.prevent="submit">
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div class="col-span-1 md:col-span-2">
                                    <InputLabel
                                        for="file"
                                        class="font-medium leading-6 text-gray-900"
                                        >Archivo</InputLabel
                                    >
                                    <div class="mt-2">
                                        <InputFile
                                            type="file"
                                            accept="xls,xlsx"
                                            v-model="form.file"
                                            id="file"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        />
                                        <InputError
                                            :message="form.errors.file"
                                        />
                                    </div>
                                </div>

                                <div :class="form.concept === '14' ? 'col-span-1' : 'col-span-1 md:col-span-2'">
                                    <InputLabel for="concept" class="font-medium leading-6 text-gray-900">Concepto</InputLabel>
                                    <div class="mt-2">
                                        <select required id="concept" v-model="form.concept"
                                            class="block w-full rounded-md border-gray-300 py-2 px-3 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm">
                                            <option disabled value="">Seleccione uno</option>
                                            <option value="1">Venta</option>
                                            <option value="2">Venta Sujeta a Confirmar</option>
                                            <option value="3">Compra</option>
                                            <option value="4">Consignación</option>
                                            <option value="5">Devolución</option>
                                            <option value="6">Traslado entre establecimientos de una misma empresa</option>
                                            <option value="7">Para Transformación</option>
                                            <option value="8">Recojo de bienes transformados</option>
                                            <option value="9">Emisor itinerante</option>
                                            <option value="10">Zona Primaria</option>
                                            <option value="11">Importación</option>
                                            <option value="12">Exportación</option>
                                            <option value="13">Venta con entrega a terceros</option>
                                            <option value="14">Otros</option>
                                        </select>
                                    </div>
                                    <InputError :message="form.errors.concept" />
                                </div>

                                <div v-if="form.concept === '14'" class="col-span-1">
                                    <InputLabel for="additional" class="font-medium leading-6 text-gray-900">Especifique</InputLabel>
                                    <div class="mt-2">
                                        <input type="text" v-model="form.additional" id="additional"
                                            class="block w-full rounded-md border-gray-300 py-2 px-3 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm"
                                        />
                                        <InputError :message="form.errors.additional" />
                                    </div>
                                </div>

                            </div>

                            <div class="mt-6 flex gap-3 justify-end">
                                <SecondaryButton @click="close_add_code">
                                    Cancelar
                                </SecondaryButton>
                                <button
                                    type="submit"
                                    :class="{ 'opacity-25': form.processing }"
                                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                                >
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <ConfirmCreateModal :confirmingcreation="showModal" itemType="Código" />
        <ConfirmDeleteModal
            :confirmingDeletion="confirmingDocDeletion"
            itemType="Código"
            :deleteFunction="deleteCode"
            @closeModal="closeModalDoc"
        />
        <ErrorOperationModal
            :showError="errorModal"
            :title="'Error'"
            :message="errorMessage"
        />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import ConfirmCreateModal from "@/Components/ConfirmCreateModal.vue";
import ConfirmDeleteModal from "@/Components/ConfirmDeleteModal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";
import InputFile from "@/Components/InputFile.vue";
// import { TrashIcon, EyeIcon } from "@heroicons/vue/24/outline";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { formattedDate } from "@/utils/utils";
import ErrorOperationModal from "@/Components/ErrorOperationModal.vue";

const create_code = ref(false);
const showModal = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);
const errorModal = ref(false);
const errorMessage = ref("");

const props = defineProps({
    internal_guides: Object
});

const add_code = () => {
    create_code.value = true;
};

const close_add_code = () => {
    form.reset();
    create_code.value = false;
};

const form = useForm({
    concept: "",
    additional: "",
    file: null,
});

const submit = async () => {
    await axios
        .post(route("huawei.internalguides.store"), form, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        .then((res) => {
            if (res.data.file_error) {
                errorMessage.value = res.data.file_error;
                errorModal.value = true;
                setTimeout(() => {
                    errorModal.value = false;
                    errorMessage.value = "";
                }, 3000);
            } else {
                close_add_code();
                form.reset();
                openPreviewDocumentModal(res.data.guide_id);
                setTimeout(() => {
                    router.visit(route("huawei.internalguides"));
                }, 2000);
            }
        })
        .catch((error) => {
            console.error(error)
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
        router.delete(route("huawei.internalguides.delete", { id: docId }), {
            onSuccess: () => {
                closeModalDoc(), router.visit(route("huawei.internalguides"));
            },
        });
    }
};

function openPreviewDocumentModal(documentId) {
    const routeToShow = route("huawei.internalguides.show", { id: documentId });
    window.open(routeToShow, "_blank");
}
</script>
