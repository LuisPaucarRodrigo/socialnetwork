<template>

    <Head title="Gestion de Documentos" />
    <AuthenticatedLayout :redirectRoute="'documents.index'">
        <template #header> Documentos </template>
        <div class="flex gap-4 justify-between rounded-lg">
            <div class="flex flex-col sm:flex-row gap-4 justify-between w-full">
                <div class="flex gap-4 items-center">
                    <PrimaryButton v-permission-and="['documents_create']" @click="openCreateDocumentModal"
                        type="button"
                        class="hidden sm:block rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                        + Agregar Documento
                    </PrimaryButton>
                    <PrimaryButton v-if="hasPermission('HumanResourceManager')" @click="management_section"
                        type="button"
                        class="hidden sm:block rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                        Gestionar Secciones
                    </PrimaryButton>

                    <div class="sm:hidden">
                        <dropdown align="left">
                            <template #trigger>
                                <button @click="dropdownOpen = !dropdownOpen"
                                    class="relative block overflow-hidden rounded-md bg-gray-200 px-2 py-2 text-center text-sm text-white hover:bg-gray-100">
                                    <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 6H20M4 12H20M4 18H20" stroke="#000000" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </template>

                            <template #content class="origin-left">
                                <div>
                                    <!-- Alineación a la derecha -->
                                    <div class="dropdown">
                                        <div class="dropdown-menu">
                                            <button @click="openCreateDocumentModal" type="button"
                                                class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                                + Agregar Documento
                                            </button>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <div class="dropdown-menu">
                                            <button @click="management_section" type="button"
                                                class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                                Gestionar Secciones
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </dropdown>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex w-full mt-5">
            <!-- Tabla -->
            <div class="w-[100%] overflow-x-auto">
                <div class="max-h-[77vh] overflow-y-auto border rounded-md">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre
                                </th>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sección
                                </th>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subdivisión
                                </th>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Empleado
                                </th>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="document in dataToRender" :key="document.id">
                                <td class="px-6 max-w-[230px] py-4 text-sm font-medium text-gray-900">
                                    {{ getDocumentName(document.title) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ document.subdivision?.section?.name }}
                                </td>
                                <td class="px-6 max-w-[100px] py-4 text-sm text-gray-700">
                                    {{ document.subdivision?.name }}
                                </td>
                                <td class="px-6 py-4 max-w-[150px] text-sm text-gray-700">
                                    {{ document.emp_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <button v-if="
                                            document.title &&
                                            /\.(pdf|png|jpe?g)$/.test(
                                                document.title
                                            )
                                        " @click="
                                            openPreviewDocumentModal(
                                                document.id
                                            )
                                            ">
                                            <ShowIcon />
                                        </button>
                                        <button @click="
                                            downloadDocument(document.id)
                                            ">
                                            <DownloadIcon />
                                        </button>
                                        <button v-permission="'documents_update'" @click="
                                            openEditDocumentModal(document)
                                            ">
                                            <EditIcon />
                                        </button>
                                        <button v-permission="'document_delete'" @click="
                                            confirmDeleteDocument(
                                                document.id
                                            )
                                            ">
                                            <DeleteIcon />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <Modal :show="create_document || update_document">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{
                        create_document
                            ? "Subir Documento"
                            : "Actualizar Documento"
                    }}
                </h2>
                <form @submit.prevent="create_document ? submit() : submitEdit()">
                    <div class="border-b border-gray-900/10 pb-12">
                        <div class="mt-2">
                            <InputLabel for="documentFile">Documento</InputLabel>
                            <div class="mt-2">
                                <InputFile type="file" v-model="form.document" id="documentFile" />
                                <InputError :message="form.errors.document" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="documentSection">Sección:</InputLabel>
                            <select v-model="form.section_id" id="documentSection"
                                class="border rounded-md px-3 py-2 mb-3 w-full">
                                <option value="">Seleccionar Seccion</option>
                                <option v-for="section in sections" :key="section.id" :value="section.id">
                                    {{ section.name }}
                                </option>
                            </select>
                            <InputError :message="form.errors.subdivision_id" />
                        </div>
                        <div v-if="form.section_id">
                            <InputLabel for="documentSubdivision">Subdivisión:</InputLabel>

                            <template v-if="filteredSubdivisions.length > 0">
                                <select v-model="form.subdivision_id" id="documentSubdivision"
                                    class="border rounded-md px-3 py-2 mb-3 w-full">
                                    <option value="">
                                        Seleccionar Subdivisión
                                    </option>
                                    <option v-for="subdivision in filteredSubdivisions" :key="subdivision.id"
                                        :value="subdivision.id">
                                        {{ subdivision.name }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.subdivision_id" />
                            </template>

                            <template class="mt-2" v-else>
                                <p class="text-red-500">
                                    Cree subdivisiones para esta sección.
                                </p>
                            </template>
                        </div>

                        <div v-if="create_document" class="mt-4 flex gap-6">
                            <label class="flex gap-3 items-center" for="empTypePlanilla">
                                Planilla
                                <input id="empTypePlanilla" type="radio" :value="1" v-model="form.employeeType"
                                    class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                            </label>
                            <label class="flex gap-3 items-center" for="empTypeTerceros">
                                Terceros
                                <input id="empTypeTerceros" type="radio" :value="0" v-model="form.employeeType"
                                    class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                            </label>
                        </div>
                        <div v-else>
                            <InputLabel class="flex gap-3 items-center">
                                Empleado
                            </InputLabel>
                        </div>
                        <div v-if="form.employeeType" class="mt-2">
                            <select v-model="form.employee_id" id="docEmp"
                                class="border rounded-md px-3 py-2 mb-3 w-full">
                                <option value="" disabled>
                                    Seleccionar Empleado
                                </option>
                                <option v-for="item in employees" :key="item.id" :value="item.id">
                                    {{ item.name }} {{ item.lastname }}
                                </option>
                            </select>
                            <InputError :message="form.errors.employee_id" />
                        </div>
                        <div v-else class="mt-2">
                            <select v-model="form.e_employee_id" id="docEmp"
                                class="border rounded-md px-3 py-2 mb-3 w-full">
                                <option value="" disabled>
                                    Seleccionar Empleado
                                </option>
                                <option v-for="item in e_employees" :key="item.id" :value="item.id">
                                    {{ item.name }} {{ item.lastname }}
                                </option>
                            </select>
                            <InputError :message="form.errors.e_employee_id" />
                        </div>

                        <div class="mt-4 flex gap-4">
                            ¿Tiene Fecha de Vencimiento?
                            <label class="flex gap-2 items-center" for="hasExpDateYes">
                                Si
                                <input id="hasExpDateYes" type="radio" :value="1" v-model="form.has_exp_date"
                                    class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                            </label>
                            <label class="flex gap-2 items-center" for="hasExpDateNo">
                                No
                                <input id="hasExpDateNo" type="radio" :value="0" v-model="form.has_exp_date"
                                    class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                            </label>
                        </div>
                        <InputError :message="form.errors.has_exp_date" />
                        <div v-if="form.has_exp_date" class="mt-2">
                            <TextInput id="phone" type="date" maxlength="9"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                v-model="form.exp_date" autocomplete="off" />
                            <InputError :message="form.errors.exp_date" />
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="
                                create_document
                                    ? closeModal()
                                    : closeEditModal()
                                ">
                                Cancelar
                            </SecondaryButton>
                            <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                                Guardar
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="documento"
            :deleteFunction="deleteDocument" @closeModal="closeModalDoc" />
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="documento" />
        <ConfirmUpdateModal :confirmingupdate="showEditModal" itemType="documento" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ConfirmCreateModal from "@/Components/ConfirmCreateModal.vue";
import ConfirmUpdateModal from "@/Components/ConfirmUpdateModal.vue";
import ConfirmDeleteModal from "@/Components/ConfirmDeleteModal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputFile from "@/Components/InputFile.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";
import { ref, computed, watch } from "vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import { notifyError } from "@/Components/Notification";
import { EditIcon, DeleteIcon, ShowIcon, DownloadIcon } from "@/Components/Icons/Index";

const props = defineProps({
    sections: Object,
    documents: Object,
    subdivisions: Object,
    employees: Array,
    e_employees: Array,
    userPermissions: Array,
    section: [String, null],
    subdivision: [String, null],
    search: [String, null],
});

console.log(props.documents);

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
};

const form = useForm({
    id: "",
    document: null,
    section_id: "",
    subdivision_id: "",
    employeeType: 1,
    employee_id: "",
    e_employee_id: "",
    has_exp_date: "",
    exp_date: "",
});

const filteredSubdivisions = ref([]);
const create_document = ref(false);
const update_document = ref(false);
const showModal = ref(false);
const showEditModal = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);
const editingDocument = ref(null);
const selectedSection = ref("");
const newSection = ref(props.section);
const newSubdivision = ref(props.subdivision);
const dataToRender = ref(props.documents);

