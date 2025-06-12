<template>

    <Head title="Gestion de Documentos" />
    <AuthenticatedLayout :redirectRoute="'documents.index'">
        <template #header> Documentos </template>
        <Toaster richColors />

        <div class="flex gap-4 justify-between rounded-lg">
            <div class="flex flex-col sm:flex-row gap-4 justify-between w-full">
                <div class="flex gap-4 items-center px-2">
                    <PrimaryButton v-permission="'add_document_hr'" @click="openCreateDocumentModal" type="button"
                        class="hidden sm:block rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                        + Agregar Documento
                    </PrimaryButton>
                    <PrimaryButton v-permission-or="['manage_sections_subdivisions_hr', 'delete_new_sections_hr', 'delete_new_subdivisions_hr']" @click="management_section"
                        type="button"
                        class="hidden sm:block rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                        Gestionar Secciones
                    </PrimaryButton>

                    <div class="sm:hidden" v-permission-or="['add_document_hr', 'manage_sections_subdivisions_hr']">
                        <dropdown align="left">
                            <template #trigger>
                                <button @click="dropdownOpen = !dropdownOpen"
                                    class="relative block overflow-hidden rounded-md bg-gray-200 px-2 py-2 text-center text-sm text-white hover:bg-gray-100">
                                    <MenuIcon />
                                </button>
                            </template>

                            <template #content class="origin-left">
                                <div>
                                    <!-- Alineación a la derecha -->
                                    <div v-permission="'add_document_hr'" class="dropdown">
                                        <div class="dropdown-menu">
                                            <button @click="openCreateDocumentModal" type="button"
                                                class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                                + Agregar Documento
                                            </button>
                                        </div>
                                    </div>
                                    <div v-permission-or="['manage_sections_subdivisions_hr', 'delete_new_sections_hr', 'delete_new_subdivisions_hr']" class="dropdown">
                                        <div class="dropdown-menu">
                                            <button @click="management_section" type="button"
                                                class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                                Gestionar Secciones
                                            </button>
                                        </div>
                                    </div>
                                    <div v-if="
                                        filterForm.employees.length > 0 ||
                                        filterForm.external_employees
                                            .length > 0 ||
                                        filterForm.sections.length > 0 ||
                                        filterForm.subdivisions.length > 0
                                    " class="dropdown">
                                        <div v-if="!activatedFilter" class="dropdown-menu">
                                            <button @click="applyFilters" type="button"
                                                class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                                Generar Reporte
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </dropdown>
                    </div>
                </div>
                <div v-if="!activatedFilter">
                    <PrimaryButton v-if="
                        filterForm.employees.length > 0 ||
                        filterForm.external_employees.length > 0 ||
                        filterForm.sections.length > 0 ||
                        filterForm.subdivisions.length > 0
                    " @click="applyFilters" type="button"
                        class="hidden sm:block mr-4 rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                        Generar Reporte
                    </PrimaryButton>
                </div>
                <div v-else>
                    <PrimaryButton v-if="
                        filterForm.employees.length > 0 ||
                        filterForm.external_employees.length > 0 ||
                        filterForm.sections.length > 0 ||
                        filterForm.subdivisions.length > 0
                    " @click="massiveZip" type="button"
                        class="hidden sm:block mr-4 rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                        Descargar ZIP
                    </PrimaryButton>
                </div>
            </div>
        </div>
        <div v-if="!activatedFilter" class="flex flex-col md:flex-row w-full gap-2 mt-5 h-auto md:h-[70vh]">
            <transition name="fade">
                <div v-if="isFetching"
                    class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-white bg-opacity-75">
                    <svg class="animate-spin h-8 w-8 text-gray-600 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <span class="text-gray-700 text-sm font-medium">Cargando...</span>
                </div>
            </transition>
            <!-- Filtro -->
            <div class="md:w-[20%] flex flex-col rounded-md overflow-hidden">
                <!-- Buscador siempre visible -->
                <div class="px-2 flex justify-between items-stretch gap-4">
                    <TextInput type="text" placeholder="Buscar..." v-model="filterForm.search"
                        @input="handleSearchInput" @keydown.enter.prevent="goToNextMatch" class="w-full" />
                    <div class="flex items-center">
                        <input type="checkbox" :checked="isAllEmpSelected" @change="toggleEmpSelectAll"
                            class="form-checkbox w-6 h-6 aspect-square rounded-sm text-indigo-600" />
                    </div>
                </div>

                <!-- Lista con scroll que ocupa el espacio restante -->
                <div class="px-4 pr-1 space-y-2 mt-5 overflow-y-scroll h-[200px] sm:h-[10%] md:h-full">
                    <div v-for="(employee, index) in mergedEmployees" :key="`${employee.type}-${employee.id}`"
                        :id="`employee-match-${index}`" class="flex items-center space-x-2">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" :value="employee.id" :checked="employee.type === 'external'
                                ? filterForm.external_employees.includes(
                                    employee.id
                                )
                                : filterForm.employees.includes(
                                    employee.id
                                )
                                " @change="handleEmployeeToggle(employee)"
                                class="form-checkbox h-4 w-4 text-indigo-600" />
                            <span class="text-sm text-gray-700" v-html="highlightMatch(employee.name)"></span>
                            <span class="ml-1 text-xs text-gray-400 uppercase" v-if="employee.type === 'external'">
                                (Externo)
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Contenido de secciones -->
            <div class="px-5 md:w-[80%]">
                <div class="flex justify-end mb-2">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" :checked="isAllSubSelected" @change="toggleSelectAll"
                            class="form-checkbox h-4 w-4 text-indigo-600" />
                        <span class="text-sm text-gray-700">Seleccionar Todo</span>
                    </label>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div v-for="section in props.sections" :key="section.id"
                        class="bg-white p-4 rounded-sm shadow-sm border border-gray-300 relative">
                        <!-- Encabezado de la sección -->
                        <div class="flex items-center justify-between mb-2">
                            <label class="flex items-center justify-between w-full cursor-pointer">
                                <span class="text-sm font-semibold text-gray-800 break-words">
                                    {{ section.name }}
                                </span>
                                <input type="checkbox" :checked="filterForm.sections.includes(section.id)
                                    " @change.stop="
                                        handleSectionCheckbox(section)
                                        " class="form-checkbox h-4 w-4 text-indigo-600 ml-2" />
                            </label>
                        </div>

                        <div class="border-t border-gray-200 my-2"></div>

                        <!-- Subdivisiones -->
                        <div class="space-y-2">
                            <div v-for="subdivision in section.subdivisions" :key="subdivision.id"
                                class="flex items-center justify-between">
                                <label class="flex items-center justify-between w-full cursor-pointer">
                                    <span class="text-sm text-gray-700 break-words font-medium">
                                        {{ subdivision.name }}
                                    </span>
                                    <input type="checkbox" :checked="filterForm.subdivisions.includes(
                                        subdivision.id
                                    )
                                        " @change.stop="
                                            handleSubdivisionToggle(
                                                section.id,
                                                subdivision.id
                                            )
                                            " class="form-checkbox h-4 w-4 text-indigo-600 ml-2" />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="flex w-full mt-5 gap-2">
            <div class="overflow-x-auto" :class="{
                'w-full': !fileUrl,
                'w-[50%]': fileUrl,
            }">
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
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Fecha de Vencimiento
                                </th>
                                <th v-permission-or="['see_document_hr', 'download_document_hr', 'edit_document_hr', 'delete_document_hr']"
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="document in dataToRender" :key="document.id">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
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
                                <td 
                                    class="px-6 py-4 max-w-[150px] text-sm text-gray-700"
                                >
                                    {{ formattedDate(document.exp_date) }}
                                </td>
                                <td v-permission-or="['see_document_hr', 'download_document_hr', 'edit_document_hr', 'delete_document_hr']" class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <button v-permission="'see_document_hr'" v-if="
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
                                        <button v-permission="'download_document_hr'" @click="
                                            downloadDocument(document.id)
                                            ">
                                            <DownloadIcon />
                                        </button>
                                        <button v-permission="'edit_document_hr'" @click="
                                            openEditDocumentModal(document)
                                            ">
                                            <EditIcon />
                                        </button>
                                        <button v-permission="'delete_document_hr'" @click="
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
            <div v-if="fileUrl" class="w-[50%] h-[940px]">
                <iframe :src="fileUrl" class="w-full h-full border rounded" frameborder="0"></iframe>
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
import { ref, computed, nextTick, watchEffect, reactive, watch } from "vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import { Toaster } from "vue-sonner";
import { EditIcon, DeleteIcon, ShowIcon, DownloadIcon, MenuIcon } from "@/Components/Icons/index";
import { notify, notifyError } from "@/Components/Notification";
import { formattedDate } from "@/utils/utils";

