<template>

    <Head title="F. Pext" />
    <AuthenticatedLayout :redirectRoute="'cicsa.index'">
        <template #header> Facturación Pext </template>
        <template #header-right>
            <div>
                <span class="text-gray-700 font-medium pr-2 bg-green-100">Co</span>
                <span class="text-gray-700 font-medium">: Completado</span>
            </div>
            <div>
                <span class="text-gray-700 font-medium pr-2 bg-yellow-100">Pr</span>
                <span class="text-gray-700 font-medium">: En Proceso</span>
            </div>
            <div>
                <span class="text-gray-700 font-medium pr-2 bg-red-100">Pe</span>
                <span class="text-gray-700 font-medium">: Pendiente</span>
            </div>
        </template>
        <div class="min-w-full ">
            <div class="flex justify-between">
                <div class="flex space-x-4">
                    <FilterProcess v-if="filterForm.typeStages === 'Todos'" :options="selectableOptions"
                        v-model="selectedOptions" :width="'w-[230px]'" />
                    <!-- <button @click="getAllData()"
                        class="p-2 bg-white ring-1 ring-slate-400 rounded-md text-slate-900 hover:text-slate-400">
                        <ServerIcon class="h-5 w-5 font-bold" />
                    </button> -->
                    <button @click="router.visit(route('cicsa.index'))"
                        class="p-2 bg-transparent ring-1 ring-slate-300 rounded-md text-slate-900 hover:text-slate-400">
                        <ArrowPathIcon class="h-5 w-5" />
                    </button>
                    <button data-tooltip-target="export_cicsa_process" type="button"
                        class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500"
                        @click="openExportExcel">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9.29289 1.29289C9.48043 1.10536 9.73478 1 10 1H18C19.6569 1 21 2.34315 21 4V9C21 9.55228 20.5523 10 20 10C19.4477 10 19 9.55228 19 9V4C19 3.44772 18.5523 3 18 3H11V8C11 8.55228 10.5523 9 10 9H5V20C5 20.5523 5.44772 21 6 21H7C7.55228 21 8 21.4477 8 22C8 22.5523 7.55228 23 7 23H6C4.34315 23 3 21.6569 3 20V8C3 7.73478 3.10536 7.48043 3.29289 7.29289L9.29289 1.29289ZM6.41421 7H9V4.41421L6.41421 7ZM19 12C19.5523 12 20 12.4477 20 13V19H23C23.5523 19 24 19.4477 24 20C24 20.5523 23.5523 21 23 21H19C18.4477 21 18 20.5523 18 20V13C18 12.4477 18.4477 12 19 12ZM11.8137 12.4188C11.4927 11.9693 10.8682 11.8653 10.4188 12.1863C9.96935 12.5073 9.86526 13.1318 10.1863 13.5812L12.2711 16.5L10.1863 19.4188C9.86526 19.8682 9.96935 20.4927 10.4188 20.8137C10.8682 21.1347 11.4927 21.0307 11.8137 20.5812L13.5 18.2205L15.1863 20.5812C15.5073 21.0307 16.1318 21.1347 16.5812 20.8137C17.0307 20.4927 17.1347 19.8682 16.8137 19.4188L14.7289 16.5L16.8137 13.5812C17.1347 13.1318 17.0307 12.5073 16.5812 12.1863C16.1318 11.8653 15.5073 11.9693 15.1863 12.4188L13.5 14.7795L11.8137 12.4188Z"
                                fill="#ffffff" />
                        </svg>
                    </button>
                    <div id="export_cicsa_process" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Exportar Excel {{ filterForm.typeStages }}
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
                <div class="flex space-x-4">
                    <!-- <Link :href="route('cicsa.charge_areas.accepted')"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                    Completados
                    </Link>
                    <Link :href="route('cicsa.charge')"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 whitespace-nowrap">
                    Por Cobrar
                    </Link> -->
                    <TextInput data-tooltip-target="search_fields" type="text" v-model="filterForm.search"
                        placeholder="Buscar ..." />
                    <div id="search_fields" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Nombre,Cliente,Codigo
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <select v-model="filterForm.typeStages"
                        class="block pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option disabled value="">Seleccionar Etapa</option>
                        <option v-for="item in stages" :key="item.id">
                            {{ item === "" ? "Todos" : item }}
                        </option>
                    </select>
                    <SelectCicsaComponent currentSelect="Proceso" />

                </div>
            </div>
            <br />
            <div class="overflow-x-auto h-[70vh] rounded-lg shadow">
                <table :class="[
                    'w-full '
                ]">
                    <thead class="sticky top-0 z-40">
                        <tr class="text-xs font-semibold uppercase tracking-wide text-white">
                            <th v-if="!checkVisibility('Asignación')"
                                class="bg-gray-700 sticky left-0 z-50 border-b-2 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider"
                                colspan="3"></th>
                            <th v-if="checkVisibility('Asignación')"
                                class="bg-indigo-800 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider"
                                colspan="10">
                                Asignación
                            </th>
                            <th v-if="
                                checkVisibility('Factibilidad PINT y PEXT')
                            " class="bg-indigo-800 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider"
                                colspan="6">
                                Factibilidad PINT y PEXT
                            </th>
                            <th v-if="checkVisibility('Materiales')"
                                class="bg-indigo-800 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider"
                                colspan="5">
                                Materiales
                            </th>
                            <th v-if="
                                checkVisibility('Instalación PINT y PEXT')
                            " class="bg-indigo-800 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider"
                                colspan="10">
                                Instalación PINT y PEXT
                            </th>
                            <th v-if="
                                checkVisibility(['Asignación', 'Factibilidad PINT y PEXT', 'Materiales'
                                    , 'Instalación PINT y PEXT'])"
                                class="w-[150px] bg-indigo-800 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider">
                                Estado del Proyecto
                            </th>
                            <th v-if="checkVisibility('Orden de Compra')"
                                class="bg-purple-700 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider"
                                colspan="7">
                                Orden de Compra
                            </th>
                            <th v-if="checkVisibility('Validación de OC')"
                                class="bg-purple-700 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider"
                                colspan="9">
                                Validación de OC
                            </th>
                            <th v-if="checkVisibility('Orden de Servicio')"
                                class="bg-purple-700 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider"
                                colspan="9">
                                Orden de Servicio
                            </th>
                            <th v-if="checkVisibility(['Orden de Compra', 'Validación de OC'
                                , 'Orden de Servicio'])"
                                class="w-[150px] bg-green-700 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider">
                                Estado de Administracion
                            </th>
                            <th v-if="checkVisibility('Cobranza')"
                                class="bg-purple-700 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider"
                                colspan="16">
                                Cobranza
                            </th>
                            <!-- <th v-if="!checkVisibility('Cobranza')"
                                class="bg-gray-700 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r-2">
                            </th> -->
                            <th v-if="checkVisibility('Cobranza')"
                                class="w-[150px] bg-purple-700 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider">
                                Estado de Cobranza
                            </th>
                            <th v-if="auth.user.role_id === 1"
                                class="bg-gray-700 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider">
                            </th>
                        </tr>
                        <tr
                            class=" border-b bg-gray-50 text-[10px] font-semibold uppercase tracking-wide text-gray-500">
                            <th v-if="checkVisibility('Asignación')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <TableDateFilter labelClass="title" label="Fecha de Asignación" :reverse="true"
                                    v-model:startDate="filterForm.opStartDate" v-model:endDate="filterForm.opEndDate"
                                    v-model:noDate="filterForm.opNoDate" width="w-40" />
                            </th>
                            <th ref="thProjectName" :class="[
                                'border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600',
                                `sticky left-0 z-30`,
                            ]">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Nombre del Proyecto')"></p>
                                </div>
                            </th>
                            <th ref="thProjectCode" :style="thStickyStyle.pc_sticky" :class="[
                                `border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600`,
                            ]">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Código del Proyecto')"></p>
                                </div>
                            </th>

                            <th :style="thStickyStyle.pcpe_sticky" :class="[
                                'border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600',
                                checkVisibility('Asignación')
                                    ? ''
                                    : 'border-r-2',
                            ]">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('CPE')"></p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Asignación')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="w-[190px]">
                                    <TableHeaderCicsaFilter label="Centro de Costos" labelClass="title text-gray-600"
                                        :reverse="true" :options="[...cost_center]" v-model="filterForm.cost_center" />
                                </div>

                            </th>
                            <th v-if="checkVisibility('Asignación')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Zona')"></p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Asignación')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Cliente')"></p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Asignación')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Gestor')"></p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Asignación')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Encargado CCIP')"></p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Asignación')"
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Acciones')"></p>
                                </div>
                            </th>
                            <th v-if="
                                checkVisibility('Factibilidad PINT y PEXT')
                            "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Fecha de Factibilidad')"></p>
                                </div>
                            </th>
                            <th v-if="
                                checkVisibility('Factibilidad PINT y PEXT')
                            "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Informe')"></p>
                                </div>
                            </th>
                            <th v-if="
                                checkVisibility('Factibilidad PINT y PEXT')
                            "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Materiales de Factibilidad')"></p>
                                </div>
                            </th>
                            <th v-if="
                                checkVisibility('Factibilidad PINT y PEXT')
                            "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Coordinador')"></p>
                                </div>
                            </th>
                            <th v-if="
                                checkVisibility('Factibilidad PINT y PEXT')
                            "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Encargado CCIP')"></p>
                                </div>
                            </th>
                            <th v-if="
                                checkVisibility('Factibilidad PINT y PEXT')
                            "
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Acciones')"></p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Materiales')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Fecha de Recojo')"></p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Materiales')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Guía')"></p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Materiales')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Lista de Materiales')"></p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Materiales')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Encargado CCIP')"></p>
                                </div>
                            </th>
                            <th v-if="
                                checkVisibility('Materiales')
                            "
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Acciones')"></p>
                                </div>
                            </th>
                            <th v-if="
                                checkVisibility('Instalación PINT y PEXT')
                            "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Fecha de Pext')"></p>
                                </div>
                            </th>
                            <th v-if="
                                checkVisibility('Instalación PINT y PEXT')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Fecha de Pint')"></p>
                                </div>
                            </th>
                            <th v-if="
                                checkVisibility('Instalación PINT y PEXT')
                            "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Acta de Conformidad')"></p>
                                </div>
                            </th>
                            <th v-if="
                                checkVisibility('Instalación PINT y PEXT')
                            "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Informe')"></p>
                                </div>
                            </th>
                            <th v-if="
                                checkVisibility('Instalación PINT y PEXT')
                            "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Lista de Materiales Liquidados')">
                                    </p>
                                </div>
                            </th>
                            <th v-if="
                                checkVisibility('Instalación PINT y PEXT')
                            "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Fecha de Envío de Informe')">
                                    </p>
                                </div>
                            </th>
                            <th v-if="
                                checkVisibility('Instalación PINT y PEXT')
                            "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Monto Proyectado sin IGV')">
                                    </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Instalación PINT y PEXT')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Coordinador')">
                                    </p>
                                </div>
                            </th>
                            <th v-if="
                                checkVisibility('Instalación PINT y PEXT')
                            "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Encargado CCIP')"></p>
                                </div>
                            </th>
                            <th v-if="
                                checkVisibility('Instalación PINT y PEXT')
                            "
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Acciones')"></p>
                                </div>
                            </th>
                            <th v-if="
                                checkVisibility(['Asignación', 'Factibilidad PINT y PEXT', 'Materiales'
                                    , 'Instalación PINT y PEXT'])" class=" border-b-2 border-r-2 border-gray-300 bg-gray-100 px-2 py-1 text-[10px]
                                font-semibold uppercase tracking-wider">
                                <div class="w-[150px]">
                                    <TableHeaderCicsaFilter label="E. P." labelClass=" text-gray-600"
                                        :options="[...stats]" v-model="filterForm.project_status" ref="childRef" />
                                </div>
                            </th>
                            <th v-if="checkVisibility('Orden de Compra')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Fecha de OC')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Orden de Compra')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Numero de OC')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Orden de Compra')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Doc OC')">
                                    </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Orden de Compra')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Formato Maestro')">
                                    </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Orden de Compra')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Item 3456')">
                                    </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Orden de Compra')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Presupuesto')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Orden de Compra')"
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Acciones')">
                                    </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Validación de OC')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Fecha de Inicio')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Validación de OC')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Validacion de expediente')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Validación de OC')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Control de Materiales')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Validación de OC')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Supervisor')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Validación de OC')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Almacen')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Validación de OC')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Jefe de Obra')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Validación de OC')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Liquidador')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Validación de OC')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Superintendente')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Validación de OC')"
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Acciones')">
                                    </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Orden de Servicio')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Fecha de Orden de Servicio')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Orden de Servicio')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Orden de Servicio')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Orden de Servicio')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Doc OS')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Orden de Servicio')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Hoja de Estimación')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Orden de Servicio')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Orden de Compra')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Orden de Servicio')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Factura en PDF')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Orden de Servicio')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Factura en ZIP')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Orden de Servicio')"
                                class="border-b-2border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Doc Fac')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Orden de Servicio')"
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Acciones')">
                                    </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility(['Orden de Compra', 'Validación de OC'
                                , 'Orden de Servicio'])"
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-wider">
                                <div class="w-[150px]">
                                    <TableHeaderCicsaFilter label="E. C." labelClass=" text-gray-600"
                                        :options="[...stats]" v-model="filterForm.administration_status"
                                        ref="childRef2" />
                                </div>
                            </th>

                            <th v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Número de Factura')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Fecha de Factura')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Crédito A')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Fecha de Pago')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Días de Atraso')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Fecha de Abono')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Monto con IGV')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title"
                                        v-html="reverseWordsWithBreaks('Fecha de abono de Cuenta Corriente')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title"
                                        v-html="reverseWordsWithBreaks('Numero de Transaccion de Cuenta Corriente')">
                                    </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Monto de Cuenta Corriente')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Fecha de abono de Detraccion')">
                                    </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title"
                                        v-html="reverseWordsWithBreaks('Numero de Transaccion de Detraccion')">
                                    </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Monto de Detraccion')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Doc Detraccion')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Cobranza')"
                                class="border-b-2border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Estado de Pago')"> </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-2 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <div class="flex justify-center">
                                    <p class="title" v-html="reverseWordsWithBreaks('Acciones')">
                                    </p>
                                </div>
                            </th>
                            <th v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-wider">
                                <div class="w-[150px]">
                                    <TableHeaderCicsaFilter label="E. C." labelClass=" text-gray-600"
                                        :options="[...stats]" v-model="filterForm.charge_status" ref="childRef3" />
                                </div>
                            </th>
                            <th v-if="auth.user.role_id === 1"
                                class="border-b-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in dataToRender" :key="item.id" class="text-gray-700">
                            <td v-if="checkVisibility('Asignación')" :class="stateClass(item.assignation_date)"
                                class="border-b border-gray-200 px-2 py-1 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.assignation_date) }}
                                </p>
                            </td>
                            <td :class="stateClassSticky(item.project_name)"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] sticky left-0 z-30">

                                <div data-tooltip-target="project_name"
                                    class="flex justify-center w-64 truncate relative">
                                    {{ item.project_name }}
                                </div>

                                <div id="project_name" role="tooltip" class="absolute z-50 -top-8 left-0 w-max opacity-0 invisible transition-opacity duration-300 
                bg-gray-900 text-white text-xs font-medium px-2 py-1 rounded-lg shadow-sm dark:bg-gray-700">
                                    {{ item.project_name }}
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </td>

                            <td :style="thStickyStyle.pc_sticky" :class="stateClassSticky(
                                item.project_code
                            )
                                " class="sticky border-b border-gray-200 px-2 py-1 text-[13px]">
                                <div class="flex justify-center">
                                    <p class="text-gray-900 text-center">
                                        {{ item.project_code || "--" }}
                                    </p>
                                </div>
                            </td>
                            <td :style="thStickyStyle.pcpe_sticky" :class="stateClassSticky(
                                item.cpe
                            )
                                " class="border-b border-gray-200 px-2 py-1 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.cpe }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Asignación')" :class="stateClass(item.cost_center)"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] whitespace-nowrap">
                                <p class="text-gray-900 text-center" :class="stateClassP(
                                    item.cost_center
                                )
                                    ">
                                    {{ item.cost_center || "--" }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Asignación')" :class="stateClass(item.zone)"
                                class="border-b border-gray-200 px-2 py-1 text-[13px]">
                                <p class="text-gray-900 text-center" :class="stateClassP(
                                    item.cost_center
                                )
                                    ">
                                    {{ item.zone || "--" }} {{ item.zone2 }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Asignación')" :class="stateClass(item.customer)"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] whitespace-nowrap">
                                <p class="text-gray-900 text-center">
                                    {{ item.customer }}
                                </p>
                            </td>
                            <td :class="stateClass(item.manager)" v-if="checkVisibility('Asignación')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] whitespace-nowrap">
                                <p class="text-gray-900 text-center">
                                    {{ item.manager }}
                                </p>
                            </td>

                            <td :class="stateClass(item.user_name)" v-if="checkVisibility('Asignación')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] whitespace-nowrap">
                                <p class="text-gray-900 text-center">
                                    {{ formatoManager(item.user_name) }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Asignación')"
                                class="bg-white border-b border-r-2 border-gray-200 px-2 py-1 text-[13px] whitespace-nowrap">
                                <button @click="router.get(route('assignation.index', { searchCondition: item.cpe }))">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m15 11.25-3-3m0 0-3 3m3-3v7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            </td>
                            <td :class="stateClass(
                                item?.cicsa_feasibility?.feasibility_date
                            )
                                " v-if="
                                    checkVisibility('Factibilidad PINT y PEXT')
                                " class="border-b border-gray-200 px-2 py-1 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{
                                        formattedDate(
                                            item?.cicsa_feasibility
                                                ?.feasibility_date
                                        )
                                    }}
                                </p>
                            </td>
                            <td :class="stateClass(item?.cicsa_feasibility?.report)
                                " v-if="
                                    checkVisibility('Factibilidad PINT y PEXT')
                                " class="border-b border-gray-200 px-2 py-1 text-[13px]">
                                <p class="text-gray-900 text-center whitespace-nowrap">
                                    {{ formattedState(item?.cicsa_feasibility?.report) }}
                                </p>
                            </td>
                            <td :class="stateClass(
                                item?.cicsa_feasibility
                                    ?.cicsa_feasibility_materials
                                    ?.length > 0
                            )
                                " v-if="
                                    checkVisibility('Factibilidad PINT y PEXT')
                                " class="border-b border-gray-200 px-2 py-1 text-[13px]">
                                <div v-if="
                                    item?.cicsa_feasibility
                                        ?.cicsa_feasibility_materials
                                        ?.length > 0
                                " class="flex items-center justify-center">
                                    <button @click="
                                        openMaterialsModal(
                                            item?.cicsa_feasibility
                                                ?.cicsa_feasibility_materials,
                                            'Materiales de Factibilidad'
                                        )
                                        " class="text-green-600">
                                        <EyeIcon class="h-4 w-4 ml-1" />
                                    </button>
                                </div>
                            </td>
                            <td :class="stateClass(
                                item?.cicsa_feasibility?.coordinator
                            )
                                " v-if="
                                    checkVisibility('Factibilidad PINT y PEXT')
                                " class="border-b border-gray-200 px-2 py-1 text-[13px] whitespace-nowrap">
                                <p class="text-gray-900 text-center">
                                    {{ formatoManager(item?.cicsa_feasibility?.coordinator) }}
                                </p>
                            </td>

                            <td :class="stateClass(
                                item?.cicsa_feasibility?.user_name
                            )
                                " v-if="
                                    checkVisibility('Factibilidad PINT y PEXT')
                                " class="border-b border-gray-200 px-2 py-1 text-[13px] whitespace-nowrap">
                                <p class="text-gray-900 text-center">
                                    {{ formatoManager(item?.cicsa_feasibility?.user_name) }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Factibilidad PINT y PEXT')"
                                class="bg-white border-b border-r-2 border-gray-200 px-2 py-1 text-[13px] whitespace-nowrap">
                                <button
                                    @click="router.get(route('feasibilities.index', { searchCondition: item.cpe }))">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m15 11.25-3-3m0 0-3 3m3-3v7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            </td>
                            <td :class="stateClass(
                                item?.cicsa_materials?.some(
                                    (item) => item?.pick_date
                                )
                            )
                                " v-if="checkVisibility('Materiales')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px]">
                                <p v-for="materials in item?.cicsa_materials"
                                    class="text-gray-900 text-center whitespace-nowrap">
                                    {{
                                        materials.pick_date || "--"
                                    }}
                                </p>
                            </td>
                            <td :class="stateClass(
                                item?.cicsa_materials?.some(
                                    (item) => item?.guide_number
                                )
                            )
                                " v-if="checkVisibility('Materiales')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px]">
                                <p v-for="materials in item?.cicsa_materials"
                                    class="text-gray-900 text-center whitespace-nowrap">
                                    {{
                                        materials.guide_number || "--"
                                    }}
                                </p>
                            </td>
                            <td :class="stateClass(
                                item?.total_materials?.length > 0
                            )
                                " v-if="checkVisibility('Materiales')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px]">
                                <div class="flex items-center justify-center">
                                    <button v-if="item?.total_materials?.length > 0" type="button" @click="
                                        openMaterialsModal(
                                            item?.total_materials,
                                            'Materiales Recibidos'
                                        )
                                        " class="text-green-600">
                                        <EyeIcon class="h-4 w-4 ml-1" />
                                    </button>
                                </div>
                            </td>
                            <td :class="stateClass(
                                item?.cicsa_materials?.some(
                                    (item) => item?.user_name
                                )
                            )
                                " v-if="checkVisibility('Materiales')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px]">
                                <p v-for="materials in item?.cicsa_materials"
                                    class="text-gray-900 text-center whitespace-nowrap">
                                    {{
                                        formatoManager(materials.user_name)
                                    }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Materiales')"
                                class="bg-white border-b border-r-2 border-gray-200 px-2 py-1 text-[13px] whitespace-nowrap">
                                <button @click="router.get(route('material.index', { searchCondition: item.cpe }))">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m15 11.25-3-3m0 0-3 3m3-3v7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            </td>
                            <td :class="stateClass(
                                item?.cicsa_installation?.pext_date
                            )
                                " v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                " class="border-b border-gray-200 px-2 py-1 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{
                                        formattedDate(
                                            item.cicsa_installation?.pext_date
                                        )
                                    }}
                                </p>
                            </td>
                            <td :class="stateClass(
                                item?.cicsa_installation?.pint_date
                            )
                                " v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                " class="border-b border-gray-200 px-2 py-1 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{
                                        formattedDate(
                                            item.cicsa_installation?.pint_date
                                        )
                                    }}
                                </p>
                            </td>
                            <td :class="stateClass(
                                item?.cicsa_installation?.conformity
                            )
                                " v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                " class="border-b border-gray-200 px-2 py-1 text-[13px]">
                                <p class="text-gray-900 text-center whitespace-nowrap">
                                    {{ formattedState(item?.cicsa_installation?.conformity) }}
                                </p>
                            </td>
                            <td :class="stateClass(item?.cicsa_installation?.report)
                                " v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                " class="border-b border-gray-200 px-2 py-1 text-[13px]">
                                <p class="text-gray-900 text-center whitespace-nowrap">
                                    {{ formattedState(item?.cicsa_installation?.report) }}
                                </p>
                            </td>
                            <td :class="stateClass(
                                item?.cicsa_installation
                                    ?.cicsa_installation_materials
                                    ?.length > 0
                            )
                                " v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                " class="border-b border-gray-200 px-2 py-1 text-[13px]">
                                <div class="flex items-center justify-center">
                                    <button v-if="
                                        item?.cicsa_installation
                                            ?.cicsa_installation_materials
                                            ?.length > 0
                                    " @click="
                                        openInstMaterialsModal(
                                            item?.cicsa_installation
                                                ?.cicsa_installation_materials
                                        )
                                        " class="text-green-600">
                                        <EyeIcon class="h-4 w-4 ml-1" />
                                    </button>
                                </div>
                            </td>
                            <td :class="stateClass(
                                item?.cicsa_installation
                                    ?.shipping_report_date
                            )
                                " v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                " class="border-b border-gray-200 px-2 py-1 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{
                                        formattedDate(
                                            item?.cicsa_installation
                                                ?.shipping_report_date
                                        )
                                    }}
                                </p>
                            </td>
                            <td :class="stateClass(
                                item?.cicsa_installation
                                    ?.projected_amount
                            )
                                " v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                " class="border-b border-gray-200 px-2 py-1 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{
                                        item?.cicsa_installation
                                            ?.projected_amount
                                    }}
                                </p>
                            </td>
                            <td :class="stateClass(
                                item?.cicsa_installation
                                    ?.coordinator
                            )
                                " v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                " class="border-b border-gray-200 px-2 py-1 text-[13px]">
                                <p class="text-gray-900 text-center whitespace-nowrap">
                                    {{
                                        formatoManager(item?.cicsa_installation?.coordinator)
                                    }}
                                </p>
                            </td>
                            <td :class="stateClass(
                                item?.cicsa_installation?.user_name
                            )
                                " v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                " class="border-b border-gray-200 px-2 py-1 text-[13px]">
                                <p class="text-gray-900 text-center whitespace-nowrap">
                                    {{ formatoManager(item?.cicsa_installation?.user_name) }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Instalación PINT y PEXT')"
                                class="bg-white border-b border-r-2 border-gray-200 px-2 py-1 text-[13px] whitespace-nowrap">
                                <button
                                    @click="router.get(route('cicsa.installation.index', { searchCondition: item.cpe }))">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m15 11.25-3-3m0 0-3 3m3-3v7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            </td>
                            <td v-if="checkVisibility(['Asignación', 'Factibilidad PINT y PEXT', 'Materiales', 'Instalación PINT y PEXT'])"
                                :class="stateClass(item?.cicsa_project_status)"
                                class="border-b border-r-2 border-gray-200 px-2 py-1 text-[13px]">
                                <div class="flex justify-center">
                                    <p class="font-black uppercase text-center">
                                        {{ item?.cicsa_project_status }}
                                        <br>
                                        {{ item?.last_project_status_date }}
                                    </p>
                                </div>
                            </td>

                            <td v-if="checkVisibility('Orden de Compra')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] bg-white">
                                <p v-for="order in item?.cicsa_purchase_order" :class="stateClassP(
                                    order?.oc_date
                                )
                                    " class="text-gray-900 text-center">
                                    {{
                                        formattedDate(
                                            order?.oc_date
                                        ) || "--"
                                    }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Orden de Compra')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] bg-white">
                                <p v-for="order in item?.cicsa_purchase_order" :class="stateClassP(
                                    order?.oc_number
                                )
                                    " class="text-gray-900 text-center">
                                    {{ order?.oc_number || "--" }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Orden de Compra')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] bg-white">
                                <div v-for="order in item?.cicsa_purchase_order" class="text-center text-red-500">
                                    <button v-if="order.document" type="button"
                                        @click="openPDF(order?.id, 'purchaseOrder')">
                                        <EyeIcon class="w-4 h-4 text-green-600" />
                                    </button>
                                    <p v-else>
                                        --
                                    </p>
                                </div>
                            </td>
                            <td v-if="checkVisibility('Orden de Compra')"
                                class="border-b border-gray-200 py-2 text-[13px] bg-white">
                                <p v-for="order in item?.cicsa_purchase_order" :class="stateClassP(
                                    order?.master_format
                                )
                                    " class="text-center">
                                    {{
                                        formattedState(order
                                            ?.master_format)
                                    }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Orden de Compra')"
                                class="border-b border-gray-200 py-2 text-[13px] bg-white">
                                <p v-for="order in item?.cicsa_purchase_order" :class="stateClassP(
                                    order?.item3456
                                )
                                    " class="text-center">
                                    {{ formattedState(order?.item3456) }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Orden de Compra')"
                                class="border-b border-gray-200 py-2 text-[13px] bg-white">
                                <p v-for="order in item?.cicsa_purchase_order" :class="stateClassP(
                                    order?.budget
                                )
                                    " class="text-center">
                                    {{ formattedState(order?.budget) }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Orden de Compra')"
                                class="bg-white border-b border-r-2 border-gray-200 px-2 py-1 text-[13px] whitespace-nowrap">
                                <div v-for="order in item?.cicsa_purchase_order">
                                    <button
                                        @click="router.get(route('purchase.order.index', { searchCondition: order.oc_number }))">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m15 11.25-3-3m0 0-3 3m3-3v7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td v-if="checkVisibility('Validación de OC')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] bg-white">
                                <p v-for="order_validation in item?.cicsa_purchase_order_validation" :class="stateClassP(
                                    order_validation
                                        ?.validation_date
                                )
                                    " class="text-center">
                                    {{
                                        formattedDate(
                                            order_validation
                                                ?.validation_date
                                        ) || "--"
                                    }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Validación de OC')"
                                class="border-b border-gray-200 py-2 text-[13px] bg-white">
                                <p v-for="order_validation in item?.cicsa_purchase_order_validation" :class="stateClassP(
                                    order_validation
                                        ?.file_validation
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">
                                    {{
                                        formattedState(order_validation
                                            ?.file_validation)
                                    }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Validación de OC')"
                                class="border-b border-gray-200 py-2 text-[13px] bg-white">
                                <p v-for="order_validation in item?.cicsa_purchase_order_validation" :class="stateClassP(
                                    order_validation
                                        ?.materials_control
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">
                                    {{
                                        formattedState(order_validation
                                            ?.materials_control)
                                    }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Validación de OC')"
                                class="border-b border-gray-200 py-2 text-[13px] bg-white">
                                <p v-for="order_validation in item?.cicsa_purchase_order_validation" :class="stateClassP(
                                    order_validation
                                        ?.supervisor
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">
                                    {{
                                        formattedState(order_validation
                                            ?.supervisor)
                                    }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Validación de OC')"
                                class="border-b border-gray-200 py-2 text-[13px] bg-white">
                                <p v-for="order_validation in item?.cicsa_purchase_order_validation" :class="stateClassP(
                                    order_validation
                                        ?.warehouse
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">
                                    {{
                                        formattedState(order_validation
                                            ?.warehouse)
                                    }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Validación de OC')"
                                class="border-b border-gray-200 py-2 text-[13px] bg-white">
                                <p v-for="order_validation in item?.cicsa_purchase_order_validation" :class="stateClassP(
                                    order_validation
                                        ?.boss
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">
                                    {{
                                        formattedState(order_validation
                                            ?.boss)
                                    }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Validación de OC')"
                                class="border-b border-gray-200 py-2 text-[13px] bg-white">
                                <p v-for="order_validation in item?.cicsa_purchase_order_validation" :class="stateClassP(
                                    order_validation
                                        ?.liquidator
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">
                                    {{
                                        formattedState(order_validation
                                            ?.liquidator)
                                    }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Validación de OC')"
                                class="border-b border-gray-200 py-2 text-[13px] bg-white">
                                <p v-for="order_validation in item?.cicsa_purchase_order_validation" :class="stateClassP(
                                    order_validation
                                        ?.superintendent
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">
                                    {{
                                        formattedState(order_validation
                                            ?.superintendent)
                                    }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Validación de OC')"
                                class="bg-white border-b border-r-2 border-gray-200 px-2 py-1 text-[13px] whitespace-nowrap">
                                <div v-for="order in item?.cicsa_purchase_order">
                                    <button
                                        @click="router.get(route('cicsa.purchase_orders.validation', { searchCondition: order.oc_number }))">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m15 11.25-3-3m0 0-3 3m3-3v7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td v-if="checkVisibility('Orden de Servicio')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] bg-white">
                                <p v-for="service_order in item?.cicsa_service_order" :class="stateClassP(
                                    service_order
                                        ?.service_order_date
                                )
                                    " class="text-center whitespace-nowrap">
                                    {{
                                        formattedDate(
                                            service_order
                                                ?.service_order_date
                                        ) || "--"
                                    }}
                                </p>
                            </td>

                            <td v-if="checkVisibility('Orden de Servicio')"
                                class="border-b border-gray-200 py-2 text-[13px] bg-white">
                                <p v-for="service_order in item?.cicsa_service_order" :class="stateClassP(
                                    service_order
                                        ?.service_order
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">
                                    {{
                                        formattedState(service_order?.service_order)
                                    }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Orden de Servicio')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] bg-white">
                                <div v-for="service_order in item?.cicsa_service_order"
                                    class="text-center text-red-500">
                                    <button v-if="service_order.document" type="button"
                                        @click="openPDF(service_order?.id, 'serviceOrder', 'OS')">
                                        <EyeIcon class="w-4 h-4 text-green-600" />
                                    </button>
                                    <p v-else>
                                        --
                                    </p>
                                </div>
                            </td>
                            <td v-if="checkVisibility('Orden de Servicio')"
                                class="border-b border-gray-200 py-2 text-[13px] bg-white">
                                <p v-for="service_order in item?.cicsa_service_order" :class="stateClassP(
                                    service_order
                                        ?.estimate_sheet
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">
                                    {{
                                        formattedState(service_order
                                            ?.estimate_sheet)
                                    }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Orden de Servicio')"
                                class="border-b border-gray-200 py-2 text-[13px] bg-white">
                                <p v-for="service_order in item?.cicsa_service_order" :class="stateClassP(
                                    service_order
                                        ?.purchase_order
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">
                                    {{
                                        formattedState(service_order
                                            ?.purchase_order)
                                    }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Orden de Servicio')"
                                class="border-b border-gray-200 py-2 text-[13px] bg-white">
                                <p v-for="service_order in item?.cicsa_service_order" :class="stateClassP(
                                    service_order
                                        ?.pdf_invoice
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">
                                    {{ formattedState(service_order?.pdf_invoice) }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Orden de Servicio')"
                                class="border-b border-gray-200 py-2 text-[13px] bg-white">
                                <p v-for="service_order in item?.cicsa_service_order" :class="stateClassP(
                                    service_order
                                        ?.zip_invoice
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">
                                    {{ formattedState(service_order?.zip_invoice) }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Orden de Servicio')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] bg-white">
                                <div v-for="service_order in item?.cicsa_service_order"
                                    class="text-center text-red-500">
                                    <button v-if="service_order.document_invoice" type="button"
                                        @click="openPDF(service_order?.id, 'serviceOrder', 'invoice')">
                                        <EyeIcon class="w-4 h-4 text-green-600" />
                                    </button>
                                    <p v-else>
                                        --
                                    </p>
                                </div>
                            </td>
                            <td v-if="checkVisibility('Orden de Servicio')"
                                class="bg-white border-b border-r-2 border-gray-200 px-2 py-1 text-[13px] whitespace-nowrap">
                                <div v-for="order in item?.cicsa_purchase_order">
                                    <button
                                        @click="router.get(route('cicsa.service_orders', { searchCondition: order.oc_number }))">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m15 11.25-3-3m0 0-3 3m3-3v7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td v-if="checkVisibility(['Orden de Compra', 'Validación de OC', 'Orden de Servicio'])"
                                :class="stateClass(item?.cicsa_administration_status)"
                                class="border-b border-r-2 border-gray-200 px-2 py-1 text-[13px]">
                                <div class="flex justify-center">
                                    <p class="font-black uppercase text-center">
                                        {{ item?.cicsa_administration_status }}
                                        <br>
                                        {{ item?.last_administration_status_date }}
                                    </p>
                                </div>
                            </td>

                            <td v-if="checkVisibility('Cobranza')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] bg-white">
                                <p v-for="charge_area in item?.cicsa_charge_area" :class="stateClassP(
                                    charge_area
                                        ?.invoice_number
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">
                                    {{
                                        charge_area?.invoice_number || "--"
                                    }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Cobranza')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] bg-white">
                                <p v-for="charge_area in item?.cicsa_charge_area" :class="stateClassP(
                                    charge_area
                                        ?.invoice_date
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">
                                    {{
                                        formattedDate(
                                            charge_area
                                                ?.invoice_date
                                        ) || "--"
                                    }}
                                </p>
                            </td>


                            <td v-if="checkVisibility('Cobranza')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] bg-white">
                                <p v-for="charge_area in item?.cicsa_charge_area" :class="stateClassP(
                                    charge_area
                                        ?.credit_to
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">
                                    {{ charge_area?.credit_to }}
                                    {{
                                        charge_area?.credit_to
                                            ? "días"
                                            : "" || "--"
                                    }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Cobranza')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] bg-white">
                                <p v-for="charge_area in item?.cicsa_charge_area" :class="stateClassP(
                                    charge_area
                                        ?.payment_date
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">
                                    {{
                                        formattedDate(
                                            charge_area
                                                ?.payment_date
                                        ) || "--"
                                    }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Cobranza')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] bg-white">
                                <p v-for="charge_area in item?.cicsa_charge_area" :class="stateClassP(
                                    charge_area
                                        ?.days_late
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">
                                    {{ charge_area?.days_late }}
                                    {{
                                        charge_area?.days_late
                                            ? "días"
                                            : ""
                                    }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Cobranza')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] bg-white">
                                <p v-for="charge_area in item?.cicsa_charge_area" :class="stateClassP(
                                    charge_area
                                        ?.deposit_date
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">
                                    {{
                                        formattedDate(
                                            charge_area
                                                ?.deposit_date
                                        ) || "--"
                                    }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Cobranza')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] bg-white">
                                <p v-for="charge_area in item?.cicsa_charge_area" :class="stateClassP(
                                    charge_area
                                        ?.amount
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">
                                    {{
                                        charge_area?.amount
                                            ? "S/."
                                            : ""
                                    }}
                                    {{ charge_area?.amount ? charge_area?.amount.toFixed(2) : "--" }}
                                </p>
                            </td>

                            <td v-if="checkVisibility('Cobranza')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] bg-white">
                                <p v-for="charge_area in item?.cicsa_charge_area" :class="stateClassP(
                                    charge_area
                                        ?.deposit_date
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">
                                    {{ charge_area?.deposit_date ? formattedDate(charge_area?.deposit_date) : "--" }}
                                </p>
                            </td>

                            <td v-if="checkVisibility('Cobranza')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] bg-white">
                                <p v-for="charge_area in item?.cicsa_charge_area" :class="stateClassP(
                                    charge_area
                                        ?.transaction_number_current
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">
                                    {{ charge_area?.transaction_number_current ?? "--" }}
                                </p>
                            </td>

                            <td v-if="checkVisibility('Cobranza')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] bg-white">
                                <p v-for="charge_area in item?.cicsa_charge_area" :class="stateClassP(
                                    charge_area
                                        ?.checking_account_amount
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">
                                    {{
                                        charge_area?.checking_account_amount
                                            ? "S/."
                                            : ""
                                    }}
                                    {{ charge_area?.checking_account_amount ?
                                        charge_area?.checking_account_amount.toFixed(2) :
                                        "--" }}
                                </p>
                            </td>

                            <td v-if="checkVisibility('Cobranza')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] bg-white">
                                <p v-for="charge_area in item?.cicsa_charge_area" :class="stateClassP(
                                    charge_area
                                        ?.deposit_date_bank, charge_area
                                    ?.state_detraction
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">

                                    {{ charge_area?.deposit_date_bank ? formattedDate(charge_area?.deposit_date_bank) :
                                        "--" }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Cobranza')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] bg-white">
                                <p v-for="charge_area in item?.cicsa_charge_area" :class="stateClassP(
                                    charge_area
                                        ?.transaction_number_bank, charge_area
                                    ?.state_detraction
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">

                                    {{ charge_area?.transaction_number_bank ?? "--" }}
                                </p>
                            </td>

                            <td v-if="checkVisibility('Cobranza')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] bg-white">
                                <p v-for="charge_area in item?.cicsa_charge_area" :class="stateClassP(
                                    charge_area
                                        ?.amount_bank, charge_area
                                    ?.state_detraction
                                )
                                    " class="text-gray-900 text-center whitespace-nowrap">
                                    {{
                                        charge_area?.amount_bank
                                            ? "S/."
                                            : ""
                                    }}
                                    {{ charge_area?.amount_bank ? charge_area?.amount_bank.toFixed(2) : "--" }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Cobranza')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] bg-white">
                                <div v-for="charge_area in item?.cicsa_charge_area" :class="stateClassP(
                                    charge_area
                                        ?.document, charge_area
                                    ?.state_detraction
                                )
                                    " class="text-center">
                                    <button v-if="charge_area.document" type="button"
                                        @click="openPDF(charge_area?.id, 'chargeAreaOrder')">
                                        <EyeIcon class="w-4 h-4 text-green-600" />
                                    </button>
                                    <p v-else>
                                        --
                                    </p>
                                </div>
                            </td>
                            <td v-if="checkVisibility('Cobranza')"
                                class="border-b border-gray-200 px-2 py-1 text-[13px] bg-white">
                                <p v-for="charge_area in item?.cicsa_charge_area"
                                    class="text-gray-900 text-center whitespace-nowrap">
                                    {{ charge_area?.state }}
                                </p>
                            </td>
                            <td v-if="checkVisibility('Cobranza')"
                                class="bg-white border-b border-r-2 border-gray-200 px-2 py-1 text-[13px] whitespace-nowrap">
                                <div v-for="order in item?.cicsa_purchase_order">
                                    <button
                                        @click="router.get(route('cicsa.charge_areas', { searchCondition: order.oc_number }))">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m15 11.25-3-3m0 0-3 3m3-3v7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td v-if="checkVisibility('Cobranza')"
                                class="border-b border-r-2 border-gray-200 px-2 py-1 text-[13px]" :class="stateClass(
                                    item?.cicsa_charge_status
                                )
                                    ">
                                <div class="flex justify-center">
                                    <p class="font-black uppercase text-center">
                                        {{ item?.cicsa_charge_status }}
                                        <br>
                                        {{ item?.last_charge_status_date }}
                                    </p>
                                </div>
                            </td>
                            <td v-if="auth.user.role_id === 1"
                                class="border-b border-gray-200 bg-white px-2 py-0 text-[13px]">
                                <div class="flex space-x-3 justify-center">
                                    <button type="button" @click="openSotDeleteModal(item.id)">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-red-500">
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

            <table class="w-full">
                <tbody>
                    <tr class="text-gray-700 w-full">
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 text-center">
                                Total Registros: {{ dataToRender.length }}
                            </p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p v-if="dataToRender[0]?.cicsa_charge_area" class="text-gray-900 text-center">
                                Monto Total: S/.
                                {{ getTotalAmount(dataToRender) }}
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="!filterMode"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="projects.links" />
            </div>
        </div>

        <Modal :show="showMaterials" @close="closeMaterialsModal" :closeable="true">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    Total Materiales Recibidos
                </h2>
                <br />
                <div class="mt-2">
                    <div v-if="materials.length > 0" class="overflow-auto">
                        <table class="w-full whitespace-no-wrap border-collapse border border-slate-300">
                            <thead>
                                <tr
                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        N° Guía
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Material
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Unidad
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Cantidad
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, i) in materials" :key="i" class="text-gray-700 bg-white text-sm">
                                    <td class="border-b border-slate-300 px-4 py-4 whitespace-nowrap">
                                        {{ item?.guide_number }}
                                    </td>
                                    <td class="border-b border-slate-300 px-4 py-4">
                                        {{ item?.name }}
                                    </td>
                                    <td class="border-b border-slate-300 px-4 py-4">
                                        {{ item?.unit }}
                                    </td>
                                    <td class="border-b border-slate-300 px-4 py-4">
                                        {{ item?.quantity }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p v-else>No hay materiales asignados</p>
                    <br />
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="closeMaterialsModal">
                            Cerrar
                        </SecondaryButton>
                    </div>
                </div>
            </div>
        </Modal>

        <Modal :show="showInstMaterials" @close="closeInstMaterialsModal" :closeable="true">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    Materiales Liquidados
                </h2>
                <br />
                <div class="mt-2">
                    <div v-if="instMaterials.length > 0" class="overflow-auto">
                        <table class="w-full whitespace-no-wrap border-collapse border border-slate-300">
                            <thead>
                                <tr
                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Material
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Cantidad Total
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Recibidos
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Usados
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Resto
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Materiales Asignados
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, i) in instMaterials" :key="i" class="text-gray-700 bg-white text-sm">
                                    <td class="border-b border-slate-300 px-4 py-4">
                                        {{ item?.name }}
                                    </td>
                                    <td class="border-b border-slate-300 px-4 py-4">
                                        {{ item?.total_quantity }}
                                    </td>
                                    <td class="border-b border-slate-300 px-4 py-4">
                                        {{ item?.quantity }}
                                    </td>
                                    <td class="border-b border-slate-300 px-4 py-4">
                                        {{ item?.used_quantity }}
                                    </td>
                                    <td class="border-b border-slate-300 px-4 py-4">
                                        {{
                                            item?.quantity - item?.used_quantity
                                        }}
                                    </td>
                                    <td class="border-b border-slate-300 px-4 py-4">
                                        {{ item.quantity === item.used_quantity ? item.total_quantity -
                                            item.quantity : 'No se asigno materiales' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p v-else>No hay materiales asignados</p>
                    <br />
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="closeInstMaterialsModal">
                            Cerrar
                        </SecondaryButton>
                    </div>
                </div>
            </div>
        </Modal>

        <Modal :show="showSotDeleteModal" @close="closeSotDeleteModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    ¿Estás seguro de que quieres eliminar el registro?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Una vez que se elimine el registro, todos sus recursos y
                    datos se eliminarán permanentemente.
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeSotDeleteModal">
                        Cancelar
                    </SecondaryButton>

                    <DangerButton class="ml-3" @click="deleteSot">
                        Eliminar
                    </DangerButton>
                </div>
            </div>
        </Modal>

        <SuccessOperationModal :confirming="confirmSotDelete" :title="'Registro Eliminado'"
            :message="'El proyecto fue eliminado'" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { Head, router, Link } from "@inertiajs/vue3";
import { ref, nextTick, onMounted, onUnmounted, watch } from "vue";
import SelectCicsaComponent from "@/Components/SelectCicsaComponent.vue";
import { formattedDate } from "@/utils/utils";
import SuccessOperationModal from "@/Components/SuccessOperationModal.vue";
import FilterProcess from "@/Components/FilterProcess.vue";
import TableHeaderCicsaFilter from "@/Components/TableHeaderCicsaFilter.vue";
import { ArrowPathIcon, ServerIcon, EyeIcon } from "@heroicons/vue/24/outline";
import TextInput from "@/Components/TextInput.vue";
import TableDateFilter from "@/Components/TableDateFilter.vue";

const { auth, projects } = defineProps({
    auth: Object,
    projects: Object,
});

const dataToRender = ref(projects.data);
const filterMode = ref(false);

const showSotDeleteModal = ref(false);
const sotToDelete = ref(null);
const confirmSotDelete = ref(false);

function openSotDeleteModal(id) {
    sotToDelete.value = id;
    showSotDeleteModal.value = true;
}

function closeSotDeleteModal() {
    sotToDelete.value = null;
    showSotDeleteModal.value = false;
}

async function deleteSot() {
    let url = route("cicsa.assignation.destroy", { ca_id: sotToDelete.value });
    try {
        await axios.delete(url)
        updateCicsaIndex(sotToDelete.value)
        closeSotDeleteModal();
        confirmSotDelete.value = true;
        setTimeout(() => {
            confirmSotDelete.value = false;
        }, 1500);
    } catch (error) {
        console.error(error)
    }
}

const material_title = ref("");
//materials
const showMaterials = ref(false);
const materials = ref([]);
function openMaterialsModal(arrayMaterials, title) {
    materials.value = arrayMaterials ? arrayMaterials : [];
    material_title.value = title;
    showMaterials.value = true;
}
function closeMaterialsModal() {
    showMaterials.value = false;
}
//installation materials
const showInstMaterials = ref(false);
const instMaterials = ref([]);
function openInstMaterialsModal(arrayMaterials) {
    instMaterials.value = arrayMaterials ? arrayMaterials : [];
    showInstMaterials.value = true;
}
function closeInstMaterialsModal() {
    showInstMaterials.value = false;
}
const selectableOptions = [
    "Asignación",
    "Factibilidad PINT y PEXT",
    "Materiales",
    "Instalación PINT y PEXT",
    "Orden de Compra",
    "Validación de OC",
    "Orden de Servicio",
    "Cobranza",
]
//Stage visibility
const selectedOptions = ref(selectableOptions);

function checkVisibility(option) {
    if (typeof option === 'string') {
        return selectedOptions.value.includes(option);
    } else {
        return option.some(item => selectedOptions.value.includes(item));
    }
}

watch(selectedOptions, async () => {
    await nextTick();
    establishStickyStyles();
});

//Cells background
const stateClass = (state) => {
    switch (state) {
        case undefined:
        case null:
        case false:
        case "Pendiente":
            return "bg-red-100";
        case "En Proceso":
            return "bg-yellow-100";
        case "Completado":
            return "bg-green-100";
        default:
            return "bg-white";
    }
};

const stateClassSticky = (state) => {
    switch (state) {
        case undefined:
        case null:
        case false:
        case "":
            return "bg-red-200";
        default:
            return "bg-amber-200";
    }
};

const stateClassP = (state, state_detraction) => {
    if (state_detraction === 0) {
        return "bg-white";
    }

    switch (state) {
        case undefined:
        case null:
        case false:
        case "":
        case "Pendiente":
            return "bg-red-100";
        case "En Proceso":
            return "bg-yellow-100";
        case "Completado":
            return "bg-green-100";
        default:
            return "bg-white";
    }
};

//Total
function getTotalAmount(objArray) {
    return objArray.reduce((a, item) => {
        const sumAmount = item.cicsa_charge_area.reduce((sum, chargeArea) => {
            return sum + (chargeArea?.amount ? chargeArea.amount : 0);
        }, 0);
        return a + sumAmount;
    }, 0
    ).toFixed(2);
}

//filter
const stages = ["", "Proyecto", "Administracion", "Cobranza"];
const stats = ["Pendiente", "En Proceso", "Completado"];
const cost_center = ["Mantto Pext Claro", "Instalaciones GTD", "Mantto Pext GTD", "Densificacion", "Adicionales", "Instalaciones Claro", "TSS"];
const initSearch = {
    typeStages: "Todos",
    cost_center: [...cost_center],
    project_status: [...stats],
    administration_status: [...stats],
    charge_status: [...stats],
    opStartDate: "",
    opEndDate: "",
    opNoDate: "",
    search: "",
};
const filterForm = ref({ ...initSearch });

watch(
    () => [
        filterForm.value.typeStages,
        filterForm.value.cost_center,
        filterForm.value.project_status,
        filterForm.value.administration_status,
        filterForm.value.charge_status,
        filterForm.value.opStartDate,
        filterForm.value.opEndDate,
        filterForm.value.opNoDate,
        filterForm.value.search,
    ],
    () => {
        search_advance(filterForm.value);
        filterMode.value = true;
    },
    { deep: true }
);

watch(
    () => [
        filterForm.value.typeStages,
    ],
    () => {
        if (filterForm.value.typeStages === "Proyecto") {
            selectedOptions.value = ["Asignación",
                "Factibilidad PINT y PEXT",
                "Materiales",
                "Instalación PINT y PEXT"]
        } else if (filterForm.value.typeStages === "Administracion") {
            selectedOptions.value = [
                "Orden de Compra",
                "Validación de OC",
                "Orden de Servicio"]
        } else if (filterForm.value.typeStages === "Cobranza") {
            selectedOptions.value = [
                "Cobranza"]
        } else {
            selectedOptions.value = selectableOptions
        }

        filterForm.value.project_status = [...stats]
        filterForm.value.administration_status = [...stats]
        filterForm.value.charge_status = [...stats]
    },
    { deep: true }
);

watch(dataToRender, async () => {
    await nextTick();
    establishStickyStyles();
});

async function search_advance($data) {
    let res = await axios.post(route("cicsa.advance.search"), $data);
    dataToRender.value = res.data;
}

const childRef = ref(null);
const childRef2 = ref(null);
const childRef3 = ref(null);
function getAllData() {
    filterForm.value.typeStages = ""
    filterMode.value = true;
    // childRef.value.checkAll();
    // childRef2.value.checkAll();
    // childRef3.value.checkAll();
    search_advance(filterForm.value);
}

const thProjectName = ref(null);
const thProjectCode = ref(null);
const getThWidth = (thElement) => {
    return thElement ? Math.floor(thElement.getBoundingClientRect().width) : 0;
};
const establishStickyStyles = () => {
    thStickyStyle.value =
        window.innerWidth >= 768 // > sm
            ? {
                pc_sticky: {
                    left: getThWidth(thProjectName.value) + "px",
                    position: "sticky",
                    zIndex: 30,
                },
                pcpe_sticky: {
                    left:
                        getThWidth(thProjectName.value) +
                        getThWidth(thProjectCode.value) +
                        "px",
                    position: "sticky",
                    zIndex: 30,
                },
            }
            : {
                pc_sticky: {},
                pcpe_sticky: {},
            };
};
const thStickyStyle = ref({
    pc_sticky: {},
    pcpe_sticky: {},
});
const handleResize = () => {
    establishStickyStyles();
};
onMounted(async () => {
    await nextTick();
    establishStickyStyles();
    window.addEventListener("resize", handleResize);
});
onUnmounted(() => {
    window.removeEventListener("resize", handleResize);
});

function updateCicsaIndex(cisca_assignation_id) {
    const index = dataToRender.value.findIndex(item => item.id = cisca_assignation_id)
    dataToRender.value.splice(index, 1)
}

async function openPDF(id, cicsa, doc) {
    let url = null
    if (cicsa === 'purchaseOrder') {
        url = route('purchase.order.showDocument', { purchaseOrder: id })
    } if (cicsa === 'serviceOrder') {
        url = route('cicsa.service_orders.showDocument', { serviceOrder: id, doc: doc })
    } if (cicsa === 'chargeAreaOrder') {
        url = route('cicsa.charge_areas.showDocument', { chargeAreaOrder: id })
    }
    if (id) {
        await axios.get(url)
            .then(response => {
                const imageUrl = response.data.url;
                window.open(imageUrl, '_blank');
            })
            .catch(error => {
                console.error('Error fetching image URL:', error);
            });
    } else {
        console.error('No se proporcionó un ID de imagen válido');
    }
}

function formattedState(state) {
    switch (state) {
        case 'Pendiente': return 'Pe'
        case 'En Proceso': return 'Pr'
        case 'Completado': return 'Co'
        default: return ''
    }
}

function formatoManager(userName) {
    if (userName) {
        const name = userName.split(" ");
        const firstName = name[0];
        const firstLastName = name[1] ? name[1][0] + "." : "";
        return `${firstName} ${firstLastName}`;
    } else {
        return ""
    }
}

function reverseWordsWithBreaks(columnTitle) {
    return columnTitle.split(" ").reverse().join("<br>");
}

function openExportExcel() {
    const uniqueParam = `timestamp=${new Date().getTime()}`;
    const url =
        route("cicsa.export", { stages: filterForm.value.typeStages }) +
        "?" +
        uniqueParam;
    window.location.href = url;
}

</script>
<style>
.title {
    writing-mode: vertical-lr;
    transform: rotate(180deg);
}

/* Tooltip oculto inicialmente */
#project_name {
    visibility: hidden;
    opacity: 0;
    transition: opacity 0.3s ease;
    top: -25px;
}

/* Mostrar tooltip al pasar el cursor */
[data-tooltip-target="project_name"]:hover+#project_name {
    visibility: visible;
    opacity: 1;
}
</style>