const management_section = () => {
    router.get(route("documents.sections"));
};

const openCreateDocumentModal = () => {
    create_document.value = true;
};

const closeModal = () => {
    form.reset();
    form.clearErrors();
    create_document.value = false;
};

const openEditDocumentModal = (document) => {
    // Copia de los datos de la subsección existente al formulario
    editingDocument.value = JSON.parse(JSON.stringify(document));
    form.id = editingDocument.value.id;
    form.document = editingDocument.value.name;
    form.section_id = editingDocument.value.subdivision.section_id;
    form.subdivision_id = editingDocument.value.subdivision_id;
    form.employee_id = editingDocument.value.employee_id;
    form.e_employee_id = document.e_employee_id;
    form.has_exp_date = editingDocument.value.exp_date ? 1 : 0;
    form.exp_date = editingDocument.value.exp_date;
    form.employeeType = editingDocument.value.employee_id ? 1 : 0;
    update_document.value = true;
};

const closeEditModal = () => {
    form.reset();
    form.clearErrors();
    update_document.value = false;
};

function submit() {
    let url = route("documents.create");
    // try{
    //   let response = await axios.post(url,form)
    // }catch(){

    // }
    form.post(url, {
        onSuccess: () => {
            closeEditModal();
            showModal.value = true;
            setTimeout(() => {
                showModal.value = false;
                router.visit(route("documents.index"));
            }, 2000);
        },
        onError: (e) => {
            console.log(e);
        },
    });
}

