<template>
    <Modal :show="showPayModal" :maxWidth="showDetails ? '8xl' : '7xl'">
        <div class="p-6 flex flex-col gap-6">
            <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                Registrar Pagos
            </h2>
            <div class="flex md:flex-row flex-col gap-4">
                <div :class="showDetails ? 'md:w-3/4' : 'w-full'">
                    <form @submit.prevent="submit">
                        <div class="overflow-auto rounded-lg shadow-sm">
                            <table class="w-full ">
                                <thead>
                                    <tr class="font-bold">
                                        <th></th>
                                        <th></th>
                                        <td
                                            class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1 text-center">
                                            <select @change="handleHeaderMasive" name="expense_type"
                                                class="inline-block w-28 rounded-md border-0 py-1 text-indigo-700 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6 h-full">
                                                <option value="">
                                                    Seleccionar
                                                </option>
                                                <option v-for="op in expenseConstants.expenseTypes">
                                                    {{ op }}
                                                </option>
                                            </select>
                                        </td>
                                        <th
                                            class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1 text-center">
                                            <select @change="handleHeaderMasive" name="type_doc"
                                                class="inline-block w-28 rounded-md border-0 py-1 text-indigo-700 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6 h-full">
                                                <option value="">
                                                    Seleccionar
                                                </option>
                                                <option v-for="op in expenseConstants.docTypes">
                                                    {{ op }}
                                                </option>
                                            </select>
                                        </th>
                                        <th
                                            class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1 text-center">
                                            <input type="date" @input="handleHeaderMasive" name="doc_date"
                                                class="inline-block  rounded-md border-0 py-1 text-indigo-700 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6" />
                                        </th>
                                        <th
                                            class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1 text-center">
                                            <input type="text" @input="handleHeaderMasive" name="doc_number"
                                                class="inline-block w-28 text-center  rounded-md border-0 py-1 text-indigo-700 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6" />
                                        </th>
                                        <th
                                            class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1 text-center">
                                            <input type="date" @input="handleHeaderMasive" name="operation_date"
                                                class="inline-block  rounded-md border-0 py-1 text-indigo-700 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6" />
                                        </th>
                                        <th class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1">
                                            <input type="text" @input="handleHeaderMasive" name="operation_number"
                                                class="inline-block w-28 text-center  rounded-md border-0 py-1 text-indigo-700 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6" />
                                        </th>
                                        <th>
                                        </th>
                                        <th>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th
                                            class="text-xs text-gray-600 bg-gray-100 py-1">
                                            <label :for="`mod-check-all`" class="flex gap-2 justify-center tex-center py-1">
                                                <input @change="handleCheckAll" :id="`mod-check-all`" :checked="selectedIds.ids.length > 0"
                                                    type="checkbox" />
                                                {{ selectedIds.ids.length ?? "" }}
                                            </label>
                                        </th>
                                        <th class="text-xs text-gray-600 bg-gray-100 py-1">Nombre</th>
                                        <th class="text-xs text-gray-600 bg-gray-100 py-1">Tipo de gasto <span
                                                class="text-red-500">*</span></th>
                                        <th class="text-xs text-gray-600 bg-gray-100 py-1">Tipo de documento <span
                                                class="text-red-500">*</span></th>
                                        <th class="text-xs text-gray-600 bg-gray-100 py-1">Fecha de documento</th>
                                        <th class="text-xs text-gray-600 bg-gray-100 py-1">Nro. documento</th>
                                        <th class="text-xs text-gray-600 bg-gray-100 py-1">Fecha de operación</th>
                                        <th class="text-xs text-gray-600 bg-gray-100 py-1">Nro. de operación</th>
                                        <th class="text-xs text-gray-600 bg-gray-100 py-1">Monto <span
                                                class="text-red-500">*</span></th>
                                        <th class="text-xs text-gray-600 bg-gray-100 py-1"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, i) in form.payroll_detail_expenses" :key="i">
                                        <td class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1">
                                            <label :for="`mod-check-${i}`" class="block px-2 py-3">
                                                <input v-model="selectedIds.ids" :value="i" :id="`mod-check-${i}`" type="checkbox" />
                                            </label>
                                        </td>
                                        <td class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1">{{
                                            i+1 }}. {{ item.employee_name }}</td>
                                        <td
                                            class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1 text-center">
                                            <select v-model="item.expense_type"
                                                class="inline-block w-28 rounded-md border-0 py-1 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6 h-full"
                                                :class="[{
                                                    ' text-gray-900': !!item.expense_type,
                                                    ' text-gray-300': !!!item.expense_type,
                                                }]"
                                                >
                                                <option disabled value="">
                                                    Seleccionar
                                                </option>
                                                <option v-for="op in expenseConstants.expenseTypes" class="text-gray-900">
                                                    {{ op }}
                                                </option>
                                            </select>
                                            <InputError textSize="text-xs"
                                                :message="form.errors[`payroll_detail_expenses.${i}.expense_type`]" />
                                        </td>
                                        <td
                                            class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1 text-center">
                                            <select v-model="item.type_doc"
                                                class="inline-block w-28 rounded-md border-0 py-1 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6 h-full"
                                                :class="[{
                                                    ' text-gray-900': !!item.type_doc,
                                                    ' text-gray-300': !!!item.type_doc,
                                                }]">
                                                <option disabled value="">
                                                    Seleccionar
                                                </option>
                                                <option v-for="op in expenseConstants.docTypes" class="text-gray-900">
                                                    {{ op }}
                                                </option>
                                            </select>
                                            <InputError textSize="text-xs"
                                                :message="form.errors[`payroll_detail_expenses.${i}.type_doc`]" />
                                        </td>
                                        <td
                                            class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1 text-center">
                                            <input type="date" v-model="item.doc_date"
                                                class="inline-block  rounded-md border-0 py-1 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6" 
                                                :class="[{
                                                    ' text-gray-900': !!item.doc_date,
                                                    ' text-gray-300': !!!item.doc_date,
                                                }]"
                                                />
                                            <InputError textSize="text-xs"
                                                :message="form.errors[`payroll_detail_expenses.${i}.doc_date`]" />
                                        </td>
                                        <td
                                            class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1 text-center">
                                            <input type="text" v-model="item.doc_number"
                                                class="inline-block w-28 text-center  rounded-md border-0 py-1 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6"
                                                
                                                />
                                            <InputError textSize="text-xs"
                                                :message="form.errors[`payroll_detail_expenses.${i}.doc_number`]" />
                                        </td>
                                        <td
                                            class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1 text-center">
                                            <input type="date" v-model="item.operation_date"
                                                class="inline-block  rounded-md border-0 py-1 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6" 
                                                :class="[{
                                                    ' text-gray-900': !!item.operation_date,
                                                    ' text-gray-300': !!!item.operation_date,
                                                }]"
                                                />
                                            <InputError textSize="text-xs"
                                                :message="form.errors[`payroll_detail_expenses.${i}.operation_date`]" />
                                        </td>
                                        <td class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1">
                                            <input type="text" v-model="item.operation_number"
                                                class="inline-block w-28 text-center  rounded-md border-0 py-1 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6" />
                                            <InputError textSize="text-xs"
                                                :message="form.errors[`payroll_detail_expenses.${i}.operation_number`]" />
                                        </td>
                                        <td class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1">
                                            <input type="number" min="1" v-model="item.amount" step="0.01"
                                                class="inline-block w-28 text-center  rounded-md border-0 py-1 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6" />
                                            <InputError textSize="text-xs"
                                                :message="form.errors[`payroll_detail_expenses.${i}.amount`]" />
                                        </td>
                                        <td class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1">

                                            <button type="button" @click="openDetailsModal(item.payroll_detail_id)">
                                                <EyeOutlineIcon class="text-teal-400 h-5 w-5" />
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="8" class="text-sm text-gray-800">Total</td>
                                        <td class="text-sm text-gray-800 bg-white px-2 py-1 text-right">
                                            S/. {{parseFloat(form.payroll_detail_expenses.reduce((a, b) => a + b.amount, 0)).toFixed(2)}}</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6 flex gap-3 justify-end">
                            <SecondaryButton type="button" @click="minimizePayModal">
                                Minimizar
                            </SecondaryButton>
                            <SecondaryButton type="button" @click="closePayModal">
                                Cancelar
                            </SecondaryButton>
                            <PrimaryButton class="tracking-widest uppercase text-xs"
                                :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                                Guardar
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
                <div v-if="showDetails" class="relative md:w-1/4 bg-gray-100 rounded-lg p-4 overflow-y-auto">
                    <div v-if="currentPayrollDetailExpenses.length>0" class="mt-8 flex flex-col gap-4">
                        <h1 class="text-sm font-bold">
                            Pagos hechos a: {{ currentPayrollDetailExpenses[0].employee_name }}
                        </h1>
                        <div>
                            <div  class="font-semibold text-sm grid grid-cols-3">
                                <p>
                                    Gasto
                                </p>
                                <p>
                                    N° Doc
                                </p>
                            </div>
                            <div v-for="item in currentPayrollDetailExpenses"  class="text-sm grid grid-cols-3">
                                <p>
                                    {{ item.general_expense.expense_type }}
                                </p>
                                <p>
                                    {{ item.general_expense.doc_number }}
                                </p>
                                <p class="text-right">
                                    S/. {{ item.general_expense.amount.toFixed(2) }}
                                </p>

                            </div>
                        </div>
                    </div>
                    <div v-else class="italic text-gray-500 text-center mt-8">
                        No hay pagos registrados
                    </div>
                    <button class="absolute top-2 left-2 p-2  bg-gray-400 rounded-md" @click="closeDetailsModal">
                        <EyeSlashSolidIcon class="text-gray-200 h-4 w-4" />
                    </button>
                </div>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { EyeOutlineIcon, EyeSlashSolidIcon } from "@/Components/Icons";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { setAxiosErrors } from "@/utils/utils";