const props = defineProps({
    sections: Object,
    subdivisions: Object,
    employees: Array,
    e_employees: Array
});

const mergedEmployeesRaw = computed(() => {
    return [
        ...props.employees.map((e) => ({
            id: e.id,
            name: `${e.name} ${e.lastname}`,
            type: "normal",
        })),
        ...props.e_employees.map((e) => ({
            id: e.id,
            name: `${e.name} ${e.lastname}`,
            type: "external",
        })),
    ];
});

const mergedEmployees = ref([]);

watchEffect(() => {
    mergedEmployees.value = mergedEmployeesRaw.value;
});

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
const selectedSection = ref("");
const showEditModal = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);
const editingDocument = ref(null);

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
    editingDocument.value = JSON.parse(JSON.stringify(document));
    form.id = editingDocument.value.id;
    form.document = editingDocument.value.name;
    form.section_id = editingDocument.value.subdivision.section_id;
    form.subdivision_id = editingDocument.value.subdivision_id;
    form.employee_id = editingDocument.value.employee_id;
    form.e_employee_id = editingDocument.value.e_employee_id;
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
            closeModal();
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
    console.log(form)
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
watch(()=>form.has_exp_date, ()=>{
  if(!form.has_exp_date) form.exp_date = ''
})

//new_test_filter
const filterForm = reactive({
    search: "",
    sections: [],
    subdivisions: [],
    employees: [],
    external_employees: [],
});

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

