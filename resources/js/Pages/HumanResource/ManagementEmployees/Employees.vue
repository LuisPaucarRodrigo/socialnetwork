<template>
    <Head title="Gestion de Empleados" />

    <AuthenticatedLayout>
        <template #header>
            Empleados
        </template>
        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
            <button @click="add_information" type="button"
                class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                + Agregar
            </button>

            <button @click="openScheduleModal" type="button"
                class="mx-3 rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                Horario
            </button>

            <table class="w-full whitespace-no-wrap overflow-auto">
                <thead>
                    <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
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
                            Email
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Telefono
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="employee in employees.data" :key="employee.id" class="text-gray-700">
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <img :src="employee.cropped_image" alt="Empleado" class="w-12 h-13 rounded-full">
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ employee.name }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ employee.lastname }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ employee.email }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ employee.phone1 }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <div class="flex space-x-3 justify-center">
                                <Link class="text-blue-900 whitespace-no-wrap"
                                    :href="route('management.employees.information.details', { id: employee.id })">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6 text-teal-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                </Link>
                                <Link class="text-blue-900 whitespace-no-wrap"
                                    :href="route('management.employees.edit', { id: employee.id })">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6 text-amber-400">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                                </Link>
                                <button type="button" @click="confirmUserDeletion(employee.id)"
                                    class="text-blue-900 whitespace-no-wrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                                <button type="button" @click="confirmFired(employee.id)"
                                    class="text-blue-900 whitespace-no-wrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 3.75H6.912a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="employees.links" />
            </div>
        </div>
        <Modal :show="showModalFired">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Despido del Empleado
                </h2>
                <form @submit.prevent="submit">
                    <div class="border-b border-gray-900/10 pb-12">
                        <div class="mt-2">
                            <InputLabel for="fired_date" class="font-medium leading-6 text-gray-900">Fecha de Deceso:
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" id="fired_date" v-model="form.fired_date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.fired_date" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="days_taken" class="font-medium leading-6 text-gray-900">Dias Tomados:
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="days_taken" v-model="form.days_taken"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.days_taken" />
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeFiredModal"> Cancel </SecondaryButton>
                            <button type="submit" :class="{ 'opacity-25': form.processing }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <Modal :show="showModalSchedule">
            <div class="p-8">
                <h2 class="text-lg font-medium leading-7 text-gray-900"> 
                    {{ props.fileExists ? 'Actualizar Horario' : 'Agregar Horario' }}
                </h2>
                <form @submit.prevent="submitSchedule">
                    <div class="space-y-8">
                        <div class="border-b border-gray-900/10 pb-8" v-if="!props.fileExists">
                            <div>
                                <div class="mt-4">
                                    <InputFile type="file" v-model="formSchedule.document" id="documentFile" accept=".xlsx"
                                        class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.document" />
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            
                            <div>
                                <div class="mt-4">
                                    <InputFile type="file" v-model="formSchedule.document" id="documentFile" accept=".xlsx"
                                        class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.document" />
                                </div>
                            </div>
                            <h2 class="text-lg font-medium leading-7 text-gray-900 mt-4">
                                Horario
                            </h2>
                            <div class="mt-4" style="max-height: 600px; overflow-y: auto;"> 
                                <table v-if="excelData"
                                    class="w-full whitespace-no-wrap overflow-auto border border-gray-200">
                                    <thead>
                                        <tr class="border-b bg-gray-50 text-left text-sm font-semibold uppercase tracking-wide text-gray-500">
                                            <th v-for="header in excelData[0]" :key="header"
                                                class="border-b-2 border-gray-200 bg-gray-100 px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider text-gray-600">
                                                {{ header }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(row, index) in excelData" :key="index" class="text-gray-700">
                                            <td v-for="cell in row" :key="cell"
                                                class="border-b border-gray-200 bg-white px-6 py-4 text-sm">
                                                {{ cell }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p v-else class="mt-2">Cargando archivo...</p>
                            </div>
                        </div>
                        <div class="mt-8 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeScheduleModal"> Cancel </SecondaryButton>
                            <button type="submit" :class="{ 'opacity-25': form.processing }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>


        <ConfirmDeleteModal :confirmingDeletion="confirmingUserDeletion" itemType="empleado" :deleteText="deleteButtonText"
            :deleteFunction="deleteEmployee" @closeModal="closeModal" />
        <ConfirmCreateModal :confirmingcreation="createSchedule" itemType="Horario" />
        <ConfirmUpdateModal :confirmingupdate="updateSchedule" itemType="Horario" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Pagination from '@/Components/Pagination.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputFile from '@/Components/InputFile.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import InputError from '@/Components/InputError.vue';
import * as XLSX from 'xlsx';


const confirmingUserDeletion = ref(false);
const deleteButtonText = 'Eliminar';
const employeeToDelete = ref(null);
const employeeToFired = ref(null);
const showModalFired = ref(false);
const createSchedule = ref(false);
const updateSchedule = ref(false);
const showModalSchedule = ref(false);

const props = defineProps({
    employees: Object,
    fileExists: Boolean,
    filePath: String
})

const form = useForm({
    fired_date: '',
    days_taken: '',
    state: 'Inactive'
})

const formSchedule = useForm({
    document: null
})

const submit = () => {
    router.put(route('management.employees.fired',employeeToFired.value), form,{
        onSuccess: () => {
            closeFiredModal();
            router.visit(route('management.employees'));
        }

    })
}

const submitSchedule = () => {

    if(props.fileExists){
        formSchedule.post(route('management.employees.updateSchedule'), {
        onSuccess: () => {
            closeScheduleModal();
            formSchedule.reset();
            updateSchedule.value = true
            setTimeout(() => {
            updateSchedule.value = false;
            router.visit(route('management.employees'))
            }, 2000);
        },
        onError: () => {
            formSchedule.reset();
        },
        onFinish: () => {
            formSchedule.reset();
        }
        });
    }else{
        formSchedule.post(route('management.employees.addSchedule'), {
        onSuccess: () => {
            closeScheduleModal();
            formSchedule.reset();
            createSchedule.value = true
            setTimeout(() => {
            createSchedule.value = false;
            router.visit(route('management.employees'))
            }, 2000);
        },
        onError: () => {
            formSchedule.reset();
        },
        onFinish: () => {
            formSchedule.reset();
        }
        });
    }
  };

const confirmUserDeletion = (employeeId) => {
    employeeToDelete.value = employeeId;
    confirmingUserDeletion.value = true;
};

const confirmFired = (firedId) => {
    employeeToFired.value = firedId
    showModalFired.value = true
}

const closeFiredModal = () => {
    console.log('dfdff');
    showModalFired.value = false
}

const deleteEmployee = () => {
    const employeeId = employeeToDelete.value;
    if (employeeId) {
        router.delete(route('management.employees.destroy', { id: employeeId }), {
            onSuccess: () => closeModal()
        });
    }
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
};

const add_information = () => {
    router.get(route('management.employees.information'));
};


const openScheduleModal = () => {
    showModalSchedule.value = true;
  };

  const closeScheduleModal = () => {
    showModalSchedule.value = false;
  };

  const excelData = ref(null);

  const loadExcelFile = async () => {
  try {
    // Obtener el archivo Excel directamente utilizando la ruta
    const response = await axios.get('http://localhost:8000/documents/schedule/EmployeesSchedule.xlsx', {
      responseType: 'blob' // Especificar el tipo de respuesta como blob para manejar archivos binarios
    });
    
    // Convertir la respuesta a un ArrayBuffer
    const arrayBuffer = await response.data.arrayBuffer();
    
    // Leer el ArrayBuffer como un libro de trabajo de Excel
    const workbook = XLSX.read(new Uint8Array(arrayBuffer), { type: 'array' });

    // Obtener el nombre de la primera hoja
    const sheetName = workbook.SheetNames[0];

    // Obtener los datos de la hoja como JSON
    const sheetData = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName], { header: 1 });

    // Asignar los datos al ref excelData
    excelData.value = sheetData;
  } catch (error) {
    console.error('Error al cargar el archivo:', error);
    excelData.value = null;
  }
};


onMounted(() => {
  loadExcelFile();
});

</script>