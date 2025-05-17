<template>
    <div>
        <Head title="Gestion de Secciones" />
        <AuthenticatedLayout :redirectRoute="'documents.index'">
            <Toaster richColors />

            <template #header> Gestión de Secciones </template>
            <div
                class="inline-block min-w-full overflow-hidden rounded-lg shadow"
            >
                <PrimaryButton @click="openCreateSectionModal">
                    Crear Nueva Sección
                </PrimaryButton>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                    <div
                        v-for="section in dataToRender"
                        :key="section.id"
                        class="bg-white p-4 rounded-sm shadow-sm border border-gray-300"
                        @dragover.prevent
                        @drop="handleDrop(section.id)"
                    >
                        <!-- Encabezado: Título y botones -->
                        <div
                            class="flex flex-row items-start justify-between mb-2 gap-2"
                        >
                            <h2
                                class="text-sm font-semibold text-gray-800 flex-1 break-words"
                            >
                                {{ section.name }}
                            </h2>
                            <div class="flex flex-wrap items-center gap-2">
                                <button
                                    type="button"
                                    @click="
                                        openCreateSubdivisionModal(section.id)
                                    "
                                    class="ml-2"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-6 h-6 text-blue-600"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                        />
                                    </svg>
                                </button>
                                <a
                                    :href="
                                        route('documents.zipSection', {
                                            sectionId: section.id,
                                        })
                                    "
                                    class="text-blue-600 hover:underline"
                                >
                                    <ArrowDownIcon class="h-5 w-5" />
                                </a>
                                <button
                                    @click="openUpdateSectionModal(section)"
                                    class="text-orange-400 hover:underline"
                                >
                                    <PencilSquareIcon class="h-5 w-5" />
                                </button>
                                <button
                                    v-if="section.id > 10"
                                    @click="confirmDeleteSection(section.id)"
                                    class="text-red-600 hover:underline"
                                >
                                    <TrashIcon class="h-5 w-5" />
                                </button>
                            </div>
                        </div>

                        <!-- Línea separadora -->
                        <div class="border-t border-gray-200 my-2"></div>

                        <!-- Subdivisiones -->
                        <!-- Subdivisiones -->
                        <div class="space-y-2">
                            <div
                                v-for="subdivision in section.subdivisions"
                                :key="subdivision.id"
                                class="flex items-start justify-between hover:bg-gray-100 hover:border hover:border-black hover:rounded border-transparent transition"
                                draggable="true"
                                @dragstart="
                                    handleDragStart(subdivision, section.id)
                                "
                            >
                                <!-- Nombre (65%) -->
                                <p
                                    class="text-sm text-gray-700 w-2/3 break-words"
                                >
                                    <span class="font-medium">{{
                                        subdivision.name
                                    }}</span>
                                </p>

                                <!-- Botones (35%) -->
                                <div
                                    class="flex items-center justify-end space-x-2 w-1/3"
                                >
                                    <a
                                        :href="
                                            route('documents.zipSubdivision', {
                                                section: subdivision.section_id,
                                                subdivisionId: subdivision.id,
                                            })
                                        "
                                        class="text-blue-600 hover:underline"
                                    >
                                        <ArrowDownIcon class="h-5 w-5" />
                                    </a>
                                    <button
                                        @click="
                                            openUpdateSubdivisionModal(
                                                section.id,
                                                subdivision
                                            )
                                        "
                                        class="text-orange-400 hover:underline"
                                    >
                                        <PencilSquareIcon class="h-5 w-5" />
                                    </button>
                                    <button
                                        v-if="subdivision.id > 154"
                                        @click="
                                            confirmDeleteSubdivision(
                                                section.id,
                                                subdivision.id
                                            )
                                        "
                                        class="text-red-600 hover:underline"
                                    >
                                        <TrashIcon class="h-5 w-5" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <Modal :show="isCreateSectionModalOpen || isUpdateSectionModalOpen">
                <div class="p-6">
                    <h2 class="text-base font-medium leading-7 text-gray-900">
                        {{
                            isCreateSectionModalOpen
                                ? "Agregar Sección"
                                : "Actualizar Sección"
                        }}
                    </h2>
                    <form
                        @submit.prevent="
                            isCreateSectionModalOpen
                                ? submit(false)
                                : submit(true)
                        "
                    >
                        <div class="space-y-12">
                            <div class="border-b border-gray-900/10 pb-12">
                                <div>
                                    <InputLabel for="name"
                                        >{{
                                            isCreateSectionModalOpen
                                                ? "Agregar nueva sección:"
                                                : "Actualizar Secciòn:"
                                        }}
                                    </InputLabel>
                                    <div class="mt-2">
                                        <TextInput
                                            type="text"
                                            v-model="form.name"
                                            id="name"
                                            autocomplete="off"
                                        />
                                        <InputError
                                            :message="form.errors.name"
                                        />
                                    </div>
                                </div>
                                <div
                                    class="mt-6 flex items-center justify-end gap-x-6"
                                >
                                    <SecondaryButton
                                        @click="
                                            isCreateSectionModalOpen
                                                ? closeCreateSectionModal()
                                                : closeUpdateSectionModal()
                                        "
                                    >
                                        Cancelar
                                    </SecondaryButton>
                                    <PrimaryButton
                                        type="submit"
                                        :class="{
                                            'opacity-25': form.processing,
                                        }"
                                    >
                                        {{
                                            isCreateSectionModalOpen
                                                ? "Guardar"
                                                : "Actualizar"
                                        }}
                                    </PrimaryButton>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </Modal>

            <Modal
                :show="
                    isCreateSubdivisionModalOpen || isUpdateSubdivisionModalOpen
                "
            >
                <div class="p-6">
                    <h2 class="text-base font-medium leading-7 text-gray-900">
                        {{
                            isCreateSubdivisionModalOpen
                                ? "Agregar Subdivisión"
                                : "Actualizar Subdivisión"
                        }}
                    </h2>
                    <form
                        @submit.prevent="
                            isCreateSubdivisionModalOpen
                                ? submitSub(false)
                                : submitSub(true)
                        "
                    >
                        <div class="space-y-12">
                            <div class="border-b border-gray-900/10 pb-12">
                                <div>
                                    <InputLabel for="name">{{
                                        isCreateSubdivisionModalOpen
                                            ? "Agregar nueva Subdivisión:"
                                            : "Actualizar Subdivisión"
                                    }}</InputLabel>
                                    <div class="mt-2">
                                        <TextInput
                                            type="text"
                                            v-model="formSub.name"
                                            id="name"
                                            autocomplete="off"
                                        />
                                        <InputError
                                            :message="formSub.errors.name"
                                        />
                                    </div>
                                </div>
                                <div
                                    class="mt-6 flex items-center justify-end gap-x-6"
                                >
                                    <SecondaryButton
                                        @click="
                                            isCreateSubdivisionModalOpen
                                                ? closeCreateSubdivisionModal()
                                                : closeUpdateSubdivisionModal()
                                        "
                                    >
                                        Cancelar
                                    </SecondaryButton>
                                    <PrimaryButton
                                        type="submit"
                                        :class="{
                                            'opacity-25': form.processing,
                                        }"
                                        >{{
                                            isCreateSubdivisionModalOpen
                                                ? "Guardar"
                                                : "Actualizar"
                                        }}</PrimaryButton
                                    >
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </Modal>

            <ConfirmCreateModal
                :confirmingcreation="showModal"
                itemType="Sección de documentos"
            />
            <ConfirmUpdateModal
                :confirmingupdate="showModalEdit"
                itemType="Sección de documentos"
            />
            <ConfirmDeleteModal
                :confirmingDeletion="create_section"
                itemType="Sección"
                :deleteFunction="deleteSection"
                @closeModal="closeModalSection"
            />
            <ConfirmDeleteModal
                :confirmingDeletion="create_subdivision"
                itemType="Subdivisión"
                :deleteFunction="deleteSubdivision"
                @closeModal="closeModalSubdivision"
            />
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
import { Head, useForm, router, Link } from "@inertiajs/vue3";
import {
    TrashIcon,
    PencilSquareIcon,
    ArrowDownIcon,
} from "@heroicons/vue/24/outline";
import { ref } from "vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { setAxiosErrors, toFormData } from "@/utils/utils";
import { notify, notifyError, notifyWarning } from "@/Components/Notification";
import { Toaster } from "vue-sonner";

