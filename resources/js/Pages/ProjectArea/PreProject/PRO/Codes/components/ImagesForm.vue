<template>
    <Modal :show="showStoreImage">
        <div class="p-6">
            <div class="flex justify-start space-x-3">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    Agregar Imagenes
                </h2>
                <button type="button" @click="addimage">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 text-green-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </button>
            </div>

            <form @submit.prevent="submitImage">
                <div v-for="(image, index) in formImage.images" :key="index">
                    <div class="flex justify-between space-x-3 w-full">
                        <div class="mt-2">
                            <InputFile v-model="image.image" id="image" accept=".img,.png,.jpg" />
                            <InputError :message="formImage.errors['images.' + index + '.image']" />
                        </div>
                        <button type="button" @click="removeimage(index)">
                            <DeleteIcon />
                        </button>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-x-3">
                    <SecondaryButton @click="closeImagesForm()">Cerrar</SecondaryButton>
                    <PrimaryButton type="submit" :class="{ 'opacity-25': formImage.processing }">
                        Guardar
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
<script setup>
import { DeleteIcon } from '@/Components/Icons';
import InputError from '@/Components/InputError.vue';
import InputFile from '@/Components/InputFile.vue';
import Modal from '@/Components/Modal.vue';
import { notify } from '@/Components/Notification';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useAxiosErrorHandler } from '@/utils/axiosError';
import { toFormData } from '@/utils/utils';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const showStoreImage = ref(null)

const initialStatusForm = {
    code_id: null,
    images: []
}

const formImage = useForm({ ...initialStatusForm })

function toogleModal() {
    showStoreImage.value = !showStoreImage.value
}

function openImagesForm(code_id) {
    toogleModal()
    formImage.defaults({ code_id })
    formImage.reset()
}

function closeImagesForm() {
    toogleModal()
    formImage.defaults({ ...initialStatusForm })
    formImage.reset()
}

function addimage() {
    formImage.images.push({
        image: '',
    });
}

function removeimage(index) {
    formImage.images.splice(index, 1);
}

async function submitImage() {
    try {
        let formData = toFormData(formImage)
        await axios.post(route('preprojects.code.images.store'), formData)
        notify("Creación exitosa")
        closeImagesForm()
    } catch (error) {
        console.error(error);
        useAxiosErrorHandler(error, formImage)
    }
};

defineExpose({ openImagesForm })
</script>