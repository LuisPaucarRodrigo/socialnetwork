<template>

    <Head title="Imagenes para Reporte" />
    <AuthenticatedLayout :redirectRoute="backUrl">
        <template #header>
            Imagenes para Reporte
        </template>
        <div class="min-w-full overflow-hidden rounded-lg">
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <div class="mt-2">
                    <select required id="code" @change="requestPhotos($event.target.value)"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        <option disabled>Seleccione Code</option>
                        <option value="0">Todos</option>
                        <option v-for="item in codesWithStatus" :key="item.id" :value="item.id"> {{ item.code }}
                        </option>
                    </select>
                </div>
            </div>
            <div v-for="imageCode in codes" :key="imageCode.id">
                <div class="mt-6 flex items-center justify-between gap-x-6">
                    <h1 class="text-md font-bold text-gray-700 line-clamp-1 m-5">
                        {{ imageCode.code }} / {{ imageCode.description }}
                    </h1>
                    <span v-if="imageCode.status === 'Aprobado'" class="text-green-600">Aprobado</span>
                    <PrimaryButton v-else @click="approveCode(imageCode.id)" type="button"
                        class="bg-green-700 hover:bg-green-600">
                        Aprobar
                    </PrimaryButton>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4 mt-5">
                    <div v-for="image in photoCodeFiltrado(imageCode.id)" :key="image.id"
                        class="bg-white p-4 rounded-md shadow sm:col-span-1 md:col-span-2">
                        <h2 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">{{ image.description }}
                        </h2>
                        <div class="grid grid-cols-1 gap-y-1 text-sm">
                            <img :src="image.image">
                        </div>
                        <div class="pt-3 flex space-x-3 justify-center item-center">
                            <span v-if="image.state != null"
                                :class="image.state == '1' ? 'text-green-600' : 'text-red-600'">
                                {{ image.state == '1' ? 'Aprobado' : 'Rechazado' }}</span>
                            <div v-else class="flex space-x-3">
                                <button @click="approveImageModal(image.id)"
                                    class="flex items-center text-green-600 hover:underline">
                                    <CheckCircleIcon class="h-4 w-4 ml-1" />
                                </button>
                                <button @click="rejectModal(image.id)"
                                    class="flex items-center text-red-600 hover:underline">
                                    <XCircleIcon class="h-4 w-4 ml-1" />
                                </button>
                            </div>

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
        </div>

        <Modal :show="showRejectModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Rechazar imagen
                </h2>
                <form @submit.prevent="submitRejectImage">
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">
                            <div>
                                <InputLabel for="observation">Ingrese la razón o
                                    motivo
                                    por el cual la imagen no es valida
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="form.observation" id="observation" />
                                    <InputError :message="form.observation.coment" />
                                </div>
                            </div>
                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <SecondaryButton @click="closeRejectModal"> Cancelar
                                </SecondaryButton>
                                <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                                    Rechazar
                                </PrimaryButton>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <SuccessOperationModal :confirming="approve_reject_Image" :title="titleSuccessImage"
            :message="messageSuccessImage" />
        <ConfirmDeleteModal :confirmingDeletion="confirmingImageDeletion" itemType="imagen"
            :deleteFunction="deleteImage" @closeModal="closeModalImage" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { TrashIcon, ArrowDownIcon, EyeIcon, CheckCircleIcon, XCircleIcon } from '@heroicons/vue/24/outline';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    codesWithStatus: Object,
    imagesCode: Object,
    preproject: Object,
});

let backUrl = props.preproject.status === null
    ? 'preprojects.index'
    : props.preproject.status == true
        ? { route: 'preprojects.index', params: { preprojects_status: 1 } }
        : { route: 'preprojects.index', params: { preprojects_status: 0 } }

const confirmingImageDeletion = ref(false);
const approve_reject_Image = ref(false);
const imageToDelete = ref(null);
const photoCode = ref(props.imagesCode);
const showRejectModal = ref(false)
const imageCodeId = ref('');
const codes = ref(props.codesWithStatus);

const titleSuccessImage = ref('')
const messageSuccessImage = ref('')

const form = useForm({
    'id': '',
    'state': '',
    'observation': ''
})

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
        router.delete(route('preprojects.imagereport.delete', { preproject_id: docId }), {
            onSuccess: () => {
                router.get(route('preprojects.imagereport.index', { preproject_id: props.preproject.id }))
            }
        });
    }
};

function downloadImagen(imageId) {
    const backendImageUrl = route('preprojects.imagereport.download', { preproject_id: imageId });
    window.open(backendImageUrl, '_blank');
};

function openPreviewImagenModal(imageId) {
    if (imageId) {
        const url = route('preprojects.imagereport.show', { image: imageId });
        window.open(url, '_blank');
    } else {
        console.error('No se proporcionó un ID de imagen válido');
    }
}


function closeRejectModal() {
    imageCodeId.value = ''
    showRejectModal.value = false
}

function rejectModal(imageId) {
    imageCodeId.value = imageId
    showRejectModal.value = true
}

function approveImageModal(imageId) {
    form.state = true
    form.put(route('preprojects.imagereport.approveReject', { preproject_image_id: imageId }), {
        onSuccess: () => {
            titleSuccessImage.value = "Imagen Aprobada"
            messageSuccessImage.value = "La imagen se aprovo correctamente"
            approve_reject_Image.value = true
            setTimeout(() => {
                approve_reject_Image.value = false
                router.get(route('preprojects.imagereport.index', { preproject_id: props.preproject.id }))
            }, 2000)
        }
    })
}

function submitRejectImage() {
    form.state = false
    form.put(route('preprojects.imagereport.approveReject', { preproject_image_id: imageCodeId.value }), {
        onSuccess: () => {
            showRejectModal.value = false
            titleSuccessImage.value = "Imagen Rechazada"
            messageSuccessImage.value = "La imagen se rechazo correctamente"
            approve_reject_Image.value = true
            setTimeout(() => {
                approve_reject_Image.value = false
                router.get(route('preprojects.imagereport.index', { preproject_id: props.preproject.id }))
            }, 2000)
        }
    })
}

function requestPhotos($e) {
    if ($e === '0') {
        router.get(route('preprojects.imagereport.index', { preproject_id: props.preproject.id }))
    } else {
        axios.get(route('preprojects.report.images', $e))
            .then(response => {
                if (response.status === 200) {
                    codes.value = response.data.codes;
                    photoCode.value = response.data.images;
                } else {
                    throw new Error('Fallo en el servidor con imagenes ' + response.status)
                }
            })
            .catch(error => {
                console.error(error)
            })
    }
}

function photoCodeFiltrado($preproject_code_id) {
    return photoCode.value.filter(image => image.preproject_code_id === $preproject_code_id);
}

function approveCode(preproject_code_id) {
    router.get(route('preprojects.codereport.approveCode', { preproject_code_id: preproject_code_id }), {
        onSuccess: () => {
            titleSuccessImage.value = "Code Aprobado"
            messageSuccessImage.value = "El Codigo de aprobo correctamente"
            approve_reject_Image.value = true
            setTimeout(() => {
                router.get(route('preprojects.imagereport.index', { preproject_id: props.preproject.id }))
            }, 2000)
        }
    })
}
</script>