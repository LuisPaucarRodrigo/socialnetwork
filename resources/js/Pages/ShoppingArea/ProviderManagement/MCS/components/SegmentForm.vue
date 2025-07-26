<template>
    <Modal :show="showModalAddSegment">
        <form class="p-6" @submit.prevent="submitCategoryOrSegment">
            <h2 class="text-lg font-medium text-gray-900">
                Nuevo Segmento
            </h2>
            <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2 mt-2">
                <div>
                    <InputLabel for="name" class="font-medium leading-6 text-gray-900">Categoria</InputLabel>
                    <select v-model="formSegment.category_id" id="segment"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                        <option disabled value="">Seleccione Categoria</option>
                        <option v-for="item in category" :key="item.id" :value="item.id">
                            {{ item.name }}
                        </option>
                    </select>
                </div>
                <div>
                    <InputLabel for="name" class="font-medium leading-6 text-gray-900">Nombre</InputLabel>
                    <div class="mt-2">
                        <TextInput id="name" :to-uppercase="true" require v-model="formSegment.name" />
                        <InputError :message="formSegment.errors.name" />
                    </div>
                </div>
            </div>
            <div class="mt-6 flex gap-3 justify-end">
                <SecondaryButton type="button" @click="closeAddModal()"> Cerrar </SecondaryButton>
                <PrimaryButton type="submit"> Agregar </PrimaryButton>
            </div>
        </form>
    </Modal>
</template>
<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useAxiosErrorHandler } from '@/utils/axiosError';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const initialStateFormSegment = {
    category_id: '',
    name: ''
}

const showModalAddSegment = ref(false);
const categoryId = ref(null)
const segmentsList = ref([]);

const formSegment = useForm({
    ...initialStateFormSegment
})

async function handleCategoryChange(category_id) {
    let url = route('provider.segments.list', { category_id: category_id });
    try {
        let response = await axios.get(url)
        segmentsList.value = response.data
    } catch (error) {
        console.error(error)
    }
}

async function submitCategoryOrSegment() {
    let url = categoryAndSegment.value == false ? route('provider.category.post') : route('provider.segment.post')
    let selectedForm = categoryAndSegment.value == false ? formCategory : formSegment
    try {
        let response = await axios.post(url, selectedForm)
        let cs = categoryAndSegment.value == false ? 'Category' : 'Segment'
        updateCategoryOrSegment(response.data, cs);
        closeAddModal()
    } catch (error) {
        useAxiosErrorHandler(error,)
    }
};
</script>