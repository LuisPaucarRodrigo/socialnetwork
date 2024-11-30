<template>

    <Head title="Imagenes para Reporte" />
    <AuthenticatedLayout :redirectRoute="backUrl">
        <template #header>
            Imagenes para Reporte
        </template>
        <div class="min-w-full overflow-hidden rounded-lg">

            <!-- <GoogleMaps :mapVisible="mapVisible" :origin="origin" :destination="destination" :waypoints="waypoints" /> -->
            <div v-for="preprojectImage in preprojectImages" :key="preprojectImage.id">
                <div class="mt-6 flex items-center justify-start gap-x-3">
                    <h2 class="text-md font-bold text-gray-700 line-clamp-1 m-5">
                        {{ preprojectImage.type }}
                    </h2>
                    <PrimaryButton @click="approveTitle(preprojectImage.id)" data-tooltip-target="enable_and_disabled"
                        :customColor="[preprojectImage.state ? 'bg-red-600 hover:bg-red-500' : 'bg-green-600 hover:bg-green-500']">
                        <svg v-if="preprojectImage.state" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </PrimaryButton>
                    <div id="enable_and_disabled" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        {{ preprojectImage.state ? 'Desabilitar' : 'Habilitar' }}
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <a :href="`${route('preprojects.report.download', { preproject_title_id: preprojectImage.id })}?t=${Date.now()}`"
                        target="_blank" data-tooltip-target="export_image_report"
                        class="rounded-md bg-green-500 px-4 py-2 text-center text-sm text-white hover:bg-green-400">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            stroke-width="1.5">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9.29289 1.29289C9.48043 1.10536 9.73478 1 10 1H18C19.6569 1 21 2.34315 21 4V9C21 9.55228 20.5523 10 20 10C19.4477 10 19 9.55228 19 9V4C19 3.44772 18.5523 3 18 3H11V8C11 8.55228 10.5523 9 10 9H5V20C5 20.5523 5.44772 21 6 21H7C7.55228 21 8 21.4477 8 22C8 22.5523 7.55228 23 7 23H6C4.34315 23 3 21.6569 3 20V8C3 7.73478 3.10536 7.48043 3.29289 7.29289L9.29289 1.29289ZM6.41421 7H9V4.41421L6.41421 7ZM19 12C19.5523 12 20 12.4477 20 13V19H23C23.5523 19 24 19.4477 24 20C24 20.5523 23.5523 21 23 21H19C18.4477 21 18 20.5523 18 20V13C18 12.4477 18.4477 12 19 12ZM11.8137 12.4188C11.4927 11.9693 10.8682 11.8653 10.4188 12.1863C9.96935 12.5073 9.86526 13.1318 10.1863 13.5812L12.2711 16.5L10.1863 19.4188C9.86526 19.8682 9.96935 20.4927 10.4188 20.8137C10.8682 21.1347 11.4927 21.0307 11.8137 20.5812L13.5 18.2205L15.1863 20.5812C15.5073 21.0307 16.1318 21.1347 16.5812 20.8137C17.0307 20.4927 17.1347 19.8682 16.8137 19.4188L14.7289 16.5L16.8137 13.5812C17.1347 13.1318 17.0307 12.5073 16.5812 12.1863C16.1318 11.8653 15.5073 11.9693 15.1863 12.4188L13.5 14.7795L11.8137 12.4188Z"
                                fill="#ffffff" />
                        </svg>
                    </a>
                    <div id="export_image_report" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Exportar
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
                <div v-for="imageCode in preprojectImage.preproject_codes" :key="imageCode.id" class="border">
                    <div class="grid grid-cols-1 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <h1 class="text-md font-bold text-gray-700 m-2">
                                {{ imageCode.code.code }} / {{ imageCode.code.description }}
                            </h1>
                        </div>
                        <template v-if="hasPermission('ProjectManager')">
                            <div class="sm:col-span-2 space-x-3 text-right" v-if="!imageCode.status">
                                <PrimaryButton data-tooltip-target="approve_images" @click="approveImages(imageCode.id)"
                                    type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
                                    </svg>
                                </PrimaryButton>
                                <div id="approve_images" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                    Aprobar Imagenes
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                                <PrimaryButton data-tooltip-target="approve_codes"
                                    @click="verifyApproveModal(preprojectImage.id, imageCode.id)" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 0 1 9 9v.375M10.125 2.25A3.375 3.375 0 0 1 13.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 0 1 3.375 3.375M9 15l2.25 2.25L15 12" />
                                    </svg>
                                </PrimaryButton>
                                <div id="approve_codes" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                    Aprobar Codigo
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </div>
                            <span v-if="imageCode.status" class="text-green-600">Aprobado</span>
                        </template>
                    </div>
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4 mt-5">
                        <div v-for="image in imageCode.imagecodepreprojet" :key="image.id"
                            class="bg-white p-4 rounded-md shadow sm:col-span-1 md:col-span-2">
                            <h2 :data-tooltip-target="`info-tooltip-${image.id}`"
                                class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
                                {{ image.description }}
                            </h2>
                            <div :id="`info-tooltip-${image.id}`" role="tooltip"
                                class="absolute z-50 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                {{ image.description }}
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>

                            <div class="grid grid-cols-1 gap-y-1 text-sm text-center">
                                <img :src="image.image">
                                <p>Lat:{{ image?.lat }} Lon:{{ image?.lon }}</p>
                            </div>
                            <div class="pt-3 flex space-x-3 justify-center item-center">
                                <span v-if="image.state != null"
                                    :class="image.state == '1' ? 'text-green-600' : 'text-red-600'">
                                    {{ image.state == '1' ? 'Aprobado' : 'Rechazado' }}</span>
                                <div v-else class="flex space-x-3">
                                    <button @click="approveImageModal(preprojectImage.id, imageCode.id, image.id)"
                                        class="flex items-center text-green-600 hover:underline">
                                        <CheckCircleIcon class="h-4 w-4 ml-1" />
                                    </button>
                                    <button @click="rejectModal(preprojectImage.id, imageCode.id, image.id)"
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
        <ConfirmateModal :showConfirm="showApproveCode" tittle="Aprobacion de Codigo"
            text="Las fotos asociadas a este código serán eliminadas si no han sido aprobadas. ¿Desea continuar con la aprobación?"
            :actionFunction="approveCode" @closeModal="verifyApproveModal" />
        <SuccessOperationModal :confirming="approve_reject_Image" :title="titleSuccessImage"
            :message="messageSuccessImage" />
        <ConfirmDeleteModal :confirmingDeletion="confirmingImageDeletion" itemType="imagen"
            :deleteFunction="deleteImage" @closeModal="closeModalImage" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import ConfirmateModal from '@/Components/ConfirmateModal.vue';
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
// import GoogleMaps from '@/Components/GoogleMaps.vue';


