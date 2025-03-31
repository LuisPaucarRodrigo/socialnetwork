<template>
    <Modal :show="showModal">
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
                            <input type="date" v-model="form.assignation_date" autocomplete="off" id="assignation_date"
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
                                <option disabled value="">Seleccionar Zona</option>
                                <option v-for="item in optionZones">{{ item }}</option>
                            </select>
                            <InputError :message="form.errors.zone" />
                        </div>
                    </div>
                    <div class="">
                        <InputLabel for="zone2">Zona2 (Opcional)</InputLabel>
                        <div class="mt-2">
                            <select id="zone2" v-model="form.zone2" autocomplete="off"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option disabled value="">Seleccionar Zona</option>
                                <option v-for="item in optionZones">{{ item }}</option>
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
    </Modal>
</template>
<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { notify, notifyError } from '@/Components/Notification';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { setAxiosErrors } from '@/utils/utils';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const { auth, cost_line, optionZones, projects } = defineProps({
    auth: Object,
    cost_line: Object,
    optionZones: Array,
    projects: Object
})

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
const showModal = ref(false)

const createOrEditModal = () => {
    if (showModal.value) {
        form.defaults({ ...initialState })
        form.reset()
    }
    showModal.value = !showModal.value

};

function editProject(pext) {
    form.defaults({ ...pext, cost_center_id: pext.project.cost_center.id })
    form.reset()
    createOrEditModal()
}

async function submit() {
    let url = route('projectmanagement.pext.additional.store', { 'cicsa_assignation_id': form.id ?? null })
    try {
        const response = await axios.post(url, { ...form.data() })
        const action = form.id ? 'update' : 'create'
        updatePext(response.data, action)
    } catch (error) {
        console.error(error)
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
    } else if (action === "update") {
        let index = validations.findIndex(item => item.id === pext.id)
        validations[index] = pext
        createOrEditModal()
        notify('Actualización Exitosa')
    }

    if (validations.length > projects.per_page) {
        validations.pop();
    }
}

defineExpose({ createOrEditModal, editProject })
</script>