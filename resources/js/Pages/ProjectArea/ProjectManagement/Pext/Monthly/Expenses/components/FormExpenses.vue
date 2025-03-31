<template>
    <Modal :show="create_additional" @close="closeModal">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                {{ form.id ? "Actualizar" : "Agregar" }} Gasto
            </h2>
            <form @submit.prevent="submit">
                <div class="space-y-12 mt-4">
                    <div class="grid sm:grid-cols-2 gap-6 pb-6">
                        <div>
                            <InputLabel for="zone" class="font-medium leading-6 text-gray-900">Zona</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.zone" id="zone"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">
                                        Seleccionar Zona
                                    </option>
                                    <option v-for="op in zones">{{ op }}</option>
                                </select>
                                <InputError :message="form.errors.zone" />
                            </div>
                        </div>
                        <div>
                            <InputLabel for="expense_type" class="font-medium leading-6 text-gray-900">Tipo de Gasto
                            </InputLabel>
                            <div class="mt-2">
                                <select v-model="form.expense_type" id="expense_type"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">
                                        Seleccionar Gasto
                                    </option>
                                    <option v-for="op in expenseTypes">{{ op }}</option>
                                </select>
                                <InputError :message="form.errors.expense_type" />
                            </div>
                        </div>
                        <div>
                            <InputLabel for="type_doc" class="font-medium leading-6 text-gray-900">Tipo de Documento
                            </InputLabel>
                            <div class="mt-2">
                                <select v-model="form.type_doc" id="type_doc"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">
                                        Seleccionar Documento
                                    </option>
                                    <option v-for="op in docTypes">{{ op }}</option>
                                </select>
                                <InputError :message="form.errors.type_doc" />
                            </div>
                        </div>
                        <div>
                            <InputLabel for="ruc" class="font-medium leading-6 text-gray-900">RUC / DNI
                            </InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.ruc" id="ruc" maxlength="11"
                                    @input="handleRucDniAutocomplete" autocomplete="off" list="options"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <datalist id="options">
                                    <option v-for="item in providers" :value="item.ruc">
                                        {{ item.company_name }}
                                    </option>
                                </datalist>
                                <InputError :message="form.errors.ruc" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="operation_number" class="font-medium leading-6 text-gray-900">Numero de
                                Operacion
                            </InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.operation_number" id="operation_number"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.operation_number" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="operation_date" class="font-medium leading-6 text-gray-900">Fecha de
                                Operacion
                            </InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.operation_date" id="operation_date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.operation_date" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="doc_number" class="font-medium leading-6 text-gray-900">Numero de
                                Documento
                            </InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.doc_number" id="doc_number"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.doc_number" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="doc_date" class="font-medium leading-6 text-gray-900">Fecha de
                                Documento
                            </InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.doc_date" id="doc_date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.doc_date" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="amount" class="font-medium leading-6 text-gray-900">Monto</InputLabel>
                            <div class="mt-2">
                                <input type="number" step="0.0001" v-model="form.amount" id="amount"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.amount" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="igv" class="font-medium leading-6 text-gray-900">
                                IGV (%)
                            </InputLabel>
                            <div class="mt-2">
                                <div class="flex gap-3 items-center">
                                    <input type="number" step="1" max="100" v-model="form.igv" id="igv"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />%
                                </div>
                                <InputError :message="form.errors.igv" />
                            </div>
                        </div>
                        <div>
                            <InputLabel for="amount" class="font-medium leading-6 text-gray-900">Monto sin IGV
                            </InputLabel>
                            <div class="mt-2">
                                <InputLabel for="amount" class="font-medium leading-6 text-gray-900">{{
                                    form.amount
                                        ? (
                                            form.amount /
                                            (1 + form.igv / 100)
                                        ).toFixed(4)
                                        : 0
                                }}</InputLabel>
                            </div>
                        </div>
                        <div>
                            <InputLabel for="description" class="font-medium leading-6 text-gray-900">Descripción
                            </InputLabel>
                            <div class="mt-2">
                                <textarea type="text" v-model="form.description" id="description"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.description" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel class="font-medium leading-6 text-gray-900">
                                Archivo
                            </InputLabel>
                            <div class="mt-2">
                                <InputFile type="file" v-model="form.photo" accept=".jpeg, .jpg, .png"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.photo" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <SecondaryButton @click="closeModal">
                            Cancelar
                        </SecondaryButton>
                        <button type="submit" :disabled="form.processing" :class="{ 'opacity-25': form.processing }"
                            class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </Modal>
</template>
<script setup>
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import { ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import InputFile from "@/Components/InputFile.vue";
import axios from "axios";
import { setAxiosErrors, toFormData } from "@/utils/utils";
import { notify, notifyError } from "@/Components/Notification";

const { expenses, fixedOrAdditional, project_id, zones, docTypes, expenseTypes, providers } = defineProps({
    expenses: Object,
    fixedOrAdditional: Boolean,
    project_id: String,
    zones: Array,
    docTypes: Array,
    expenseTypes: Array,
    providers: Object
})
const create_additional = ref(false);
const pext_project_zone = ref("");

const form = useForm({
    id: "",
    fixedOrAdditional: fixedOrAdditional,
    expense_type: "",
    ruc: "",
    zone: "",
    provider_id: "",
    project_id: project_id,
    type_doc: "",
    operation_number: "",
    operation_date: "",
    doc_number: "",
    doc_date: "",
    description: "",
    photo: "",
    // state: props.fixedOrAdditional ? true : false,
    is_accepted: true,
    amount: "",
    igv: 0,
});

const openCreateAdditionalModal = () => {
    create_additional.value = true;
};

const openEditAdditionalModal = (additional) => {
    Object.assign(form, additional)
    pext_project_zone.value = additional.zone
    form.pext_project_name = additional.cicsa_assignation?.project_name
    create_additional.value = true;
};

const closeModal = () => {
    form.clearErrors()
    form.reset();
    create_additional.value = false;
};

watch([() => form.type_doc, () => form.zone], () => {
    if (
        form.type_doc === "Factura" &&
        !["", "MDD"].includes(form.zone)
    ) {
        form.igv = form.igv ? form.igv : 18;
    } else {
        form.igv = 0;
    }
});

async function submit() {
    const url = route('pext.expenses.storeOrUpdate', { 'expense_id': form.id ?? null })
    try {
        const formData = toFormData(form)
        const response = await axios.post(url, formData);
        const action = form.id ? "update" : "create"
        updateExpense(response.data, action)
        closeModal();
    } catch (error) {
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, form)
            } else {
                notifyError('Server Error', error.response.data)
            }
        } else {
            notifyError('Server Error')
        }
    }
};

const handleRucDniAutocomplete = (e) => {
    let ruc = e.target.value;
    let findProv = props.providers.find((item) => item.ruc == ruc);
    if (findProv) {
        form.provider_id = findProv.id;
    } else {
        form.provider_id = "";
    }
};

function updateExpense(expense, action) {
    let listDate = expenses.data || expenses
    if (action === "create") {
        listDate.unshift(expense)
        notify('Gasto Creado')
    } else if (action === "update") {
        let index = listDate.findIndex(item => item.id == expense.id)
        listDate[index] = expense
        notify('Gasto Actualizado')
    }
}

defineExpose({ openCreateAdditionalModal, openEditAdditionalModal })
</script>