import Modal from "@/Components/Modal.vue";
import { ref } from "vue";
import { notify, notifyError, notifyWarning } from "@/Components/Notification";

const { actionForm, payroll, data } = defineProps({
    actionForm: Object,
    payroll: Object,
    data: Array,
});

const showPayModal = ref(false);
const showDetails = ref(false);
const expenseConstants = ref({
    expenseTypes: [],
    docTypes: [],
})

openDetailsModal
const form = useForm({
    payroll_detail_expenses: []
});

function openPayModal() {
    getConstants()
    if (actionForm.ids.length <= 0) { notifyWarning('No hay registros seleccionados'); return }
    const formIds = form.payroll_detail_expenses.map(item=>item.payroll_detail_id)
    const setActionFormIds = new Set(actionForm.ids);
    const setFormIds = new Set(formIds);
    const idsToAdd = actionForm.ids.filter(x => !setFormIds.has(x));
    const idsToDelete = formIds.filter(x => !setActionFormIds.has(x));
    idsToDelete.forEach(element => {
        const index = form.payroll_detail_expenses.findIndex(item=>item.payroll_detail_id === element)
        console.log(index)
        selectedIds.value.ids = selectedIds.value.ids.filter(n => n !== index);
    });
    const setIdsToDelete = new Set(idsToDelete);
    const result = form.payroll_detail_expenses.filter(obj => !setIdsToDelete.has(obj.payroll_detail_id));
    form.payroll_detail_expenses = result




    idsToAdd.forEach(item => {
        const rgFromData = data.find(i=>i.id===item)
        if(rgFromData)
            form.payroll_detail_expenses.push({
                payroll_detail_id: rgFromData.id,
                employee_name: rgFromData.employee_name,
                zone: 'Oficina',
                expense_type: '',
                location: `Planilla ${payroll.month}`,
                operation_number: '',
                operation_date: '',
                doc_date: '',
                doc_number: '',
                type_doc: '',
                amount: '',
            })
    });
    showPayModal.value = true;
}

