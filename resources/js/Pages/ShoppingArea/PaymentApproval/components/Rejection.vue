<template>
    <Modal :show="showModal" @close="closeModal">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                Motivo de Rechazo
            </h2>
            <form @submit.prevent="submit">
                <div class="space-y-12">
                    <div class="border-b grid grid-cols-1 gap-6 border-gray-900/10 pb-12">
                        <div>
                            <InputLabel class="font-medium leading-6 text-gray-900">
                                Motivo
                            </InputLabel>
                            <div class="mt-2">
                                <textarea v-model="form.reason_rejection"
                                    class="block w-full py-1.5 rounded-md sm:text-sm focus:border-indigo-600" />
                                <InputError :message="form.errors.reason_rejection" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <SecondaryButton @click="closeModal">
                            Cancelar
                        </SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
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
import { notify } from '@/Components/Notification';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useAxiosErrorHandler } from '@/utils/axiosError';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const { payments } = defineProps({
    payments: Object
})

const showModal = ref(false)
const paymentId = ref(null)

const initialState = ({
    reason_rejection: '',
    is_validated: false
})

const form = useForm({
    ...initialState
});

function toogleModal() {
    showModal.value = !showModal.value
}

function openRejectedModal(id) {
    paymentId.value = id
    toogleModal()
}

function closeModal() {
    toogleModal()
    form.clearErrors()
    form.defaults({ ...initialState })
    form.reset()
    paymentId.value = null
}

async function submit() {
    try {
        let url = route('payment.approval.rejected', { id: paymentId.value })
        let res = await axios.post(url, form)
        console.log(res)
        let listData = payments.data || payments
        const index = listData.findIndex(item => item.id === paymentId.value)
        listData[index] = res.data
        notify('Se rechazo con exito')
        closeModal()
    } catch (error) {
        useAxiosErrorHandler(error, form)
    }
}
defineExpose({ openRejectedModal })
</script>