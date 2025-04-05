<template>
    <a v-if="permissionsPorVencer.length + vacationPorVencer3.length + vacationPorVencer7.length > 0 || formationProgramsAlarms.length > 0 || employeeBirthdayAlarms.length > 0 || documentsToExpire.length > 0"
        class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#" @click="showingHumanResource = (showingMembers && showingMembers7)
            ? false
            : !showingHumanResource;
        showingMembers = showingMembers7 = false;
        showDocumentsToExpireAlarms = false;
        ">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="red"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
        </svg>
        <span class="mx-3 ">Recursos Humanos</span>
    </a>
    <a  v-else class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#"
        @click="showingHumanResource = !showingHumanResource">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            :stroke="'currentColor'" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
        </svg>
        <span class="mx-3">Recursos Humanos</span>
    </a>
    <MyTransition v-if="subModulePermission(submodules.hremployees_submodule, userSubModules)"  :transitiondemonstration="showingHumanResource">
        <div class="relative">
            <Link class="w-full" :href="route('management.employees')">Empleados</Link>
            <button @click="showEmployeeBirthdayAlarms = !showEmployeeBirthdayAlarms">
                <span v-if="employeeBirthdayAlarms.length > 0"
                    class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                    {{ employeeBirthdayAlarms.length }}
                </span>
            </button>
        </div>
    </MyTransition>
    <template v-if="employeeBirthdayAlarms.length !== 0" v-permission="'management_employees_happy_birthday'">
        <MyTransition v-for="item in employeeBirthdayAlarms" :key="item.id" class="ml-4"
            :transitiondemonstration="showEmployeeBirthdayAlarms">
            <Link class="w-full flex items-center" :href="route('management.employees.show', { id: item.id })">
            <svg class="w-4 h-4 mr-2 text-red-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
            </svg>
            <span>{{ item.name }} {{ item.lastname }}</span>
            </Link>
        </MyTransition>
    </template>
    <MyTransition v-if="subModulePermission(submodules.hreemployees_submodule, userSubModules)"  :transitiondemonstration="showingHumanResource">
        <Link class="w-full" :href="route('employees.external.index')">Empleados Externos</Link>
    </MyTransition>
    <!-- <MyTransition :transitiondemonstration="showingHumanResource">
        <Link class="w-full" :href="route('controlEmployees.index')">Control de Empleados</Link>
    </MyTransition> -->
    <MyTransition v-if="subModulePermission(submodules.hrspreedsheet_submodule, userSubModules)" :transitiondemonstration="showingHumanResource">
        <Link class="w-full" :href="route('payroll.index')">Nomina</Link>
    </MyTransition>

    <!-- <MyTransition :transitiondemonstration="showingHumanResource">
        <div class="relative">
            <button @click="showFormationProgramsAlarms = !showFormationProgramsAlarms">
                <Link class="w-full" :href="route('management.employees.formation_development')">Formacion y
                Desarrollo
                </Link>
                <span v-if="formationProgramsAlarms.length > 0"
                    class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                    {{ formationProgramsAlarms.length }}
                </span>
            </button>
        </div>
    </MyTransition>
    
    <template v-if="formationProgramsAlarms.length !== 0">
        <MyTransition v-for="item in formationProgramsAlarms" :key="item.id" class="ml-4"
            :transitiondemonstration="showFormationProgramsAlarms">
            <Link class="w-full flex items-center"
                :href="route('management.employees.formation_development.detail', { employee_id: item.id })">
            <svg :class="`w-4 h-4 mr-2 ${item.critical ? 'text-red-600' : 'text-yellow-400'} dark:text-red`"
                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20">
                <path
                    d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
            </svg>
            <span>{{ item.name }} {{ item.lastname }}</span>
            </Link>
        </MyTransition>
    </template> -->


    <!-- <MyTransition :transitiondemonstration="showingHumanResource">
        <div class="relative">
            <button @click="alarmVacaPermisions">
                <span
                    v-if="permissionsPorVencer.length + vacationPorVencer3.length + vacationPorVencer7.length > 0"
                    class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                    {{ permissionsPorVencer.length + vacationPorVencer3.length + vacationPorVencer7.length
                    }}</span>
            </button>
            <Link class="w-full" :href="route('management.vacation')">Vacaciones y Permisos</Link>
        </div>
    </MyTransition> -->
    <!-- vacatioon permissions alarms -->
    <!-- <template v-if="showingPermissionsAlarm && showingVacationAlarm">
        <div class="mb-4">
            <MyTransition v-for="item in permissionsPorVencer" :key="item.id" class="ml-4"
                :transitiondemonstration="showingPermissionsAlarm">
                <Link class="w-full flex items-center"
                    :href="route('management.vacation.information.details', { vacation: item.id })">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-yellow-600 dark:text-red" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                    </svg>
                    <span>{{ item.employee.name }}</span>
                </div>
                </Link>
            </MyTransition>
            <MyTransition v-for="item in vacationPorVencer3" :key="item.id" class="ml-4"
                :transitiondemonstration="showingVacationAlarm">
                <Link class="w-full flex items-center"
                    :href="route('management.vacation.information.details', { vacation: item.id })">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-red-600 dark:text-red" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                    </svg>
                    <span>{{ item.employee.name }}</span>
                </div>
                </Link>
            </MyTransition>
            <MyTransition v-for="item in vacationPorVencer7" :key="item.id" class="ml-4"
                :transitiondemonstration="showingVacationAlarm">
                <Link class="w-full flex items-center"
                    :href="route('management.vacation.information.details', { vacation: item.id })">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-yellow-600 dark:text-yellow" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                    </svg>
                    <span>{{ item.employee.name }}</span>
                </div>
                </Link>
            </MyTransition>
        </div>
    </template> -->
    <MyTransition v-if="subModulePermission(submodules.hrresdoc_submodule, userSubModules)" :transitiondemonstration="showingHumanResource">
        <Link class="w-full" :href="route('documents.index')">Documentos</Link>
    </MyTransition>
    <MyTransition  v-if="subModulePermission(submodules.hrhrstate_submodule, userSubModules)" :transitiondemonstration="showingHumanResource">
        <div class="relative">
            <div class="absolute top-0 right-0 flex justify-end gap-3">
                <button 
                    @click="()=>{
                        showNoDocumentsAlarms = !showNoDocumentsAlarms;
                        showDocumentsToExpireAlarms=false;
                    }"
                    class="cursor-pointer bg-indigo-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4"
                    >
                    <span
                        v-if="noDocuments.length > 0"
                        >
                        {{ noDocuments.length }}
                    </span>
                </button>
                <button 
                    @click="()=>{
                        showDocumentsToExpireAlarms = !showDocumentsToExpireAlarms;
                        showNoDocumentsAlarms=false;
                    }"
                    class="cursor-pointer bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4"
                    >
                    <span
                        v-if="documentsToExpire.length > 0"
                        >
                        {{ documentsToExpire.length }}
                    </span>
                </button>

            </div>
        </div>
            <Link class="w-full" :href="route('document.rrhh.status')">Estatus RRHH</Link>
    </MyTransition>
    <template v-if="showDocumentsToExpireAlarms">
        <div class="mb-4">
            <MyTransition v-for="item, i in documentsToExpire" :key="i" class="ml-4"
                :transitiondemonstration="showDocumentsToExpireAlarms">
                <div class="w-full flex items-center">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-red-600 dark:text-red" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                        </svg>
                        <a :href="route('employee.document.rrhh.status', { emp_id: item.id, type:item.type })">
                            <span>{{ item.name }} {{ item.lastname }}</span>
                        </a>
                    </div>
                </div>
            </MyTransition>

        </div>
    </template>
    <template v-if="showNoDocumentsAlarms">
        <div class="mb-4">
            <MyTransition v-for="item, i in noDocuments" :key="i" class="ml-4"
                :transitiondemonstration="showNoDocumentsAlarms">
                <div class="w-full flex items-center">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-indigo-600 dark:text-indigo" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                        </svg>
                        <a :href="route('employee.document.rrhh.status', { emp_id: item.id, type:item.type })">
                            <span>{{ item.name }} {{ item.lastname }}</span>
                        </a>
                    </div>
                </div>
            </MyTransition>

        </div>
    </template>
