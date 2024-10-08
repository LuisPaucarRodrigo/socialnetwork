<template>

    <Head title="Imagenes para Reporte" />
    <AuthenticatedLayout :redirectRoute="{route: 'huawei.projects', params: {status: backStatus, prefix: 'Claro'}}">
        <template #header>
            Imagenes para Reporte
        </template>
        <div class="min-w-full overflow-hidden rounded-lg">
            <div class="mt-6 flex items-center justify-between gap-x-3">
                <div class="mt-2 sm:flex sm:items-center space-x-4">
                <button v-if="props.huawei_project.status" @click="openAddStage" type="button" class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 whitespace-nowrap">
                    Agregar Etapa
                </button>
                <button v-if="props.selectedStage && props.huawei_project.status" @click.prevent="updateStage" type="button" class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 whitespace-nowrap">
                    {{ props.stages.find(item => item.id === props.selectedStage).status === 0 ? 'Habilitar': 'Deshabilitar' }}
                </button>
                </div>
                <div class="mt-2">
                    <select v-model="selectedStage" required id="code" @change="filterStage($event.target.value)"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        <option disabled>Seleccione Etapa</option>
                        <option value="">Ninguna</option>
                        <option v-for="item in props.stages" :key="item.id" :value="item.id"> {{ item.description }}
                        </option>
                    </select>
                </div>
            </div>
            <div v-for="imageCode in props.codes" :key="imageCode.id" class="border mt-2">
                <div class="flex items-center justify-between gap-x-6">
                    <h1 class="text-md font-bold text-gray-700 line-clamp-1 m-5">
                        {{ imageCode.huawei_code.code }} / {{ imageCode.huawei_code.description }}
                    </h1>
                    <PrimaryButton class="mr-5" v-if="!imageCode.status && props.huawei_project.status" @click="verifyApproveModal(imageCode.id)" type="button">
                        Aprobar
                    </PrimaryButton>
                    <span v-if="imageCode.status" class="text-green-600 mr-5">Aprobado</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4 mt-5">
                    <div v-for="image in imageCode.huawei_project_images" :key="image.id"
                        class="bg-white p-4 rounded-md shadow sm:col-span-1 md:col-span-2">
                        <h2 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">{{ image.description }}
                        </h2>
                        <div class="grid grid-cols-1 gap-y-1 text-sm text-center">
                            <img :src="image.image">
                            <p>Lat:{{ image?.lat }} Lon:{{ image?.lon }}</p>
                        </div>
                        <div class="pt-3 flex space-x-3 justify-center item-center">
                            <span v-if="image.state != null"
                                :class="image.state == '1' ? 'text-green-600' : 'text-red-600'">
                                {{ image.state == '1' ? 'Aprobado' : 'Rechazado' }}</span>
                            <div v-else class="flex space-x-3">
                                <button v-if="props.huawei_project.status" @click="approveImageModal(image.id)"
                                    class="flex items-center text-green-600 hover:underline">
                                    <CheckCircleIcon class="h-4 w-4 ml-1" />
                                </button>
                                <button v-if="props.huawei_project.status" @click="rejectModal(image.id)"
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
                            <button v-if="props.huawei_project.status" @click="confirmDeleteImagen(image.id)"
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
                                    <InputError :message="form.errors.observation" />
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

        <Modal :show="showAddStage">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Agregar Etapa
                </h2>
                <form @submit.prevent="submitAddStage">
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">
                            <div>
                                <InputLabel for="description">Descripción
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="formStage.description" id="description" />
                                    <InputError :message="formStage.errors.description" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="description">Título
                                </InputLabel>
                                <select v-model="formStage.title" required id="description"
                                    class="block mt-2 w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccione título</option>
                                    <option v-for="item in props.titles" :key="item.id" :value="item.id"> {{ item.name }}
                                    </option>
                                </select>
                                <span v-if="formStage.title" class="text-md text-gray-600">
                                    {{
                                    props.titles
                                        .filter(item => item.id === formStage.title)
                                        .flatMap(item => item.huawei_codes.map(code => code.code))
                                        .join(', ')
                                    }}
                                </span>
                            </div>
                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <SecondaryButton @click="closeAddStage"> Cancelar
                                </SecondaryButton>
                                <PrimaryButton type="submit" :class="{ 'opacity-25': formStage.processing }">
                                    Guardar
                                </PrimaryButton>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <ConfirmateModal :showConfirm="showApproveCode" tittle="Aprobacion de Código"
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
import { ref } from 'vue';
import { Head, router, useForm, Link } from '@inertiajs/vue3';
import { TrashIcon, ArrowDownIcon, EyeIcon, CheckCircleIcon, XCircleIcon } from '@heroicons/vue/24/outline';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ConfirmateModal from '@/Components/ConfirmateModal.vue';