const showApproveCode = ref(false);
const title_code_id = ref(null);
const preproject_image_id = ref(null);
const imageCodeId = ref('');
const props = defineProps({
    // codesWithStatus: Object,
    preprojectImage: Object,
    imagesCode: Object,
    preproject: Object,
    userPermissions: Array
});

const preprojectImages = ref(props.preprojectImage)

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}

let backUrl = (props.preproject?.status === undefined || props.preproject?.status === null)
    ? 'preprojects.index'
    : props.preproject.status == true
        ? { route: 'preprojects.index', params: { preprojects_status: 1 } }
        : { route: 'preprojects.index', params: { preprojects_status: 0 } }

const confirmingImageDeletion = ref(false);
const approve_reject_Image = ref(false);
const imageToDelete = ref(null);
// const photoCode = ref(props.imagesCode);
const showRejectModal = ref(false)

// const codes = ref(props.codesWithStatus);
// const mapVisible = ref(false);

// const filteredImages = ref(Object.values(props.imagesCode).filter(image => image.state == true));

// const origin = {
//     lat: Number(filteredImages.value[0]?.lat),
//     lng: Number(filteredImages.value[0]?.lon)
// };

// const destination = {
//     lat: Number(filteredImages.value[filteredImages.value.length - 1]?.lat),
//     lng: Number(filteredImages.value[filteredImages.value.length - 1]?.lon)
// };
// const waypoints = filteredImages.value.slice(1, -1).map(item => ({
//     location: {
//         lat: Number(item?.lat),
//         lng: Number(item?.lon)
//     },
//     stopover: true
// }));
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
    axios.get(backendImageUrl)
        .then(response => {
            const imageUrl = response.data.url;
            const link = document.createElement('a');
            link.href = imageUrl;
            link.download = imageUrl.split('/').pop();
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        })
        .catch(error => {
            console.error('Error fetching the image URL:', error);
        });
};

function openPreviewImagenModal(imageId) {
    if (imageId) {
        const url = route('preprojects.imagereport.show', { image: imageId });
        axios.get(url)
            .then(response => {
                const imageUrl = response.data.url;
                window.open(imageUrl, '_blank');
            })
            .catch(error => {
                console.error('Error fetching image URL:', error);
            });
    } else {
        console.error('No se proporcionó un ID de imagen válido');
    }
}


function closeRejectModal() {
    imageCodeId.value = ''
    showRejectModal.value = false
}

function rejectModal(title_id, code_id, imageId) {
    title_code_id.value = title_id
    preproject_image_id.value = code_id
    imageCodeId.value = imageId
    showRejectModal.value = true
}

async function approveImageModal(titleId, CodeId, imageId) {
    form.state = true
    try {
        await axios.put(route('preprojects.imagereport.approveReject', { preproject_image_id: imageId }), form);
        visuallyChangeImage(titleId, CodeId, imageId, 1)
        titleSuccessImage.value = "Imagen Aprobada"
        messageSuccessImage.value = "La imagen se aprovo correctamente"
        approve_reject_Image.value = true
        setTimeout(() => {
            approve_reject_Image.value = false
        }, 2000)
    } catch (error) {
        console.error(error);
    }
}

