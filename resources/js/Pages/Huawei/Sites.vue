<template>
    <div>

        <Head title="Gestion de Sites" />
        <AuthenticatedLayout>
            <template #header> Gestión de Sites Huawei </template>
            <div class="overflow-hidden rounded-lg shadow">
                <div class="flex gap-4 justify-between rounded-lg">
                    <div class="flex flex-col sm:flex-row gap-4 justify-between w-auto">
                        <button @click="openCreateModal"
                            class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 whitespace-nowrap">
                            Crear Nuevo Site
                        </button>
                    </div>

                    <div class="flex items-center ml-auto sm:ml-0">
                        <!-- ml-auto para alinear a la derecha en pantallas grandes y sm:ml-0 para mantener en la izquierda en pantallas pequeñas -->
                        <form @submit.prevent="search" class="flex items-center w-full sm:w-auto">
                            <TextInput type="text" placeholder="Buscar..." v-model="searchForm.searchTerm"
                                class="mr-2" />
                            <button type="submit" :class="{ 'opacity-25': searchForm.processing }"
                                class="ml-2 rounded-md bg-indigo-600 px-2 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                <svg width="30px" height="21px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                                        stroke="white" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="overflow-x-auto mt-2">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                    Código
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                    Nombre
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                    Operador
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                    Dirección
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                    Latitud
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                    Longitud
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="site in props.search
                                ? props.sites
                                : sites.data" :key="site.id">
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ site.code }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ site.name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ site.prefix }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ site.address }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ site.latitude }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ site.longitude }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex justify-center gap-2 items-center">
                                        <button @click="openUpdateSite(site)">
                                            <EditIcon />
                                        </button>
                                        <button @click="confirmingDeleteSite(site.id)">
                                            <DeleteIcon />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="!props.search"
                    class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                    <pagination :links="props.sites.links" />
                </div>
            </div>

            <Modal :show="isCreateModalOpen || isUpdateModalOpen">
                <div class="p-6">
                    <h2 class="text-base font-medium leading-7 text-gray-900">
                        {{
                            isCreateModalOpen
                                ? "Agregar Site"
                                : "Actualizar Site"
                        }}
                    </h2>
                    <form @submit.prevent="
                        isCreateModalOpen ? submit(false) : submit(true)
                        ">
                        <div class="space-y-12">
                            <div class="border-b border-gray-900/10 pb-12">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <InputLabel for="code">Código</InputLabel>
                                        <div class="mt-2">
                                            <TextInput type="text" v-model="form.code" id="code" autocomplete="off" />
                                            <InputError :message="form.errors.code" />
                                        </div>
                                    </div>

                                    <div>
                                        <InputLabel for="name">Nombre</InputLabel>
                                        <div class="mt-2">
                                            <TextInput type="text" v-model="form.name" id="name" autocomplete="off" />
                                            <InputError :message="form.errors.name" />
                                        </div>
                                    </div>

                                    <div>
                                        <InputLabel for="prefix">Operador</InputLabel>
                                        <div class="mt-2">
                                            <select id="prefix" v-model="form.prefix"
                                                class="mt-1 block w-full rounded-md border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                <option value="" disabled>
                                                    Seleccione un operador
                                                </option>
                                                <option>Claro</option>
                                                <option>Entel</option>
                                                <option>Telefonica</option>
                                            </select>
                                            <InputError :message="form.errors.prefix" />
                                        </div>
                                    </div>

                                    <div>
                                        <InputLabel for="address">Dirección</InputLabel>
                                        <div class="mt-2">
                                            <TextInput type="text" v-model="form.address" id="address"
                                                autocomplete="off" />
                                            <InputError :message="form.errors.address" />
                                        </div>
                                    </div>

                                    <div>
                                        <InputLabel for="latitude">Latitud</InputLabel>
                                        <div class="mt-2">
                                            <TextInput type="text" v-model="form.latitude" id="latitude"
                                                autocomplete="off" />
                                            <InputError :message="form.errors.latitude" />
                                        </div>
                                    </div>

                                    <div>
                                        <InputLabel for="longitude">Longitud</InputLabel>
                                        <div class="mt-2">
                                            <TextInput type="text" v-model="form.longitude" id="longitude"
                                                autocomplete="off" />
                                            <InputError :message="form.errors.longitude" />
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 flex items-center justify-end gap-x-6">
                                    <SecondaryButton @click="
                                        isCreateModalOpen
                                            ? closeCreateModal()
                                            : closeUpdateModal()
                                        ">
                                        Cancelar
                                    </SecondaryButton>
                                    <PrimaryButton type="submit" :class="{
                                        'opacity-25': form.processing,
                                    }">{{
                                        isCreateModalOpen
                                            ? "Guardar"
                                            : "Actualizar"
                                    }}</PrimaryButton>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </Modal>

            <Modal :show="showConfirmNameModal" :maxWidth="'sm'">
                <div class="p-6">
                    <h2 class="text-base font-medium leading-7 text-gray-900 text-center">
                        ¿Está seguro de crear o actualizar el site?
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 text-wrap">
                        Actualmente, hay un site que tiene un nombre similar al
                        que esta intentando registrar:
                        <span class="font-black">{{ foundName }}</span>.
                    </p>
                    <div class="space-y-12">
                        <div class="border-gray-900/10">
                            <div class="mt-6 flex items-center justify-end gap-x-3">
                                <SecondaryButton @click="noAccept">
                                    No
                                </SecondaryButton>
                                <PrimaryButton @click="accept"
                                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                    Si</PrimaryButton>
                            </div>
                        </div>
                    </div>
                </div>
            </Modal>

            <ConfirmCreateModal :confirmingcreation="showModal" itemType="Site" />
            <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Site" />
            <ConfirmDeleteModal :confirmingDeletion="confirmDeleteSite" itemType="Site" :deleteFunction="deleteSite"
                @closeModal="closeModalDoc" />
        </AuthenticatedLayout>
    </div>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ConfirmCreateModal from "@/Components/ConfirmCreateModal.vue";