const showModal = ref(false);
const showModalEdit = ref(false);
const isFetching = ref(false);

const props = defineProps({
    sections: Object,
});

const dataToRender = ref(props.sections);

const form = useForm({
    id: "",
    name: "",
});

const isCreateSectionModalOpen = ref(false);
const isUpdateSectionModalOpen = ref(false);
const editingSection = ref(null);
const create_section = ref(false);
const sectionToDelete = ref(null);
const isCreateSubdivisionModalOpen = ref(false);
const isUpdateSubdivisionModalOpen = ref(false);
const editingSubdivision = ref(null);
const sectionId = ref(null);
const subdivisionToDelete = ref(null);
const create_subdivision = ref(false);

const openCreateSectionModal = () => {
    isCreateSectionModalOpen.value = true;
};

const openUpdateSectionModal = (item) => {
    editingSection.value = JSON.parse(JSON.stringify(item));
    form.id = editingSection.value.id;
    form.name = editingSection.value.name;
    isUpdateSectionModalOpen.value = true;
};

const closeCreateSectionModal = () => {
    form.reset();
    isCreateSectionModalOpen.value = false;
};

const closeUpdateSectionModal = () => {
    form.reset();
    isUpdateSectionModalOpen.value = false;
};

const submit = async (update) => {
    isFetching.value = true;

    const url = update
        ? route("documents.updateSection", { section: form.id })
        : route("documents.storeSection");

    const formData = toFormData(form);

    try {
        const res = await axios.post(url, formData, {
            headers: { "Content-Type": "multipart/form-data" },
        });

        const section = res.data;

        const map = new Map(dataToRender.value.map((s) => [s.id, s]));
        map.set(section.id, section);
        dataToRender.value = Array.from(map.values());

        update ? closeUpdateSectionModal() : closeCreateSectionModal();
        notify(
            update
                ? "Se actualizó correctamente la sección"
                : "Se creó correctamente la sección"
        );
    } catch (e) {
        console.error(e);
        isFetching.value = false;

        if (e.response?.data?.errors) {
            setAxiosErrors(e.response.data.errors, form);
        } else {
            notifyError("Error del servidor");
        }
    }
};

