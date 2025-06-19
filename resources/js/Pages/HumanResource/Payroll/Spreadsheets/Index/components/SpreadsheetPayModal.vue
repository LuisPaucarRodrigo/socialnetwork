<template>
    <Modal :show="showPayModal" :maxWidth="showDetails ? '8xl' : '6xl'">
        <div class="p-6 flex flex-col gap-6">
            <h2
                class="text-lg font-medium text-gray-800 border-b-2 border-gray-100"
            >
                Registrar Pagos
            </h2>
            <div class="flex md:flex-row flex-col gap-4">
                <div :class="showDetails ? 'md:w-3/4' : 'w-full'">
                    <form  @submit.prevent="submit">
                        <div class="overflow-auto rounded-lg shadow-sm">
                            <table class="w-full ">
                                <thead>
                                    <tr>
                                        <th class="text-xs text-gray-600 bg-gray-100 py-1">Nombre</th>
                                        <th class="text-xs text-gray-600 bg-gray-100 py-1">Tipo de gasto <span class="text-red-500">*</span></th>
                                        <th class="text-xs text-gray-600 bg-gray-100 py-1">Tipo de documento <span class="text-red-500">*</span></th>
                                        <th class="text-xs text-gray-600 bg-gray-100 py-1">Fecha de documento</th>
                                        <th class="text-xs text-gray-600 bg-gray-100 py-1">Nro. documento</th>
                                        <th class="text-xs text-gray-600 bg-gray-100 py-1">Fecha de operación</th>
                                        <th class="text-xs text-gray-600 bg-gray-100 py-1">Nro. de operación</th>
                                        <th class="text-xs text-gray-600 bg-gray-100 py-1">Monto <span class="text-red-500">*</span></th>
                                        <th class="text-xs text-gray-600 bg-gray-100 py-1"></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item,i) in form.payroll_detail_expenses" :key="i">
                                        <td class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1">{{ i+1 }}. {{ item.employee_name }}</td>
                                        <td class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1 text-center">
                                            <select v-model="item.expense_type"
                                                class="inline-block w-28 rounded-md border-0 py-1 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6 h-full">
                                                <option disabled value="">
                                                    Seleccionar
                                                </option>
                                                <option v-for="op in expenseConstants.expenseTypes">
                                                    {{ op }}
                                                </option>
                                            </select>
                                            <InputError textSize="text-xs" :message="form.errors[`payroll_detail_expenses.${i}.expense_type`]"/>
                                        </td>
                                        <td class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1 text-center">
                                            <select v-model="item.type_doc"
                                                class="inline-block w-28 rounded-md border-0 py-1 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6 h-full">
                                                <option disabled value="">
                                                    Seleccionar
                                                </option>
                                                <option v-for="op in expenseConstants.docTypes">
                                                    {{ op }}
                                                </option>
                                            </select>
                                            <InputError textSize="text-xs" :message="form.errors[`payroll_detail_expenses.${i}.type_doc`]"/>
                                        </td>
                                        <td class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1 text-center">
                                            <input type="date" v-model="item.doc_date"  
                                            class="inline-block  rounded-md border-0 py-1 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6" />
                                            <InputError textSize="text-xs" :message="form.errors[`payroll_detail_expenses.${i}.doc_date`]"/>
                                        </td>
                                        <td class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1 text-center">
                                            <input type="text" v-model="item.doc_number" 
                                            class="inline-block w-28 text-center  rounded-md border-0 py-1 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6" />
                                            <InputError textSize="text-xs" :message="form.errors[`payroll_detail_expenses.${i}.doc_number`]"/>
                                        </td>
                                        <td class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1 text-center">
                                            <input type="date" v-model="item.operation_date" 
                                            class="inline-block  rounded-md border-0 py-1 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6" />
                                            <InputError textSize="text-xs" :message="form.errors[`payroll_detail_expenses.${i}.operation_date`]"/>
                                        </td>
                                        <td class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1">
                                            <input type="text" v-model="item.operation_number" 
                                            class="inline-block w-28 text-center  rounded-md border-0 py-1 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6" />
                                            <InputError textSize="text-xs" :message="form.errors[`payroll_detail_expenses.${i}.operation_number`]"/>
                                        </td>
                                        <td class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1">
                                            <input type="number" min="1" v-model="item.amount" 
                                            class="inline-block w-28 text-center  rounded-md border-0 py-1 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 text-xs sm:leading-6" />
                                            <InputError textSize="text-xs" :message="form.errors[`payroll_detail_expenses.${i}.amount`]"/>
                                        </td>
                                        <td class="text-xs text-gray-800 bg-white border-b border-gray-200 px-2 py-1">
                                          
                                            <button type="button"  @click="openDetailsModal(item.payroll_detail_id)">
                                                <EyeOutlineIcon class="text-teal-400 h-5 w-5"/>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-sm text-gray-800">Total</td>
                                        <td class="text-sm text-gray-800 bg-white px-2 py-1 text-right">
                                            S/. {{ form.payroll_detail_expenses.reduce((a,b)=> a+b.amount, 0) }}</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <SecondaryButton
                                type="button"
                                @click="closePayModal"
                            >
                                Cancelar
                            </SecondaryButton>
                            <PrimaryButton
                                class="ml-3 tracking-widest uppercase text-xs"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                                type="submit"
                            >
                                Guardar
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
                <div
                    v-if="showDetails"
                    class="relative md:w-1/4 bg-gray-100 rounded-lg p-4 overflow-y-auto"
                >
                <div>
                    <!-- to complete -->
                </div>
                <button class="absolute top-2 left-2 p-2  bg-gray-400 rounded-md" @click="closeDetailsModal">
                    <EyeSlashSolidIcon class="text-gray-200 h-4 w-4"/>
                </button>
                </div>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { EyeOutlineIcon, EyeSlashSolidIcon } from "@/Components/Icons/Index";
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
    payroll_detail_expenses:[]
});

function openPayModal() {
    getConstants()
    if(actionForm.ids.length <= 0) {notifyWarning('No hay registros seleccionados'); return}
    data.forEach(item => {
        if(actionForm.ids.includes(item.id))
        form.payroll_detail_expenses.push({
            payroll_detail_id: item.id,
            employee_name: item.employee_name,
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
    form.reset()
    form.clearErrors()
    showPayModal.value = false;
}


async function submit () {
    const formToSend = form.data()
    const res = await axios.post(route('payroll.detail.expenses.multiple.store'), formToSend)
        .catch(e=>{
            if (e.response?.data?.errors) {
                setAxiosErrors(e.response.data.errors, form);
            } else {
                notifyError("Server Error");
            }
        })
    if(res.status === 200) {
        closePayModal()
        notify('Pagos registrados')
    }
    
}









function openDetailsModal(payroll_detail_id) {
    showDetails.value = true
}
function closeDetailsModal() {
    showDetails.value = false
}




async function getConstants() {
    const res = await axios.get(route('payroll.detail.expense.constants.show'))
    if(res.status === 200) {expenseConstants.value = res.data}
}









defineExpose({ openPayModal });
</script>
