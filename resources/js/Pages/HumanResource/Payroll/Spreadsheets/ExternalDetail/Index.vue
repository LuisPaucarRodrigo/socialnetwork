<template>
    <Head title="4ta Categoría" />
    <Toaster richColors />
    <AuthenticatedLayout :redirectRoute="{
        route: 'spreadsheets.index',
        params: { payroll_id },
    }">
        <template #header> PS 4ta Categoría </template>
        <div class="flex justify-between items-center gap-4">
            <button @click="openPEDModal" type="button"
                class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                + Agregar
            </button>
        </div>
        <TableStructure :info="dataToRender">
            <template #thead>
                <TableTitle>Tipo Doc</TableTitle>
                <TableTitle>Número Doc</TableTitle>
                <TableTitle>Apellidos y Nombres</TableTitle>
                <TableTitle :style="'text-right bg-gray-100'">Monto</TableTitle>
                <TableTitle :style="'text-right bg-gray-100'">Ret Imp 4ta cat</TableTitle>
                <TableTitle></TableTitle>
            </template>
            <template #tbody>
                <tr v-for="item in dataToRender" :key="item.id">
                    <TableRow>{{ item.doc_type }}</TableRow>
                    <TableRow>{{ item.doc_number }}</TableRow>
                    <TableRow>{{ item.lastname }} {{ item.name }}</TableRow>
                    <TableRow :style="'tabular-nums text-right'">S/. {{ item.amount.toFixed(2) }}</TableRow>
                    <TableRow :style="'tabular-nums text-right'">
                        S/. {{ item.ret_tax.toFixed(2) }}
                    </TableRow>
                    <TableRow>
                        <div class="flex space-x-3 justify-center">
                            <button type="button" @click="openPEDModal(item)">
                                <EditIcon />
                            </button>
                            <button type="button" @click="deletePED(item.id)">
                                <DeleteIcon />
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
                        <div class="border-b grid grid-cols-1 gap-6 border-gray-900/10 pb-12">
                            <div>
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    Colaborador Externo
                                </InputLabel>
                                <div class="mt-2">
                                    <select v-model="external_employee"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">
                                            Selecciona
                                        </option>
                                        <option>Otro</option>
                                        <option :value="op" v-for="op in external_employees">
                                            {{ op.name }} {{ op.lastname }}
                                        </option>
                                    </select>

                                    <div class="grid grid-cols-2 gap-4 mt-4">
                                        <div>
                                            <input v-model="form.name" placeholder="nombres"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                            <InputError :message="form.errors.name" />
                                        </div>
                                        <div>
                                            <input v-model="form.lastname" placeholder="apellidos"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                            <InputError :message="form.errors.lastname" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    Tipo de Documento
                                </InputLabel>
                                <div class="mt-2">
                                    <select v-model="form.doc_type"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">
                                            Selecciona
                                        </option>
                                        <option v-for="op in ['RUC', 'DNI']">
                                            {{ op }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.doc_type" />
                                </div>
                            </div>

                            <div>
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    Número de documento
                                </InputLabel>
                                <div class="mt-2">
                                    <input v-model="form.doc_number"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.doc_number" />
                                </div>
                            </div>
                            <div>
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    Monto
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="number" min="0" v-model="form.amount"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.amount" />
                                </div>
                            </div>
                            <div>
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    Ret. Imp. Rta. 4ta. Categoría
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="number" min="0" v-model="form.ret_tax"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.ret_tax" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closePEDModal">
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
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import TableTitle from "@/Components/TableTitle.vue";
import TableRow from "@/Components/TableRow.vue";
import TableStructure from "@/Layouts/TableStructure.vue";
import { Toaster } from "vue-sonner";
import { notify, notifyError } from "@/Components/Notification";
import { ref, watch } from "vue";
import { setAxiosErrors } from "@/utils/utils";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
import { DeleteIcon, EditIcon } from "@/Components/Icons";

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
    name: "",
    lastname: "",
    doc_type: "",
    doc_number: "",
    amount: "",
    ret_tax: 0,
};
const form = useForm({ ...initState });

const openPEDModal = (item = null) => {
    showPEDModal.value = true;
    if (item) {
        form.defaults({ ...item });
        form.reset();
    }
};

const closePEDModal = () => {
    external_employee.value = "";
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
            console.log(e);
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
        .delete(
            route("payroll.store.payroll.external.destroy", {
                payroll_detail_id: id,
            })
        )
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

const external_employee = ref("");

watch(external_employee, (newval) => {
    if (newval === "Otro") {
        form.name = "";
        form.lastname = "";
    } else {
        form.name = newval.name;
        form.lastname = newval.lastname;
    }
});
</script>