const confirmDeleteSection = (sectionId) => {
    sectionToDelete.value = sectionId;
    create_section.value = true;
};

const closeModalSection = () => {
    create_section.value = false;
};

const deleteSection = async () => {
    try {
        const sectionIdValue = sectionToDelete.value;

        const res = await axios.delete(
            route("documents.destroySection", { section: sectionIdValue })
        );

        if (res.data.message === "success") {
            dataToRender.value = dataToRender.value.filter(
                (section) => section.id !== sectionIdValue
            );
            notify("Sección eliminada correctamente");
            closeModalSection();
        }
    } catch (error) {
        console.error(error);
        notifyError("Error al eliminar la sección");
    }
};

//subdivisions
const openCreateSubdivisionModal = (section) => {
    sectionId.value = section;
    isCreateSubdivisionModalOpen.value = true;
};

const openUpdateSubdivisionModal = (section, item) => {
    sectionId.value = section;
    editingSubdivision.value = JSON.parse(JSON.stringify(item));
    formSub.id = editingSubdivision.value.id;
    formSub.name = editingSubdivision.value.name;
    isUpdateSubdivisionModalOpen.value = true;
};

const closeCreateSubdivisionModal = () => {
    formSub.reset();
    sectionId.value = null;
    isCreateSubdivisionModalOpen.value = false;
};

