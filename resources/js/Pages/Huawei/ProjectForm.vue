<template>
    <Head title="Proyecto de Huawei" />
    <AuthenticatedLayout :redirectRoute="{route: 'huawei.projects', params: {status: '1', prefix: 'Claro'}}">
        <template v-if="props.huawei_project" #header>
            Edición de proyecto
        </template>
        <template v-else #header>
            Creación de proyecto
        </template>
        <div class="min-w-full p-3 rounded-lg shadow">
            <form @submit.prevent="submit">
                <div class="pt-1">
                    <div class="border-b border-gray-900/10 mb-2">
                    </div>

                    <div class="border-b border-gray-900 pb-12">
                        <h2 v-if="props.huawei_project" class="text-base font-semibold leading-7 text-gray-900">{{ props.huawei_project.name }} | {{
                            props.huawei_project.code }}</h2>
                        <h2 v-else class="text-base font-semibold leading-7 text-gray-900">Registrar nuevo proyecto</h2>
                        <br>
                        <div v-if="props.huawei_project" class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mb-4">
                            <div class="sm:col-span-3">
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    Site
                                </InputLabel>
                                <div class="mt-2">
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    {{ props.huawei_project.huawei_site.name }}
                                </InputLabel>
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    Almacén
                                </InputLabel>
                                <div class="mt-2">
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    {{ props.huawei_project.prefix }}
                                </InputLabel>
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    OT
                                </InputLabel>
                                <div class="mt-2">
                                <InputLabel class="font-medium leading-6 text-gray-200">
                                    {{ props.huawei_project.ot }}
                                </InputLabel>
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    Macroproyecto
                                </InputLabel>
                                <div class="mt-2">
                                <InputLabel class="font-medium leading-6 text-gray-200">
                                    {{ props.huawei_project.macro_project }}
                                </InputLabel>
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    Código
                                </InputLabel>
                                <div class="mt-2">
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    {{ props.huawei_project.code }}
                                </InputLabel>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <InputLabel for="name" class="font-medium leading-6 text-gray-900">Nombre
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput v-model="form.name" id="description"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                    <InputError :message="form.errors.name" />
                                </div>
                            </div>

                            <div v-if="!props.huawei_project" class="sm:col-span-3">
                                <InputLabel for="huawei_site_id" class="font-medium leading-6 text-gray-900">Site
                                </InputLabel>
                                <div class="mt-2">
                                    <select required id="huawei_site_id" v-model="form.huawei_site_id"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">Seleccione uno</option>
                                        <option v-for="item in props.huawei_sites" :key="item.id" :value="item.id">{{ item.name }}</option>
                                    </select>
                                </div>
                                <InputError :message="form.errors.huawei_site_id" />
                            </div>

                            <div v-if="!props.huawei_project" class="sm:col-span-3">
                                <InputLabel for="prefix" class="font-medium leading-6 text-gray-900">Almacén
                                </InputLabel>
                                <div class="mt-2">
                                    <select required id="prefix" v-model="form.prefix"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">Seleccione uno</option>
                                        <option>Claro</option>
                                        <option>Entel</option>
                                    </select>
                                </div>
                                <InputError :message="form.errors.prefix" />
                            </div>

                            <div v-if="!props.huawei_project" class="sm:col-span-3">
                                <InputLabel for="prefix" class="font-medium leading-6 text-gray-900">Macroproyecto
                                </InputLabel>
                                <div class="mt-2">
                                    <select required id="prefix" v-model="form.macro_project"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">Seleccione uno</option>
                                        <option>IPRAN24</option>
                                        <option>DWDM</option>
                                        <option>FTTH</option>
                                        <option>NAZCANEWPECOM</option>
                                    </select>
                                </div>
                                <InputError :message="form.errors.macro_project" />
                            </div>

                            <div v-if="!props.huawei_project" class="sm:col-span-3">
                                <InputLabel for="ot" class="font-medium leading-6 text-gray-900">OT
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.ot" id="ot"
                                        class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                    <InputError :message="form.errors.ot" />
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <div class="flex gap-2">
                                    <InputLabel for="pre_report" class="font-medium leading-6 text-gray-900">Reporte
                                    </InputLabel>
                                    <button @click.prevent="openPreviewPreReport(props.huawei_project?.id)" class="mt-1" v-if="props.huawei_project?.pre_report">
                                        <EyeIcon class="text-green-500 h-5 w-5 " />
                                    </button>
                                </div>
                                <div class="mt-2">
                                    <InputFile type="file" v-model="form.pre_report" id="pre_report"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                    <InputError :message="form.errors.pre_report" />
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <InputLabel for="initial_amount" class="font-medium leading-6 text-gray-900">Monto Inicial
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="number" step="0.01" v-model="form.initial_amount" id="initial_amount"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                    <InputError :message="form.errors.initial_amount" />
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <InputLabel for="assigned_diu" class="font-medium leading-6 text-gray-900">DU del Proyecto
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="form.assigned_diu" id="assigned_diu"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                    <InputError :message="form.errors.assigned_diu" />
                                </div>
                            </div>

                            <div :class="props.huawei_project ? 'sm:col-span-6' : 'md:col-span-6 sm:col-span-3'">
                                <InputLabel for="name" class="font-medium leading-6 text-gray-900">Descripción
                                </InputLabel>
                                <div class="mt-2">
                                    <textarea v-model="form.description" id="description"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                    <InputError :message="form.errors.description" />
                                </div>
                            </div>


                            <div class="sm:col-span-3">
                                <div class="flex gap-2">
                                    <InputLabel for="trainings" class="font-medium leading-6 text-gray-900">Miembros del
                                        equipo al proyecto
                                    </InputLabel>
                                    <button @click.prevent="showToAddEmployee" type="button">
                                        <UserPlusIcon class="text-indigo-800 h-6 w-6 hover:text-purple-400" />
                                    </button>
                                </div>

                                <div class="mt-2" v-if="props.huawei_project">
                                    <div v-for="(member, index) in form.employees" :key="index"
                                        class="grid grid-cols-8 items-center my-2">
                                        <p class=" text-sm col-span-7 line-clamp-2">{{ member.employee.name }} {{
                                            member.employee.lastname }}: {{ member.charge }} - S/. {{ member.cost.toFixed(2) }} </p>
                                        <div class="flex gap-2">
                                            <button type="button" @click="edit_employee(member, index)"
                                                class="col-span-1 flex justify-end">
                                                <PencilSquareIcon class=" text-yellow-500 h-5 w-5 " />
                                            </button>
                                            <button type="button" @click="delete_already_employee(member.id, index)"
                                                class="col-span-1 flex justify-end">
                                                <TrashIcon class=" text-red-500 h-5 w-5 " />
                                            </button>
                                        </div>
                                        <div class="border-b col-span-8 border-gray-900/10">
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2" v-else>
                                    <div v-for="(member, index) in form.employees" :key="index"
                                        class="grid grid-cols-8 items-center my-2">
                                        <p class=" text-sm col-span-7 line-clamp-2">{{ member.employee.name }} {{
                                            member.employee.lastname }}: {{ member.charge + ' - ' + member.cost}} </p>
                                        <button type="button" @click="delete_employee(index)"
                                            class="col-span-1 flex justify-end">
                                            <TrashIcon class=" text-red-500 h-4 w-4 " />
                                        </button>
                                        <div class="border-b col-span-8 border-gray-900/10">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="auth.user.role_id === 1" class="mt-3 flex items-center justify-end gap-x-6">
                    <button type="submit" :class="{ 'opacity-25': form.processing }"
                        class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
                </div>
            </form>
            <Modal :show="showModalMember">
                <form class="p-6" @submit.prevent="add_employee">
                    <h2 class="text-lg font-medium text-gray-900">
                        Agregar un miembro del equipo
                    </h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                        <div class="sm:col-span-3">
                            <InputLabel for="name" class="font-medium leading-6 text-gray-900">Empleado
                            </InputLabel>
                            <div class="mt-2">
                                <select required id="type" v-model="employeeToAdd.employee"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccione uno</option>
                                    <option v-for="item in available_employees" :key="item.id" :value="item">{{ item.name }} {{
                                        item.lastname }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel class="font-medium leading-6 text-gray-900">Rol de la persona</InputLabel>
                            <div class="mt-2">
                                <select required id="type" v-model="employeeToAdd.charge"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccione uno</option>
                                    <option value="lider">Lider</option>
                                    <option value="sublider">Sublider</option>
                                    <option value="supervisor">Supervisor</option>
                                    <option value="trabajador">Trabajador</option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel class="font-medium leading-6 text-gray-900">Costo de Planilla</InputLabel>
                            <div class="mt-2">
                                <input type="number" min="0" step="0.01" v-model="employeeToAdd.cost" required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3 justify-end">
                        <SecondaryButton type="button" @click="closeModal"> Cerrar </SecondaryButton>
                        <PrimaryButton type="submit"> Agregar </PrimaryButton>
                    </div>
                </form>
            </Modal>

            <Modal :show="edit_employee_modal">
                <form class="p-6" @submit.prevent="submit_edit_employee">
                    <h2 class="text-lg font-medium text-gray-900">
                        Editar miembro
                    </h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                        <div class="sm:col-span-3">
                            <InputLabel class="font-medium leading-6 text-gray-900">Rol de la persona</InputLabel>
                            <div class="mt-2">
                                <select required id="type" v-model="edit_employee_form.charge"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccione uno</option>
                                    <option value="lider">Lider</option>
                                    <option value="sublider">Sublider</option>
                                    <option value="supervisor">Supervisor</option>
                                    <option value="trabajador">Trabajador</option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel class="font-medium leading-6 text-gray-900">Costo de Planilla</InputLabel>
                            <div class="mt-2">
                                <input type="number" min="0" step="0.01" v-model="edit_employee_form.cost" required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3 justify-end">
                        <SecondaryButton type="button" @click="close_edit_employee"> Cerrar </SecondaryButton>
                        <PrimaryButton type="submit"> Agregar </PrimaryButton>
                    </div>
                </form>
            </Modal>
        </div>
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="Proyecto de Huawei" />
        <ConfirmUpdateModal :confirmingupdate="showUpdateModal" itemType="Proyecto de Huawei" />

        <SuccessOperationModal :confirming="showPersonalAddModal" :title="`Personal creado.`"
            :message="`El personal fue añadido.`" />
        <SuccessOperationModal :confirming="showPersonalRemoveModal" :title="`Personal removido.`"
            :message="`El personal fue removido.`"/>
        <SuccessOperationModal :confirming="show_edit_employee" :title="`Personal actualizado.`"
            :message="`El personal fue actualizado.`"/>
        <ErrorOperationModal :showError="alreadyEmployeeInProject" title="Empleado ya agregado" message="El empleado ya ha sido agregado al proyecto." />

  </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import { ref, watch } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { UserPlusIcon, TrashIcon, EyeIcon, PencilSquareIcon } from '@heroicons/vue/24/outline';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';
import TextInput from '@/Components/TextInput.vue';
import InputFile from '@/Components/InputFile.vue';

const showModal = ref(false)
const showUpdateModal = ref(false)
const alreadyEmployeeInProject = ref(false)
const props = defineProps({
    huawei_sites: Object,
    huawei_project: Object,
    employees: Object,
    auth: Object,
    userPermissions: Array
})

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}