</template>
<script setup>
import MyTransition from '@/Components/MyTransition.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { subModulePermission } from '@/utils/roles/roles';
import { onMounted, ref } from 'vue';

const { userPermissions } = defineProps({
    userPermissions: Array
})

const showingHumanResource = ref(false)

const showingMembers = ref(false)
const showingMembers7 = ref(false)
const showFormationProgramsAlarms = ref(false)
const showArchivesAlarms = ref(false)
const showEmployeeBirthdayAlarms = ref(false)
const showDocumentsToExpireAlarms =ref(false)
const showNoDocumentsAlarms =ref(false)

const employeeBirthdayAlarms = ref([])
const permissionsPorVencer = ref([])
const documentsToExpire = ref([])
const noDocuments = ref([])
const formationProgramsAlarms = ref([])
const vacationPorVencer3 = ref([])
const vacationPorVencer7 = ref([])

const {submodules} = usePage().props
const {userSubModules} = usePage().props.auth

async function fetchAlarmHappyBirthdayCount() {
    try {
        const response = await axios.get(route('management.employees.happy.birthday'));
        employeeBirthdayAlarms.value = response.data.happyBirthday;
    } catch (error) {
        console.error('Error al obtener el cumpleaños de los empleados:', error);
    }
}

async function fetchDocumentsToExpireAlarmCount() {
    try {
        const response = await axios.get(route('document.rrhh.status.alarms'));
        documentsToExpire.value = response.data;
    } catch (error) {
        console.error('Error al obtener alarma de documentos de estatus rrhh', error);
    }
}

