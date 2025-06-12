<template>
    <Modal :show="create_code">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                {{ !form.id ? 'Agregar código' : 'Actualizar código' }}
            </h2>
            <form @submit.prevent="!form.id ? submit() : submitEdit()">
                <div class="space-y-12">
                    <div>
                        <div>
                            <InputLabel for="code" class="font-medium leading-6 text-gray-900 mt-3">Código
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput v-model="form.code" id="code" />
                                <InputError :message="form.errors.code" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="description" class="font-medium leading-6 text-gray-900 mt-3">
                                Descripción
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.description" id="description" />
                                <InputError :message="form.errors.description" />
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-3">
                            <SecondaryButton @click="closeModal()"> Cerrar
                            </SecondaryButton>
                            <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                                {{ !form.id ? 'Guardar' : 'Actualizar' }}</PrimaryButton>
                        </div>
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
import TextInput from '@/Components/TextInput.vue';
import { useAxiosErrorHandler } from '@/utils/axiosError';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const { codes } = defineProps({
    codes: Object
})

const create_code = ref(false);
const initialStatusForm = {
    id: '',
    code: '',
    description: '',
}

const form = useForm({
    ...initialStatusForm
});

function toogleModal() {
    create_code.value = !create_code.value;
}

function openModal(item) {
    form.defaults({ ...item })
    form.reset();
    toogleModal()
}

function closeModal() {
    toogleModal()
    form.defaults({ ...initialStatusForm })
    form.reset();
}

async function submit() {
    let url = route('preprojects.codes.post')
    try {
        const response = await axios.post(url, form)
        updateFrontEnd(response.data, 'create', null)
    } catch (error) {
        console.error(error)
        useAxiosErrorHandler(error, form)
    }
};

async function submitEdit() {
    let url = route('preprojects.codes.put', { code: form.id })
    try {
        const response = await axios.put(url, form)
        updateFrontEnd(response.data, 'udpate', form.id)
    } catch (error) {
        console.error(error)
        useAxiosErrorHandler(error, form)
    }
};

function updateFrontEnd(response, action, id) {
    let data = codes.data || codes
    if (action === 'create') {
        data.unshift(response)
        notify("Creacion exitosa")
        closeModal()
    } else if (action === 'udpate') {
        let index = data.findIndex(item => item.id === id)
        data[index] = response
        notify("Actualizacion exitosa")
        closeModal()
    }
}

defineExpose({ openModal })
</script>