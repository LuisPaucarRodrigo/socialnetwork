<template>
    <Modal :show="showModal" @close="closeModal">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                Importar Excel
            </h2>
            <form @submit.prevent="submit">
                <div class="space-y-12">
                    <div class="border-b grid grid-cols-1 gap-6 border-gray-900/10 pb-12">
                        <div>
                            <InputLabel class="font-medium leading-6 text-gray-900">
                                Archivo
                            </InputLabel>
                            <div class="mt-2">
                                <InputFile v-model="form.document" accept=".pdf" />
                                <InputError :message="form.errors.document" />
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
import InputFile from '@/Components/InputFile.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { notify } from '@/Components/Notification';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useAxiosErrorHandler } from '@/utils/axiosError';
import { toFormData } from '@/utils/utils';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const { payments } = defineProps({
    payments: Object
})

const showModal = ref(false)
const paymentId = ref(null)

const initialState = ({
    document: null,
})

const form = useForm({
    ...initialState
});

function toogleModal() {
    showModal.value = !showModal.value
}

function openDocumentModal(id) {
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
    let url = route('payment.approval.document', { id: paymentId.value })
    try {
        let formData = toFormData(form)
        const res = await axios.post(url, formData)
        let listData = payments.data || payments
        const index = listData.findIndex(item => item.id === paymentId.value)
        listData[index] = res.data
        notify('Documento guardado con exito')
        closeModal()
    } catch (error) {
        console.error(error)
        useAxiosErrorHandler(error, form)
    }
}

defineExpose({ openDocumentModal })
</script>