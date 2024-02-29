<template>
    <Head title="Proyectos" />
    <AuthenticatedLayout :redirectRoute="'preprojects.index'">
        <template #header>
            Gestión de Anteproyectos
        </template>
        <div class="min-w-full p-3 rounded-lg shadow">
            <div class="flex gap-4">
                <button @click="openCreatePreprojectModal" type="button"
                    class="inline-flex items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white">
                    + Agregar
                </button>
            </div>
            <br>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <div v-for="item in preprojects.data" :key="item.id"
                    class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
                    <div class="grid grid-cols-2">
                        <h2 class="text-sm font-semibold mb-3">
                            N° {{ item.code }}
                        </h2>
                        <div v-if="auth.user.role_id === 1" class="inline-flex justify-end gap-x-2">
                            <button @click="openEditPreprojectModal(item)" class="text-green-600 hover:underline mb-4 flex items-start">
                                <PencilIcon class="h-4 w-4" />
                            </button>
                            <button class="flex items-start" @click="confirmProjectDeletion(item.id)">
                                <TrashIcon class="h-4 w-4 text-red-500" />
                            </button>
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
                        {{ item.customer }}
                    </h3>
                    <div class="grid grid-cols-1 gap-y-1 text-sm">
                        <div>
                            <Link :href="route('preprojects.imagereport.index', {preproject_id:item.id})" class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Descargar imagenes
                            </Link>
                        </div>
                        <div>
                            <Link :href="route('preprojects.photoreport.index', {preproject_id:item.id})" class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Informe fotográfico
                            </Link>
                        </div>
                        <div>
                            <Link :href="route('preprojects.providersquotes.index',{
                                preproject_id: item.id
                            })" class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Cotización de proveedores
                            </Link>
                        </div>
                        <div v-if="item.has_photo_report">
                            <Link :href="route('preprojects.quote', {preproject_id: item.id})" class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Cotización para proyecto
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="preprojects.links" />
            </div>
        </div>

        <Modal :show="create_preproject || edit_preproject">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{ edit_preproject ? 'Actualizar AnteProyecto' : 'Crear Anteproyecto' }}
                </h2>
                <form @submit.prevent="edit_preproject ? submitEdit() : submit()">
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">

                            <div>
                                <InputLabel for="customer" class="mt-2 font-medium leading-6 text-gray-900">Cliente
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.customer" id="customer"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.customer" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="phone" class="mt-4 font-medium leading-6 text-gray-900">Teléfono
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.phone" id="phone" maxlength="9"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.phone" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="description" class="mt-4 font-medium leading-6 text-gray-900">Descripción
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.description" id="description"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.description" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="address" class="mt-4 font-medium leading-6 text-gray-900">Dirección
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.address" id="address"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.address" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="date" class="mt-4 font-medium leading-6 text-gray-900">Fecha de Visita
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="date" v-model="form.date" id="date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.date" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="date" class="mt-4 font-medium leading-6 text-gray-900">Código de proyecto
                                </InputLabel>
                                <p class="text-gray-400">Ejemplo: CCCCC-PPPPP </p>
                                <p class="text-gray-400">CCCCC -> 5 iniciales cliente | PPPPP -> 5 iniciales proyecto</p>
                                <div class="mt-2 flex justify-center items-center gap-2">
                                    <input required type="text" v-model="form.code" id="name"  pattern="[a-zA-Z]{5}-[a-zA-Z]{5}" maxlength="11"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 uppercase" />
                                </div>
                                <InputError :message="form.errors.code" />
                            </div>

                            <div>
                                <InputLabel for="observation" class="mt-4 font-medium leading-6 text-gray-900">Observaciones
                                </InputLabel>
                                <div class="mt-2">
                                    <textarea type="text" v-model="form.observation" id="observation"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.observation" />
                                </div>
                            </div>

                            <div v-if="edit_preproject" >
                                <InputLabel for="observation" class="mt-4 font-medium leading-6 text-green-500">Imagen actual
                                </InputLabel>
                                <img class="ring-1 ring-green-300 " :src="`/image/facades/${previewFacade}`">
                            </div>

                            <div>
                                <InputLabel for="facade" class="mt-4 font-medium leading-6 text-gray-900">Foto de Fachada
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="file" @change="handleFacadeChange" id="facade"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" accept="image/*" />
                                    <InputError :message="form.errors.facade" />
                                </div>
                            </div>


                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <SecondaryButton
                                    @click="edit_preproject ? closeEditPreprojectModal() : closeCreatePreprojectModal()">
                                    Cancelar </SecondaryButton>
                                <button type="submit" :class="{ 'opacity-25': form.processing }"
                                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
                                    
                                </div>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <ConfirmDeleteModal :confirmingDeletion="confirmingProjectDeletion" itemType="Anteproyecto"
            :deleteFunction="delete_project" @closeModal="closeModal" />
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="Anteproyecto" />
        <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Anteproyecto" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import Pagination from '@/Components/Pagination.vue'
import InputError from '@/Components/InputError.vue';
import InputFile from '@/Components/InputFile.vue';
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';
import { ref, computed } from 'vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';


const { preprojects, visits, auth} = defineProps({
    preprojects: Object,
    visits: Object,
    auth:Object
})

const initial_state = {
    customer:'',
    code:'',
    phone:'',
    description:'',
    address:'',
    date:'',
    observation:'',
    facade:null,
}
const form = useForm({
    ...initial_state
});

const submit = () => {
    form.post(route('preprojects.store'), {
        onSuccess: () => {
            closeCreatePreprojectModal();
            form.reset();
            showModal.value = true
            setTimeout(() => {
                showModal.value = false;
                router.visit(route('preprojects.index'))
            }, 2000);
        },
        onError: () => {
            form.reset();
        },
        onFinish: () => {
            form.reset();
        }
    });
};

const submitEdit = () => {
    form.post(route('preprojects.update', { preproject: form.id }), {
        preserveScroll: true,
        onSuccess: () => {
            closeEditPreprojectModal();
            form.reset();
            showModalEdit.value = true
            setTimeout(() => {
                showModalEdit.value = false;
                router.visit(route('preprojects.index'))
            }, 2000);
        },
    });
};

const confirmingProjectDeletion = ref(false);
const projectToDelete = ref('');
const create_preproject = ref(false);
const edit_preproject = ref(false);
const showModal = ref(false);
const showModalEdit = ref(false);

const openCreatePreprojectModal = () => {
    create_preproject.value = true;
}

const closeCreatePreprojectModal = () => {
    create_preproject.value = false;
}

const openEditPreprojectModal = (preproject) => {
    form.defaults({
        ...preproject, code: preproject.code.slice(9), facade:null
    })
    form.reset()
    previewFacade.value = preproject.facade
    edit_preproject.value = true;
}

const closeEditPreprojectModal = () => {
    form.defaults(initial_state)
    form.reset();
    edit_preproject.value = false;
}

const delete_project = () => {
    const projectId = projectToDelete.value;
    router.delete(route('preprojects.destroy', { preproject: projectId }), {
        onSuccess: () => closeModal()
    });
}

const confirmProjectDeletion = (employeeId) => {
    projectToDelete.value = employeeId;
    confirmingProjectDeletion.value = true;
};

const closeModal = () => {
    confirmingProjectDeletion.value = false;
};


const previewFacade = ref(null)
function handleFacadeChange (e) {
    form.facade = e.target.files[0]
}



</script>