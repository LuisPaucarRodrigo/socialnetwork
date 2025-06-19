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
                                    <option>INDRA</option>
                                </select>
                                <InputError :message="form.errors.customer" />
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
                                    <option v-for="zone in zones" :key="zone" :value="zone">{{ zone }}</option>
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
                                    <option v-for="zone in zones" :key="zone" :value="zone">{{ zone }}</option>
                                </select>
                                <InputError :message="form.errors.zone2" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="zone2">Zona3 (Opcional)</InputLabel>
                            <div class="mt-2">
                                <select id="zone2" v-model="form.zone3" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">Seleccionar Zona</option>
                                    <option v-for="zone in zones" :key="zone" :value="zone">{{ zone }}</option>
                                </select>
                                <InputError :message="form.errors.zone3" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-3">
                        <SecondaryButton @click="closeForm()">
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
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { notify } from '@/Components/Notification';
import { useAxiosErrorHandler } from '@/utils/axiosError';
import { zones } from '../constants';

const { assignations } = defineProps({
    assignations: Object
})

const initialState = {
    id: null,
    project_code: '',
    project_name: '',
    manager: '',
    assignation_date: '',
    form_name: '',
    customer: '',
    form_code: '',
    cpe: '',
    zone: '',
    zone2: '',
    zone3: '',
}

const form = useForm({
    ...initialState
})

const showModal = ref(false)

function toogleModal() {
    showModal.value = !showModal.value
}

function updateAssignation(item) {
    form.defaults({ ...item })
    form.reset()
    toogleModal()
}

function closeForm() {
    form.defaults({ ...initialState })
    form.reset()
    toogleModal()
}

async function submit() {
    let url = route('assignation.update', { cicsa_assignation_id: form.id })
    try {
        const response = await axios.post(url, form)
        updateFrontEnd(response.data, 'update', form.id)
    } catch (error) {
        console.error(error)
        useAxiosErrorHandler(error, form);
    }
}

function updateFrontEnd(response, action, itemId) {
    const validations = assignations.data || assignations
    if (action === 'update') {
        let index = validations.findIndex(item => item.id === itemId)
        validations[index] = response
        notify("Actualizacion exitosa")
        closeForm()
    }
}

defineExpose({ updateAssignation })
</script>