import ConfirmUpdateModal from "@/Components/ConfirmUpdateModal.vue";
import ConfirmDeleteModal from "@/Components/ConfirmDeleteModal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import { ref } from "vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Pagination from "@/Components/Pagination.vue";
import axios from "axios";
import EditIcon from "@/Components/Icons/EditIcon.vue";
import DeleteIcon from "@/Components/Icons/DeleteIcon.vue";

const showModal = ref(false);
const showModalEdit = ref(false);

const props = defineProps({
    sites: Object,
    search: String,
});

const form = useForm({
    id: "",
    name: "",
    address: "",
    code: "",
    prefix: "",
    latitude: "",
    longitude: "",
});

const isCreateModalOpen = ref(false);
const isUpdateModalOpen = ref(false);
const editingSite = ref(null);
const showConfirmNameModal = ref(false);
const foundName = ref(null);
const docToDelete = ref(null);
const confirmDeleteSite = ref(false);

const confirmingDeleteSite = (documentId) => {
    docToDelete.value = documentId;
    confirmDeleteSite.value = true;
};

const closeModalDoc = () => {
    confirmDeleteSite.value = false;
};

const deleteSite = () => {
    const docId = docToDelete.value;
    if (docId) {
        router.delete(route("huawei.sites.delete", { site: docId }), {
            onSuccess: () => closeModalDoc(),
        });
    }
};

const openCreateModal = () => {
    isCreateModalOpen.value = true;
};

const openUpdateSite = (item) => {
    editingSite.value = JSON.parse(JSON.stringify(item));
    form.id = editingSite.value.id;
    form.name = editingSite.value.name;
    form.address = editingSite.value.address;
    isUpdateModalOpen.value = true;
};

const closeCreateModal = () => {
    form.reset();
    isCreateModalOpen.value = false;
};

const closeUpdateModal = () => {
    form.reset();
    isUpdateModalOpen.value = false;
};

const noAccept = () => {
    foundName.value = null;
    form.reset();
    showConfirmNameModal.value = false;
    isCreateModalOpen.value = false;
    isUpdateModalOpen.value = false;
};

const accept = () => {
    if (isCreateModalOpen.value) {
        form.post(route("huawei.sites.post"), {
            onSuccess: () => {
                closeCreateModal();
                form.reset();
                showConfirmNameModal.value = false;
                closeCreateModal();
                showModal.value = true;
                setTimeout(() => {
                    showModal.value = false;
                }, 2000);
            },
        });
    } else {
        form.put(route("huawei.sites.put", { site: form.id }), {
            onSuccess: () => {
                closeUpdateModal();
                form.reset();
                showConfirmNameModal.value = false;
                closeUpdateModal();
                showModalEdit.value = true;
                setTimeout(() => {
                    showModalEdit.value = false;
                }, 2000);
            },
        });
    }
};

const submit = (update) => {
    if (update) {
        axios
            .post(route("huawei.sites.verify", { update: form.id }), form)
            .then((res) => {
                if (res.data.message == "found") {
                    foundName.value = res.data.name;
                    showConfirmNameModal.value = true;
                } else {
                    form.put(route("huawei.sites.put", { site: form.id }), {
                        onSuccess: () => {
                            closeUpdateModal();
                            form.reset();
                            showModalEdit.value = true;
                            setTimeout(() => {
                                showModalEdit.value = false;
                            }, 2000);
                        },
                    });
                }
            });
    } else {
        axios.post(route("huawei.sites.verify"), form).then((res) => {
            if (res.data.message == "found") {
                foundName.value = res.data.name;
                showConfirmNameModal.value = true;
            } else {
                form.post(route("huawei.sites.post"), {
                    onSuccess: () => {
                        closeCreateModal();
                        form.reset();
                        showModal.value = true;
                        setTimeout(() => {
                            showModal.value = false;
                        }, 2000);
                    },
                });
            }
        });
    }
};

const searchForm = useForm({
    searchTerm: props.search ? props.search : "",
});

const search = () => {
    if (searchForm.searchTerm == "") {
        router.visit(route("huawei.sites"));
    } else {
        router.visit(
            route("huawei.sites.search", { request: searchForm.searchTerm })
        );
    }
};
</script>