const props = defineProps({
    stages: Array,
    huawei_project: Object,
    userPermissions: Array,
    codes: [Object, null],
    selectedStage: Number,
    titles: Object
});

const backStatus = props.huawei_project.status == 1 ? '1' : (props.huawei_project.status == null ? '2' : '3');

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}

const confirmingImageDeletion = ref(false);
const approve_reject_Image = ref(false);
const imageToDelete = ref(null);
const title_code_id = ref(null);
const showRejectModal = ref(false)
const imageCodeId = ref('');
const showApproveCode = ref(false);
const titleSuccessImage = ref('')
const messageSuccessImage = ref('')
const selectedStage = props.selectedStage ? props.selectedStage : '';
const showAddStage = ref(false);

const form = useForm({
    'id': '',
    'state': '',
    'observation': ''
})

const formStage = useForm({
    description: '',
    title: ''
});

const openAddStage = () => {
    showAddStage.value = true;
}

const closeAddStage = () => {
    formStage.clearErrors();
    formStage.reset();
    showAddStage.value = false
}

const submitAddStage = () => {
    formStage.post(route('huawei.projects.stages.addStage', { huawei_project: props.huawei_project.id }), {
        onSuccess: () => {
            closeAddStage();
            titleSuccessImage.value = "Etapa creada"
            messageSuccessImage.value = "La etapa se creó correctamente"
            approve_reject_Image.value = true
            setTimeout(() => {
                approve_reject_Image.value = false
            }, 2000)
        }
    })
}

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
        router.delete(route('huawei.projects.stages.deleteimage', { image: docId }), {
            onSuccess: () => {
                confirmingImageDeletion.value = false;
            }
        });
    }
};

function downloadImagen(imageId) {
    const routeToShow = route('huawei.projects.stages.downloadimage', {image: imageId});
    window.open(routeToShow, '_blank');
};

function openPreviewImagenModal(imageId) {
    const routeToShow = route('huawei.projects.stages.viewimage', {image: imageId});
    window.open(routeToShow, '_blank');
}


function closeRejectModal() {
    form.reset();
    form.clearErrors();
    imageCodeId.value = ''
    showRejectModal.value = false
}

function rejectModal(imageId) {
    imageCodeId.value = imageId
    showRejectModal.value = true
}

function approveImageModal(imageId) {
    form.state = true
    form.put(route('huawei.projects.stages.approveReject', { image: imageId }), {
        onSuccess: () => {
            titleSuccessImage.value = "Imagen Aprobada"
            messageSuccessImage.value = "La imagen se aprobó correctamente"
            approve_reject_Image.value = true
            setTimeout(() => {
                approve_reject_Image.value = false
            }, 2000)
        }
    })
}

function submitRejectImage() {
    form.state = false
    form.put(route('huawei.projects.stages.approveReject', { image: imageCodeId.value }), {
        onSuccess: () => {
            showRejectModal.value = false
            titleSuccessImage.value = "Imagen Rechazada"
            messageSuccessImage.value = "La imagen se rechazó correctamente"
            approve_reject_Image.value = true
            setTimeout(() => {
                approve_reject_Image.value = false
            }, 2000)
        }
    })
}

function filterStage($e) {
    if ($e === '') {
        router.visit(route('huawei.projects.stages', { huawei_project: props.huawei_project.id }))
    } else {
        router.visit(route('huawei.projects.stages.filter', {huawei_project: props.huawei_project.id, stage: $e}));
    }
}

function approveCode() {
    router.put(route('huawei.projects.stages.approveCode', { code: title_code_id.value }), null, {
        onSuccess: () => {
            titleSuccessImage.value = "Código Aprobado"
            messageSuccessImage.value = "El Codigo de aprobó correctamente"
            approve_reject_Image.value = true
            showApproveCode.value = false;
            setTimeout(() => {
                approve_reject_Image.value = false
            }, 2000)
        }
    })
}

function verifyApproveModal(preproject_code_id) {
    title_code_id.value = preproject_code_id
    showApproveCode.value = !showApproveCode.value
}

const updateStage = () => {
    router.put(route('huawei.projects.stages.updatestage', {stage: props.selectedStage}), null, {
        onSuccess: () => {
            titleSuccessImage.value = "Etapa Actualizada"
            messageSuccessImage.value = "La etapa se actualizó correctamente"
            approve_reject_Image.value = true
            setTimeout(() => {
                approve_reject_Image.value = false
            }, 2000)
        }
    });
}

</script>
