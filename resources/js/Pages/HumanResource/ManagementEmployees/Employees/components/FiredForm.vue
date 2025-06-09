<template>
    <Modal :show="showModalFired">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                Despido del Empleado
            </h2>
            <form @submit.prevent="submit">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-2">
                        <InputLabel for="fired_date">Fecha de Despido:
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput type="date" id="fired_date" v-model="firedForm.fired_date" />
                            <InputError :message="firedForm.errors.fired_date" />
                        </div>
                    </div>
                    <div class="mt-6">
                        <InputLabel for="days_taken">Dias Tomados:
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput type="text" id="days_taken" v-model="firedForm.days_taken" />
                            <InputError :message="firedForm.errors.days_taken" />
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <SecondaryButton @click="closeFiredModal"> Cancel </SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': firedForm.processing }">
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
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { notify } from '@/Components/Notification';
import { useAxiosErrorHandler } from '@/utils/axiosError';

const { employees } = defineProps({
    employees: Object
})

const showModalFired = ref(false);
const employeeId = ref(null)

const initialFiredForm = {
    fired_date: '',
    days_taken: '',
    state: 'Inactive'
}

const firedForm = useForm({
    ...initialFiredForm
})

function toogleModal() {
    showModalFired.value = !showModalFired.value
}

const openFiredModal = (firedId) => {
    employeeId.value = firedId
    toogleModal()
}

const closeFiredModal = () => {
    toogleModal()
    firedForm.defaults({ ...initialFiredForm })
    firedForm.reset()
}

async function submit() {
    let url = route('management.employees.fired', { id: employeeId.value })
    try {
        await axios.put(url, firedForm)
        updateFrontEnd(employeeId.value)
    } catch (error) {
        console.error(error)
        useAxiosErrorHandler(error,firedForm)
    }
}

function updateFrontEnd(id) {
    const data = employees.data || employees
    let index = data.findIndex(item => item.id = id)
    data.splice(index, 1)
    notify("Despido Exitoso")
    closeFiredModal()
}

defineExpose({ openFiredModal })
</script>