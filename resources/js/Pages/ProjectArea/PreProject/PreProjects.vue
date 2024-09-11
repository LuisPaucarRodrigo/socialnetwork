<template>

    <Head title="AnteProyectos" />
    <AuthenticatedLayout :redirectRoute="'preprojects.index'">
        <template #header>
            Anteproyectos {{ preprojects_status !== null
        ? preprojects_status === '1' ? 'Aprobados'
            : 'Anulados'
        : '' }}
        </template>
        <div class="min-w-full p-3 rounded-lg shadow">
            <div class="mt-6 flex items-center justify-between gap-x-6">
                <div class="flex space-x-2">
                    <Link v-if="preprojects_status === null && hasPermission('ProjectManager')"
                        :href="route('preprojects.create')"
                        class="inline-flex items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-500 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white">
                    + Agregar
                    </Link>
                    <Link v-if="preprojects_status === null && hasPermission('ProjectManager')"
                        :href="route('project.auto.pint')"
                        class="inline-flex items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs  uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-500 focus:border-indigo-600 focus:outline-none focus:ring-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="white" class="h-5 w-5 hover:text-gray-700">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 0 0 2.25-2.25V6a2.25 2.25 0 0 0-2.25-2.25H6A2.25 2.25 0 0 0 3.75 6v2.25A2.25 2.25 0 0 0 6 10.5Zm0 9.75h2.25A2.25 2.25 0 0 0 10.5 18v-2.25a2.25 2.25 0 0 0-2.25-2.25H6a2.25 2.25 0 0 0-2.25 2.25V18A2.25 2.25 0 0 0 6 20.25Zm9.75-9.75H18a2.25 2.25 0 0 0 2.25-2.25V6A2.25 2.25 0 0 0 18 3.75h-2.25A2.25 2.25 0 0 0 13.5 6v2.25a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>

                    </Link>

                </div>
                <div class="flex gap-4">
                    <PrimaryButton v-if="preprojects_status === null" @click="() => {
        router.get(route('preprojects.index', { preprojects_status: '1' }))
    }" type="button">
                        Aprobados
                    </PrimaryButton>
                    <PrimaryButton v-if="preprojects_status === null" @click="() => {
        router.get(route('preprojects.index', { preprojects_status: '0' }))
    }" type="button">
                        Anulados
                    </PrimaryButton>

                    <input type="text" @input="search($event.target.value)" placeholder="Codigo,Descripcion" >
                </div>
            </div>

            <br>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <div v-for="(item, i) in preprojects.data" :key="item.id"
                    class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
                    <div class="grid grid-cols-2">
                        <h2 class="text-sm font-semibold mb-3 sm:col-span-1">
                            N° {{ i }} {{ item.code }}
                        </h2>
                        <div v-if="auth.user.role_id === 1" class="inline-flex justify-end gap-x-2 m:col-span-1">
                            <Link :href="route('preprojects.create', { preproject_id: item.id })"
                                class="text-green-600 hover:underline mb-4 flex items-start">
                            <PencilIcon class="h-4 w-4" />
                            </Link>
                            <button class="flex items-start" @click="confirmProjectDeletion(item.id, i)">
                                <TrashIcon class="h-4 w-4 text-red-500" />
                            </button>
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
                        {{ item.customer }}
                    </h3>
                    <div class="grid grid-cols-1 gap-y-1 text-sm">
                        <div v-if="hasPermission('ProjectManager')">
                            <button @click="assignUser(item.id, item.users)"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                                Asignar Usuarios
                            </button>
                        </div>
                        <div>
                            <Link :href="route('preprojects.imagereport.index', { preproject_id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                            Descargar imagenes
                            </Link>
                        </div>
                        <div>
                            <Link :href="route('preprojects.photoreport.index', { preproject_id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                            Informe fotográfico
                            </Link>
                        </div>
                        <div>
                            <Link v-if="item.customer_id == 1"
                                :href="route('preprojects.products', { preproject: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                            Productos de Almacén
                            </Link>
                            <span v-else class="text-gray-400">Productos de Almacen</span>
                        </div>
                        <div v-if="item.project == null && item.status === null">
                            <Link v-if="item.customer_id == 1"
                                :href="route('preprojects.request.index', { id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                            Solicitud de Compras
                            </Link>
                            <span v-else class="text-gray-400">Solicitud de Compras</span>
                        </div>
                        <div v-else>
                            <span class="text-gray-600"> Solicitud de Compras</span>
                        </div>
                        <div v-if="item.project == null && item.status === null">
                            <Link v-if="item.customer_id == 1"
                                :href="route('preprojects.purchase_quote', { id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                            Cotizaciones de Compras
                            </Link>
                            <span v-else class="text-gray-400">Cotizaciones de Compras</span>
                        </div>
                        <div
                            v-if="item.has_photo_report && (item.status === null || item.status == true) && hasPermission('ProjectManager')">
                            <Link v-if="item.customer_id == 1"
                                :href="route('preprojects.quote', { preproject_id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                            Cotización para proyecto
                            </Link>
                            <span v-else class="text-gray-400">Cotización para proyecto</span>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="preprojects.links" />
            </div>
        </div>

        <Modal :show="assignUserModal">
            <form class="p-6" @submit.prevent="submitAssignUser">
                <h2 class="text-lg font-medium text-gray-900">
                    Agregar usuarios
                </h2>
                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                    <div class="sm:col-span-3">
                        <InputLabel for="users" class="font-medium leading-6 text-gray-900">Usuarios</InputLabel>
                        <div class="mt-2">
                            <select multiple v-model="assignUserForm.user_id_array" id="users"
                                size="20"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option v-for="user in props.users" :key="user.id" :value="user.id">
                                    {{ user.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <InputLabel for="users" class="font-medium leading-6 text-gray-900">
                            Usuarios Asginados
                        </InputLabel>
                        <div class="mt-2">
                                <p v-for="user in props.users" :key="user.id" :value="user.id" class="text-xs">
                                    - {{ user.name }}
                                </p>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex gap-3 justify-end">
                    <SecondaryButton type="button" @click="closeAssignUser">Cerrar</SecondaryButton>
                    <PrimaryButton type="submit">Asignar</PrimaryButton>
                </div>
            </form>
        </Modal>

        <ConfirmDeleteModal :confirmingDeletion="confirmingProjectDeletion" itemType="Anteproyecto"
            :deleteFunction="delete_project" @closeModal="closeModal" />
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="Anteproyecto" />
        <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Anteproyecto" />
        <ConfirmCreateModal :confirmingcreation="successAssign" itemType="Asignación" />

    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Pagination from '@/Components/Pagination.vue'
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    preprojects: Object,
    auth: Object,
    preprojects_status: String,
    users: Object,
    userPermissions: Array
})

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}