async function fetchNoDocumentsAlarmCount() {
    try {
        const response = await axios.get(route('document.rrhh.nodoc.alarms'));
        noDocuments.value = response.data;
    } catch (error) {
        console.error('Error al obtener alarma sin documentos de estatus rrhh', error);
    }
}

async function fetchAlarmPermissionsCount() {
    try {
        const response = await axios.get(route('alarm.permissions'));
        permissionsPorVencer.value = response.data.permissions;
    } catch (error) {
        console.error('Error al obtener el contador de permissions:', error);
    }
}

async function fetchAlarmVacationCount() {
    try {
        const response = await axios.get(route('alarm.vacation'));
        vacationPorVencer3.value = response.data.vacation3;
        vacationPorVencer7.value = response.data.vacation7;
    } catch (error) {
        console.error('Error al obtener el contador de vacation:', error);
    }
}

async function fetchFormationProgramAlarms() {
    try {
        const response = await axios.get(route('employees_in_programs.alarms'))
        formationProgramsAlarms.value = [
            ...response.data.alarm3d.map(i => ({ ...i, critical: true })),
            ...response.data.alarm7d
        ]
    } catch (error) {
        console.error('Error al obtener alarmas de programa de formación:', error);
    }
}
onMounted(() => {
    const fetchAllAlarms = () => {
        return Promise.all([
            fetchAlarmHappyBirthdayCount(),
            fetchDocumentsToExpireAlarmCount(),
            fetchNoDocumentsAlarmCount(),
            fetchAlarmPermissionsCount(),
            fetchAlarmVacationCount(),
            fetchFormationProgramAlarms(),
        ]);
    };
    fetchAllAlarms();
    setInterval(fetchAllAlarms, 60000);
});
</script>