<template>
    <Modal :show="showModal" @close="toogleModalImport">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                {{ form.id ? "Actualizar" : "Agregar" }} Gasto
            </h2>
            <form @submit.prevent="submit">
                <div class="space-y-12 mt-4">
                    <div class="grid sm:grid-cols-2 gap-6 pb-6">
                        <div class="sm:col-span-2">
                            <InputLabel class="font-medium leading-6 text-gray-900">
                                Archivo
                            </InputLabel>
                            <div class="mt-2">
                                <InputFile type="file" v-model="form.file" accept=".xlsx"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.file" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <SecondaryButton @click="toogleModalImport">
                            Cancelar
                        </SecondaryButton>
                        <PrimaryButton :disabled="form.processing" :class="{ 'opacity-25': form.processing }">
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
import { notify, notifyError } from '@/Components/Notification';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { setAxiosErrors, toFormData } from '@/utils/utils';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const { project_id, fixedOrAdditional } = defineProps({
    project_id: String,
    fixedOrAdditional: Boolean
})

const form = useForm({
    project_id: project_id,
    fixedOrAdditional: fixedOrAdditional,
    file: null
})

const showModal = ref(false)

function toogleModalImport() {
    showModal.value = !showModal.value
}

async function submit() {
    let url = route('importar.excel.expenses')
    try {
        console.log(form)
        let formData = toFormData(form)
        let response = await axios.post(url, formData)
        toogleModalImport()
        notify("Importación Exitosa")
    } catch (error) {
        console.log(error)
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, form)
            } else {
                notifyError(`Server Error ${error.response.data.error}`)
            }
        } else {
            notifyError('Network Error')
        }
    }
}

defineExpose({ toogleModalImport })
</script>