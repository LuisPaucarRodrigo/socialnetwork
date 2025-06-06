<template>
    <Head title="Proyectos" />
    <AuthenticatedLayout :redirectRoute="{route: 'huawei.projects', params: {status: 1, prefix: 'Claro'}}">
        <template #header>
            Proyectos{{
                props.status == "2"
                    ? " Detenidos de "
                    : props.status == "3"
                    ? " Liquidados de "
                    : " de "
            }}Huawei
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div
                class="flex flex-col gap-4 justify-center sm:flex-row sm:justify-between rounded-lg items-center text-center sm:text-left"
            >
                <div
                    class="flex flex-col sm:flex-row gap-4 w-full justify-between items-center"
                >
                    <div
                        class="flex gap-4 items-center justify-center sm:justify-start"
                    >
                        <!-- Botones grandes visibles solo en pantallas sm y superiores -->
                        <Link
                            v-if="props.status == '1'"
                            :href="route('huawei.projects.create')"
                            type="button"
                            v-permission="'huawei_projects_add'"
                            class="hidden sm:block items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white whitespace-nowrap"
                        >
                            + Agregar
                        </Link>
                        <Link
                            v-if="props.status == '1'"
                            :href="
                                route('huawei.projects', {
                                    status: '3',
                                    prefix: selectedPrefix,
                                })
                            "
                            type="button"
                            class="hidden sm:block items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white"
                        >
                            Historial
                        </Link>
                        <Link
                            v-if="props.status == '1'"
                            :href="
                                route('huawei.projects', {
                                    status: '2',
                                    prefix: selectedPrefix,
                                })
                            "
                            type="button"
                            class="hidden sm:block items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white"
                        >
                            Detenidos
                        </Link>
                        <Link
                            v-if="props.status !== '1'"
                            :href="
                                route('huawei.projects', {
                                    status: '1',
                                    prefix: selectedPrefix,
                                })
                            "
                            type="button"
                            class="hidden sm:block px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white whitespace-nowrap"
                            style="min-width: 80px; max-width: 120px"
                        >
                            Proyectos
                        </Link>
                        <!-- Menú desplegable visible en pantallas pequeñas -->
                        <div class="sm:hidden">
                            <dropdown align="left">
                                <template #trigger>
                                    <button
                                        @click="dropdownOpen = !dropdownOpen"
                                        class="relative block overflow-hidden rounded-md bg-gray-200 px-2 py-2 text-center text-sm text-white hover:bg-gray-100"
                                    >
                                        <svg
                                            width="25px"
                                            height="25px"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M4 6H20M4 12H20M4 18H20"
                                                stroke="#000000"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </button>
                                </template>

                                <template #content class="origin-left">
                                    <div class="dropdown">
                                        <div class="dropdown-menu">
                                            <div v-if="props.status == '1'">
                                                <Link
                                                    v-permission="'huawei_projects_add'"
                                                    :href="
                                                        route(
                                                            'huawei.projects.create'
                                                        )
                                                    "
                                                    type="button"
                                                    class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                                >
                                                    + Agregar
                                                </Link>
                                                <Link
                                                    :href="
                                                        route(
                                                            'huawei.projects',
                                                            {
                                                                status: '3',
                                                                prefix: selectedPrefix,
                                                            }
                                                        )
                                                    "
                                                    type="button"
                                                    class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                                >
                                                    Historial
                                                </Link>
                                                <Link
                                                    :href="
                                                        route(
                                                            'huawei.projects',
                                                            {
                                                                status: '2',
                                                                prefix: selectedPrefix,
                                                            }
                                                        )
                                                    "
                                                    type="button"
                                                    class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                                >
                                                    Detenidos
                                                </Link>
                                            </div>
                                            <div v-else>
                                                <Link
                                                    :href="
                                                        route(
                                                            'huawei.projects',
                                                            {
                                                                status: '1',
                                                                prefix: selectedPrefix,
                                                            }
                                                        )
                                                    "
                                                    type="button"
                                                    class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                                >
                                                    Proyectos
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </dropdown>
                        </div>
                    </div>

                    <!-- Select Almacén -->
                    <div
                        class="flex flex-col sm:flex-row gap-2 items-center justify-center sm:justify-start"
                    >
                        <p>Operador</p>
                        <select
                            v-model="selectedPrefix"
                            id="code"
                            @change="changePrefix($event.target.value)"
                            class="block w-full min-w-[150px] rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        >
                            <option disabled>Seleccione Operador</option>
                            <option
                                v-for="operator in props.operators"
                                :value="operator"
                            >
                                {{ operator }}
                            </option>
                        </select>
                    </div>

                    <!-- Formulario de búsqueda -->
                    <div
                        class="flex items-center justify-center sm:justify-end w-full sm:w-auto mt-4 sm:mt-0"
                    >
                        <form
                            @submit.prevent="search"
                            class="flex items-center w-full sm:w-auto"
                        >
                            <TextInput
                                type="text"
                                placeholder="Buscar..."
                                v-model="searchForm.searchTerm"
                                class="mr-2"
                            />
                            <button
                                type="submit"
                                :class="{ 'opacity-25': searchForm.processing }"
                                class="ml-2 rounded-md bg-indigo-600 px-2 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            >
                                <svg
                                    width="30px"
                                    height="21px"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                                        stroke="white"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <br />
            <div
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3"
            >
                <div
                    v-permission="'huawei_expenses_view'"
                    class="bg-blue-200 p-3 rounded-md shadow-sm border border-gray-300 items-center"
                >
                    <div class="grid grid-cols-2">
                        <h2 class="text-sm font-semibold mb-3 mr-3">N° 0001</h2>
                    </div>
                    <div class="flex gap-1">
                        <p class="text-sm font-semibold text-black">Nombre:</p>
                        <h3
                            class="text-sm font-semibold text-gray-700 line-clamp-1 mb-1 whitespace-nowrap"
                        >
                            General
                        </h3>
                    </div>
                    <div class="flex gap-1">
                        <p class="text-sm font-semibold text-black">Site:</p>
                        <h3
                            class="text-sm font-semibold text-gray-700 line-clamp-1 mb-1 whitespace-nowrap"
                        >
                            -
                        </h3>
                    </div>
                    <div class="flex gap-1">
                        <p class="text-sm font-semibold text-black">Zona:</p>
                        <h3
                            class="text-sm font-semibold text-gray-700 line-clamp-1 mb-1 whitespace-nowrap"
                        >
                            -
                        </h3>
                    </div>
                    <div class="flex gap-1">
                        <p class="text-sm font-semibold text-black">PO:</p>
                        <h3
                            class="text-sm font-semibold text-gray-700 line-clamp-1 mb-1 whitespace-nowrap"
                        >
                            -
                        </h3>
                    </div>
                    <div class="flex gap-1">
                        <p class="text-sm font-semibold text-black">
                            Macroproyecto:
                        </p>
                        <h3
                            class="text-sm font-semibold text-gray-700 line-clamp-1 mb-1 whitespace-nowrap"
                        >
                            -
                        </h3>
                    </div>
                    <div class="flex gap-1">
                        <p class="text-sm font-semibold text-black">DU:</p>
                        <h3
                            class="text-sm font-semibold text-gray-700 line-clamp-1 mb-1 whitespace-nowrap"
                        >
                            -
                        </h3>
                    </div>

                    <div class="text-gray-500 text-sm mt-1">
                        <div class="grid grid-cols-1 gap-y-1">
                            <Link
                                :href="route('huawei.projects.generalbalance')"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600"
                            >
                                Gestión de Gastos
                            </Link>
                        </div>
                    </div>
                </div>

                <div
                    v-permission="'huawei_projects_view'"
                    v-for="item in props.projects"
                    :key="item.id"
                    class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center"
                >
                    <div class="grid grid-cols-2">
                        <h2 class="text-sm font-semibold mb-3 mr-3">
                            N° {{ item.code }}
                        </h2>

                        <div
                            class="inline-flex justify-end items-start gap-x-2"
                        >
                            <button
                                @click="openLiquidateModal(item.id)"
                                v-if="item.status"
                                v-permission="'huawei_projects_admin'"
                                :class="`h-6 px-1 rounded-md bg-indigo-700 text-white text-sm  ${
                                    item.state
                                        ? item.pre_report
                                            ? ''
                                            : 'opacity-60'
                                        : 'opacity-60'
                                }`"
                                :disabled="
                                    item.state
                                        ? item.pre_report
                                            ? false
                                            : true
                                        : true
                                "
                            >
                                Liquidar
                            </button>
                            <Link
                                v-if="item.status"
                                v-permission-or="['huawei_projects_see_project', 'huawei_projects_edit']"
                                :href="
                                    route('huawei.projects.toupdate', {
                                        huawei_project: item.id,
                                    })
                                "
                                class="flex items-start"
                            >
                                <EditIcon/>
                            </Link>
                            <button
                                v-permission="'huawei_projects_admin'"
                                @click.prevent="openResumeModal(item.id)"
                                v-if="item.status == null"
                                class="flex items-start"
                            >
                                <PlayIcon class="h-5 w-5 text-green-600" />
                            </button>
                            <button
                                v-permission="'huawei_projects_admin'"
                                @click.prevent="openCancelModal(item.id)"
                                v-if="item.status"
                                class="flex items-start"
                            >
                                <PauseIcon class="h-5 w-5 text-red-600" />
                            </button>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <p class="text-sm font-semibold text-black">Nombre:</p>
                        <h3
                            class="text-sm font-semibold text-gray-700 line-clamp-1 mb-1 whitespace-nowrap"
                        >
                            {{ item.name }}
                        </h3>
                    </div>
                    <div class="flex gap-1">
                        <p class="text-sm font-semibold text-black">Site:</p>
                        <h3
                            class="text-sm font-semibold text-gray-700 line-clamp-1 mb-1 whitespace-nowrap"
                        >
                            {{ item.huawei_site.name }}
                        </h3>
                    </div>
                    <div class="flex gap-1">
                        <p class="text-sm font-semibold text-black">Zona:</p>
                        <h3
                            class="text-sm font-semibold text-gray-700 line-clamp-1 mb-1 whitespace-nowrap"
                        >
                            {{ item.zone }}
                        </h3>
                    </div>
                    <div class="flex gap-1">
                        <p class="text-sm font-semibold text-black">PO:</p>
                        <h3
                            class="text-sm font-semibold text-gray-700 line-clamp-1 mb-1 whitespace-nowrap"
                        >
                            {{ item.ot }}
                        </h3>
                    </div>
                    <div class="flex gap-1">
                        <p class="text-sm font-semibold text-black">
                            Macroproyecto:
                        </p>
                        <h3
                            class="text-sm font-semibold text-gray-700 line-clamp-1 mb-1 whitespace-nowrap"
                        >
                            {{ item.macro_project }}
                        </h3>
                    </div>
                    <div class="flex gap-1">
                        <p class="text-sm font-semibold text-black">DU:</p>
                        <h3
                            class="text-sm font-semibold text-gray-700 line-clamp-1 mb-1 whitespace-nowrap"
                        >
                            {{ item.assigned_diu }}
                        </h3>
                    </div>

                    <div class="text-gray-500 text-sm mt-1">
                        <div class="grid grid-cols-1 gap-y-1">
                            <Link
                                v-permission="'huawei_expenses_view'"
                                :href="
                                    route(
                                        'huawei.projects.additionalcosts.summary',
                                        { huawei_project: item.id }
                                    )
                                "
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600"
                            >
                                Gestión de Gastos
                            </Link>
                            <!-- <Link
                                :href="route('huawei.projects.stages', { huawei_project: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                                Imágenes
                            </Link> -->
                            <Link
                                :href="
                                    route('huawei.projects.resources', {
                                        huawei_project: item.id,
                                    })
                                "
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600"
                            >
                                Asignar Productos
                            </Link>
                            <Link
                                :href="
                                    route('huawei.projects.earnings', {
                                        huawei_project: item.id,
                                    })
                                "
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600"
                            >
                                Ingresos Proyectados
                            </Link>
                            <Link
                                :href="
                                    route('huawei.projects.realearnings', {
                                        huawei_project: item.id,
                                    })
                                "
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600"
                            >
                                Ingresos Actuales
                            </Link>
                            <Link
                                :href="
                                    route('huawei.projects.liquidations', {
                                        huawei_project: item.id,
                                    })
                                "
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600"
                            >
                                Liquidaciones
                            </Link>
                            <Link
                                :href="
                                    route('huawei.projects.balance', {
                                        huawei_project: item.id,
                                    })
                                "
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600"
                            >
                                Balance del Proyecto
                            </Link>
                            <a
                                v-if="item.pre_report"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600"
                                :href="
                                    route('huawei.projects.prereport', {
                                        huawei_project: item.id,
                                    })
                                "
                                target="_blank"
                            >
                                Reporte
                            </a>
                            <span v-else class="text-gray-400">Reporte</span>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <!-- <div v-if="!props.search" class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="props.projects.links" />
            </div> -->
        </div>
        <Modal :show="liquidateModal" :maxWidth="'md'">
            <!-- Contenido del modal cuando no hay empleados -->
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    ¿Seguro de liquidar el proyecto?
                </h2>
                <!-- Puedes agregar más contenido o personalizar según tus necesidades -->
                <p class="mt-2 text-sm text-gray-500">
                    ¿Está seguro de liquidar el proyecto? No se podrán deshacer
                    los cambios.
                </p>
                <div class="mt-6 flex space-x-3 justify-end">
                    <SecondaryButton type="button" @click="closeLiquidateModal">
                        Cancelar
                    </SecondaryButton>
                    <PrimaryButton type="button" @click="liquidate()">
                        Aceptar
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
        <Modal :show="cancelModal" :maxWidth="'md'">
            <!-- Contenido del modal cuando no hay empleados -->
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    ¿Seguro de pausar el proyecto?
                </h2>
                <!-- Puedes agregar más contenido o personalizar según tus necesidades -->
                <p class="mt-2 text-sm text-gray-500">
                    ¿Está seguro de pausar el proyecto? Podrá reanudarlo
                    después.
                </p>
                <div class="mt-6 flex space-x-3 justify-end">
                    <SecondaryButton type="button" @click="closeCancelModal">
                        Cancelar
                    </SecondaryButton>
                    <button
                        class="rounded-md bg-indigo-600 px-2 py-2 whitespace-no-wrap text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600 focus-visible:border-transparent"
                        type="button"
                        @click="cancel_project()"
                    >
                        Aceptar
                    </button>
                </div>
            </div>
        </Modal>
        <Modal :show="resumeModal" :maxWidth="'md'">
            <!-- Contenido del modal cuando no hay empleados -->
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    ¿Seguro de reanudar el proyecto?
                </h2>
                <!-- Puedes agregar más contenido o personalizar según tus necesidades -->
                <p class="mt-2 text-sm text-gray-500">
                    EL proyecto volverá a la vista principal y podrá ser
                    manejado con normalidad.
                </p>
                <div class="mt-6 flex space-x-3 justify-end">
                    <SecondaryButton type="button" @click="closeResumeModal">
                        Cancelar
                    </SecondaryButton>
                    <button
                        class="rounded-md bg-indigo-600 px-2 py-2 whitespace-no-wrap text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600 focus-visible:border-transparent"
                        type="button"
                        @click="resume_project()"
                    >
                        Aceptar
                    </button>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import Dropdown from "@/Components/Dropdown.vue";
import { Head, router, Link, useForm } from "@inertiajs/vue3";
import { PencilIcon, PauseIcon, PlayIcon } from "@heroicons/vue/24/outline";
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";
import { ref } from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { EditIcon } from "@/Components/Icons/Index";

const props = defineProps({
    projects: Object,
    auth: Object,
    userPermissions: Array,
    search: String,
    prefix: String,
    status: String,
    operators: Array,
});


const selectedPrefix = ref(props.prefix);
const liquidateModal = ref(false);
const projectId = ref(null);
const cancelModal = ref(false);
const resumeModal = ref(false);

const searchForm = useForm({
    searchTerm: props.search ?? "",
});

const search = () => {
    if (searchForm.searchTerm == "") {
        router.visit(
            route("huawei.projects", {
                status: props.status,
                prefix: selectedPrefix.value,
            })
        );
    } else {
        router.visit(
            route("huawei.projects.search", {
                status: props.status,
                prefix: selectedPrefix.value,
                request: searchForm.searchTerm,
            })
        );
    }
};

const openLiquidateModal = (id) => {
    projectId.value = id;
    liquidateModal.value = true;
};

const closeLiquidateModal = () => {
    projectId.value = null;
    liquidateModal.value = false;
};

const openCancelModal = (id) => {
    projectId.value = id;
    cancelModal.value = true;
};

const closeCancelModal = () => {
    projectId.value = null;
    cancelModal.value = false;
};

const liquidate = () => {
    router.put(
        route("huawei.projects.liquidateproject", {
            huawei_project: projectId.value,
        }),
        null,
        {
            onSuccess: () => {
                closeLiquidateModal();
                router.visit(
                    route("huawei.projects", {
                        status: "3",
                        prefix: selectedPrefix.value,
                    })
                );
            },
        }
    );
};

const cancel_project = () => {
    router.put(
        route("huawei.projects.cancelproject", {
            huawei_project: projectId.value,
        }),
        null,
        {
            onSuccess: () => {
                closeCancelModal();
                router.visit(
                    route("huawei.projects", {
                        status: "2",
                        prefix: selectedPrefix.value,
                    })
                );
            },
        }
    );
};

const changePrefix = (value) => {
    selectedPrefix.value = value;
    if (props.search) {
        router.visit(
            route("huawei.projects.search", {
                status: props.status,
                prefix: selectedPrefix.value,
                request: searchForm.searchTerm,
            })
        );
    } else {
        router.visit(
            route("huawei.projects", {
                status: props.status,
                prefix: selectedPrefix.value,
            })
        );
    }
};

const openResumeModal = (id) => {
    projectId.value = id;
    resumeModal.value = true;
};

const closeResumeModal = () => {
    projectId.value = null;
    resumeModal.value = false;
};

const resume_project = () => {
    router.put(
        route("huawei.projects.stopped.resume", {
            huawei_project: projectId.value,
        }),
        null,
        {
            onSuccess: () => {
                closeResumeModal();
                router.visit(
                    route("huawei.projects", {
                        status: 1,
                        prefix: selectedPrefix.value,
                    })
                );
            },
        }
    );
};
</script>
