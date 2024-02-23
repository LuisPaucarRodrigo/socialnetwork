<template>
    <Head title="Imagenes para Reporte" />
    <AuthenticatedLayout :redirectRoute="'preprojects.index'">
        <template #header>
            Imagenes para Reporte
        </template>
        <div class="min-w-full overflow-hidden rounded-lg shadow">
            <div class="grid grid-cols-1 sm:grid-cols-2  md:grid-cols-4 lg:grid-cols-6 gap-4 mt-5">
                <div v-for="image in images" :key="image.id"
                    class="bg-white p-4 rounded-md shadow md:col-span-2">
                    <h2 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">{{ getImageName(image.description) }}
                    </h2>
                    <div class="flex space-x-3 item-center">
                        <button @click="openPreviewImagenModal(image.id)"
                            class="flex items-center text-green-600 hover:underline">
                            <EyeIcon class="h-4 w-4 ml-1" />
                        </button>
                        <button @click="downloadImagen(image.id)"
                            class="flex items-center text-blue-600 hover:underline">
                            <ArrowDownIcon class="h-4 w-4 ml-1" />
                        </button>
                        <button @click="confirmDeleteImagen(image.id)"
                            class="flex items-center text-red-600 hover:underline">
                            <TrashIcon class="h-4 w-4" />
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <teleport to="body">
            <div v-if="isPreviewImageModalOpen" class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="absolute inset-0 bg-gray-800 opacity-75" @click="closePreviewImageModal"></div>
                <div class="flex items-center justify-center h-full w-3/4">
                    <div class="bg-white p-5 rounded-md relative w-full h-3/5">
                        <button @click="closePreviewImageModal"
                            class="close-button absolute top-0 right-0 mt-2 mr-2">&#10006;</button>
                        <iframe :src="getImageUrl(imageToShow)" class="w-full h-full"></iframe>
                    </div>
                </div>
            </div>
        </teleport>

        <ConfirmDeleteModal :confirmingDeletion="confirmingImageDeletion" itemType="imagen"
            :deleteFunction="deleteImage" @closeModal="closeModalImage" />
    </AuthenticatedLayout>
</template>
    
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { TrashIcon, ArrowDownIcon, EyeIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    images: Object,
});

const confirmingImageDeletion = ref(false);
const imageToDelete = ref(null);

const imageToShow = ref(null);
const isPreviewImageModalOpen = ref(false);

const confirmDeleteImagen = (imagenId) => {
    imageToDelete.value = imagenId;
    confirmingImageDeletion.value = true;
};

const closeModalImage = () => {
    confirmingImageDeletion.value = false;
};

const deleteImage = () => {
    const docId = imageToDelete.value;
    if (docId) {
        router.delete(route('documents.destroy', { id: docId }), {
            onSuccess: () => closeModalImage()
        });
    }
};

function downloadImagen(imageId) {
    const backendImageUrl = route('preprojects.imagereport.download', { preproject_id: imageId });
    window.open(backendImageUrl, '_blank');
};

const closePreviewImageModal = () => {
    isPreviewImageModalOpen.value = false;
};

function openPreviewImagenModal(imageId) {
    imageToShow.value = imageId;
    isPreviewImageModalOpen.value = true;
}

function getImageUrl(imageId) {
    return route('preprojects.imagereport.show', { image: imageId });
}

const getImageName = (documentTitle) => {
    const parts = documentTitle.split('_');
    return parts.length > 1 ? parts.slice(1).join('_') : documentTitle;
};

</script>
  
    