const expandedSections = ref([]);
const isFetching = ref(false);

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
        filterForm.sections.push(sectionId);
        section.subdivisions.forEach((sub) => {
            if (!filterForm.subdivisions.includes(sub.id)) {
                filterForm.subdivisions.push(sub.id);
            }
        });
        if (!expandedSections.value.includes(sectionId)) {
            expandedSections.value.push(sectionId);
        }
    }
}

function handleSubdivisionToggle(sectionId, subId) {
    const isChecked = filterForm.subdivisions.includes(subId);

    if (isChecked) {
        filterForm.subdivisions = filterForm.subdivisions.filter(
            (id) => id !== subId
        );

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
        filterForm.subdivisions.push(subId);

        if (!filterForm.sections.includes(sectionId)) {
            filterForm.sections.push(sectionId);
        }

        if (!expandedSections.value.includes(sectionId)) {
            expandedSections.value.push(sectionId);
        }
    }
}

const activatedFilter = ref(false);
const dataToRender = ref([]);

async function applyFilters() {
    isFetching.value = true;
    const form = useForm({
        employees: filterForm.employees,
        external_employees: filterForm.external_employees,
        subdivisions: filterForm.subdivisions,
    });
    try {
        const res = await axios.post(route("documents.filter_document"), form);
        dataToRender.value = res.data;
        isFetching.value = false;
        activatedFilter.value = true;
    } catch (error) {
        isFetching.value = false;
        notifyError("Server Error");
        console.error(error);
    }
}

const getDocumentName = (documentTitle) => {
    const parts = documentTitle.split("_");
    return parts.length > 1 ? parts.slice(1).join("_") : documentTitle;
};

function handleEmployeeToggle(employee) {
    if (employee.type === "external") {
        const index = filterForm.external_employees.indexOf(employee.id);
        if (index > -1) {
            filterForm.external_employees = [
                ...filterForm.external_employees.slice(0, index),
                ...filterForm.external_employees.slice(index + 1),
            ];
        } else {
            filterForm.external_employees = [
                ...filterForm.external_employees,
                employee.id,
            ];
        }
    } else {
        const index = filterForm.employees.indexOf(employee.id);
        if (index > -1) {
            filterForm.employees = [
                ...filterForm.employees.slice(0, index),
                ...filterForm.employees.slice(index + 1),
            ];
        } else {
            filterForm.employees = [...filterForm.employees, employee.id];
        }
    }
}

