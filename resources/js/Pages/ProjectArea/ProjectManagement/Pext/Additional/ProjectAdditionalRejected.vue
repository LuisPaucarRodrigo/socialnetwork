<template>

    <Head title="Proyectos" />
    <AuthenticatedLayout :redirectRoute="{
        route: 'projectmanagement.pext.additional.index',
        params: { type: type }
    }">
        <template #header>
            Proyectos Adicionales - No Proceden
        </template>
        <Toaster richColors />
        <div class="min-w-full">
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <Search fields="Nombre,Codigo,CPE" v-model:search="formSearch.searchQuery" :searchFunction="search" />
            </div>
            <br>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <ProjectCard v-for="item in projects.data || projects" :key="item.id" :item="item"
                    :hasPermission="hasPermission" :rejectOrReturnAdditionalProject="approveAdditionalProject"
                    :type="type" :openQuickQuote="openQuickQuote" :editProject="editProject" />
            </div>
            <div v-if="projects.data" class="flex flex-col items-center px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="projects.links" />
            </div>
        </div>

        <!-- <Modal :show="showModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    {{ form.id ? 'Editar Asignación' : 'Nueva Asignación' }}
                </h2>
                <br>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div class="">
                            <InputLabel for="manager">Gestor</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.manager" autocomplete="off" id="manager"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.manager" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="assignation_date">Fecha de Asignación</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.assignation_date" autocomplete="off"
                                    id="assignation_date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.assignation_date" />
                            </div>
                        </div>

                        <div class="">
                            <InputLabel for="project_name">Nombre del Proyecto</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.project_name" autocomplete="off" id="project_name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.project_name" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="customer">Cliente</InputLabel>
                            <div class="mt-2">
                                <select id="customer" v-model="form.customer"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">Seleccionar Cliente</option>
                                    <option>CICSA</option>
                                    <option>STL</option>
                                </select>
                                <InputError :message="form.errors.customer" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="cost_center">Centro de Costos</InputLabel>
                            <div class="mt-2">
                                <select id="cost_center" v-model="form.cost_center_id"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">Seleccionar Centro de Costo</option>
                                    <option v-for="item in cost_line.cost_center" :key="item.id" :value="item.id">{{
                                        item.name
                                    }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.cost_center_id" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="project_code">Codigo de Proyecto</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.project_code" autocomplete="off" id="project_code"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.project_code" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="cpe">CPE</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.cpe" autocomplete="off" id="cpe"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.cpe" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="zone">Zona</InputLabel>
                            <div class="mt-2">
                                <select id="zone" v-model="form.zone" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">Seleccionar Zona</option>
                                    <option>Arequipa</option>
                                    <option>Moquegua</option>
                                    <option>Tacna</option>
                                    <option>Cuzco</option>
                                    <option>Puno</option>
                                    <option>MDD</option>
                                </select>
                                <InputError :message="form.errors.zone" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="zone2">Zona2 (Opcional)</InputLabel>
                            <div class="mt-2">
                                <select id="zone2" v-model="form.zone2" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">Seleccionar Zona</option>
                                    <option>Arequipa</option>
                                    <option>Moquegua</option>
                                    <option>Tacna</option>
                                    <option>Cuzco</option>
                                    <option>Puno</option>
                                    <option>MDD</option>
                                </select>
                                <InputError :message="form.errors.zone2" />
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="createOrEditModal"> Cancelar </SecondaryButton>
                        <PrimaryButton class="ml-3 tracking-widest uppercase text-xs"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                            Guardar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal> -->

        <FormQuickQuote ref="formQuickQuote" :projects="projects" :auth="auth" />

    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { notifyError, notify } from '@/Components/Notification';
import { Toaster } from 'vue-sonner';
import ProjectCard from './components/ProjectCard.vue';
import Search from '@/Components/Search.vue';
import FormQuickQuote from './components/FormQuickQuote.vue';

const { project, auth, userPermissions, cost_line, type } = defineProps({
    project: Object,
    userPermissions: Array,
    auth: Object,
    cost_line: Object,
    type: String,
})

const formQuickQuote = ref(null)

const initialState = {
    id: null,
    user_id: auth.user.id,
    assignation_date: '',
    project_name: '',
    cost_line_id: cost_line.id,
    cost_center_id: '',
    customer: '',
    project_code: '',
    cpe: '',
    zone: '',
    zone2: '',
    manager: '',
    user_name: auth.user.name,
}

const form = useForm({ ...initialState })

// const formExport = ref({
//     startDate: "",
//     endDate: ""
// })
// const modalExport = ref(false)

const hasPermission = (permission) => {
    return userPermissions.includes(permission);
}

const showModal = ref(false)
const projects = ref({ ...project });
// const confirmingProjectDeletion = ref(false);
// const projectToDelete = ref('');

function editProject(pext) {
    form.defaults({ ...pext, cost_center_id: pext.project.cost_center.id })
    form.reset()
    createOrEditModal()
}

const createOrEditModal = () => {
    if (showModal.value) {
        form.defaults({ ...initialState })
        form.reset()
    }
    showModal.value = !showModal.value

};

const formSearch = useForm({
    searchQuery: '',
})

async function search() {
    console.log(formSearch)
    let url = route('projectmanagement.pext.additional.index_rejected', { type })
    try {
        const response = await axios.post(url, formSearch);
        projects.value = response.data;
        console.log(response.data)
    } catch (error) {
        notifyError('Error searching:', error);
    }
};

// async function submit() {
//     let url = route('projectmanagement.pext.additional.store', { 'cicsa_assignation_id': form.id ?? null })
//     try {
//         let response = await axios.post(url, { ...form.data() })
//         let action = form.id ? 'update' : 'create'
//         updatePext(response.data, action)
//     } catch (error) {
//         if (error.response) {
//             if (error.response.data.errors) {
//                 setAxiosErrors(error.response.data.errors, form)
//             } else {
//                 notifyError("Server error:", error.response.data)
//             }
//         } else {
//             notifyError("Network or other error:", error)
//         }
//     }
// }

function openQuickQuote(item) {
    formQuickQuote.value.openQuickQuote(item)
}

function updatePext(pext, action) {
    const validations = projects.value.data || projects.value
    if (action === "create") {
        validations.unshift(pext);
        createOrEditModal()
        notify('Creacion Exitosa')
    } if (action === "update") {
        let index = validations.findIndex(item => item.id === pext.id)
        validations[index] = pext
        createOrEditModal()
        notify('Actualización Exitosa')
    } if (action === "delete") {
        let index = validations.findIndex(item => item.id === pext.id)
        validations[index].splice(index, 1)
        notify('Eliminación Exitosa')
    }

    if (validations.length > projects.value.per_page) {
        validations.pop();
    }
}

async function approveAdditionalProject(id) {
    try {
        const res = await axios.post(route('projectmanagement.pext.additional.reject', { pa_id: id }), { action: 'approve' })
        if (res.data.msg) {
            const validations = projects.value.data || projects.value
            let index = validations.findIndex(item => item.project.id === id)
            validations.splice(index, 1)
        }
    } catch (e) {
        console.log(e)
        notifyError('Server Error')
    }
}
</script>
