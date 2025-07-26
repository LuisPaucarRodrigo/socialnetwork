<template>
    <div>
        <Head title="Gestion de Sites" />
        <AuthenticatedLayout>
            <template #header> Gestión de Sites Huawei </template>
            <Toaster richColors />
            <div class="overflow-hidden rounded-lg shadow">
                <div class="flex gap-4 justify-between rounded-lg">
                    <div
                        class="flex flex-col sm:flex-row gap-4 justify-between w-auto"
                    >
                        <button
                            v-permission="'huawei_sites_add'"
                            @click="openCreateModal"
                            class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 whitespace-nowrap"
                        >
                            Crear Nuevo Site
                        </button>
                    </div>

                    <div class="flex items-center ml-auto sm:ml-0">
                        <form
                            @submit.prevent="search"
                            class="flex items-center w-full sm:w-auto"
                        >
                            <TextInput
                                type="text"
                                placeholder="Buscar..."
                                v-model="searchForm.searchTerm"
                                class="mr-2"
                            />
                            <button
                                type="submit"
                                :class="{ 'opacity-25': searchForm.processing }"
                                class="ml-2 rounded-md bg-indigo-600 px-2 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            >
                                <svg
                                    width="30px"
                                    height="21px"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                                        stroke="white"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                <SitesTable
                    :dataToRender="dataToRender"
                    :links="props.sites.links"
                    :search="props.search"
                    @edit="openUpdateSite"
                    @delete="confirmingDeleteSite"
                />
            </div>
            <SiteModal
                :show="isCreateModalOpen || isUpdateModalOpen"
                :isCreate="isCreateModalOpen"
                :editForm="editForm"
                :operators="props.operators"
                :close="isCreateModalOpen ? closeCreateModal : closeUpdateModal"
                @close="
                    () => {
                        isCreateModalOpen = false;
                        isUpdateModalOpen = false;
                    }
                "
                @update="handleUpdateSite"
            />

            <ConfirmDeleteModal
                :confirmingDeletion="confirmDeleteSite"
                itemType="Site"
                :deleteFunction="deleteSite"
                @closeModal="closeModalDoc"
            />
        </AuthenticatedLayout>
    </div>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ConfirmDeleteModal from "@/Components/ConfirmDeleteModal.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import { ref } from "vue";
import SiteModal from "./SiteModal.vue";
import { notify, notifyError } from "@/Components/Notification";
import { Toaster } from "vue-sonner";
import SitesTable from "./SitesTable.vue";

const props = defineProps({
    sites: Object,
    users: Object,
    search: String,
    operators: Array,
});

console.log(props.users);

const isCreateModalOpen = ref(false);
const isUpdateModalOpen = ref(false);
const editingSite = ref(null);
const docToDelete = ref(null);
const confirmDeleteSite = ref(false);
const dataToRender = ref(props.search ? props.sites : props.sites.data);

const confirmingDeleteSite = (documentId) => {
    docToDelete.value = documentId;
    confirmDeleteSite.value = true;
};

const closeModalDoc = () => {
    confirmDeleteSite.value = false;
};

const deleteSite = async () => {
    const docId = docToDelete.value;
    if (docId) {
        const res = await axios.delete(
            route("huawei.sites.delete", { site: docId })
        );
        if (res.data === true) {
            const index = dataToRender.value.findIndex(
                (site) => site.id === docId
            );
            if (index !== -1) {
                dataToRender.value.splice(index, 1);
                closeModalDoc();
                notify("Site eliminado correctamente");
            } else {
                closeModalDoc();
                notifyError("No se encontró el site en la lista");
            }
        }
    }
};

const openCreateModal = () => {
    isCreateModalOpen.value = true;
};

const editForm = ref({});

const openUpdateSite = (item) => {
    editingSite.value = JSON.parse(JSON.stringify(item));
    editForm.value = {
        id: editingSite.value.id,
        code: editingSite.value.code,
        prefix: editingSite.value.prefix,
        latitude: editingSite.value.latitude,
        longitude: editingSite.value.longitude,
        name: editingSite.value.name,
        address: editingSite.value.address,
    };
    isUpdateModalOpen.value = true;
};

const closeCreateModal = () => {
    isCreateModalOpen.value = false;
};

const closeUpdateModal = () => {
    isUpdateModalOpen.value = false;
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

const handleUpdateSite = (site) => {
    if (site === "error") {
        notifyError(
            isCreateModalOpen.value
                ? "Error al crear el site"
                : "Error al actualizar el site"
        );
        return;
    }

    const array = Array.isArray(dataToRender.value)
        ? dataToRender.value
        : dataToRender.value.data ?? [];

    const index = array.findIndex((s) => s.id === site.id);

    if (index !== -1) {
        array[index] = site;
        notify("Site actualizado correctamente");
    } else {
        array.push(site);
        notify("Site creado correctamente");
    }

    dataToRender.value = [...array];
};
</script>