//search
const matchedIndexes = ref([]);
const currentMatchIndex = ref(0);

const handleSearchInput = (event) => {
    filterForm.search = event.target.value;
    currentMatchIndex.value = 0;
    findMatches();
};

const findMatches = () => {
    matchedIndexes.value = [];

    const keyword = filterForm.search.toLowerCase().trim();
    if (!keyword) return;

    mergedEmployees.value.forEach((emp, index) => {
        const fullName = emp.name.toLowerCase();
        if (fullName.includes(keyword)) {
            matchedIndexes.value.push(index);
        }
    });

    if (matchedIndexes.value.length > 0) {
        scrollToMatch(0);
    }
};

const highlightMatch = (text) => {
    const keyword = filterForm.search.trim();
    if (!keyword) return text;

    const regex = new RegExp(`(${keyword})`, "ig");
    return text.replace(regex, '<mark class="bg-yellow-300">$1</mark>');
};

const goToNextMatch = () => {
    if (!matchedIndexes.value.length) return;

    currentMatchIndex.value =
        (currentMatchIndex.value + 1) % matchedIndexes.value.length;

    scrollToMatch(currentMatchIndex.value);
};

const scrollToMatch = (index) => {
    nextTick(() => {
        const el = document.getElementById(
            `employee-match-${matchedIndexes.value[index]}`
        );
        if (el) {
            el.scrollIntoView({ behavior: "smooth", block: "center" });
        }
    });
};

const isAllSubSelected = computed(() => {
    return filterForm.sections.length > 0 || filterForm.subdivisions.length > 0;
});

const toggleSelectAll = () => {
    if (isAllSubSelected.value) {
        filterForm.sections = [];
        filterForm.subdivisions = [];
    } else {
        filterForm.sections = props.sections.map((section) => section.id);
        filterForm.subdivisions = props.sections.flatMap((section) =>
            section.subdivisions.map((subdivision) => subdivision.id)
        );
    }
};

const isAllEmpSelected = computed(() => {
    return (
        filterForm.employees.length > 0 ||
        filterForm.external_employees.length > 0
    );
});

const toggleEmpSelectAll = () => {
    if (isAllEmpSelected.value) {
        filterForm.employees = [];
        filterForm.external_employees = [];
    } else {
        filterForm.employees = props.employees.map((employee) => employee.id);
        filterForm.external_employees = props.e_employees.map(
            (external_employee) => external_employee.id
        );
    }
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
            onSuccess: () => {
                closeModalDoc();
                notify('Documento eliminado')
                setTimeout(() => {
                    router.visit(route("documents.index"));
                }, 2000);
            },
        });
    }
};

function downloadDocument(documentId) {
    const backendDocumentUrl = route("documents.download", {
        document: documentId,
    });
    window.open(backendDocumentUrl, "_blank");
}

const fileUrl = ref(null);

function openPreviewDocumentModal(documentId) {
    fileUrl.value = route("documents.show", { document: documentId });
}

async function massiveZip() {
    const ids = dataToRender.value.map((document) => document.id);
    const url = route("documents.filter_document.massive_zip");

    try {
        const response = await axios.post(url, { ids }, { responseType: "blob" });

        // Crear blob y URL
        const blob = new Blob([response.data], { type: 'application/zip' });
        const downloadUrl = window.URL.createObjectURL(blob);

        // Extraer el nombre del archivo desde headers (si viene)
        const disposition = response.headers['content-disposition'];
        let fileName = "download.zip";
        if (disposition && disposition.indexOf('filename=') !== -1) {
            const matches = disposition.match(/filename="?(.+)"?/);
            if (matches.length === 2) fileName = matches[1];
        }

        // Crear elemento <a> y forzar descarga
        const link = document.createElement('a');
        link.href = downloadUrl;
        link.setAttribute('download', fileName);
        document.body.appendChild(link);
        link.click();

        // Limpiar
        link.remove();
        window.URL.revokeObjectURL(downloadUrl);

    } catch (error) {
        console.error(error);
    }
}

</script>