const initialState = {
    name: '',
    description: '',
    huawei_site_id: '',
    ot: '',
    prefix: '',
    pre_report: null,
    employees: [],
    initial_amount: '',
    assigned_diu: '',
    macro_project: ''
}

const form = useForm(
    { ...initialState }
)

if (props.huawei_project) {
    form.name = props.huawei_project.name || '';
    form.huawei_site_id = props.huawei_project.huawei_site_id || '';
    form.description = props.huawei_project.description || '';
    form.ot = props.huawei_project.ot || '';
    form.initial_amount = props.huawei_project.initial_amount || '';
    form.assigned_diu = props.huawei_project.assigned_diu || '';
    form.employees = props.huawei_project.huawei_project_employees ? props.huawei_project.huawei_project_employees.map(employee => ({
        id: employee.id,
        employee: employee.employee,
        charge: employee.role,
        cost: employee.cost
    })) : [];
}

const submit = () => {
    if (!props.huawei_project){
        form.post(route('huawei.projects.store'), {
            onSuccess: () => {
                showModal.value = true;
                setTimeout(() => {
                    showModal.value = false;
                    router.visit(route('huawei.projects', {status: '1', prefix: 'Claro'}))
                }, 2000);
            },
        })
    }else{
        form.post(route('huawei.projects.update', {huawei_project: props.huawei_project.id}), {
            onSuccess: () => {
                showUpdateModal.value = true;
                setTimeout(() => {
                    showUpdateModal.value = false;
                    router.visit(route('huawei.projects', {status: 1, prefix: 'Claro'}))
                }, 2000);
            },
        })
    }
}

