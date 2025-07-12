<template>
    <Modal :show="show && showEditForm" @close="handleClose" :closeable="true">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                Agregar Gasto
            </h2>
            <form @submit.prevent="form.id ? submit(true) : submit(false)">
                <div class="space-y-12 mt-4">
                    <div class="grid sm:grid-cols-2 gap-6 pb-6">
                        <div
                            class="md:col-span-2 col-span-1 p-4 border border-black rounded-lg"
                        >
                            <div class="grid sm:grid-cols-4 gap-6">
                                <div class="sm:col-span-2">
                                    <InputLabel
                                        for="refund_status"
                                        class="font-medium leading-6 text-gray-900"
                                        >Macroproyecto</InputLabel
                                    >
                                    <div class="mt-2">
                                        <select
                                            v-model="selectedMacro"
                                            @change="fetchSites"
                                            id="refund_status"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        >
                                            <option disabled value="">
                                                Seleccionar Macroproyecto
                                            </option>
                                            <option
                                                v-for="macro in macroProjects"
                                                :value="macro"
                                            >
                                                {{ macro }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-2" v-if="selectedMacro">
                                    <InputLabel
                                        for="refund_status"
                                        class="font-medium leading-6 text-gray-900"
                                        >Site</InputLabel
                                    >
                                    <div class="mt-2">
                                        <select
                                            v-model="selectedSite"
                                            @change="fetchProjects"
                                            id="refund_status"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        >
                                            <option disabled value="">
                                                Seleccionar site
                                            </option>
                                            <option
                                                v-for="site in sites"
                                                :key="site.id"
                                                :value="site.id"
                                            >
                                                {{ site.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="sm:col-span-4" v-if="selectedSite">
                                    <InputLabel
                                        for="huawei_project_id"
                                        class="font-medium leading-6 text-gray-900"
                                        >Proyecto</InputLabel
                                    >
                                    <div class="mt-2">
                                        <select
                                            v-model="form.huawei_project_id"
                                            id="huawei_project_id"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        >
                                            <option disabled value="">
                                                Seleccionar Proyecto
                                            </option>
                                            <option
                                                v-for="project in projects"
                                                :key="project.id"
                                                :value="project.id"
                                            >
                                                {{ project.assigned_diu }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <InputLabel
                                for="expense_type"
                                class="font-medium leading-6 text-gray-900"
                                >Tipo de Gasto
                            </InputLabel>
                            <div class="mt-2">
                                <select
                                    v-model="form.expense_type"
                                    id="expense_type"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                >
                                    <option disabled value="">
                                        Seleccionar Gasto
                                    </option>
                                    <option v-for="op in expenseTypes">
                                        {{ op }}
                                    </option>
                                </select>
                                <InputError
                                    :message="form.errors.expense_type"
                                />
                            </div>
                        </div>

                        <div>
                            <InputLabel
                                for="employee"
                                class="font-medium leading-6 text-gray-900"
                                >Empleado
                            </InputLabel>
                            <div class="mt-2">
                                <select
                                    v-model="form.employee"
                                    id="employee"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                >
                                    <option disabled value="">
                                        Seleccionar Empleado
                                    </option>
                                    <option v-for="emp in employees">
                                        {{ emp }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.employee" />
                            </div>
                        </div>
                        <div>
                            <InputLabel
                                for="cdp_type"
                                class="font-medium leading-6 text-gray-900"
                                >Tipo de Documento
                            </InputLabel>
                            <div class="mt-2">
                                <select
                                    v-model="form.cdp_type"
                                    id="cdp_type"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                >
                                    <option disabled value="">
                                        Seleccionar Tipo de Documento
                                    </option>
                                    <option v-for="cdp in cdpTypes">
                                        {{ cdp }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.cdp_type" />
                            </div>
                        </div>

                        <div>
                            <InputLabel
                                for="expense_date"
                                class="font-medium leading-6 text-gray-900"
                                >Fecha de Gasto
                            </InputLabel>
                            <div class="mt-2">
                                <input
                                    type="date"
                                    v-model="form.expense_date"
                                    id="expense_date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                />
                                <InputError
                                    :message="form.errors.expense_date"
                                />
                            </div>
                        </div>

                        <div>
                            <InputLabel
                                for="doc_number"
                                class="font-medium leading-6 text-gray-900"
                                >Número de Serie
                            </InputLabel>
                            <div class="mt-2">
                                <input
                                    type="text"
                                    v-model="form.doc_number"
                                    id="doc_number"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                />
                                <InputError :message="form.errors.doc_number" />
                            </div>
                        </div>

                        <div>
                            <InputLabel
                                for="op_number"
                                class="font-medium leading-6 text-gray-900"
                                >Correlativo
                            </InputLabel>
                            <div class="mt-2">
                                <input
                                    type="text"
                                    v-model="form.op_number"
                                    id="op_number"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                />
                                <InputError :message="form.errors.op_number" />
                            </div>
                        </div>

                        <div>
                            <InputLabel
                                for="ruc"
                                class="font-medium leading-6 text-gray-900"
                                >RUC
                            </InputLabel>
                            <div class="mt-2">
                                <input
                                    type="text"
                                    maxlength="11"
                                    v-model="form.ruc"
                                    id="ruc"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                />
                                <InputError :message="form.errors.ruc" />
                            </div>
                        </div>

                        <div>
                            <InputLabel
                                for="description"
                                class="font-medium leading-6 text-gray-900"
                                >Descripción
                            </InputLabel>
                            <div class="mt-2">
                                <textarea
                                    type="text"
                                    v-model="form.description"
                                    id="description"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                />
                                <InputError
                                    :message="form.errors.description"
                                />
                            </div>
                        </div>

                        <div>
                            <InputLabel
                                for="amount"
                                class="font-medium leading-6 text-gray-900"
                                >Monto</InputLabel
                            >
                            <div class="mt-2">
                                <input
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    v-model="form.amount"
                                    id="amount"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                />
                                <InputError :message="form.errors.amount" />
                            </div>
                        </div>

                        <div>
                            <InputLabel
                                for="ec_expense_date"
                                class="font-medium leading-6 text-gray-900"
                                >Fecha de Depósito de E.C.</InputLabel
                            >
                            <div class="mt-2">
                                <input
                                    type="date"
                                    v-model="form.ec_expense_date"
                                    id="ec_expense_date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                />
                                <InputError
                                    :message="form.errors.ec_expense_date"
                                />
                            </div>
                        </div>

                        <div>
                            <InputLabel
                                for="ec_op_number"
                                class="font-medium leading-6 text-gray-900"
                                >N° de Operación de E.C.</InputLabel
                            >
                            <div class="mt-2">
                                <input
                                    type="text"
                                    v-model="form.ec_op_number"
                                    id="ec_op_number"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                />
                                <InputError
                                    :message="form.errors.ec_op_number"
                                />
                            </div>
                        </div>

                        <div>
                            <InputLabel
                                for="ec_amount"
                                class="font-medium leading-6 text-gray-900"
                                >Monto de E.C.</InputLabel
                            >
                            <div class="mt-2">
                                <input
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    v-model="form.ec_amount"
                                    id="ec_amount"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                />
                                <InputError :message="form.errors.ec_amount" />
                            </div>
                        </div>

                        <div>
                            <InputLabel
                                class="font-medium leading-6 text-gray-900"
                            >
                                Imagen
                            </InputLabel>
                            <div class="mt-2">
                                <InputFile
                                    type="file"
                                    v-model="form.image"
                                    accept=".jpeg, .jpg, .png, .pdf"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                />
                                <InputError :message="form.errors.image" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <SecondaryButton @click="handleClose">
                            Cancelar
                        </SecondaryButton>
                        <button
                            type="submit"
                            :disabled="form.processing || isFetching"
                            :class="{ 'opacity-25': form.processing }"
                            class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                        >
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </Modal>
</template>

<script setup>
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import InputFile from "@/Components/InputFile.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useForm, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { setAxiosErrors, toFormData } from "@/utils/utils";
import axios from "axios";

const {
    show,
    close,
    editForm,
    macroProjects,
    expenseTypes,
    cdpTypes,
    employees,
} = defineProps({
    show: Boolean,
    close: Function,
    editForm: Object,
    macroProjects: Array,
    expenseTypes: Array,
    cdpTypes: Array,
    employees: Array,
});

const emit = defineEmits(["update"]);

const isFetching = ref(false);
const selectedMacro = ref("");
const selectedSite = ref("");
const sites = ref([]);
const projects = ref([]);
const showEditForm = ref(true);

const form = useForm({
    id: "",
    expense_type: "",
    zone: "",
    employee: "",
    expense_date: "",
    cdp_type: "",
    doc_number: "",
    op_number: "",
    ruc: "",
    description: "",
    amount: "",
    image: "",
    ec_expense_date: "",
    ec_op_number: "",
    ec_amount: "",
    huawei_project_id: "",
});

const fetchSites = async (macro) => {
    selectedSite.value = "";
    form.huawei_project_id = "";
    return axios
        .get(
            route("huawei.projects.general.expenses.fetchsites", {
                macro: macro.target.value,
            })
        )
        .then((response) => {
            sites.value = response.data;
        })
        .catch((error) => {
            console.error(error);
        });
};

const fetchProjects = async (site) => {
    form.huawei_project_id = "";
    return axios
        .get(
            route("huawei.projects.general.expenses.fetchprojects", {
                macro: selectedMacro.value,
                site_id: site.target.value,
            })
        )
        .then((response) => {
            projects.value = response.data;
        })
        .catch((error) => {
            console.error(error);
        });
};

watch(
    () => editForm,
    async (newVal) => {
        showEditForm.value = false;
        if (newVal && Object.keys(newVal).length > 0) {
            form.id = newVal.id ?? "";
            form.expense_type = newVal.expense_type ?? "";
            form.zone = newVal.zone ?? "";
            form.employee = newVal.employee ?? "";
            form.expense_date = newVal.expense_date ?? "";
            form.cdp_type = newVal.cdp_type ?? "";
            form.doc_number = newVal.doc_number ?? "";
            form.op_number = newVal.op_number ?? "";
            form.ruc = newVal.ruc ?? "";
            form.description = newVal.description ?? "";
            form.amount = newVal.amount ?? "";
            form.image = newVal.image ?? "";
            form.ec_expense_date = newVal.ec_expense_date ?? "";
            form.ec_op_number = newVal.ec_op_number ?? "";
            form.ec_amount = newVal.ec_amount ?? "";

            const macro = newVal.huawei_project?.macro_project;
            const siteId = newVal.huawei_project?.huawei_site_id;

            if (newVal.huawei_project_id && macro && siteId) {
                selectedMacro.value = macro;
                await fetchSites({ target: { value: macro } });
                selectedSite.value = siteId;
                await fetchProjects({ target: { value: siteId } });
                form.huawei_project_id = newVal.huawei_project_id ?? "";
            }
            showEditForm.value = true;
        } else {
            showEditForm.value = true;
        }
    },
    { deep: true, immediate: true }
);

function handleClose() {
    selectedMacro.value = "";
    selectedSite.value = "";
    sites.value = [];
    projects.value = [];
    isFetching.value = false;
    form.clearErrors();
    form.reset();
    close();
}

async function submit(mode) {
    isFetching.value = true;
    const url = mode
        ? route("huawei.projects.general.expenses.update", { expense: form.id })
        : route("huawei.projects.general.expenses.store");

    const formData = toFormData(form);

    try {
        const res = await axios.post(url, formData, {
            headers: { "Content-Type": "multipart/form-data" },
        });
        emit("update", { expense: res.data, mode: mode });
        isFetching.value = false;
    } catch (e) {
        console.error(e);
        isFetching.value = false;
        if (e.response?.data?.errors) {
            setAxiosErrors(e.response.data.errors, form);
        } else {
            emit("update", "error");
        }
    }
}
</script>
