<template>
    <Head title="4ta Categoría" />
    <Toaster richColors />
    <AuthenticatedLayout
        :redirectRoute="{
            route: 'spreadsheets.index',
            params: { payroll_id },
        }"
    >
        <template #header> PS 4ta Categoría </template>
        <div class="flex justify-between items-center gap-4">
            <button
                @click="openPEDModal"
                type="button"
                class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500"
            >
                + Agregar
            </button>
        </div>
        <TableStructure>
            <template #thead>
                <TableTitle>Tipo Doc</TableTitle>
                <TableTitle>Número Doc</TableTitle>
                <TableTitle>Apellidos y Nombres</TableTitle>
                <TableTitle>Monto</TableTitle>
                <TableTitle>Ret Imp 4ta cat</TableTitle>
                <TableTitle></TableTitle>
            </template>
            <template #tbody>
                <tr v-for="item in dataToRender" :key="item.id">
                    <TableRow>{{ item.doc_type }}</TableRow>
                    <TableRow>{{ item.doc_number }}</TableRow>
                    <TableRow
                        >{{ item.external_employee?.lastname }}
                        {{ item.external_employee?.name }}</TableRow
                    >
                    <TableRow>{{ item.amount }}</TableRow>
                    <TableRow>{{ item.ret_tax }}</TableRow>
                    <TableRow>
                        <div class="flex space-x-3 justify-center">
                            <button type="button" @click="openPEDModal(item)">
                                <PencilSquareIcon
                                    class="w-6 h-6 text-yellow-400"
                                />
                            </button>
                            <button type="button" @click="deletePED(item.id)">
                                <TrashIcon class="w-6 h-6 text-red-400" />
                            </button>
                        </div>
                    </TableRow>
                </tr>
            </template>
        </TableStructure>

        <Modal :show="showPEDModal" @close="closePEDModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900 mb-2">
                    {{ form.id ? "Editar" : "Agregar" }}
                </h2>
                <form @submit.prevent="submitPED">
                    <div class="space-y-12">
                        <div
                            class="border-b grid grid-cols-1 gap-6 border-gray-900/10 pb-12"
                        >
                            <div>
                                <InputLabel
                                    class="font-medium leading-6 text-gray-900"
                                >
                                    Colaborador Externo
                                </InputLabel>
                                <div class="mt-2">
                                    <select
                                        v-model="form.external_employee_id"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    >
                                        <option disabled value="">
                                            Selecciona
                                        </option>
                                        <option
                                            :value="op.id"
                                            v-for="op in external_employees"
                                        >
                                            {{ op.name }} {{ op.lastname }}
                                        </option>
                                    </select>
                                    <InputError
                                        :message="
                                            form.errors.external_employee_id
                                        "
                                    />
                                </div>
                            </div>
                            <div>
                                <InputLabel
                                    class="font-medium leading-6 text-gray-900"
                                >
                                    Tipo de Documento
                                </InputLabel>
                                <div class="mt-2">
                                    <select
                                        v-model="form.doc_type"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    >
                                        <option disabled value="">
                                            Selecciona
                                        </option>
                                        <option v-for="op in ['RUC', 'DNI']">
                                            {{ op }}
                                        </option>
                                    </select>
                                    <InputError
                                        :message="form.errors.doc_type"
                                    />
                                </div>
                            </div>
                            <div>
                                <InputLabel
                                    class="font-medium leading-6 text-gray-900"
                                >
                                    Número de documento
                                </InputLabel>
                                <div class="mt-2">
                                    <input
                                        v-model="form.doc_number"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError
                                        :message="form.errors.doc_number"
                                    />
                                </div>
                            </div>
                            <div>
                                <InputLabel
                                    class="font-medium leading-6 text-gray-900"
                                >
                                    Monto
                                </InputLabel>
                                <div class="mt-2">
                                    <input
                                        type="number"
                                        min="0"
                                        v-model="form.amount"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError :message="form.errors.amount" />
                                </div>
                            </div>
                            <div>
                                <InputLabel
                                    class="font-medium leading-6 text-gray-900"
                                >
                                    Ret. Imp. Rta. 4ta. Categoría
                                </InputLabel>
                                <div class="mt-2">
                                    <input
                                        type="number"
                                        min="0"
                                        v-model="form.ret_tax"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError
                                        :message="form.errors.ret_tax"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closePEDModal">
                                Cancelar
                            </SecondaryButton>
                            <button
                                type="submit"
                                :disabled="isFetching"
                                :class="{ 'opacity-25': isFetching }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            >
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import TableTitle from "@/Components/TableTitle.vue";
import TableRow from "@/Components/TableRow.vue";
import TableStructure from "@/Layouts/TableStructure.vue";
import { Toaster } from "vue-sonner";
import { notify, notifyError, notifyWarning } from "@/Components/Notification";
import { ref } from "vue";
import { PencilSquareIcon, TrashIcon } from "@heroicons/vue/24/outline";
import { formattedDate, setAxiosErrors, toFormData } from "@/utils/utils";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";

const { payroll_external_details, external_employees, payroll_id } =
    defineProps({
        payroll_external_details: Array,
        external_employees: Array,
        payroll_id: Number,
    });

const isFetching = ref(false);
const dataToRender = ref([...payroll_external_details]);
const showPEDModal = ref(false);
const initState = {
    id: "",
    payroll_id: payroll_id,
    external_employee_id: "",
    doc_type: "",
    doc_number: "",
    amount: "",
    ret_tax: 0,
}
const form = useForm({...initState});

const openPEDModal = (item = null) => {
    showPEDModal.value = true;
    if (item) {
        form.defaults({ ...item });
        form.reset();
    }
};

const closePEDModal = () => {
    showPEDModal.value = false;
    form.clearErrors();
    form.defaults({ ...initState });
    form.reset();
};

const submitPED = () => {
    isFetching.value = true;
    const formToSend = form.data();
    let url = route("payroll.store.payroll.external.detail");
    axios
        .post(url, formToSend)
        .then((res) => {
            if (form.id) {
                const index = dataToRender.value.findIndex(
                    (item) => item.id == form.id
                );
                dataToRender.value[index] = res.data;
            } else {
                dataToRender.value.push(res.data);
            }
            closePEDModal();
            notify("Registro Guardado");
        })
        .catch((e) => {
            console.log(e)
            if (e.response?.data?.errors) {
                setAxiosErrors(e.response.data.errors, form);
            } else {
                notifyError("Server Error");
            }
        })
        .finally(() => {
            isFetching.value = false;
        });
};

const deletePED = (id) => {
    isFetching.value = true;
    axios
        .delete(route("payroll.store.payroll.external.destroy", { payroll_detail_id: id }))
        .then((res) => {
            const index = dataToRender.value.findIndex((item) => item.id == id);
            dataToRender.value.splice(index, 1);
            notify("Registro Eliminado");
        })
        .catch((e) => {
            notifyError("Server Error");
        })
        .finally(() => {
            isFetching.value = false;
        });
};
</script>