const submitEdit = () => {
    form.post(route("documents.update", { id: form.id }), {
        onSuccess: () => {
            closeModal();
            showEditModal.value = true;
            setTimeout(() => {
                showEditModal.value = false;
                router.visit(route("documents.index"));
            }, 2000);
        },
        onError: (e) => {
            console.log(e);
        },
    });
};

const confirmDeleteDocument = (documentId) => {
    docToDelete.value = documentId;
    confirmingDocDeletion.value = true;
};

const closeModalDoc = () => {
    confirmingDocDeletion.value = false;
};

const deleteDocument = () => {
    const docId = docToDelete.value;
    if (docId) {
        router.delete(route("documents.destroy", { id: docId }), {
            onSuccess: () => closeModalDoc(),
        });
    }
};

function downloadDocument(documentId) {
    const backendDocumentUrl = route("documents.download", {
        document: documentId,
    });
    window.open(backendDocumentUrl, "_blank");
}

function openPreviewDocumentModal(documentId) {
    const routeToShow = route("documents.show", { document: documentId });
    window.open(routeToShow, "_blank");
}

const getDocumentName = (documentTitle) => {
    const parts = documentTitle.split("_");
    return parts.length > 1 ? parts.slice(1).join("_") : documentTitle;
};

const selectedSubdivision = ref("");

const subdivisionsForSelectedSection = computed(() => {
    if (newSection.value === "") {
        return []; // Mostrar todas las subdivisiones si no se selecciona ninguna sección
    }
    return props.subdivisions.filter(
        (subdivision) => subdivision.section_id == newSection.value
    );
});

watch(
    () => selectedSection,
    () => {
        // Reiniciar la selección de la subdivisión cuando cambia la sección
        selectedSubdivision.value = "";
    }
);

watch(
    () => form.section_id,
    (newSectionId, oldSectionId) => {
        // Si la sección cambió, restablecemos la lista de subdivisiones
        if (newSectionId !== oldSectionId) {
            filteredSubdivisions.value = [];
        }

        // Filtra las subdivisiones según la nueva sección seleccionada
        if (newSectionId) {
            filteredSubdivisions.value = props.subdivisions.filter(
                (subdivision) => subdivision.section_id === newSectionId
            );
        }
    }
);

const searchForm = useForm({
    search: props.search,
    section: props.section,
    subdivision: props.subdivision,
});

const search = () => {
    if (!searchForm.search) {
        if (props.section) {
            if (props.subdivision) {
                router.visit(
                    route("documents.filter.subdivision", {
                        section: props.section,
                        subdivision: props.subdivision,
                    })
                );
            } else {
                router.visit(
                    route("documents.filter.section", {
                        section: props.section,
                    })
                );
            }
        } else {
            router.visit(route("documents.index"));
        }
    } else {
        const url = route("documents.search", {
            section: props.section ? props.section : "no",
            subdivision: props.subdivision ? props.subdivision : "no",
            request: searchForm.search,
        });
        router.visit(url);
    }
};

