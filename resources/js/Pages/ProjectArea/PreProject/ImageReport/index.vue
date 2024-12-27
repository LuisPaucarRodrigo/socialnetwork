<template>

    <Head title="Imagenes para Reporte" />
    <AuthenticatedLayout :redirectRoute="backUrl">
        <template #header>
            Imagenes para Reporte
        </template>
        <div class="min-w-full overflow-hidden rounded-lg">
            <button class="ml-2" data-tooltip-target="add_stages" @click="openModalAddedStages">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 text-green-500 hover:bg-green-400">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </button>
            <div id="add_stages" role="tooltip"
                class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Agregar Etapas
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <div v-for="preprojectImage in preprojectImages" :key="preprojectImage.id">
                <div class="mt-6 flex items-center justify-between">
                    <div class="flex items-center justify-between gap-x-3">
                        <h2 class="text-md font-bold text-gray-700 line-clamp-1 ml-2 mt-5 mr-5 mb-5">
                            {{ preprojectImage.type }}
                        </h2>
                        <button @click="openModalDelete(preprojectImage.id)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5 text-red-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-between gap-x-3">
                        <PrimaryButton @click="approveTitle(preprojectImage.id)"
                            :customColor="[preprojectImage.state ? 'bg-red-600 hover:bg-red-500' : 'bg-green-600 hover:bg-green-500']">
                            {{ preprojectImage.state ? 'Desabilitar' : 'Habilitar' }}

                        </PrimaryButton>
                        <a :href="`${route('preprojects.report.download', { preproject_title_id: preprojectImage.id })}?t=${Date.now()}`"
                            target="_blank"
                            class="rounded-md bg-green-500 px-4 py-2 text-center text-sm text-white hover:bg-green-400">
                            Exportar
                        </a>
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
                                <PrimaryButton @click="approveImages(imageCode.id)" type="button">
                                    Aprobar Imagenes
                                </PrimaryButton>
                                <PrimaryButton @click="verifyApproveModal(preprojectImage.id, imageCode.id)"
                                    type="button">
                                    Aprobar Codigo
                                </PrimaryButton>
                            </div>
                            <span v-if="imageCode.status" class="text-green-600">Aprobado</span>
                        </template>
                    </div>
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4 mt-5">
                        <div v-for="image in imageCode.imagecodepreprojet" :key="image.id"
                            class="bg-white p-4 rounded-md shadow sm:col-span-1 md:col-span-2">
                            <h2 
                                class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
                                {{ image.description }}
                            </h2>
                            <!-- <div :id="`info-tooltip-${image.id}`" role="tooltip"
                                class="absolute z-50 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                {{ image.description }}
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div> -->

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
        <Modal :show="showOpenAddedStages">
            <div class="p-6">
                <div class="flex space-x-3 justify-start sm:col-span-2">
                    <h2 class="text-base font-semibold leading-7 text-gray-900 items-center">
                        Agregar etapas de reporte
                    </h2>
                    <button type="button" @click="addReportStage"
                        class="font-medium text-indigo-600 hover:text-indigo-500 self-start sm:self-end items-center">Agregar
                        etapas</button>
                </div>
                <form @submit.prevent="submitStages">
                    <div v-for="(reportStage, index) in formStages.reportStages" :key="index">
                        <div class="flex justify-end mt-5">
                            <button type="button" @click="removeReportStage(index)"
                                class="font-medium text-red-600 hover:text-indigo-500">Eliminar</button>
                        </div>

                        <InputLabel for="stage">
                            Etapas
                        </InputLabel>
                        <div class="mt-2">
                            <select v-model="reportStage.type" id="stage"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option value="">Selecciona etapa</option>
                                <option v-for="stage in stages" :key="stage.id" :value="stage.name">
                                    {{ stage.name }}
                                </option>
                            </select>
                            <InputError :message="formStages.errors['reportStages.' + index + '.type']" />
                        </div>

                        <InputLabel for="emergency_lastname">
                            Titulo
                        </InputLabel>
                        <div class="mt-2">
                            <select v-model="reportStage.title_id" id="title_id"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option value="">Selecciona un título</option>
                                <option v-for="title in titles" :key="title.id" :value="title.id">
                                    {{ title.title }}
                                </option>
                            </select>
                            <InputError :message="formStages.errors['reportStages.' + index + '.title_id']" />
                        </div>
                    </div>
                    <InputError :message="formStages.errors.reportStages" />
                    <div class="mt-6 flex items-center justify-end gap-x-3">
                        <SecondaryButton @click="openModalAddedStages">Cerrar</SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">Agregar
                        </PrimaryButton>
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
        <ConfirmDeleteModal :confirmingDeletion="showModalDelete" itemType="etapa" nameText="la etapa"
            :deleteFunction="deleteStages" @closeModal="openModalDelete" />
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
import { notifyError, notifyWarning } from '@/Components/Notification';
import { setAxiosErrors } from '@/utils/utils';

