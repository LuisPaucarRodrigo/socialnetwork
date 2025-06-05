<template>

    <Head title="Proyectos Pint" />
    <AuthenticatedLayout :redirectRoute="'monthproject.index'">
        <template #header> Proyectos Administrativos Mensuales </template>
        <Toaster richColors />
        <div class="min-w-full">
            <div class="mt-6 flex items-center justify-between gap-x-6">
                <div class="hidden sm:flex sm:items-center sm:space-x-4">
                    <button @click="openAddModal" type="button"
                        class="whitespace-nowrap inline-flex items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white">
                        + Agregar
                    </button>
                </div>
                <div class="sm:hidden">
                    <dropdown align='left'>
                        <template #trigger>
                            <button @click="dropdownOpen = !dropdownOpen"
                                class="relative block overflow-hidden rounded-md bg-gray-200 px-2 py-2 text-center text-sm text-white hover:bg-gray-100">
                                <Menuicon />
                            </button>
                        </template>

                        <template #content class="origin-left">
                            <div>
                                <button @click="openAddModal" type="button"
                                    class="dropdown-item block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    Agregar
                                </button>
                            </div>
                        </template>
                    </dropdown>
                </div>
            </div>
            <br />
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <div v-for="item in dataToRender" :key="item.id"
                    class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
                    <div class="flex justify-between gap-2">
                        <h2 class="text-lg uppercase whitespace-nowrap font-semibold mb-3">
                            N° {{ item.name }}
                        </h2>
                        <div class="flex justify-end items-start gap-2">
                            <button @click="openEditModal(item)" class="flex items-start">
                                <QueueListIcon class="h-6 w-6 text-teal-700" />
                            </button>
                            <button @click="confirmDeleteProject(item.id)">
                                <DeleteIcon />
                            </button>
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-700 line-clamp-3 mb-2">
                        {{ item.date }}
                    </h3>
                    <div :class="`text-gray-500 text-sm`">
                        <div class="grid grid-cols-1 gap-y-1">
                            <Link :href="route('projectmanagement.administrativeCosts', { month_project_id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                            Gastos Administrativos
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="month_projects.links" />
            </div>
        </div>
        <Modal :show="showAddEditModal" @close="closeAddEditModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{
                        form.id
                            ? "Editar Proyecto Administrativo"
                            : "Nuevo Proyecto Administrativo"
                    }}
                </h2>
                <form @submit.prevent="submit">
                    <br />
                    <div class="space-y-12">
                        <div class="border-b grid grid-cols-1 gap-6 border-gray-900/10 pb-12">
                            <div>
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    Mes del Proyecto
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="month" v-model="form.date" id="date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.date" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeAddEditModal">
                                Cancelar
                            </SecondaryButton>
                            <button type="submit" :disabled="isFetching" :class="{ 'opacity-25': isFetching }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Proyecto Administrativo"
            :deleteFunction="deleteProject" @closeModal="closeModalDoc" :processing="isFetching" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ConfirmDeleteModal from "@/Components/ConfirmDeleteModal.vue";
import { Toaster } from "vue-sonner";
import { notify, notifyError, notifyWarning } from "@/Components/Notification";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import Pagination from "@/Components/Pagination.vue";
import axios from "axios";
import { ref } from "vue";
import { Head, router, Link, useForm } from "@inertiajs/vue3";
import { QueueListIcon } from "@heroicons/vue/24/outline";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { setAxiosErrors } from "@/utils/utils";
import { DeleteIcon, Menuicon } from "@/Components/Icons/Index";
import Dropdown from '@/Components/Dropdown.vue';

const { month_projects } = defineProps({
    month_projects: Object,
});

const dataToRender = ref(month_projects.data);
const confirmingProjectDeletion = ref(false);
const projectToDelete = ref("");

const add_project = () => {
    router.get(route("projectmanagement.create"));
};

const delete_project = () => {
    const projectId = projectToDelete.value;
    router.delete(
        route("projectmanagement.delete", { project_id: projectId }),
        {
            onSuccess: () => {
                closeModal();
                router.visit(route("projectmanagement.index"));
            },
        }
    );
};

const confirmProjectDeletion = (employeeId) => {
    projectToDelete.value = employeeId;
    confirmingProjectDeletion.value = true;
};

const closeModal = () => {
    confirmingProjectDeletion.value = false;
};

const initialState = { id: "", date: "" };
const form = useForm({
    ...initialState,
});
const isFetching = ref(false);

const showAddEditModal = ref(false);
function openAddModal() {
    showAddEditModal.value = true;
}
function openEditModal(item) {
    form.defaults({ ...item });
    form.reset();
    showAddEditModal.value = true;
}
function closeAddEditModal() {
    form.defaults({ ...initialState });
    form.reset();
    form.clearErrors();
    isFetching.value = false;
    showAddEditModal.value = false;
}
async function submit() {
    try {
        isFetching.value = true;
        const formToSend = form.data();
        console.log(formToSend);
        const res = await axios.post(
            route("monthproject.store", {
                mp_id: formToSend.id,
            }),
            formToSend
        );
        if (formToSend.id) {
            let index = dataToRender.value.findIndex(
                (item) => item.id == formToSend.id
            );
            dataToRender.value[index] = res.data;
        } else {
            dataToRender.value.unshift(res.data);
        }
        closeAddEditModal();
        notify("Proyecto Guardado");
    } catch (e) {
        isFetching.value = false;
        if (e.response?.data?.errors) {
            setAxiosErrors(e.response.data.errors, form);
        } else {
            notifyError("Server Error");
        }
    }
}

const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);
const confirmDeleteProject = (id) => {
    docToDelete.value = id;
    confirmingDocDeletion.value = true;
};
const closeModalDoc = () => {
    confirmingDocDeletion.value = false;
};
const deleteProject = async () => {
    const docId = docToDelete.value;
    isFetching.value = true;
    try {
        const res = await axios.delete(
            route("monthproject.destroy", {
                mp_id: docId,
            })
        );
        isFetching.value = false;
        if (res?.data?.msg === "success") {
            closeModalDoc();
            notify("Proyecto Eliminado");
            let index = dataToRender.value.findIndex(
                (item) => item.id == docId
            );
            dataToRender.value.splice(index, 1);
        }
    } catch (e) {
        isFetching.value = false;
    }
};
</script>