const assignUserModal = ref(false);
const successAssign = ref(false);
const confirmingProjectDeletion = ref(false);
const projectToDelete = ref('');
const indexToSplice = ref('');
const showModal = ref(false);
const showModalEdit = ref(false);
const preprojects = ref(props.preprojects)

const assignUser = (id, users) => {
    assignUserModal.value = true;
    assignUserForm.preproject_id = id;
    assignUserForm.user_id_array = users.map(item=>item.id);
}

const closeAssignUser = () => {
    assignUserForm.reset();
    assignUserModal.value = false;
}

const assignUserForm = useForm({
    preproject_id: null,
    user_id_array: []
});

const submitAssignUser = () => {
    assignUserForm.post(route('preprojects.assign.users'), {
        onSuccess: () => {
            closeAssignUser();
            successAssign.value = true
            setTimeout(() => {
                successAssign.value = false;
                router.visit(route('preprojects.index'))
            }, 2000);
        },
        onError: (e) => {
            console.log(e)
        }
    })
}

const delete_project = () => {
    const projectId = projectToDelete.value;
    router.delete(route('preprojects.destroy', { preproject: projectId }), {
        onSuccess: () => {
            closeModal();
            preprojects.value.data.splice(indexToSplice, 1)

        }
    });
}

const confirmProjectDeletion = (id, i) => {
    projectToDelete.value = id;
    indexToSplice.value = i
    confirmingProjectDeletion.value = true;
};

const closeModal = () => {
    confirmingProjectDeletion.value = false;
};


const search = async ($search) => {
    if ($search === '') {
        router.visit(route('preprojects.index', {
            preprojects_status: JSON.parse(props.preprojects_status)
        }))
        return
    }
    try {
        const response = await axios.post(route('preprojects.index'), { searchQuery: $search, preprojects_status: props.preprojects });
        preprojects.value = response.data.preprojects;
    } catch (error) {
        console.error('Error searching:', error);
    }
};

</script>