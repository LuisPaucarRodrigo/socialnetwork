<template>
    <Modal :show="showModal">
        <div class="p-6">
            <form @submit.prevent="submit">
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 pb-12">
                        <h2 class="text-base font-medium leading-7 text-gray-900">
                            Factura
                        </h2>
                        <div>
                            <InputLabel for="serie_number">
                                Numero de Serie
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="number" v-model="form.serie_number" id="serie_number" maxlength="8" />
                                <InputError :message="form.errors.serie_number" />
                            </div>
                        </div>
                        <div>
                            <InputLabel for="facture_number">
                                Numero de Factura
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="number" v-model="form.facture_number" id="facture_number" />
                                <InputError :message="form.errors.facture_number" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="facture_date">
                                Fecha de Factura
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" v-model="form.facture_date" id="facture_date" />
                                <InputError :message="form.errors.facture_date" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="facture_doc">
                                Documento de Factura
                            </InputLabel>
                            <div class="mt-2">
                                <InputFile type="file" v-model="form.facture_doc" id="facture_doc" />
                                <InputError :message="form.errors.facture_doc" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="others">
                                Otros
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.others" id="others" />
                                <InputError :message="form.errors.others" />
                            </div>
                        </div>
                    </div>

                    <div class="border-b border-gray-900/10 pb-12">
                        <h2 class="text-base font-medium leading-7 text-gray-900">
                            Guia de Remision
                        </h2>
                        <div>
                            <InputLabel for="remission_guide_number">
                                Numero de Guia de Remision
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.remission_guide_number"
                                    id="remission_guide_number" />
                                <InputError :message="form.errors.remission_guide_number" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="remission_guide_date">
                                Fecha de Guia de Remission
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" v-model="form.remission_guide_date" id="remission_guide_date" />
                                <InputError :message="form.errors.remission_guide_date" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="remission_guide_doc">
                                Documento de Guia de Remision
                            </InputLabel>
                            <div class="mt-2">
                                <InputFile type="file" v-model="form.remission_guide_doc" id="remission_guide_doc" />
                                <InputError :message="form.errors.remission_guide_doc" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-3">
                        <SecondaryButton @click="closeModal()"> Cancelar </SecondaryButton>
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
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import InputFile from '@/Components/InputFile.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { notify, notifyError, notifyWarning } from '@/Components/Notification';
import { ref } from 'vue';
import { setAxiosErrors, toFormData } from '@/utils/utils';

const { orders, data } = defineProps({
    orders: Object,
    data: Object
})

const showModal = ref(false);
const id = ref(null);

const showModalUpdateSuccess = defineModel('showModalUpdateSuccess')
const form = useForm({
    id: '',
    state: '',
    serie_number: '',
    facture_number: '',
    facture_date: '',
    facture_doc: null,
    others: '',
    remission_guide_number: '',
    remission_guide_date: '',
    remission_guide_doc: null,
})

function updateState(stateid, newState, is_payments_completed) {
    id.value = stateid;
    state.value = newState;
    data.value = { state: newState, id: stateid };

    if (state.value === "Completada") {
        if (is_payments_completed) {
            showModal.value = true;
        } else {
            notifyWarning("Complete todos los pagos previos a la fecha de llegada de la compra")
            // title.value = "Pagos Incompletos"
            // message.value = "Complete todos los pagos previos a la fecha de llegada de la compra."
            // errorAmount.value = true
            // setTimeout(() => {
            //     errorAmount.value = false
            //     router.visit(route('purchaseorders.index'));
            // }, 1500);
        }
    } else if (state.value === "Pendiente" || state.value === "OC Enviada") {
        showModalUpdateSuccess.value = true
    }
}

async function submit() {
    form.id = id
    form.state = state
    // form.post(route('purchaseorders.state'), {
    //     onSuccess: () => {
    //         showCotization.value = false
    //         title_order.value = "Orden Completada"
    //         message_order.value = "Orden completada correctamente"
    //         showModalSuccess.value = true
    //         setTimeout(() => {
    //             showModalSuccess.value = false
    //             router.visit(route('purchaseorders.index'))
    //         }, 2000);

    //     }
    // })
    let url = route('purchaseorders.state')
    let formData = toFormData(form)
    try {
        let response = await axios.post(url, formData)
        updateFrontEnd("Completed", response.data)
    } catch (error) {
        if (error.response) {
            if (error.response.errors) {
                setAxiosErrors(error.response.errors, form)
            } else {
                notifyError("Server Error")
            }
        } else {
            notifyError("Network Error")
        }
    }
}

const closeModal = () => {
    form.reset
    showModal.value = false
}

function updateFrontEnd(action, response) {
    let validations = orders.data || orders
    if (action === "Completed") {
        let index = validations.findIndex(item => item.id === response.id)
        validations[index] = response
        notify("Orden Completada")
    }
}

defineExpose({ updateState })
</script>