async function submitRejectImage() {
    form.state = false
    try {
        await axios.put(route('preprojects.imagereport.approveReject', { preproject_image_id: imageCodeId.value }), form);
        visuallyChangeImage(title_code_id.value, preproject_image_id.value, imageCodeId.value, 0)
        showRejectModal.value = false
        titleSuccessImage.value = "Imagen Rechazada"
        messageSuccessImage.value = "La imagen se rechazo correctamente"
        approve_reject_Image.value = true
        setTimeout(() => {
            approve_reject_Image.value = false
        }, 2000)
    } catch (error) {
        console.error(error);
    }

    // form.state = false
    // form.put(route('preprojects.imagereport.approveReject', { preproject_image_id: imageCodeId.value }), {
    //     onSuccess: () => {
    //         showRejectModal.value = false
    //         titleSuccessImage.value = "Imagen Rechazada"
    //         messageSuccessImage.value = "La imagen se rechazo correctamente"
    //         approve_reject_Image.value = true
    //         setTimeout(() => {
    //             approve_reject_Image.value = false
    //             router.get(route('preprojects.imagereport.index', { preproject_id: props.preproject.id }))
    //         }, 2000)
    //     },
    //     onError: (e) => {
    //         console.log(e)
    //     }
    // })
}

// function requestPhotos($e) {
//     if ($e === '0') {
//         router.get(route('preprojects.imagereport.index', { preproject_id: props.preproject.id }))
//     } else {
//         axios.get(route('preprojects.report.images', $e))
//             .then(response => {
//                 if (response.status === 200) {
//                     codes.value = response.data.codes;
//                     photoCode.value = response.data.images;
//                 } else {
//                     throw new Error('Fallo en el servidor con imagenes ' + response.status)
//                 }
//             })
//             .catch(error => {
//                 console.error(error)
//             })
//     }
// }

// function photoCodeFiltrado($preproject_code_id) {
//     return photoCode.value.filter(image => image.preproject_code_id === $preproject_code_id);
// }

async function approveCode() {
    try {
        await axios.get(route('preprojects.codereport.approveCode', { preproject_code_id: title_code_id.value }));
        showApproveCode.value = !showApproveCode.value
        visuallyChangeCode(preproject_image_id.value, title_code_id.value)
        titleSuccessImage.value = "Code Aprobado"
        messageSuccessImage.value = "El Codigo se aprobo correctamente"
        approve_reject_Image.value = true
        setTimeout(() => {
            approve_reject_Image.value = false
        }, 2000)
    } catch (error) {
        console.error(error);
    }
}

async function approveTitle(preproject_title_id) {
    try {
        await axios.get(route('preprojects.codereport.approveTitle', { preproject_title_id: preproject_title_id }))
        updateStateStage(preproject_title_id)
    } catch (error) {
        console.error(error)
    }
}

async function approveImages(code_id) {
    try {
        await axios.get(route('preprojects.codereport.approveImages', { code_id: code_id }));
        titleSuccessImage.value = "Imagenes Aprobadas"
        messageSuccessImage.value = "Las imagenes se aprovaron Correctamente"
        approve_reject_Image.value = true
        setTimeout(() => {
            approve_reject_Image.value = false
        }, 2000)
    } catch (error) {
        console.error(error);
    }
}

// const showMap = () => {
//     mapVisible.value = true;
// };


// @click="approveCode(imageCode.id)"

function verifyApproveModal(preprojectImageId, preproject_code_id) {
    preproject_image_id.value = preprojectImageId
    title_code_id.value = preproject_code_id
    showApproveCode.value = !showApproveCode.value
}

function visuallyChangeCode(preprojectImageId, preprojectCodeId) {
    const preprojectImage = preprojectImages.value.find(image => image.id === preprojectImageId);
    if (preprojectImage) {
        const preprojectCode = preprojectImage.preproject_codes.find(code => code.id === preprojectCodeId);
        if (preprojectCode) {
            preprojectCode.status = "Aprobado";
        } else {
            console.log('No se encontró el código especificado.');
        }
    } else {
        console.log('No se encontró el preproject especificado.');
    }
}

function visuallyChangeImage(preprojectTitleId, preprojectImageCodeId, preprojectImageId, state) {
    const preprojectImage = preprojectImages.value.find(title => title.id === preprojectTitleId);
    if (preprojectImage) {
        const preprojectCode = preprojectImage.preproject_codes.find(code => code.id === preprojectImageCodeId);
        if (preprojectCode) {
            const preprojectCodeImage = preprojectCode.imagecodepreprojet.find(image => image.id === preprojectImageId);

            if (preprojectCodeImage) {
                preprojectCodeImage.state = state;
            } else {
                console.log('No se encontró el la imagen especificado.');
            }
        } else {
            console.log('No se encontró el código especificado.');
        }
    } else {
        console.log('No se encontró el preproject especificado.');
    }
}

function updateStateStage(preproject_stage_id) {
    const index = preprojectImages.value.findIndex(item => item.id === preproject_stage_id)
    preprojectImages.value[index].state = !preprojectImages.value[index].state;
}

</script>