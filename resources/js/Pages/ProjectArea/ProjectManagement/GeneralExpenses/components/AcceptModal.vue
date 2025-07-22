<template>
    <Modal :show="showAcceptModal" @close="closeAcceptModal">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900 mb-2">
                Aceptar Gasto Adicional
            </h2>
            <form @submit.prevent="submitAcceptModal">
                <div class="space-y-12">
                    <div class="border-b grid grid-cols-1 gap-6 border-gray-900/10 pb-12">
                        <div>
                            <InputLabel for="operation_number" class="font-medium leading-6 text-gray-900">Numero de
                                Operación
                            </InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="opNuDateForm.operation_number" id="operation_number"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="opNuDateForm.errors.operation_number" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="operation_date" class="font-medium leading-6 text-gray-900">Fecha de
                                Operación
                            </InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="opNuDateForm.operation_date" id="operation_date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="opNuDateForm.errors.operation_date" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <SecondaryButton @click="closeAcceptModal">
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
</template>
<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { notify } from '@/Components/Notification';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useAxiosErrorHandler } from '@/utils/axiosError';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const { expenses } = defineProps({
    expenses: Object
})

const showAcceptModal = ref(false)
const paymentId = ref(null)
const isFetching = ref(false)

const opNuDateForm = useForm({
    is_accepted: true,
    operation_date: '',
    operation_number: '',
})

const closeAcceptModal = () => {
    showAcceptModal.value = false
    isFetching.value = false
    paymentId.value = null
    opNuDateForm.reset()
    opNuDateForm.clearErrors()
}
const openAcceptModal = (id) => {
    paymentId.value = id
    showAcceptModal.value = true
}
async function submitAcceptModal() {
    isFetching.value = true;
    let url = route('projectmanagement.pext.expenses.validate', { expense_id: paymentId.value })
    try {
        const res = await axios.put(url, opNuDateForm)
        console.log(res.data)
        const listData = expenses.data || expenses
        const index = listData.findIndex(item => item.id === paymentId.value)
        listData[index].is_accepted = res.data.is_accepted
        listData[index].operation_number = res.data.operation_number
        listData[index].operation_date = res.data.operation_date
        listData[index].real_state = res.data.real_state
        closeAcceptModal()
        notify('Aprobación exitosa')
        isFetching.value = false;
    } catch (error) {
        isFetching.value = false;
        useAxiosErrorHandler(error, opNuDateForm)
    }
}

defineExpose({ openAcceptModal })
</script>