const showApproveCode = ref(false);
const title_code_id = ref(null);
const preproject_image_id = ref(null);
const imageCodeId = ref('');
const showOpenAddedStages = ref(false)
const props = defineProps({
    // codesWithStatus: Object,
    preprojectImage: Object,
    imagesCode: Object,
    preproject: Object,
    userPermissions: Array,
    stages: Object,
    titles: Object
});
console.log(props.preprojectImage)
const preprojectImages = ref(props.preprojectImage)

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}
console.log(props.preproject)

let backUrl = (props.preproject?.status === undefined || props.preproject?.status === null)
    ? { route: 'preprojects.index', params: { type: props.preproject.cost_line_id } }
    : props.preproject.status == true
        ? { route: 'preprojects.index', params: { type: props.preproject.cost_line_id, preprojects_status: 1 } }
        : { route: 'preprojects.index', params: { type: props.preproject.cost_line_id,preprojects_status: 0 } }

const confirmingImageDeletion = ref(false);
const approve_reject_Image = ref(false);
const imageToDelete = ref(null);
const showRejectModal = ref(false)
const titleSuccessImage = ref('')
const messageSuccessImage = ref('')
const showModalDelete = ref(false)
const title_id = ref(null)

const form = useForm({
    'id': '',
    'state': '',
    'observation': ''
})

const formStages = useForm({
    'reportStages': []
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
}

function openModalDelete(stages_id) {
    title_id.value = stages_id
    showModalDelete.value = !showModalDelete.value
}

async function deleteStages() {
    let url = route('preprojects.stages.delete', { title_id: title_id.value })
    try {
        await axios.delete(url)
        updateStages(title_id.value, 'delete')
        openModalDelete()
    } catch (error) {
        if (error.response.data) {
            notifyError('Server Error: ', error.response.data)
        } else {
            notifyError(error)
        }
    }
}

async function submitStages() {
    let url = route('preprojects.stages.store', { preproject_id: props.preproject.id })
    try {
        let response = await axios.put(url, formStages)
        updateStages(response.data, 'added')
        openModalAddedStages()
    } catch (error) {
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, formStages)
            } else {
                console.error("Server error:", error.response.data)
            }
        } else {
            notifyError("Network or other error:", error)
        }
    }
}

function openModalAddedStages() {
    showOpenAddedStages.value = !showOpenAddedStages.value
    formStages.clearErrors()
    formStages.defaults({ ... { reportStages: [] } })
    formStages.reset()
}

function updateStages(stages, action) {
    let validations = preprojectImages.value
    if (action === 'delete') {
        let index = validations.findIndex(item => item.id === stages)
        validations.splice(index, 1)
    } else if (action === 'added') {
        stages.forEach(element => {
            validations.push(element)
        });
    }
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

const addReportStage = () => {
    formStages.reportStages.push({
        type: '',
        title_id: '',
    });
}

const removeReportStage = (index) => {
    formStages.reportStages.splice(index, 1);
}
</script>