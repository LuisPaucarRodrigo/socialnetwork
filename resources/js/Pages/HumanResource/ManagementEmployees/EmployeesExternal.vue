<template>

    <Head title="Gestion de Empleados" />
    <AuthenticatedLayout :redirectRoute="'employees.external.index'">
        <template #header>
            Empleados Externos
        </template>

        <div class="min-w-full rounded-lg shadow">
            <div class="mt-6 sm:flex sm:gap-4 sm:justify-between">
                <div class="flex items-center justify-between gap-x-3 w-full">
                    <div v-if="hasPermission('HumanResourceManager')" class="hidden sm:flex sm:items-center space-x-4">
                        <PrimaryButton @click="modal_employees_external()" type="button">
                            + Agregar
                        </PrimaryButton>
                    </div>
                </div>

            </div>
            <div class="overflow-auto h-[72vh] rounded-lg shadow">
                <table class="w-full">
                    <thead class="sticky top-0 z-20">
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 w-auto">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Perfil
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                <div class="w-[190px]">
                                    <TableHeaderCicsaFilter label="Linea de Negocio" labelClass="text-gray-600"
                                        :options="cost_line" v-model="formSearch.cost_line" />
                                </div>
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Nombre
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Apellido
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                DNI
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Telefono
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Nacimiento
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Direccion
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Correo
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Correo Empresarial
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Salario
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Sctr
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Curriculum
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="employee in employees" :key="employee.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-2 text-sm">
                                <img :src="employee.cropped_image" alt="Empleado" class="w-12 h-13 rounded-full">
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-2 text-sm">
                                <p class="text-gray-900">{{ employee?.cost_line?.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900">{{ employee.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900">{{ employee.lastname }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900">{{ employee.dni }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900">{{ employee.phone1 }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900">
                                    {{ employee.birthdate }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900">
                                    {{ employee.address }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900">
                                    {{ employee.email }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900">
                                    {{ employee.email_company }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900">
                                    {{ employee.salary }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900">
                                    {{ employee.sctr ? 'Si' : 'No' }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <button v-if="employee.curriculum_vitae" @click="handlerPreview(employee.id)">
                                    <EyeIcon class="w-4 h-4 text-teal-600" />
                                </button>
                                <span v-else>-</span>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <button v-if="hasPermission('HumanResourceManager')" type="button"
                                        @click="modal_employees_external(employee)"
                                        class="text-blue-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>
                                    <button v-if="hasPermission('HumanResourceManager')" type="button"
                                        @click="confirmUserDeletion(employee.id)"
                                        class="text-blue-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <Modal :show="show_m_employee">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{ form.id ? 'Actualizar Empleado' : 'Agregar Empleado' }}
                </h2>
                <form @submit.prevent="submit">
                    <div class="border-b border-gray-900/10 pb-12">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div v-if="form.cropped_image" class="flex justify-center">
                                <img :src="form.cropped_image" alt="Imagen Personal" class="rounded-full h-45 w-45 py-5">
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
                                            .name}}
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
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.curriculum_vitae" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-3">
                            <SecondaryButton @click="modal_employees_external()"> Cancel </SecondaryButton>
                            <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }"> Guardar
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>
        <ConfirmCreateModal :confirmingcreation="showModalCreate" itemType="empleado externo" />
        <ConfirmDeleteModal :confirmingDeletion="confirmingUserDeletion" itemType="empleado" deleteText="Eliminar"
            :deleteFunction="deleteEmployee" @closeModal="closeModal" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import { EyeIcon } from "@heroicons/vue/24/outline";
import ModalImage from '@/Layouts/ModalImage.vue';
import InputFile from '@/Components/InputFile.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TableHeaderCicsaFilter from '@/Components/TableHeaderCicsaFilter.vue';

const confirmingUserDeletion = ref(false);
const employeeToDelete = ref(null);
const show_m_employee = ref(false);
const showModalCreate = ref(false);

const props = defineProps({
    employee: Object,
    userPermissions: Array,
    costLines: Object,
})

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}
const employees = ref(props.employee)
const cost_line = props.costLines.map(item => item.name)

const initialState = {
    id: null,
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
    profile: ''
}

const form = useForm({ ...initialState })

const initialFormSearch = {
    cost_line: [...cost_line],
}

const formSearch = ref({ ...initialFormSearch })

watch(
    () => [
        formSearch.value.cost_line,
    ],
    () => {
        search();
    },
    { deep: true }
);

const submit = () => {
    form.post(route('management.external.storeorupdate', { external_id: form.id }), {
        onSuccess: () => {
            modalCreate()
            modal_employees_external();
            setTimeout(() => {
                modalCreate()
                router.visit(route('employees.external.index'));
            }, 2000);

        },
        onError: (e) => {
            console.log(e);
        }
    })
}

const confirmUserDeletion = (employeeId) => {
    employeeToDelete.value = employeeId;
    confirmingUserDeletion.value = true;
};

const deleteEmployee = () => {
    const employeeId = employeeToDelete.value;
    if (employeeId) {
        router.delete(route('employees.external.delete', { id: employeeId }), {
            onSuccess: () => closeModal()
        });
    }
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
};

function modal_employees_external(employee) {
    if (employee != null) {
        form.defaults({ ...employee })
        form.reset()
    } else {
        form.defaults({ ...initialState })
        form.reset()
    }
    show_m_employee.value = !show_m_employee.value
};

function modalCreate() {
    showModalCreate.value = !showModalCreate.value
}

const handleImagenRecortada = (imagenRecorted) => {
    form.cropped_image = imagenRecorted;
};

function handlerPreview(id) {
    window.open(
        route("employees.external.preview.curriculum_vitae", { external_preview_id: id }),
        '_blank'
    );
}

async function search() {
    let url = route('employees.external.search')
    try {
        let response = await axios.post(url, formSearch.value)
        employees.value = response.data
    } catch (error) {
        if (error.response.data) {
            notifyError('Server error', error.response.data)
        } else {
            notifyError("Network or other error:", error)
        }
    }
}
</script>