const closeUpdateSubdivisionModal = () => {
    formSub.reset();
    sectionId.value = null;
    isUpdateSubdivisionModalOpen.value = false;
};

const formSub = useForm({
    id: "",
    name: "",
    section_id: "",
});

const submitSub = async (update) => {
    isFetching.value = true;

    const url = !update
        ? route("documents.storeSubdivision", { section: sectionId.value })
        : route("documents.updateSubdivision", {
              section: sectionId.value,
              subdivision: formSub.id,
          });

    const formData = toFormData(formSub);

    try {
        const res = await axios.post(url, formData, {
            headers: { "Content-Type": "multipart/form-data" },
        });

        const newSub = res.data;

        const section = dataToRender.value.find(
            (s) => s.id === newSub.section_id
        );

        if (section) {
            const subMap = new Map(section.subdivisions.map((s) => [s.id, s]));
            subMap.set(newSub.id, newSub);
            section.subdivisions = Array.from(subMap.values());
        } else {
            console.warn("Sección no encontrada para la subdivisión");
        }

        update ? closeUpdateSubdivisionModal() : closeCreateSubdivisionModal();
        notify(
            update
                ? "Se actualizó correctamente la subdivisión"
                : "Se creó correctamente la subdivisión"
        );
    } catch (e) {
        console.error(e);
        isFetching.value = false;

        if (e.response?.data?.errors) {
            setAxiosErrors(e.response.data.errors, formSub);
        } else {
            notifyError("Error del servidor");
        }
    }
};

const confirmDeleteSubdivision = (section, subdivisionId) => {
    subdivisionToDelete.value = subdivisionId;
    sectionId.value = section;
    create_subdivision.value = true;
};

const closeModalSubdivision = () => {
    create_subdivision.value = false;
};

const deleteSubdivision = async () => {
    try {
        const res = await axios.delete(
            route("documents.destroySubdivision", {
                section: sectionId.value,
                subdivision: subdivisionToDelete.value,
            })
        );

        if (res.data.message === "success") {
            const section = dataToRender.value.find(
                (s) => s.id === sectionId.value
            );
            if (section) {
                section.subdivisions = section.subdivisions.filter(
                    (sub) => sub.id !== subdivisionToDelete.value
                );
            }
            closeModalSubdivision();
            notify("Subdivisión eliminada correctamente");
        }
    } catch (error) {
        console.error(error);
        notifyError("Error al eliminar la subdivisión");
    }
};

//drag and drop
const draggedSubdivision = ref(null);

function handleDragStart(subdivision, sectionId) {
    draggedSubdivision.value = { ...subdivision, fromSectionId: sectionId };
}

async function handleDrop(targetSectionId) {
    if (
        draggedSubdivision.value &&
        draggedSubdivision.value.fromSectionId !== targetSectionId
    ) {
        const fromSectionIndex = dataToRender.value.findIndex(
            (s) => s.id === draggedSubdivision.value.fromSectionId
        );
        const toSectionIndex = dataToRender.value.findIndex(
            (s) => s.id === targetSectionId
        );

        if (fromSectionIndex === -1 || toSectionIndex === -1) return;

        const fromSection = dataToRender.value[fromSectionIndex];
        const toSection = dataToRender.value[toSectionIndex];

        const subdivisionIndex = fromSection.subdivisions.findIndex(
            (s) => s.id === draggedSubdivision.value.id
        );

        if (subdivisionIndex !== -1) {
            const movedSubdivision = fromSection.subdivisions[subdivisionIndex];

            try {
                await axios.post(route("documents.drag_and_drop"), {
                    subdivision_id: movedSubdivision.id,
                    section_id: targetSectionId,
                });

                fromSection.subdivisions.splice(subdivisionIndex, 1);
                movedSubdivision.section_id = targetSectionId;
                toSection.subdivisions.push(movedSubdivision);

                notify("Subdivisión movida correctamente");
            } catch (error) {
                console.error("Error al actualizar el backend:", error);
                notify("Error al mover la subdivisión", "error");
            }
        }

        draggedSubdivision.value = null;
    }
}
</script>