function closePayModal() {
    closeDetailsModal()
    form.reset()
    form.clearErrors()
    showPayModal.value = false;
}
function minimizePayModal() {
    closeDetailsModal()
    showPayModal.value = false;
}


async function submit() {
    const formToSend = form.data()
    const res = await axios.post(route('payroll.detail.expenses.multiple.store'), formToSend)
        .catch(e => {
            if (e.response?.data?.errors) {
                setAxiosErrors(e.response.data.errors, form);
            } else {
                notifyError("Server Error");
            }
        })
    if (res.status === 200) {
        closePayModal()
        notify('Pagos registrados')
    }

}

function openDetailsModal(payroll_detail_id) {
    showDetails.value = true
    getExpenses(payroll_detail_id)
}

function closeDetailsModal() {
    currentPayrollDetailExpenses.value = []
    showDetails.value = false
}




async function getConstants() {
    const res = await axios.get(route('payroll.detail.expense.constants.show'))
    if (res.status === 200) { expenseConstants.value = res.data }
}




const selectedIds = ref({ ids: [], });
const handleCheckAll = (e) => {
    if (e.target.checked) { selectedIds.value.ids = form.payroll_detail_expenses.map((item, i) => i); }
    else { selectedIds.value.ids = []; }
};

const handleHeaderMasive = (e) => {
    const { name, value } = e.target
    selectedIds.value.ids.forEach(element => {
        form.payroll_detail_expenses[element][name] = value
    });

    if (name === 'expense_type'){
        if(value === 'AFP') {
            selectedIds.value.ids.forEach(element => {
                const expense = form.payroll_detail_expenses[element]
                const reg = data.find(item=>item.id === expense.payroll_detail_id)
                expense.amount = reg.new_totals.employee_tac_total
            });
        }
    }

}


const currentPayrollDetailExpenses = ref([])
async function getExpenses(payroll_detail_id) {
    const res = await axios.get(route('payroll.detail.expenses.show', {payroll_detail_id}))
        .catch(e => notifyError("Server Error"))
    if (res.status === 200) {
        console.log(res.data)
        currentPayrollDetailExpenses.value = res.data
    }

}



defineExpose({ openPayModal });
</script>
