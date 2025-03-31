<template>
    <Modal :show="showModal">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                {{ form.id ? 'Actualizar Asignacion' : 'Crear ' }}
            </h2>
            <form @submit.prevent="submit">
                <div class="space-y-12 mt-4">
                    <div class="grid sm:grid-cols-2 gap-6">
                        <div v-if="!form.id">
                            <InputLabel for="pre_project_id">Ante Proyectos</InputLabel>
                            <div class="mt-2">
                                <select id="pre_project_id" v-model="form.pre_project_id" required
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">Seleccionar Ante Proyecto</option>
                                    <option v-for="item in projectsOrPreproject" :key="item.id" :value="item.id">
                                        {{ item.code }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.pre_project_id" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="manager">Gestor</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.manager" autocomplete="off" id="manager" />
                                <InputError :message="form.errors.manager" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="assignation_date">Fecha de Asignación</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" v-model="form.assignation_date" autocomplete="off"
                                    id="assignation_date" />
                                <InputError :message="form.errors.assignation_date" />
                            </div>
                        </div>

                        <div class="">
                            <InputLabel for="project_name">Nombre del Proyecto</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.project_name" autocomplete="off" id="project_name" />
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
                            <InputLabel for="project_code">Codigo de Proyecto</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.project_code" autocomplete="off" id="project_code" />
                                <InputError :message="form.errors.project_code" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="cpe">CPE</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.cpe" autocomplete="off" id="cpe" />
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
                    <div class="mt-6 flex items-center justify-end gap-x-3">
                        <SecondaryButton @click="createOrEditModal()">
                            Cancelar
                        </SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                            Guardar
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </div>
    </Modal>
</template>
<script setup>
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { setAxiosErrors } from '@/utils/utils';
import { notifyError, notify } from '@/Components/Notification';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import TextInput from '@/Components/TextInput.vue';

const { projects, auth } = defineProps({
    projects: Object,
    auth: Object,
})

const showModal = ref(false)
const projectsOrPreproject = ref(null)

const initialState = {
    id: "",
    user_id: auth.user.id,
    assignation_date: '',
    project_name: '',
    customer: '',
    project_code: '',
    cpe: '',
    zone: '',
    zone2: '',
    manager: '',
    user_name: auth.user.name,
    pre_project_id: '',
}

const form = useForm({ ...initialState })

const createOrEditModal = () => {
    requestProjectOrPreproject()
    if (showModal.value) {
        form.defaults({ ...initialState })
        form.reset()
    }
    showModal.value = !showModal.value
};

async function submit() {
    try {
        const url = route('projectmanagement.pext.storeProjectAndAssignation')
        const response = await axios.post(url, form)
        updatePext(response.data, 'create')
    } catch (error) {
        console.log(error)
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, form)
            } else {
                notifyError("Server error:", error.response.data)
            }
        } else {
            notifyError("Network or other error:", error)
        }
    }
}

function updatePext(pext, action) {
    const validations = projects.data || projects
    if (action === "create") {
        validations.unshift(pext);
        createOrEditModal()
        notify('Creacion Exitosa')
    }

    if (validations.length > projects.value.per_page) {
        validations.pop();
    }
}

async function requestProjectOrPreproject() {
    let url = route('projectmanagement.pext.requestProjectOrPreproject')
    try {
        let response = await axios.get(url)
        projectsOrPreproject.value = response.data
    } catch (error) {
        console.error('Error project or preproject:', error);
    }
}

defineExpose({ createOrEditModal })
</script>