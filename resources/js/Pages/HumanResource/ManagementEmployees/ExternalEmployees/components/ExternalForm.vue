<template>
    <Modal :show="show_m_employee">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                {{ form.id ? 'Actualizar Empleado' : 'Agregar Empleado' }}
            </h2>
            <form @submit.prevent="submit">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div v-if="form.profile" class="flex justify-center">
                            <img :src="form.profile" alt="Imagen Personal" class="rounded-full h-45 w-45 py-5">
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="reentry_date">Foto de Usuario</InputLabel>
                            <div class="mt-2">
                                <ModalImage v-model="form.cropped_image" @imagenRecortada="handleImagenRecortada" />
                                <InputError :message="form.errors.cropped_image" />
                            </div>
                        </div>
                        <div class="sm:col-span-3 sm:col-start-1">
                            <InputLabel for="name">Nombre</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="name" v-model="form.name" required />
                                <InputError :message="form.errors.name" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="lastname">Apellido</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="lastname" v-model="form.lastname" required />
                                <InputError :message="form.errors.lastname" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="cost_line_id">
                                Linea de Negocio
                            </InputLabel>
                            <div class="mt-2">
                                <select v-model="form.cost_line_id" id="cost_line_id" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Linea de Negocio</option>
                                    <option v-for="item, i in costLines" :key="item.id" :value="item.id">{{ item
                                        .name }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.cost_line_id" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="gender">Genero</InputLabel>
                            <div class="mt-2">
                                <select id="gender" v-model="form.gender" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Genero</option>
                                    <option>Masculino</option>
                                    <option>Femenino</option>
                                </select>
                                <InputError :message="form.errors.gender" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="address">Direccion</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="address" v-model="form.address" required />
                                <InputError :message="form.errors.address" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="birthdate">Fecha de Nacimiento</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" id="birthdate" v-model="form.birthdate" required />
                                <InputError :message="form.errors.birthdate" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="dni">DNI</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="dni" v-model="form.dni" maxlength="8" required />
                                <InputError :message="form.errors.dni" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="email">Correo Electronico</InputLabel>
                            <div class="mt-2">
                                <TextInput type="email" id="email" v-model="form.email" required />
                                <InputError :message="form.errors.email" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="email_company">Correo Electronico de Compañia</InputLabel>
                            <div class="mt-2">
                                <TextInput type="email" id="email_company" v-model="form.email_company" />
                                <InputError :message="form.errors.email_company" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="phone1">Telefono</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="phone1" v-model="form.phone1" maxlength="9" required />
                                <InputError :message="form.errors.phone1" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="salary">Salario</InputLabel>
                            <div class="mt-2">
                                <TextInput type="number" id="salary" v-model="form.salary" required />
                                <InputError :message="form.errors.salary" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="sctr">¿Tiene SCTR?</InputLabel>
                            <div class="mt-2 class flex gap-4">
                                <label class="flex gap-2 items-center">
                                    Sí
                                    <input type="radio" v-model="form.sctr" id="sctr" :value="1"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                </label>
                                <label class="flex gap-2 items-center">
                                    No
                                    <input type="radio" v-model="form.sctr" id="sctr" :value="0"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                </label>
                                <InputError :message="form.errors.sctr" />
                            </div>
                        </div>
                        <div class="sm:col-span-5 mt-2">
                            <InputLabel for="curriculum_vitae">Curriculum Vitae</InputLabel>
                            <div class="mt-2">
                                <InputFile type="file" v-model="form.curriculum_vitae" id="curriculum_vitae"
                                    accept=".pdf"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.curriculum_vitae" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-3">
                        <SecondaryButton @click="closeExternal()"> Cancel </SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }"> Guardar
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </div>
    </Modal>
</template>
<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ModalImage from '@/Layouts/ModalImage.vue';
import InputFile from '@/Components/InputFile.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { toFormData } from '@/utils/utils';
// import { toFormData } from 'axios'
import { useForm } from '@inertiajs/vue3';
import { notify, notifyError } from '@/Components/Notification';
import { ref } from 'vue';
import { useAxiosErrorHandler } from '@/utils/axiosError';

const { employees, costLines } = defineProps({
    employees: Object,
    costLines: Array
})

const show_m_employee = ref(false);

const initialState = {
    id: '',
    name: '',
    lastname: '',
    cost_line_id: '',
    gender: '',
    address: '',
    birthdate: '',
    dni: '',
    email: '',
    email_company: '',
    phone1: '',
    salary: '',
    sctr: '',
    curriculum_vitae: null,
    cropped_image: null,
    profile: null
}

const form = useForm({ ...initialState })

const handleImagenRecortada = (imagenRecorted) => {
    form.cropped_image = imagenRecorted;
};

function toogleExternal() {
    show_m_employee.value = !show_m_employee.value
}

function openExternal(item = initialState) {
    toogleExternal()
    form.defaults({ ...item, profile: item.cropped_image, cropped_image: null, curriculum_vitae: null });
    form.reset();
}

function closeExternal() {
    toogleExternal()
    form.defaults({ ...initialState })
    form.reset()
}

async function submit() {
    let url = route('management.external.storeorupdate', { external_id: form.id })
    try {
        let formData = toFormData(form.data())
        let response = await axios.post(url, formData)
        let action = form.id ? 'update' : 'create'
        let itemId = form.id ?? null
        try {
            updateFrontEnd(response.data, action, itemId);
        } catch (frontendError) {
            console.error(frontendError);
            notifyError("Ocurrió un error al actualizar la interfaz. Por favor, actualice la página.");
        }
    } catch (error) {
        console.log(error);
        useAxiosErrorHandler(error, form)
    }
}

function updateFrontEnd(response, action, itemId) {
    let validations = employees.data || employees
    if (action === 'create') {
        validations.unshift(response)
        closeExternal()
        notify("Creacion existosa")
    } else if (action === 'update') {
        let index = validations.findIndex(item => item.id === itemId)
        validations[index] = response
        closeExternal()
        notify("Actualización exitosa")
    }
}

defineExpose({ openExternal })
</script>