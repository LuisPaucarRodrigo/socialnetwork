<template>
    <Head title="CICSA" />

    <AuthenticatedLayout :redirectRoute="'cicsa.index'">
        <template #header> Proceso CICSA </template>

        <div class="min-w-full rounded-lg shadow">
            <div class="flex justify-between">
                <div class="flex space-x-4">
                    <FilterProcess
                        :options="[
                            'Asignación',
                            'Factibilidad PINT y PEXT',
                            'Materiales',
                            'Instalación PINT y PEXT',
                            'Orden de Compra',
                            'Validación de OC',
                            'Orden de Servicio',
                            'Cobranza',
                        ]"
                        v-model="selectedOptions"
                        :width="'w-[230px]'"
                    />
                    <button
                        @click="getAllData()"
                        class="p-2 bg-white ring-1 ring-slate-400 rounded-md text-slate-900 hover:text-slate-400"
                    >
                        <ServerIcon class="h-5 w-5 font-bold" />
                    </button>
                    <button
                        @click="router.visit(route('cicsa.index'))"
                        class="p-2 bg-transparent ring-1 ring-slate-300 rounded-md text-slate-900 hover:text-slate-400"
                    >
                        <ArrowPathIcon class="h-5 w-5" />
                    </button>
                </div>
                <div class="flex space-x-4">
                    <Link
                        :href="route('cicsa.charge_areas.accpeted')"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500"
                    >
                        Completados
                    </Link>
                    <Link
                        :href="route('cicsa.charge')"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 whitespace-nowrap"
                    >
                        Por Cobrar
                    </Link>
                    <TextInput
                        type="text"
                        v-model="filterForm.search"
                        placeholder="Nombre,Cliente,Codigo"
                    />
                    <SelectCicsaComponent currentSelect="Proceso" />
                </div>
            </div>
            <br />
            <div class="overflow-x-auto h-[70vh]">
                <table
                    :class="[
                        'w-full '
                    ]"
                >
                    <thead class="sticky top-0 z-40">
                        <tr
                            class="text-xs font-semibold uppercase tracking-wide text-white"
                        >
                            <th
                                v-if="!checkVisibility('Asignación')"
                                class="bg-gray-700 sticky left-0 z-50 border-b-2 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider"
                                colspan="3"
                            ></th>
                            <th
                                v-if="checkVisibility('Asignación')"
                                class="bg-indigo-800 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider"
                                colspan="8"
                            >
                                Asignación
                            </th>
                            <th
                                v-if="
                                    checkVisibility('Factibilidad PINT y PEXT')
                                "
                                class="bg-indigo-800 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider"
                                colspan="5"
                            >
                                Factibilidad PINT y PEXT
                            </th>
                            <th
                                v-if="checkVisibility('Materiales')"
                                class="bg-indigo-800 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider"
                                colspan="4"
                            >
                                Materiales
                            </th>
                            <th
                                v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                "
                                class="bg-indigo-800 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider"
                                colspan="9"
                            >
                                Instalación PINT y PEXT
                            </th>
                            <th
                                class="w-[150px] bg-indigo-800 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider"
                            >
                                Estado del Proyecto
                            </th>
                            <th
                                v-if="checkVisibility('Orden de Compra')"
                                class="bg-purple-700 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider"
                                colspan="6"
                            >
                                Orden de Compra
                            </th>
                            <th
                                v-if="checkVisibility('Validación de OC')"
                                class="bg-purple-700 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider"
                                colspan="9"
                            >
                                Validación de OC
                            </th>
                            <th
                                v-if="checkVisibility('Orden de Servicio')"
                                class="bg-purple-700 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider"
                                colspan="7"
                            >
                                Orden de Servicio
                            </th>
                            <th
                                v-if="checkVisibility('Cobranza')"
                                class="bg-purple-700 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider"
                                colspan="9"
                            >
                                Cobranza
                            </th>
                            <th
                                v-if="!checkVisibility('Cobranza')"
                                class="bg-gray-700 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider border-r-2"
                            ></th>
                            <th
                                class="w-[150px] bg-purple-700 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider"
                            >
                                Estado de Cobranza
                            </th>
                            <th
                                v-if="auth.user.role_id === 1"
                                class="bg-gray-700 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider"
                            ></th>
                        </tr>
                        <tr
                            class="border-b bg-gray-50 text-[11px] font-semibold uppercase tracking-wide text-gray-500"
                        >
                            <th
                                v-if="checkVisibility('Asignación')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                <div class="w-[120px]">
                                    <TableHeaderCicsaFilter
                                        label="Fecha de Asignación"
                                        labelClass="text-gray-600"
                                        v-model="filterForm.assignation_date"
                                    />
                                </div>
                            </th>
                            <th
                                ref="thProjectName"
                                :class="[
                                    'border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600',
                                    `sticky left-0 z-30`,
                                ]"
                            >
                                <div class="flex justify-center">
                                    <p class="sm:w-[250px] w-[70px]">
                                        Nombre del Proyecto
                                    </p>
                                </div>
                            </th>
                            <th
                                ref="thProjectCode"
                                :style="thStickyStyle.pc_sticky"
                                :class="[
                                    `border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600`,
                                ]"
                            >
                                <div class="flex justify-center">
                                    <p class="">Código del Proyecto</p>
                                </div>
                            </th>
                            <th
                                :style="thStickyStyle.pcpe_sticky"
                                :class="[
                                    'border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600',
                                    checkVisibility('Asignación')
                                        ? ''
                                        : 'border-r-2',
                                ]"
                            >
                                CPE
                            </th>
                            <th
                                v-if="checkVisibility('Asignación')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Cliente
                            </th>
                            <th
                                v-if="checkVisibility('Asignación')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                <div class="w-[120px]">
                                    <TableHeaderCicsaFilter
                                        label="Fecha Límite del Proyecto"
                                        labelClass="text-gray-600"
                                        v-model="filterForm.project_deadline"
                                    />
                                </div>
                            </th>
                            <th
                                v-if="checkVisibility('Asignación')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                <div class="w-[150px]">Gestor</div>
                            </th>
                            <th
                                v-if="checkVisibility('Asignación')"
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                <div class="w-[150px]">Encargado</div>
                            </th>
                            <th
                                v-if="
                                    checkVisibility('Factibilidad PINT y PEXT')
                                "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Fecha de Factibilidad
                            </th>
                            <th
                                v-if="
                                    checkVisibility('Factibilidad PINT y PEXT')
                                "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Informe
                            </th>
                            <th
                                v-if="
                                    checkVisibility('Factibilidad PINT y PEXT')
                                "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Lista de Materiales de Factibilidad
                            </th>
                            <th
                                v-if="
                                    checkVisibility('Factibilidad PINT y PEXT')
                                "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Coordinador
                            </th>
                            <th
                                v-if="
                                    checkVisibility('Factibilidad PINT y PEXT')
                                "
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                <div class="w-[150px]">Encargado</div>
                            </th>
                            <th
                                v-if="checkVisibility('Materiales')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Fecha de Recojo
                            </th>
                            <th
                                v-if="checkVisibility('Materiales')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Guía
                            </th>
                            <th
                                v-if="checkVisibility('Materiales')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Lista de Materiales
                            </th>
                            <th
                                v-if="checkVisibility('Materiales')"
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                <div class="w-[150px]">Encargado</div>
                            </th>
                            <th
                                v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Fecha de Pext
                            </th>
                            <th
                                v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Fecha de Pint
                            </th>
                            <th
                                v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Acta de Conformidad
                            </th>
                            <th
                                v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Informe
                            </th>
                            <th
                                v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Lista de Materiales Liquidados
                            </th>
                            <th
                                v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Fecha de Envío de Informe
                            </th>
                            <th
                                v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Monto Proyectado
                            </th>
                            <th
                                v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                "
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Coordinador
                            </th>
                            <th
                                v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                "
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                <div class="w-[150px]">Encargado</div>
                            </th>
                            <th
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-2 py-2 text-[11px] font-semibold uppercase tracking-wider"
                            >
                                <div class="w-[150px]">
                                    <TableHeaderCicsaFilter
                                        label="E. P."
                                        labelClass=" text-gray-600"
                                        :options="[...stats]"
                                        v-model="filterForm.project_status"
                                        ref="childRef"
                                    />
                                </div>
                            </th>
                            <th
                                v-if="checkVisibility('Orden de Compra')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Fecha de Orden de Compra
                            </th>
                            <th
                                v-if="checkVisibility('Orden de Compra')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Numero de Orden de Compra
                            </th>
                            <th
                                v-if="checkVisibility('Orden de Compra')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Formato Maestro
                            </th>
                            <th
                                v-if="checkVisibility('Orden de Compra')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Item 3456
                            </th>
                            <th
                                v-if="checkVisibility('Orden de Compra')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Presupuesto
                            </th>
                            <th
                                v-if="checkVisibility('Orden de Compra')"
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                <div class="w-[150px]">Encargado</div>
                            </th>
                            <th
                                v-if="checkVisibility('Validación de OC')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Fecha de Inicio de Validación
                            </th>
                            <th
                                v-if="checkVisibility('Validación de OC')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Validacion de expediente
                            </th>
                            <th
                                v-if="checkVisibility('Validación de OC')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Control de Materiales
                            </th>
                            <th
                                v-if="checkVisibility('Validación de OC')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Supervisor
                            </th>
                            <th
                                v-if="checkVisibility('Validación de OC')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Almacen
                            </th>
                            <th
                                v-if="checkVisibility('Validación de OC')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Jefe de Obra
                            </th>
                            <th
                                v-if="checkVisibility('Validación de OC')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Liquidador
                            </th>
                            <th
                                v-if="checkVisibility('Validación de OC')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Superintendente
                            </th>
                            <th
                                v-if="checkVisibility('Validación de OC')"
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                <div class="w-[150px]">Encargado</div>
                            </th>
                            <th
                                v-if="checkVisibility('Orden de Servicio')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Fecha de Orden de Servicio
                            </th>
                            <th
                                v-if="checkVisibility('Orden de Servicio')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Orden de Servicio
                            </th>
                            <th
                                v-if="checkVisibility('Orden de Servicio')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Hoja de Estimación
                            </th>
                            <th
                                v-if="checkVisibility('Orden de Servicio')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Orden de Compra
                            </th>
                            <th
                                v-if="checkVisibility('Orden de Servicio')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Factura en PDF
                            </th>
                            <th
                                v-if="checkVisibility('Orden de Servicio')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Factura en ZIP
                            </th>
                            <th
                                v-if="checkVisibility('Orden de Servicio')"
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                <div class="w-[150px]">Encargado</div>
                            </th>

                            <th
                                v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Número de Factura
                            </th>
                            <th
                                v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Fecha de Factura
                            </th>
                            <th
                                v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Crédito A
                            </th>
                            <th
                                v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Fecha de Pago
                            </th>
                            <th
                                v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Días de Atraso
                            </th>
                            <th
                                v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Fecha de Abono
                            </th>
                            <th
                                v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                            Monto
                            </th>
                            <th
                                v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Estado de Pago
                            </th>
                            <th
                                v-if="checkVisibility('Cobranza')"
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                            >
                                <div class="w-[150px]">Encargado</div>
                            </th>
                            <th
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-2 py-2 text-[11px] font-semibold uppercase tracking-wider"
                            >
                                <div class="w-[150px]">
                                    <TableHeaderCicsaFilter
                                        label="E. C."
                                        labelClass=" text-gray-600"
                                        :options="[...stats]"
                                        v-model="filterForm.charge_status"
                                        ref="childRef2"
                                    />
                                </div>
                            </th>
                            <th
                                v-if="auth.user.role_id === 1"
                                class="border-b-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600"
                            ></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="item in dataToRender"
                            :key="item.id"
                            class="text-gray-700"
                        >
                            <td
                                v-if="checkVisibility('Asignación')"
                                :class="stateClass(item.assignation_date)"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.assignation_date) }}
                                </p>
                            </td>
                            <td
                                class="border-b border-gray-200 bg-amber-200 px-2 py-2 text-[13px] sticky left-0 z-30"
                            >
                                <div class="flex justify-center">
                                    <p
                                        class="sm:w-[250px] w-[70px] break-words text-gray-900 text-center"
                                    >
                                        {{ item.project_name }}
                                    </p>
                                </div>
                            </td>
                            <td
                                :style="thStickyStyle.pc_sticky"
                                class="sticky border-b bg-amber-200 border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <div class="flex justify-center">
                                    <p class="text-gray-900 text-center">
                                        {{ item.project_code }}
                                    </p>
                                </div>
                            </td>
                            <td
                                :style="thStickyStyle.pcpe_sticky"
                                class="border-b bg-amber-200 border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{ item.cpe }}
                                </p>
                            </td>
                            <td
                                v-if="checkVisibility('Asignación')"
                                :class="stateClass(item.customer)"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{ item.customer }}
                                </p>
                            </td>
                            <td
                                :class="stateClass(item.project_deadline)"
                                v-if="checkVisibility('Asignación')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.project_deadline) }}
                                </p>
                            </td>
                            <td
                                :class="stateClass(item.manager)"
                                v-if="checkVisibility('Asignación')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{ item.manager }}
                                </p>
                            </td>
                            <td
                                :class="stateClass(item.user_name)"
                                v-if="checkVisibility('Asignación')"
                                class="border-b border-r-2 border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{ item.user_name }}
                                </p>
                            </td>

                            <td 
                                :class="
                                    stateClass(
                                        item?.cicsa_feasibility.feasibility_date
                                    )
                                "
                                v-if="
                                    checkVisibility('Factibilidad PINT y PEXT')
                                "
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{
                                        formattedDate(
                                            item?.cicsa_feasibility
                                                ?.feasibility_date
                                        )
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(item?.cicsa_feasibility?.report)
                                "
                                v-if="
                                    checkVisibility('Factibilidad PINT y PEXT')
                                "
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-center">
                                    {{ item?.cicsa_feasibility?.report }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_feasibility
                                            ?.cicsa_feasibility_materials
                                            ?.length > 0
                                    )
                                "
                                v-if="
                                    checkVisibility('Factibilidad PINT y PEXT')
                                "
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <div
                                    v-if="
                                        item?.cicsa_feasibility
                                            ?.cicsa_feasibility_materials
                                            ?.length > 0
                                    "
                                    class="flex items-center justify-center"
                                >
                                    <button
                                        @click="
                                            openMaterialsModal(
                                                item?.cicsa_feasibility
                                                    ?.cicsa_feasibility_materials,
                                                'Materiales de Factibilidad'
                                            )
                                        "
                                        class="text-green-600"
                                    >
                                        <EyeIcon class="h-4 w-4 ml-1" />
                                    </button>
                                </div>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_feasibility?.coordinator
                                    )
                                "
                                v-if="
                                    checkVisibility('Factibilidad PINT y PEXT')
                                "
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{ item?.cicsa_feasibility?.coordinator }}
                                </p>
                            </td>

                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_feasibility?.user_name
                                    )
                                "
                                v-if="
                                    checkVisibility('Factibilidad PINT y PEXT')
                                "
                                class="border-b border-r-2 border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{ item?.cicsa_feasibility?.user_name }}
                                </p>
                            </td>

                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_materials?.some(
                                            (item) => item?.pick_date
                                        )
                                    )
                                "
                                v-if="checkVisibility('Materiales')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{
                                        item?.cicsa_materials
                                            ?.filter(
                                                (item) =>
                                                    item?.pick_date !== null
                                            )
                                            .map((item) =>
                                                formattedDate(item?.pick_date)
                                            )
                                            .join(", ")
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_materials?.some(
                                            (item) => item?.guide_number
                                        )
                                    )
                                "
                                v-if="checkVisibility('Materiales')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{
                                        item?.cicsa_materials
                                            ?.filter(
                                                (item) =>
                                                    item?.guide_number !== null
                                            )
                                            .map((item) => item.guide_number)
                                            .join(", ")
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.total_materials?.length > 0
                                    )
                                "
                                v-if="checkVisibility('Materiales')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <div class="flex items-center justify-center">
                                    <button
                                        v-if="item?.total_materials?.length > 0"
                                        type="button"
                                        @click="
                                            openMaterialsModal(
                                                item?.total_materials,
                                                'Materiales Recibidos'
                                            )
                                        "
                                        class="text-green-600"
                                    >
                                        <EyeIcon class="h-4 w-4 ml-1" />
                                    </button>
                                </div>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_materials?.some(
                                            (item) => item?.user_name
                                        )
                                    )
                                "
                                v-if="checkVisibility('Materiales')"
                                class="border-b border-r-2 border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="w-[200px] text-gray-900 text-center">
                                    {{
                                        item?.cicsa_materials
                                            ?.filter(
                                                (item) =>
                                                    item?.user_name !== null
                                            )
                                            .map((item) => item.user_name)
                                            .join(", ")
                                    }}
                                </p>
                            </td>

                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_installation?.pext_date
                                    )
                                "
                                v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                "
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{
                                        formattedDate(
                                            item.cicsa_installation?.pext_date
                                        )
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_installation?.pint_date
                                    )
                                "
                                v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                "
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{
                                        formattedDate(
                                            item.cicsa_installation?.pint_date
                                        )
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_installation?.conformity
                                    )
                                "
                                v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                "
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-center">
                                    {{ item?.cicsa_installation?.conformity }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(item?.cicsa_installation?.report)
                                "
                                v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                "
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-center">
                                    {{ item?.cicsa_installation?.report }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_installation
                                            ?.cicsa_installation_materials
                                            ?.length > 0
                                    )
                                "
                                v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                "
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <div class="flex items-center justify-center">
                                    <button
                                        v-if="
                                            item?.cicsa_installation
                                                ?.cicsa_installation_materials
                                                ?.length > 0
                                        "
                                        @click="
                                            openInstMaterialsModal(
                                                item?.cicsa_installation
                                                    ?.cicsa_installation_materials
                                            )
                                        "
                                        class="text-green-600"
                                    >
                                        <EyeIcon class="h-4 w-4 ml-1" />
                                    </button>
                                </div>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_installation
                                            ?.shipping_report_date
                                    )
                                "
                                v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                "
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{
                                        formattedDate(
                                            item?.cicsa_installation
                                                ?.shipping_report_date
                                        )
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_installation
                                            ?.projected_amount
                                    )
                                "
                                v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                "
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{
                                        item?.cicsa_installation
                                        ?.projected_amount
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_installation
                                            ?.coordinator
                                    )
                                "
                                v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                "
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{
                                     item?.cicsa_installation?.coordinator 
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_installation?.user_name
                                    )
                                "
                                v-if="
                                    checkVisibility('Instalación PINT y PEXT')
                                "
                                class="border-b border-r-2 border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{ item?.cicsa_installation?.user_name }}
                                </p>
                            </td>

                            <td
                                :class="stateClass(item?.cicsa_project_status)"
                                class="border-b border-r-2 border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <div class="flex justify-center">
                                    <p class="font-black uppercase text-center">
                                        {{ item?.cicsa_project_status }}
                                    </p>
                                </div>
                            </td>

                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_purchase_order?.oc_number
                                    )
                                "
                                v-if="checkVisibility('Orden de Compra')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{
                                        formattedDate(
                                            item?.cicsa_purchase_order?.oc_date
                                        )
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_purchase_order?.oc_number
                                    )
                                "
                                v-if="checkVisibility('Orden de Compra')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{ item?.cicsa_purchase_order?.oc_number }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_purchase_order
                                            ?.master_format
                                    )
                                "
                                v-if="checkVisibility('Orden de Compra')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-center">
                                    {{
                                        item?.cicsa_purchase_order
                                            ?.master_format
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_purchase_order?.item3456
                                    )
                                "
                                v-if="checkVisibility('Orden de Compra')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-center">
                                    {{ item?.cicsa_purchase_order?.item3456 }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_purchase_order?.budget
                                    )
                                "
                                v-if="checkVisibility('Orden de Compra')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-center">
                                    {{ item?.cicsa_purchase_order?.budget }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_purchase_order?.user_name
                                    )
                                "
                                v-if="checkVisibility('Orden de Compra')"
                                class="border-b border-r-2 border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{ item?.cicsa_purchase_order?.user_name }}
                                </p>
                            </td>

                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_purchase_order_validation
                                            ?.validation_date
                                    )
                                "
                                v-if="checkVisibility('Validación de OC')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{
                                        formattedDate(
                                            item.cicsa_purchase_order_validation
                                                ?.validation_date
                                        )
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_purchase_order_validation
                                            ?.file_validation
                                    )
                                "
                                v-if="checkVisibility('Validación de OC')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-center">
                                    {{
                                        item?.cicsa_purchase_order_validation
                                            ?.file_validation
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_purchase_order_validation
                                            ?.materials_control
                                    )
                                "
                                v-if="checkVisibility('Validación de OC')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-center">
                                    {{
                                        item?.cicsa_purchase_order_validation
                                            ?.materials_control
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_purchase_order_validation
                                            ?.supervisor
                                    )
                                "
                                v-if="checkVisibility('Validación de OC')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-center">
                                    {{
                                        item?.cicsa_purchase_order_validation
                                            ?.supervisor
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_purchase_order_validation
                                            ?.warehouse
                                    )
                                "
                                v-if="checkVisibility('Validación de OC')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-center">
                                    {{
                                        item?.cicsa_purchase_order_validation
                                            ?.warehouse
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_purchase_order_validation
                                            ?.boss
                                    )
                                "
                                v-if="checkVisibility('Validación de OC')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-center">
                                    {{
                                        item?.cicsa_purchase_order_validation
                                            ?.boss
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_purchase_order_validation
                                            ?.liquidator
                                    )
                                "
                                v-if="checkVisibility('Validación de OC')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-center">
                                    {{
                                        item?.cicsa_purchase_order_validation
                                            ?.liquidator
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_purchase_order_validation
                                            ?.superintendent
                                    )
                                "
                                v-if="checkVisibility('Validación de OC')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-center">
                                    {{
                                        item?.cicsa_purchase_order_validation
                                            ?.superintendent
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_purchase_order_validation
                                            ?.user_name
                                    )
                                "
                                v-if="checkVisibility('Validación de OC')"
                                class="border-b border-r-2 border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{
                                        item.cicsa_purchase_order_validation
                                            ?.user_name
                                    }}
                                </p>
                            </td>

                            <td
                                :class="
                                    stateClass(
                                        item?.service_order?.service_order_date
                                    )
                                "
                                v-if="checkVisibility('Orden de Servicio')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{
                                        formattedDate(
                                            item?.cicsa_service_order
                                                ?.service_order_date
                                        )
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.service_order?.service_order
                                    )
                                "
                                v-if="checkVisibility('Orden de Servicio')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{
                                        item?.cicsa_service_order?.service_order
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_service_order
                                            ?.estimate_sheet
                                    )
                                "
                                v-if="checkVisibility('Orden de Servicio')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-center">
                                    {{
                                        item?.cicsa_service_order
                                            ?.estimate_sheet
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_service_order
                                            ?.purchase_order
                                    )
                                "
                                v-if="checkVisibility('Orden de Servicio')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-center">
                                    {{
                                        item?.cicsa_service_order
                                            ?.purchase_order
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_service_order?.pdf_invoice
                                    )
                                "
                                v-if="checkVisibility('Orden de Servicio')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-center">
                                    {{ item?.cicsa_service_order?.pdf_invoice }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_service_order?.zip_invoice
                                    )
                                "
                                v-if="checkVisibility('Orden de Servicio')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-center">
                                    {{ item?.cicsa_service_order?.zip_invoice }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(item?.service_order?.user_name)
                                "
                                v-if="checkVisibility('Orden de Servicio')"
                                class="border-b border-r-2 border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{ item?.cicsa_service_order?.user_name }}
                                </p>
                            </td>

                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_charge_area?.invoice_number
                                    )
                                "
                                v-if="checkVisibility('Cobranza')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{
                                        item?.cicsa_charge_area?.invoice_number
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_charge_area?.invoice_date
                                    )
                                "
                                v-if="checkVisibility('Cobranza')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{
                                        formattedDate(
                                            item?.cicsa_charge_area
                                                ?.invoice_date
                                        )
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_charge_area?.credit_to
                                    )
                                "
                                v-if="checkVisibility('Cobranza')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{ item?.cicsa_charge_area?.credit_to }}
                                    {{
                                        item?.cicsa_charge_area?.credit_to
                                            ? "días"
                                            : ""
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_charge_area?.payment_date
                                    )
                                "
                                v-if="checkVisibility('Cobranza')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{
                                        formattedDate(
                                            item?.cicsa_charge_area
                                                ?.payment_date
                                        )
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_charge_area?.days_late
                                    )
                                "
                                v-if="checkVisibility('Cobranza')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{ item?.cicsa_charge_area?.days_late }}
                                    {{
                                        item?.cicsa_charge_area?.days_late
                                            ? "días"
                                            : ""
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_charge_area?.deposit_date
                                    )
                                "
                                v-if="checkVisibility('Cobranza')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{
                                        formattedDate(
                                            item?.cicsa_charge_area
                                                ?.deposit_date
                                        )
                                    }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(item?.cicsa_charge_area?.amount)
                                "
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{
                                        item?.cicsa_charge_area?.amount
                                            ? "S/."
                                            : ""
                                    }}
                                    {{ item?.cicsa_charge_area?.amount }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(item?.cicsa_charge_area?.state)
                                "
                                v-if="checkVisibility('Cobranza')"
                                class="border-b border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{ item?.cicsa_charge_area?.state }}
                                </p>
                            </td>
                            <td
                                :class="
                                    stateClass(
                                        item?.cicsa_charge_area?.user_name
                                    )
                                "
                                v-if="checkVisibility('Cobranza')"
                                class="border-b border-r-2 border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <p class="text-gray-900 text-center">
                                    {{ item?.cicsa_charge_area?.user_name }}
                                </p>
                            </td>
                            <td
                                :class="stateClass(item?.cicsa_charge_status)"
                                class="border-b border-r-2 border-gray-200 px-2 py-2 text-[13px]"
                            >
                                <div class="flex justify-center">
                                    <p class="font-black uppercase text-center">
                                        {{ item?.cicsa_charge_status }}
                                    </p>
                                </div>
                            </td>

                            <td
                                v-if="auth.user.role_id === 1"
                                class="border-b border-gray-200 bg-white px-2 py-0 text-[13px]"
                            >
                                <div class="flex space-x-3 justify-center">
                                    <button
                                        type="button"
                                        @click="openSotDeleteModal(item.id)"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="w-4 h-4 text-red-500"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                            />
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
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            <p class="text-gray-900 text-center">
                                Total Registros: {{ dataToRender.length }}
                            </p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            <p class="text-gray-900 text-center">
                                Monto Total: S/.
                                {{ getTotalAmount(dataToRender) }}
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div
                v-if="!filterMode"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between"
            >
                <pagination :links="projects.links" />
            </div>
        </div>

        <Modal
            :show="showMaterials"
            @close="closeMaterialsModal"
            max-width="md"
            :closeable="true"
        >
            <div class="p-6">
                <h2
                    class="text-lg font-medium text-gray-800 border-b-2 border-gray-100"
                >
                    Total Materiales Recibidos
                </h2>
                <br />
                <div class="mt-2">
                    <div v-if="materials.length > 0" class="overflow-auto">
                        <table
                            class="w-full whitespace-no-wrap border-collapse border border-slate-300"
                        >
                            <thead>
                                <tr
                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                                >
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600"
                                    >
                                        N° Guía
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600"
                                    >
                                        Material
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600"
                                    >
                                        Unidad
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600"
                                    >
                                        Cantidad
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(item, i) in materials"
                                    :key="i"
                                    class="text-gray-700 bg-white text-sm"
                                >
                                    <td
                                        class="border-b border-slate-300 px-4 py-4"
                                    >
                                        {{ item?.guide_number }}
                                    </td>
                                    <td
                                        class="border-b border-slate-300 px-4 py-4"
                                    >
                                        {{ item?.name }}
                                    </td>
                                    <td
                                        class="border-b border-slate-300 px-4 py-4"
                                    >
                                        {{ item?.unit }}
                                    </td>
                                    <td
                                        class="border-b border-slate-300 px-4 py-4"
                                    >
                                        {{ item?.quantity }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p v-else>No hay materiales asignados</p>
                    <br />
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton
                            type="button"
                            @click="closeMaterialsModal"
                        >
                            Cerrar
                        </SecondaryButton>
                    </div>
                </div>
            </div>
        </Modal>

        <Modal
            :show="showInstMaterials"
            @close="closeInstMaterialsModal"
            max-width="md"
            :closeable="true"
        >
            <div class="p-6">
                <h2
                    class="text-lg font-medium text-gray-800 border-b-2 border-gray-100"
                >
                    Materiales Liquidados
                </h2>
                <br />
                <div class="mt-2">
                    <div v-if="instMaterials.length > 0" class="overflow-auto">
                        <table
                            class="w-full whitespace-no-wrap border-collapse border border-slate-300"
                        >
                            <thead>
                                <tr
                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                                >
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600"
                                    >
                                        Material
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600"
                                    >
                                        Recibidos
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600"
                                    >
                                        Usados
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600"
                                    >
                                        Resto
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(item, i) in instMaterials"
                                    :key="i"
                                    class="text-gray-700 bg-white text-sm"
                                >
                                    <td
                                        class="border-b border-slate-300 px-4 py-4"
                                    >
                                        {{ item?.name }}
                                    </td>
                                    <td
                                        class="border-b border-slate-300 px-4 py-4"
                                    >
                                        {{ item?.quantity }}
                                    </td>
                                    <td
                                        class="border-b border-slate-300 px-4 py-4"
                                    >
                                        {{ item?.used_quantity }}
                                    </td>
                                    <td
                                        class="border-b border-slate-300 px-4 py-4"
                                    >
                                        {{
                                            item?.quantity - item?.used_quantity
                                        }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p v-else>No hay materiales asignados</p>
                    <br />
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton
                            type="button"
                            @click="closeInstMaterialsModal"
                        >
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

        <SuccessOperationModal
            :confirming="confirmSotDelete"
            :title="'Registro Eliminado'"
            :message="'El proyecto fue eliminado'"
        />
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
import { EyeIcon } from "@heroicons/vue/24/outline";
import FilterProcess from "@/Components/FilterProcess.vue";
import TableHeaderCicsaFilter from "@/Components/TableHeaderCicsaFilter.vue";
import { ArrowPathIcon, ServerIcon } from "@heroicons/vue/24/outline";
import TextInput from "@/Components/TextInput.vue";

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
function deleteSot() {
    router.delete(
        route("cicsa.assignation.destroy", { ca_id: sotToDelete.value }),
        {
            onSuccess: () => {
                closeSotDeleteModal();
                confirmSotDelete.value = true;
                setTimeout(() => {
                    confirmSotDelete.value = false;
                }, 1500);
            },
        }
    );
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

//Stage visibility
const selectedOptions = ref([
    "Asignación",
    "Factibilidad PINT y PEXT",
    "Materiales",
    "Instalación PINT y PEXT",
    "Orden de Compra",
    "Validación de OC",
    "Orden de Servicio",
    "Cobranza",
]);
function checkVisibility(option) {
    return selectedOptions.value.includes(option);
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

//Total
function getTotalAmount(objArray) {
    return objArray.reduce(
        (a, item) =>
            a +
            (item?.cicsa_charge_area?.amount
                ? item?.cicsa_charge_area?.amount
                : 0),
        0
    ).toFixed(2);
}

//filter
const stats = ["Pendiente", "En Proceso", "Completado"];
const initSearch = {
    project_status: [...stats],
    charge_status: [...stats],
    assignation_date: "",
    project_deadline: "",
    search: "",
};
const filterForm = ref({ ...initSearch });

watch(
    () => [
        filterForm.value.project_status,
        filterForm.value.charge_status,
        filterForm.value.assignation_date,
        filterForm.value.project_deadline,
        filterForm.value.search,
    ],
    () => {
        search_advance(filterForm.value);
        filterMode.value = true;
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
function getAllData() {
    filterMode.value = true;
    childRef.value.checkAll();
    childRef2.value.checkAll();
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
</script>