const showModalMember = ref(false);
const empInitState = { employee: '', charge: '', cost: '' }
const employeeToAdd = ref(JSON.parse(JSON.stringify(empInitState)))
const edit_employee_modal = ref(false);
const showPersonalAddModal = ref(false);
const showPersonalRemoveModal = ref(false);
const editingMember = ref(null);
const show_edit_employee = ref(false);
const employee_edit_index = ref(null);
const available_employees = ref([...props.employees]);

const filterAvailableEmployees = () => {
  // Filtra empleados que no tienen el mismo `id` que los empleados en `form.employees`
  available_employees.value = props.employees.filter(employee =>
  !form.employees.some(emp => emp.employee.id === employee.id)
);
};

// Ejecutamos el filtro cuando se carga el componente
filterAvailableEmployees();

// Observamos los cambios en form.employees y actualizamos available_employees
watch(
  () => form.employees,
  () => {
    filterAvailableEmployees();
  },
  { deep: true }
);

const showToAddEmployee = () => {
    showModalMember.value = true;
}
const closeModal = () => {
    employeeToAdd.value = JSON.parse(JSON.stringify(empInitState))
    showModalMember.value = false;
};

const add_employee = () => {
    if (props.huawei_project) {
        const employeeExists = props.huawei_project.huawei_project_employees.some(employee => {
            return employee.employee_id === employeeToAdd.value.employee.id;
        });
        if (employeeExists) {
            alreadyEmployeeInProject.value = true;
                setTimeout(() => {
                alreadyEmployeeInProject.value = false;
            }, 3000);
        }else{
            router.post(route('huawei.projects.addemployee', { huawei_project: props.huawei_project.id }), { ...employeeToAdd.value },
                {
                    onError: () => {
                        alert('SERVER ERROR')
                    },
                    onSuccess: () => {
                        showPersonalAddModal.value = true
                        setTimeout(() => {
                            showPersonalAddModal.value = false;
                        }, 1500);
                        router.visit(route('huawei.projects.toupdate', {huawei_project: props.huawei_project.id}))
                    }
                }
            )
            showModalMember.value = false
        }
    } else {
        const employeeExists = form.employees.some(employee => {
            return employee.employee.id === employeeToAdd.value.employee.id;
        });
        if (employeeExists) {
            alreadyEmployeeInProject.value = true;
                setTimeout(() => {
                alreadyEmployeeInProject.value = false;
            }, 3000);
        }else{
            form.employees.push(JSON.parse(JSON.stringify(employeeToAdd.value)))
            employeeToAdd.value = JSON.parse(JSON.stringify(empInitState))
            showModalMember.value = false
        }
    }

}
const delete_employee = (index) => {
    form.employees.splice(index, 1);
}

