<template>
    <Modal :show="showDiscountModal" :maxWidth="'md'">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                Descuento de Empleado
            </h2>
            <form @submit.prevent="submitDiscount">
                <div class="border-b border-gray-900/10">
                    <div class="mt-2">
                        <InputLabel for="discount">Monto
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput type="number" id="discount" v-model="formDiscount.discount" maxlength="6" />
                            <InputError :message="formDiscount.errors.discount" />
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-3">
                        <SecondaryButton @click="openDiscountModal"> Cancelar </SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': formDiscount.processing }">
                            Guardar
                        </PrimaryButton>
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
import { notify, notifyError } from '@/Components/Notification';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { setAxiosErrors } from '@/utils/utils';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const { spreadsheets } = defineProps({
    spreadsheets: Object
})
const showDiscountModal = ref(false)
const formDiscount = useForm({
    employee_id: '',
    discount: ''
})

function openDiscountModal(employee_id, discount) {
    showDiscountModal.value = !showDiscountModal.value
    formDiscount.clearErrors()
    formDiscount.defaults({
        ...{
            employee_id: '',
            discount: ''
        }
    })
    formDiscount.reset()
    formDiscount.employee_id = employee_id
    formDiscount.discount = String(discount)
}

async function submitDiscount() {
    let url = route('payroll.discount', { payroll_id: formDiscount.employee_id })
    try {
        let response = await axios.put(url, formDiscount)
        openDiscountModal()
        updatePayrollDetails(response.data, 'discount')
    } catch (error) {
        console.error(error)
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, formDiscount)
            } else {
                notifyError(`Server error:${error.response.data}`)
            }
        } else {
            notifyError(`Network or other error:${error}`)
        }
    }
}

function updatePayrollDetails(payrollDetail, action) {
    const validations = spreadsheets
    if (action === 'discount') {
        let index = validations.findIndex(item => item.id === payrollDetail.id)
        validations[index] = payrollDetail
        notify('El descuento se registro')
    }
}

defineExpose({ openDiscountModal })
</script>