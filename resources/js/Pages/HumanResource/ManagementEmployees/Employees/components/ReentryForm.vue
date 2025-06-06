<template>
    <Modal :show="showModalFired">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                Reingreso del Empleado
            </h2>
            <form @submit.prevent="submit">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-2">
                        <InputLabel for="reentry_date">Fecha de
                            Reingreso o
                            Recontratacion:
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput type="date" id="reentry_date" v-model="reentryForm.reentry_date" required />
                            <InputError :message="reentryForm.errors.reentry_date" />
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <SecondaryButton type="button" @click="closeReentryModal"> Cancel </SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': reentryForm.processing }"> Guardar
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
import { useAxiosErrorHandler } from '@/utils/axiosError';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const { employees } = defineProps({
    employees: Object
})

const showModalFired = ref(false);
const employeeId = ref(null)

const reentryForm = useForm({
    reentry_date: '',
})

function toogleModal() {
    showModalFired.value = !showModalFired.value
};

const openReentryModal = ($id) => {
    employeeId.value = $id
    toogleModal()
};

const closeReentryModal = () => {
    toogleModal()
};

async function submit() {
    let url = route('management.employees.reentry', { id: employeeId.value })
    try {
        await axios.put(url, reentryForm)
        updateFrontEnd(employeeId.value)
    } catch (error) {
        console.log(error)
        useAxiosErrorHandler(reentryForm)
    }
}

function updateFrontEnd(id) {
    try {
        const data = employees.data || employees
        let index = data.findIndex(item => item.id == id)
        data.splice(index, 1)
        notify("Reingreso Exitoso")
        closeReentryModal()
    } catch (error) {
        notifyError("Ocurrió un error al actualizar la vista")
    }
}

defineExpose({ openReentryModal })
</script>