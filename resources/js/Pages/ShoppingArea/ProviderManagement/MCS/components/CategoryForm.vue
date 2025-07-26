<template>
    <Modal :show="showModalCategory">
        <form class="p-6" @submit.prevent="submit">
            <h2 class="text-lg font-medium text-gray-900">
                {{ form.id ? 'Actualizar' : 'Nueva' }} Categoria
            </h2>
            <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 my-2">
                <FormInput id="name" label="Nombre" v-model="form.name" :error-message="form.errors.name"
                    to-uppercase />
            </div>
            <div>
                <div class="flex gap-x-2">
                    <InputLabel>Segmentos</InputLabel>
                    <button type="button" @click="addSegment()">
                        <PlusCircleIcon />
                    </button>
                </div>
                <div v-for="(item, index) in form.segment" :key="index" class="flex items-center gap-x-2">
                    <div class="my-2 w-full">
                        <TextInput v-model="item.name" />
                        <InputError :message="form.errors[`segment.${index}.name`]" />
                    </div>
                    <button type="button" @click="() => { form.segment.splice(index, 1) }">
                        <DeleteIcon />
                    </button>
                </div>
                <InputError :message="form.errors.segment" />
            </div>
            <div class="mt-6 flex gap-3 justify-end">
                <SecondaryButton @click="closeModal()"> Cerrar </SecondaryButton>
                <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    {{ form.id ? 'Actualizar' : 'Crear' }}
                </PrimaryButton>
            </div>
        </form>
    </Modal>
</template>
<script setup>
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import FormInput from '@/Layouts/FormInput.vue';
import { useAxiosErrorHandler } from '@/utils/axiosError';
import { notify } from '@/Components/Notification';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { DeleteIcon, PlusCircleIcon } from '@/Components/Icons';

const { dataToRender } = defineProps({
    dataToRender: Object
})

const initialState = {
    id: '',
    name: '',
    segment: [],
}

const form = useForm({})
const showModalCategory = ref(false)

function toogleModal() {
    showModalCategory.value = !showModalCategory.value
}

function createCategoryForm() {
    form.defaults({ ...initialState })
    form.reset()
    toogleModal()
}

function editCategoryForm(item) {
    form.defaults({ ...item })
    form.reset()
    toogleModal()
}

function closeModal() {
    toogleModal()
    form.clearErrors()
    form.defaults({ ...initialState })
    form.reset()
}

function addSegment() {
    form.segment.push({
        id: '',
        name: ''
    })
}

async function submit() {
    let url = form.id ? route('providersmanagement.MCSManagement.category.edit', { category: form.id }) : route('providersmanagement.MCSManagement.category.store')
    try {
        let res = await axios.post(url, form)
        let action = form.id ? 'update' : 'create'
        let itemId = form.id ?? null
        updateFrontEnd(res.data, action, itemId)
    } catch (error) {
        useAxiosErrorHandler(error, form)
    }
}

function updateFrontEnd(response, action, itemId) {
    const listData = dataToRender.data || dataToRender
    if (action === 'create') {
        listData.push(response)
        notify('Creacion exitosa')
    } else if (action === 'update') {
        const index = listData.findIndex(item => item.id === itemId)
        listData[index] = response
        notify('Actualización exitosa')
    }
    closeModal()
}

defineExpose({ createCategoryForm, editCategoryForm })
</script>