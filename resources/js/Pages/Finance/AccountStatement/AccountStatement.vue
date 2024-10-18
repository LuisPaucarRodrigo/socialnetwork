<template>
    <Head title="Estado de Cuenta" />
    <AuthenticatedLayout
        :redirectRoute="{
            route: 'finance.account_statement',
        }"
    >
        <template #header> Estado de Cuenta </template>
        <div class="inline-block min-w-full mb-4">
            <PrimaryButton
                v-if="hasPermission('FinanceManager')"
                @click="openFormModal"
                type="button"
                class="whitespace-nowrap"
            >
                + Agregar
            </PrimaryButton>
        </div>
        <Toaster richColors/>
        <div class="overflow-x-auto h-[85vh]">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="sticky top-0 z-20 border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500"
                    >
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Fecha de Operación
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Número de Operación
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Descripción
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Cargo
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Abono
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Saldo Contable
                        </th>
                        <th
                            v-if="auth.user.role_id === 1"
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-gray-700">
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            k
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            gv
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            k
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            gv
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            k
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            gv
                        </td>
                        <td
                            v-if="auth.user.role_id === 1"
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            dd
                            <!-- <div class="flex items-center">
                                <button
                                    @click="openEditAdditionalModal(item)"
                                    class="text-amber-600 hover:underline mr-2"
                                >
                                    <PencilSquareIcon class="h-4 w-4 ml-1" />
                                </button>
                                <button
                                    @click="confirmDeleteAdditional(item.id)"
                                    class="text-red-600 hover:underline"
                                >
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </div> -->
                        </td>
                    </tr>
                    <!-- <tr class="sticky bottom-0 z-10 text-gray-700">
                        <td
                            class="font-bold border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                        >
                            TOTAL
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        ></td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        ></td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        ></td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        ></td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        ></td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        ></td>
                    </tr> -->
                </tbody>
            </table>
        </div>

        <Modal :show="showFormModal" @close="closeFormModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Añadir Estado de Cuenta
                </h2>
                <form @submit.prevent="submit">
                    <div class="space-y-12 mt-4">
                        <div
                            class="border-b grid sm:grid-cols-2 gap-6 border-gray-900/10 pb-12"
                        >
                            <div>
                                <InputLabel
                                    for="operation_date"
                                    class="font-medium leading-6 text-gray-900"
                                    >Fecha de Operación
                                </InputLabel>
                                <div class="mt-2">
                                    <input
                                        type="date"
                                        v-model="form.operation_date"
                                        id="operation_date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError
                                        :message="form.errors.operation_date"
                                    />
                                </div>
                            </div>
                            <div>
                                <InputLabel
                                    for="operation_number"
                                    class="font-medium leading-6 text-gray-900"
                                    >Numero de Operación
                                </InputLabel>
                                <div class="mt-2">
                                    <input
                                        type="text"
                                        v-model="form.operation_number"
                                        id="operation_number"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError
                                        :message="form.errors.operation_number"
                                    />
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <div v-if="costsFounded.acData.length + costsFounded.scData.length > 0">
                                    <p class="text-sm font-medium leading-6 text-gray-600">Registros coincidentes</p>
                                    <div class="rounded-md border border-gray-300 overflow-auto">
                                        <table class="w-full ">
                                            <thead>
                                                <tr
                                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500"
                                                >
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600"
                                                    >
                                                        Zona
                                                    </th>
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600"
                                                    >
                                                        Tipo de Gasto
                                                    </th>
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600"
                                                    >
                                                        Ubicación
                                                    </th>
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600"
                                                    >
                                                        Monto
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="text-gray-700"
                                                    v-for="(item, i) in costsFounded.scData"
                                                    :key="i"
                                                >
                                                    <td
                                                        class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]"
                                                    >
                                                        {{ item.zone }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]"
                                                    >
                                                        {{ item.expense_type }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]"
                                                    >
                                                        {{ item.project.code }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px] tabular-nums"
                                                    >
                                                        S/. {{ item.amount }}
                                                    </td>
                                                </tr>
                                                <tr class="text-gray-700"
                                                    v-for="(item, i) in costsFounded.acData"
                                                    :key="i"
                                                >
                                                    <td
                                                        class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]"
                                                    >
                                                        {{ item.zone }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]"
                                                    >
                                                        {{ item.expense_type }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]"
                                                    >
                                                        {{ item.project.code }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px] tabular-nums"
                                                    >
                                                        S/. {{ item.amount }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div v-else> 
                                    <p class="text-sm font-medium leading-6 text-gray-600">
                                        No hay registros coincidentes
                                    </p>
                                </div>
                                <InputError
                                        :message="form.errors.acData"
                                    />
                                <InputError
                                        :message="form.errors.scData"
                                    />
                            </div>
                            
                            

                            <div>
                                <InputLabel
                                    for="description"
                                    class="font-medium leading-6 text-gray-900"
                                    >Descripción</InputLabel
                                >
                                <div class="mt-2">
                                    <input
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
                                    for="charge"
                                    class="font-medium leading-6 text-gray-900"
                                    >Cargo</InputLabel
                                >
                                <div class="mt-2">
                                    <input
                                        type="number"
                                        step="0.0001"
                                        v-model="form.charge"
                                        id="charge"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError :message="form.errors.charge" />
                                </div>
                            </div>
                            <div>
                                <InputLabel
                                    for="payment"
                                    class="font-medium leading-6 text-gray-900"
                                    >Abono</InputLabel
                                >
                                <div class="mt-2">
                                    <input
                                        type="number"
                                        step="0.0001"
                                        v-model="form.payment"
                                        id="payment"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError
                                        :message="form.errors.payment"
                                    />
                                </div>
                            </div>
                            <div>
                                <InputLabel
                                    for="balance"
                                    class="font-medium leading-6 text-gray-900"
                                    >Saldo Contable</InputLabel
                                >
                                <div class="mt-2">
                                    <input
                                        type="number"
                                        step="0.0001"
                                        v-model="form.balance"
                                        id="balance"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError
                                        :message="form.errors.balance"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeFormModal">
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
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import { setAxiosErrors } from "@/utils/utils";
import { notify, notifyError } from "@/Components/Notification";
import { Toaster } from "vue-sonner";
import { ref, watch } from "vue";

const { auth, userPermissions } = defineProps({
    auth: Object,
    userPermissions: Array,
});

const hasPermission = (permission) => {
    return userPermissions.includes(permission);
};

const showFormModal = ref(false);
const form = useForm({
    operation_date: "",
    operation_number: "",
    description: "",
    charge: "",
    payment: "",
    balance: "",
    acData:[],
    scData:[],
});

function openFormModal() {
    showFormModal.value = true;
}

function closeFormModal() {
    showFormModal.value = false;
}

const isFetching = ref(false);
async function submit() {
    try{
        isFetching.value = true
        const res = await axios.post(
            route("finance.account_statement.store"), form.data())
        console.log(res.data)
        // let index = dataToRender.value.findIndex(item=>item.id == form.id)
        // dataToRender.value[index] = res.data
        // closeFormModal()
        notify('Gasto Adicional Actualizado')
    }catch (e) {
        isFetching.value = false
        if (e.response?.data?.errors){
            setAxiosErrors(e.response.data.errors, form)
        }else {
            notifyError('Server Error')
        }
    }
}

async function searchCosts (data) {
    const res = await axios.get(route('finance.search_costs', data));
    return res.data
}

const costsFounded = ref({
    acData: [],
    scData: [],
})

watch([()=>form.operation_number, ()=>form.operation_date], async()=>{
    const res = await searchCosts({
        'operation_date':form.operation_date, 
        'operation_number':form.operation_number
    })
    costsFounded.value = res
    form.acData = res.acData.map(val=>val?.id)
    form.scData = res.scData.map(val=>val?.id)
})



</script>