const filterSection = (e) => {
    newSection.value = e.target.value;
    searchForm.section = newSection;
    searchForm.subdivision = "";
    if (!newSection.value) {
        if (props.search) {
            router.visit(
                route("documents.search", {
                    section: "no",
                    subdivision: "no",
                    request: searchForm.search,
                })
            );
        } else {
            router.visit(route("documents.index"));
        }
    } else {
        if (props.search) {
            router.visit(
                route("documents.filter.section", {
                    section: newSection.value,
                    request: searchForm.search,
                })
            );
        } else {
            router.visit(
                route("documents.filter.section", { section: newSection.value })
            );
        }
    }
};

const filterSubdivision = (e) => {
    newSubdivision.value = e.target.value;
    searchForm.subdivision = newSubdivision.value;
    if (!newSubdivision.value) {
        if (props.search) {
            router.visit(
                route("documents.filter.section", {
                    section: props.section,
                    request: searchForm.search,
                })
            );
        } else {
            router.visit(
                route("documents.filter.section", { section: props.section })
            );
        }
    } else {
        if (props.search) {
            router.visit(
                route("documents.filter.subdivision", {
                    section: props.section,
                    subdivision: newSubdivision.value,
                    request: searchForm.search,
                })
            );
        } else {
            router.visit(
                route("documents.filter.subdivision", {
                    section: props.section,
                    subdivision: newSubdivision.value,
                })
            );
        }
    }
};

watch(
    () => form.employeeType,
    () => {
        if (create_document.value) {
            form.employee_id = "";
            form.e_employee_id = "";
        }
    }
);
watch(
    () => form.has_exp_date,
    () => {
        form.exp_date = "";
    }
);

//new_test_filter
const filterForm = useForm({
    search: "",
    sections: [], // array de section ids
    subdivisions: [], // array de subdivision ids
});

const expandedSections = ref([]);
const isFetching = ref(false);

function toggleSection(id) {
    const index = expandedSections.value.indexOf(id);
    if (index > -1) {
        expandedSections.value.splice(index, 1);
    } else {
        expandedSections.value.push(id);
    }
}
function handleSectionCheckbox(section) {
    const sectionId = section.id;
    const isSelected = filterForm.sections.includes(sectionId);

    if (isSelected) {
        // Desmarcar sección y sus subdivisiones
        filterForm.sections = filterForm.sections.filter(
            (id) => id !== sectionId
        );
        filterForm.subdivisions = filterForm.subdivisions.filter(
            (id) => !section.subdivisions.some((sub) => sub.id === id)
        );
        expandedSections.value = expandedSections.value.filter(
            (id) => id !== sectionId
        );
    } else {
        // Marcar sección y subdivisiones
        filterForm.sections.push(sectionId);
        section.subdivisions.forEach((sub) => {
            if (!filterForm.subdivisions.includes(sub.id)) {
                filterForm.subdivisions.push(sub.id);
            }
        });
        // Asegurar que esté expandida
        if (!expandedSections.value.includes(sectionId)) {
            expandedSections.value.push(sectionId);
        }
    }
}

function handleSubdivisionToggle(sectionId, subId) {
    const isChecked = filterForm.subdivisions.includes(subId);

    if (isChecked) {
        // Quitar subdivisión
        filterForm.subdivisions = filterForm.subdivisions.filter(
            (id) => id !== subId
        );

        // Verificar si quedan subdivisiones seleccionadas de esta sección
        const section = props.sections.find((s) => s.id === sectionId);
        const anySelected = section.subdivisions.some((sub) =>
            filterForm.subdivisions.includes(sub.id)
        );

        if (!anySelected) {
            filterForm.sections = filterForm.sections.filter(
                (id) => id !== sectionId
            );
        }
    } else {
        // Agregar subdivisión
        filterForm.subdivisions.push(subId);

        // Asegurar que la sección esté marcada
        if (!filterForm.sections.includes(sectionId)) {
            filterForm.sections.push(sectionId);
        }

        // Asegurar que esté expandida
        if (!expandedSections.value.includes(sectionId)) {
            expandedSections.value.push(sectionId);
        }
    }
}

function cleanFilters() {
    filterForm.search = "";
    filterForm.sections = [];
    filterForm.subdivisions = [];
    expandedSections.value = [];
}

async function applyFilters() {
    isFetching.value = true;

    const url = route("documents.filter_document");

    try {
        const res = await axios.post(url, filterForm);
        dataToRender.value.data = res.data;
        isFetching.value = false;
    } catch (error) {
        console.error(error);
        isFetching.value = false;
        notifyError("Server Error");
    }
}
</script>