const openPreviewPreReport = (projectId) => {
    const routeToShow = route('huawei.projects.prereport', {huawei_project: projectId});
    window.open(routeToShow, '_blank');
}

const delete_already_employee = (pivot_id, index) => {
    router.delete(route('huawei.projects.deleteemployee', { id: pivot_id }), {
        onError: () => {
            alert('SERVER ERROR')
        },
        onSuccess: () => {
            showPersonalRemoveModal.value = true
            setTimeout(() => {
                showPersonalRemoveModal.value = false;
            }, 1500);
            form.employees.splice(index, 1);
        }
    })
}

const edit_employee_form = useForm({
    id: '',
    charge: '',
    cost: ''
});

const edit_employee = (item, index) => {
    editingMember.value = JSON.parse(JSON.stringify(item));
    employee_edit_index.value = index;
    edit_employee_form.id = editingMember.value.id;
    edit_employee_form.charge = editingMember.value.charge;
    edit_employee_form.cost = editingMember.value.cost;
    edit_employee_modal.value = true;
}

const close_edit_employee = () => {
    editingMember.value = null;
    edit_employee_form.reset();
    edit_employee_form.clearErrors(),
    edit_employee_modal.value = false;
}
const submit_edit_employee = () => {
    edit_employee_form.put(route('huawei.projects.editemployee', {huawei_project: props.huawei_project.id, id: edit_employee_form.id}), {
        onSuccess: () => {
            form.employees[employee_edit_index.value].charge = edit_employee_form.charge
            form.employees[employee_edit_index.value].cost = edit_employee_form.cost
            close_edit_employee();
            show_edit_employee.value = true;
            setTimeout(() => {
                show_edit_employee.value = false;
            }, 2000);
        }
    })
}

</script>
