<template>
    <Modal :show="showAdditionalModal" @close="closeModal">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                {{ form.id ? 'Editar' : 'Crear' }} Costo Adicional
            </h2>
            <form @submit.prevent="submit">
                <div class="space-y-12">
                    <div class="border-b grid sm:grid-cols-2 gap-6 border-gray-900/10 pb-12">
                        <div>
                            <InputLabel for="zone" class="font-medium leading-6 text-gray-900">Zona</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.zone" id="zone"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">
                                        Seleccionar
                                    </option>
                                    <option v-for="op in zones">
                                        {{ op }}
                                    </option>
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
                                    <option v-for="op in expenseTypes">
                                        {{ op }}
                                    </option>
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
                                    <option v-for="op in docTypes">
                                        {{ op }}
                                    </option>
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
                                Operación
                            </InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.operation_number" id="operation_number"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.operation_number" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="operation_date" class="font-medium leading-6 text-gray-900">Fecha de
                                Operación
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
                                <textarea v-model="form.description" id="description"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.description" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel class="font-medium leading-6 text-gray-900">
                                Archivo
                            </InputLabel>
                            <div class="mt-2">
                                <InputFile type="file" v-model="form.photo" accept=".jpeg, .jpg, .png, .pdf"
                                    class="block w-full h-24 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.photo" />
                                <div v-if="
                                    form.photo_name &&
                                    form.photo_status == 'stable'
                                " class="text-sm leading-6 text-indigo-700 flex space-x-2 items-center mt-3">
                                    <span> Archivo Actual: </span>
                                    <a :href="route(
                                        'additionalcost.archive',
                                        {
                                            additional_cost_id:
                                                form.id,
                                        }
                                    )
                                        " target="_blank" class="hover:underline">
                                        {{ form.photo_name }}
                                    </a>
                                    <button type="button" @click="() => {
                                        form.photo_status =
                                            'delete';
                                    }
                                    ">
                                        <DeleteIcon />
                                    </button>
                                </div>
                                <div v-if="form.photo_status === 'delete'"
                                    class="text-amber-700 mt-3 text-sm flex space-x-2">
                                    <span>
                                        El documento esta por ser borrado,
                                    </span>
                                    <button @click="() => {
                                        form.photo_status =
                                            'stable';
                                    }
                                    " type="button" class="font-black">
                                        ANULAR
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div v-if="form.admin_state === 'Rechazado'"
                            class="sm:col-span-2 ring-1 ring-red-400 rounded-md p-2">
                            <InputLabel class="text-sm leading-6 text-red-700">
                                <span class="text-gray-700">
                                    Motivo de rechazo administrativo:
                                </span>
                                {{ form.admin_reject_reason }}
                            </InputLabel>
                            <label class="flex text-sm text-gray-700 gap-2 items-center">
                                Pedir nueva revisión
                                <input type="checkbox" v-model="form.ask_admin_review"
                                    class="focus:ring-0 outline-none" />
                            </label>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <SecondaryButton @click="closeModal">
                            Cancelar
                        </SecondaryButton>
                        <button type="submit" :disabled="isFetching" :class="{ 'opacity-25': isFetching }"
                            class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Actualizar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </Modal>
</template>
<script setup>
import { DeleteIcon } from '@/Components/Icons';
import InputError from '@/Components/InputError.vue';
import InputFile from '@/Components/InputFile.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { toFormData } from "@/utils/utils";
import { notify } from "@/Components/Notification";
import { useAxiosErrorHandler } from "@/utils/axiosError";

const { dataToRender, project_id, providers, expenseTypes, docTypes, zones } = defineProps({
    dataToRender: Object,
    project_id: Number,
    providers: Object,
    expenseTypes: Array,
    docTypes: Array,
    zones: Array,
})

const isFetching = ref(false);
const showAdditionalModal = ref(false)

const initialState = {
    id: "",
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
    amount: "",
    igv: 0,
    photo_status: "stable",
    admin_state: "",
    admin_reject_reason: "",
    ask_admin_review: false
}

const form = useForm({
    ...initialState
});

function toogleModal() {
    showAdditionalModal.value = !showAdditionalModal.value
}

function closeModal() {
    form.defaults({ ...initialState })
    form.reset()
    form.clearErrors()
    isFetching.value = false;
    toogleModal()
}

const openCreateAdditionalModal = () => {
    toogleModal()
};

const openEditAdditionalModal = (additional) => {
    form.defaults({ ...additional })
    form.reset()
    toogleModal()
};

const submit = async () => {
    try {
        isFetching.value = true;
        const formToSend = toFormData(form.data());
        const url = form.id ? route("projectmanagement.updateAdditionalCost", {
            additional_cost: form.id,
        }) : route("projectmanagement.storeAdditionalCost", {
            project_id: project_id,
        })
        const action = form.id ? 'update' : 'create'
        const res = await axios.post(url, formToSend);
        updateFrontEnd(res.data, action)
    } catch (e) {
        isFetching.value = false;
        useAxiosErrorHandler(e, form)
    }
};

function updateFrontEnd(data, action) {
    let listData = dataToRender.data || dataToRender
    console.log(listData)
    if (action === 'create') {
        listData.unshift(data);
        closeModal();
        notify("Gasto Adicional Guardado");
    } else if (action === 'update') {
        console.log(form.id)
        let index = listData.findIndex((item) => item.id == form.id);
        console.log(index)
        listData[index] = data;
        closeModal();
        notify("Gasto Adicional Actualizado");
    }
}

const handleRucDniAutocomplete = (e) => {
    let ruc = e.target.value;
    let findProv = providers.find((item) => item.ruc == ruc);
    if (findProv) {
        form.provider_id = findProv.id;
    } else {
        form.provider_id = "";
    }
};

defineExpose({ openCreateAdditionalModal, openEditAdditionalModal })
</script>