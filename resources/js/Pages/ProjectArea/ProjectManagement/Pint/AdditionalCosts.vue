<template>

    <Head title="Gestion de Costos Adicionales" />
    <AuthenticatedLayout :redirectRoute="{
        route: 'projectmanagement.purchases_request.index',
        params: { id: project_id.id },
    }">
        <template #header>
            Gastos Variables del Proyecto
            {{ props.project_id.name }}
        </template>
        <br />
        <Toaster richColors />
        <div class="inline-block min-w-full mb-4">
            <div class="flex gap-4 justify-between">
                <div class="hidden sm:flex  space-x-3">
                    <PrimaryButton v-if="
                        project_id.status === null
                    " @click="openCreateAdditionalModal" type="button" class="whitespace-nowrap">
                        + Agregar
                    </PrimaryButton>
                    <PrimaryButton data-tooltip-target="update_data_tooltip" type="button" @click="() => {
                        filterForm = { ...initialFilterFormState }
                    }
                    ">
                        <ServerIcon />
                    </PrimaryButton>
                    <div id="update_data_tooltip" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Todos los Registros
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>

                    <button data-tooltip-target="export_tooltip" type="button"
                        class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500"
                        @click="openExportExcel">
                        <ExcelIcon />
                    </button>
                    <div id="export_tooltip" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Exportar Excel
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>

                    <button type="button"
                        class="rounded-md bg-blue-600 px-4 py-2 text-center text-sm text-white hover:bg-blue-500 h-full"
                        @click="openExportArchivesModal" data-tooltip-target="export-photo-tooltip">
                        <svg fill="#ffffff" width="20px" height="20px" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0" />

                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />

                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M22.71,6.29a1,1,0,0,0-1.42,0L20,7.59V2a1,1,0,0,0-2,0V7.59l-1.29-1.3a1,1,0,0,0-1.42,1.42l3,3a1,1,0,0,0,.33.21.94.94,0,0,0,.76,0,1,1,0,0,0,.33-.21l3-3A1,1,0,0,0,22.71,6.29ZM19,13a1,1,0,0,0-1,1v.38L16.52,12.9a2.79,2.79,0,0,0-3.93,0l-.7.7L9.41,11.12a2.85,2.85,0,0,0-3.93,0L4,12.6V7A1,1,0,0,1,5,6h8a1,1,0,0,0,0-2H5A3,3,0,0,0,2,7V19a3,3,0,0,0,3,3H17a3,3,0,0,0,3-3V14A1,1,0,0,0,19,13ZM5,20a1,1,0,0,1-1-1V15.43l2.9-2.9a.79.79,0,0,1,1.09,0l3.17,3.17,0,0L15.46,20Zm13-1a.89.89,0,0,1-.18.53L13.31,15l.7-.7a.77.77,0,0,1,1.1,0L18,17.21Z" />
                            </g>
                        </svg>
                    </button>
                    <div id="export-photo-tooltip" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Facturas, Boletas y Vouchers de Pago
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>

                    <button data-tooltip-target="rejected_tooltip" type="button"
                        class="rounded-md bg-gray-100 px-4 py-1 text-center text-lg text-red-600 font-bold ring-2 ring-red-400 hover:bg-gray-100/2"
                        @click="() =>
                            router.visit(
                                route(
                                    'projectmanagement.additionalCosts.rejected',
                                    { project_id: project_id.id }
                                )
                            )
                        ">
                        R
                    </button>
                    <div id="rejected_tooltip" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Rechazados
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>

                    <div>
                        <dropdown align="left">
                            <template #trigger>
                                <button data-tooltip-target="action_button_tooltip"
                                    @click="dropdownOpen = !dropdownOpen"
                                    class="relative block overflow-hidden rounded-md text-white hover:bg-indigo-400 text-center text-sm bg-indigo-500 p-2">
                                    <MenuIcon color="text-white" />
                                </button>
                                <div id="action_button_tooltip" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700 whitespace-nowrap">
                                    Acciones
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </template>

                            <template #content class="origin-left">
                                <div>
                                    <div class="">
                                        <button @click="openOpNuDaModal"
                                            class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-gray-200 hover:text-black focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                            Actualizar Operación
                                        </button>
                                        <button @click="openSwapCostsModal"
                                            class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-gray-200 hover:text-black focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                            Swap (gastos fijos)
                                        </button>
                                        <button @click="openSwapAPModal"
                                            class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-gray-200 hover:text-black focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                            Swap (proyectos adicionales)
                                        </button>
                                        <button @click="openSwapRPModal"
                                            class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-gray-200 hover:text-black focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                            Swap (proyectos mensuales/gep)
                                        </button>
                                        <!-- <button @click=""
                                            class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-gray-200 hover:text-black focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                            Eliminar
                                        </button> -->
                                    </div>
                                </div>
                            </template>
                        </dropdown>
                    </div>

                </div>
                <div class="sm:hidden">
                    <dropdown align="left">
                        <template #trigger>
                            <button @click="dropdownOpen = !dropdownOpen"
                                class="relative block overflow-hidden rounded-md bg-gray-200 px-2 py-2 text-center text-sm text-white hover:bg-gray-100">
                                <MenuIcon />
                            </button>
                        </template>

                        <template #content class="origin-left">
                            <div>
                                <!-- Alineación a la derecha -->
                                <button @click="openCreateAdditionalModal"
                                    class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    Agregar
                                </button>
                                <div class="">
                                    <!-- <DropdownLink :href="route(
                                        'projectmanagement.additionalCosts',
                                        { project_id: project_id.id }
                                    )">
                                        Actualizar
                                    </DropdownLink> -->
                                    <button @click="() => {
                                        filterForm = { ...initialFilterFormState }
                                    }
                                    "
                                        class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        Actualizar
                                    </button>
                                </div>
                                <button @click="openExportExcel()"
                                    class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    Exportar Excel
                                </button>
                                <button @click="openExportArchivesModal()"
                                    class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    Descarga de Archivos
                                </button>
                                <DropdownLink :href="route(
                                    'projectmanagement.additionalCosts.rejected',
                                    { project_id: project_id.id }
                                )">
                                    Rechazados
                                </DropdownLink>
                            </div>
                        </template>
                    </dropdown>
                </div>

                <Search v-model:search="filterForm.search"
                    fields="Ruc , Numero de Documento, Numero de Operacion, Descripcion, Monto Total" />
            </div>
        </div>
        <div class="overflow-x-auto h-[72vh]">
            <table class="w-full bg-white">
                <thead class="sticky top-0 z-20">
                    <tr
                        class="border-b bg-gray-50 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th class="sticky left-0 z-10 bg-gray-100 border-b-2 border-gray-20">
                            <div class="w-2"></div>
                        </th>
                        <th
                            class="sticky left-2 z-10 border-b-2 border-r border-gray-200 bg-gray-100 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600 w-12">
                            <label :for="`check-all`" class="flex gap-3 justify-center w-12 px-2 py-1">
                                <input @change="handleCheckAll" :id="`check-all`" :checked="actionForm.ids.length > 0"
                                    type="checkbox" />
                                {{ actionForm.ids.length ?? "" }}
                            </label>
                        </th>
                        <th
                            class="sm:sticky sm:left-14 sm:z-10 border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableHeaderFilter labelClass="text-[11px]" label="Zona" :options="zones"
                                v-model="filterForm.selectedZones" width="w-32" />
                        </th>
                        <th
                            class="sm:sticky sm:left-48 sm:z-10 border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableHeaderFilter labelClass="text-[11px]" label="Tipo de Gasto" :options="expenseTypes"
                                v-model="filterForm.selectedExpenseTypes" width="w-44" />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableHeaderFilter labelClass="text-[11px]" label="Tipo de Documento" :options="docTypes"
                                v-model="filterForm.selectedDocTypes" width="w-32" />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            RUC
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Proveedor
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Numero de Operación
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableDateFilter labelClass="text-[11px]" label="Fecha de Operación"
                                v-model:startDate="filterForm.opStartDate" v-model:endDate="filterForm.opEndDate"
                                v-model:noDate="filterForm.opNoDate" width="w-40" />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Numero de Doc
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableDateFilter labelClass="text-[11px]" label="Fecha de Documento"
                                v-model:startDate="filterForm.docStartDate" v-model:endDate="filterForm.docEndDate"
                                v-model:noDate="filterForm.docNoDate" width="w-40" />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <div class="flex space-x-1 items-center justify-end">
                                <p>
                                    Fecha de Registro
                                </p>
                                <button @click="sortValue">
                                    <SortIcon />
                                </button>
                            </div>

                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Monto
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Monto sin IGV
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Archivo
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Descripción
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableHeaderFilter labelClass="text-[11px]" label="Estado" :options="stateTypes"
                                v-model="filterForm.selectedStateTypes" width="w-48" />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <p class="w-48">
                                Estado Administrativo
                            </p>
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Motivo de rechazo Administrativo
                        </th>

                        <th v-permission-or="['pro_pint_delete_additional_costs', 'pro_pint_mod_additional_costs' ]" v-if="project_id.status === null"
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in dataToRender" :key="item.id"
                        class="text-gray-700 bg-white hover:bg-gray-200 hover:opacity-80">
                        <td :class="[
                            'sticky left-0 z-10 border-b border-gray-200',
                            {
                                'bg-indigo-500': item.real_state === 'Pendiente',
                                'bg-green-500': item.real_state == 'Aceptado - Validado',
                                'bg-amber-500': item.real_state == 'Aceptado',
                                'bg-red-500': item.real_state == 'Rechazado',
                            },
                        ]"></td>
                        <td
                            class="sticky left-2 z-10 border-b border-r border-gray-200 bg-amber-100 text-center text-[13px] whitespace-nowrap tabular-nums">
                            <label :for="`check-${item.id}`" class="block w-12 px-2 py-1">
                                <input v-model="actionForm.ids" :value="item.id" :id="`check-${item.id}`"
                                    type="checkbox" />
                            </label>
                        </td>
                        <td
                            class="sm:sticky sm:left-14 sm:z-10 border-b w-32 border-gray-200 bg-amber-100 px-2 py-2 text-center text-[13px]">
                            {{ item.zone }}
                        </td>
                        <td
                            class="sm:sticky sm:left-48 sm:z-10 border-b border-gray-200 bg-amber-100 px-2 py-2 text-center text-[13px]">
                            <p class="w-48 break-words">
                                {{ item.expense_type }}
                            </p>
                        </td>
                        <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                            {{ item.type_doc }}
                        </td>
                        <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px] tabular-nums">
                            {{ item.ruc }}
                        </td>
                        <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                            <p class="line-clamp-2 hover:line-clamp-none">
                                {{ item?.provider?.company_name }}
                            </p>
                        </td>
                        <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                            {{ item.operation_number }}
                        </td>
                        <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                            {{
                                item.operation_date &&
                                formattedDate(item.operation_date)
                            }}
                        </td>
                        <td
                            class="border-b border-gray-200 px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap">
                            {{ item.doc_number }}
                        </td>
                        <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                            {{ formattedDate(item.doc_date) }}
                        </td>
                        <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                            {{ formattedDate(item.created_at) }}
                        </td>
                        <td
                            class="border-b border-gray-200 px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap">
                            S/. {{ item.amount.toFixed(2) }}
                        </td>
                        <td
                            class="border-b border-gray-200 px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap">
                            S/. {{ item.real_amount.toFixed(2) }}
                        </td>
                        <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                            <button v-if="item.photo" @click="handlerPreview(item.id)">
                                <ShowIcon />
                            </button>
                            <span v-else>-</span>
                        </td>
                        <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                            <p class="w-[250px]">
                                {{ item.description }}
                            </p>
                        </td>
                        <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                            <div v-permission="'pro_pint_validate_expenses'">
                                <div v-if="item.is_accepted === null ">
                                    <div v-if="registerToReject.id!==item.id"  class="flex gap-3 justify-center w-full">
                                        <button @click="() => openAcceptModal(item)">
                                            <AcceptIcon />
                                        </button>
                                        <button @click="displayRejectReason(item.id)" type="button">
                                            <RejectIcon />
                                        </button>
                                    </div>
                                    <AdditionalCostsRejectForm v-else 
                                        v-model:item="registerToReject.reject_reason" 
                                        :closeRejectReason="closeRejectReason" 
                                        :saveRejectReason="saveRejectReason"
                                    />
                                </div>
    
                                <div v-else :class="[
                                    'text-center',
                                    {
                                        'text-indigo-500': item.real_state === 'Pendiente',
                                        'text-green-500': item.real_state == 'Aceptado - Validado',
                                        'text-amber-500': item.real_state == 'Aceptado',
                                        'text-red-500': item.real_state == 'Rechazado',
                                    },
                                ]">
                                    {{ item.real_state }}
                                </div>
                            </div>
                            <div v-permission-not="'pro_pint_validate_expenses'" :class="[
                                    'text-center',
                                    {
                                        'text-indigo-500': item.real_state === 'Pendiente',
                                        'text-green-500': item.real_state == 'Aceptado - Validado',
                                        'text-amber-500': item.real_state == 'Aceptado',
                                        'text-red-500': item.real_state == 'Rechazado',
                                    },
                                ]">
                                {{ item.real_state }}
                            </div>
                        </td>

                        <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                            <div v-permission="'pro_pint_admin_validate_expenses'">
                                <div v-if="item.admin_is_accepted === null && item.is_accepted === 1" >
                                    <div v-if="regToAdminReject.id!==item.id" class="flex gap-3 justify-center w-full">
                                        <button @click="() => displayAdminAccept(item.id)">
                                            <AcceptIcon />
                                        </button>
                                        <button @click="() => displayAdminRejectReason(item.id)" type="button">
                                            <RejectIcon />
                                        </button>
                                  
                                    </div>
                                    <AdditionalCostsRejectForm v-else 
                                        v-model:item="regToAdminReject.admin_reject_reason" 
                                        :closeRejectReason="closeAdminRejectReason" 
                                        :saveRejectReason="saveAdminReject"
                                    />
                                </div>
                                   
                                <div v-else :class="[
                                    'text-center',
                                    {
                                        'text-indigo-500': item.admin_state === 'Pendiente',
                                        'text-gray-600': item.admin_state === 'No Disponible',
                                        'text-green-500': item.admin_state == 'Aceptado',
                                        'text-red-500': item.admin_state == 'Rechazado',
                                    },
                                ]">
                                    {{ item.admin_state }}
                                </div>
                            </div>
                            <div v-permission-not="'pro_pint_admin_validate_expenses'" :class="[
                                    'text-center',
                                    {
                                        'text-indigo-500': item.admin_state === 'Pendiente',
                                        'text-gray-600': item.admin_state === 'No Disponible',
                                        'text-green-500': item.admin_state == 'Aceptado',
                                        'text-red-500': item.admin_state == 'Rechazado',
                                    },
                                ]">
                                {{ item.admin_state }}
                            </div>
                        </td>

                        <td
                            class="border-b border-gray-200 px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap">
                           {{ item.admin_reject_reason }}
                        </td>


                        <td v-permission-or="[
                            'pro_pint_mod_additional_costs', 'pro_pint_delete_additional_costs'
                        ]" v-if="project_id.status === null"
                            class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                            <div class="flex items-center justify-center gap-3 w-full">
                                <div class="flex gap-3 mr-3">
                                    <button v-permission="'pro_pint_mod_additional_costs'" @click="openEditAdditionalModal(item)">
                                        <EditIcon />
                                    </button>
                                    <button v-permission="'pro_pint_delete_additional_costs'" @click="confirmDeleteAdditional(item.id)">
                                        <DeleteIcon />
                                    </button>
                                </div>
                            </div>
                        </td>
                     
                    </tr>


                    <tr class="sticky bottom-0 z-10 text-gray-700">
                        <td class="font-bold border-b border-gray-200 bg-white"></td>
                        <td colspan="10" class="font-bold border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            TOTAL
                        </td>
                        <td class="font-bold border-b border-gray-200 bg-white"></td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                            S/.
                            {{
                                dataToRender
                                    ?.reduce((a, item) => a + item.amount, 0)
                                    .toFixed(2)
                            }}
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                            S/.
                            {{
                                dataToRender
                                    .reduce(
                                        (a, item) => a + item.real_amount,
                                        0
                                    )
                                    .toFixed(2)
                            }}
                        </td>
                        <td colspan="3" class="border-b border-gray-200 bg-white px-5 py-5 text-sm"></td>

                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <div class="flex items-center"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="!filterMode"
            class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
            <pagination :links="additional_costs.links" />
        </div>
        <Modal :show="create_additional" @close="closeModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Agregar Costo adicional
                </h2>
                <form @submit.prevent="submit">
                    <div class="space-y-12 mt-4">
                        <div class="border-b grid sm:grid-cols-2 gap-6 border-gray-900/10 pb-12">
                            <div>
                                <InputLabel for="zone" class="font-medium leading-6 text-gray-900">Zona</InputLabel>
                                <div class="mt-2">
                                    <select v-model="form.zone" id="zone"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">
                                            Seleccionar
                                        </option>
                                        <option v-for="op in zones">
                                            {{ op }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.zone" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="expense_type" class="font-medium leading-6 text-gray-900">Tipo de Gasto
                                </InputLabel>
                                <div class="mt-2">
                                    <select v-model="form.expense_type" id="expense_type"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">
                                            Seleccionar Gasto
                                        </option>
                                        <option v-for="op in expenseTypes">
                                            {{ op }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.expense_type" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="type_doc" class="font-medium leading-6 text-gray-900">Tipo de Documento
                                </InputLabel>
                                <div class="mt-2">
                                    <select v-model="form.type_doc" id="type_doc"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">
                                            Seleccionar Documento
                                        </option>
                                        <option v-for="op in docTypes">
                                            {{ op }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.type_doc" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="ruc" class="font-medium leading-6 text-gray-900">RUC / DNI
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.ruc" id="ruc" maxlength="11"
                                        @input="handleRucDniAutocomplete" autocomplete="off" list="options"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <datalist id="options">
                                        <option v-for="item in providers" :value="item.ruc">
                                            {{ item.company_name }}
                                        </option>
                                    </datalist>
                                    <InputError :message="form.errors.ruc" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="operation_number" class="font-medium leading-6 text-gray-900">Numero de
                                    Operación
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.operation_number" id="operation_number"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.operation_number" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="operation_date" class="font-medium leading-6 text-gray-900">Fecha de
                                    Operación
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="date" v-model="form.operation_date" id="operation_date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.operation_date" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="doc_number" class="font-medium leading-6 text-gray-900">Numero de
                                    Documento
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.doc_number" id="doc_number"
                                        pattern="^([a-zA-Z0-9]+([-|\/][a-zA-Z0-9]+)*)|([0-9]+)$"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.doc_number" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="doc_date" class="font-medium leading-6 text-gray-900">Fecha de
                                    Documento
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="date" v-model="form.doc_date" id="doc_date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.doc_date" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="amount" class="font-medium leading-6 text-gray-900">Monto</InputLabel>
                                <div class="mt-2">
                                    <input type="number" step="0.0001" v-model="form.amount" id="amount"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.amount" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="igv" class="font-medium leading-6 text-gray-900">
                                    IGV (%)
                                </InputLabel>
                                <div class="mt-2">
                                    <div class="flex gap-3 items-center">
                                        <input type="number" step="1" max="100" v-model="form.igv" id="igv"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />%
                                    </div>

                                    <InputError :message="form.errors.igv" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="amount" class="font-medium leading-6 text-gray-900">Monto sin IGV
                                </InputLabel>
                                <div class="mt-2">
                                    <InputLabel for="amount" class="font-medium leading-6 text-gray-900">{{
                                        form.amount
                                            ? (
                                                form.amount /
                                                (1 + form.igv / 100)
                                            ).toFixed(4)
                                            : 0
                                    }}</InputLabel>
                                </div>
                            </div>

                            <div>
                                <InputLabel for="description" class="font-medium leading-6 text-gray-900">Descripción
                                </InputLabel>
                                <div class="mt-2">
                                    <textarea type="text" v-model="form.description" id="description"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.description" />
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    Archivo
                                </InputLabel>
                                <div class="mt-2">
                                    <InputFile type="file" v-model="form.photo" accept=".jpeg, .jpg, .png, .pdf"
                                        class="block w-full h-24 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.photo" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeModal">
                                Cancelar
                            </SecondaryButton>
                            <button type="submit" :disabled="isFetching" :class="{ 'opacity-25': isFetching }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <Modal :show="editAdditionalModal" @close="closeEditModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Editar Costo Adicional
                </h2>
                <form @submit.prevent="submitEdit">
                    <div class="space-y-12">
                        <div class="border-b grid sm:grid-cols-2 gap-6 border-gray-900/10 pb-12">
                            <div>
                                <InputLabel for="zone" class="font-medium leading-6 text-gray-900">Zona</InputLabel>
                                <div class="mt-2">
                                    <select v-model="form.zone" id="zone"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">
                                            Seleccionar
                                        </option>
                                        <option v-for="op in zones">
                                            {{ op }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.zone" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="expense_type" class="font-medium leading-6 text-gray-900">Tipo de Gasto
                                </InputLabel>
                                <div class="mt-2">
                                    <select v-model="form.expense_type" id="expense_type"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">
                                            Seleccionar Gasto
                                        </option>
                                        <option v-for="op in expenseTypes">
                                            {{ op }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.expense_type" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="type_doc" class="font-medium leading-6 text-gray-900">Tipo de Documento
                                </InputLabel>
                                <div class="mt-2">
                                    <select v-model="form.type_doc" id="type_doc"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">
                                            Seleccionar Documento
                                        </option>
                                        <option v-for="op in docTypes">
                                            {{ op }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.type_doc" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="ruc" class="font-medium leading-6 text-gray-900">RUC / DNI
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.ruc" id="ruc" maxlength="11"
                                        @input="handleRucDniAutocomplete" autocomplete="off" list="options"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <datalist id="options">
                                        <option v-for="item in providers" :value="item.ruc">
                                            {{ item.company_name }}
                                        </option>
                                    </datalist>
                                    <InputError :message="form.errors.ruc" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="operation_number" class="font-medium leading-6 text-gray-900">Numero de
                                    Operación
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.operation_number" id="operation_number"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.operation_number" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="operation_date" class="font-medium leading-6 text-gray-900">Fecha de
                                    Operación
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="date" v-model="form.operation_date" id="operation_date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.operation_date" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="doc_number" class="font-medium leading-6 text-gray-900">Numero de
                                    Documento
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.doc_number" id="doc_number"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.doc_number" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="doc_date" class="font-medium leading-6 text-gray-900">Fecha de
                                    Documento
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="date" v-model="form.doc_date" id="doc_date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.doc_date" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="amount" class="font-medium leading-6 text-gray-900">Monto</InputLabel>
                                <div class="mt-2">
                                    <input type="number" step="0.0001" v-model="form.amount" id="amount"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.amount" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="igv" class="font-medium leading-6 text-gray-900">
                                    IGV (%)
                                </InputLabel>
                                <div class="mt-2">
                                    <div class="flex gap-3 items-center">
                                        <input type="number" step="1" max="100" v-model="form.igv" id="igv"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />%
                                    </div>

                                    <InputError :message="form.errors.igv" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="amount" class="font-medium leading-6 text-gray-900">Monto sin IGV
                                </InputLabel>
                                <div class="mt-2">
                                    <InputLabel for="amount" class="font-medium leading-6 text-gray-900">{{
                                        form.amount
                                            ? (
                                                form.amount /
                                                (1 + form.igv / 100)
                                            ).toFixed(4)
                                            : 0
                                    }}</InputLabel>
                                </div>
                            </div>

                            <div>
                                <InputLabel for="description" class="font-medium leading-6 text-gray-900">Descripción
                                </InputLabel>
                                <div class="mt-2">
                                    <textarea v-model="form.description" id="description"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.description" />
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    Archivo
                                </InputLabel>
                                <div class="mt-2">
                                    <InputFile type="file" v-model="form.photo" accept=".jpeg, .jpg, .png, .pdf"
                                        class="block w-full h-24 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.photo" />
                                    <div v-if="
                                        form.photo_name &&
                                        form.photo_status == 'stable'
                                    " class="text-sm leading-6 text-indigo-700 flex space-x-2 items-center mt-3">
                                        <span> Archivo Actual: </span>
                                        <a :href="route(
                                            'additionalcost.archive',
                                            {
                                                additional_cost_id:
                                                    form.id,
                                            }
                                        )
                                            " target="_blank" class="hover:underline">
                                            {{ form.photo_name }}
                                        </a>
                                        <button type="button" @click="() => {
                                            form.photo_status =
                                                'delete';
                                        }
                                        ">
                                            <DeleteIcon />
                                        </button>
                                    </div>
                                    <div v-if="form.photo_status === 'delete'"
                                        class="text-amber-700 mt-3 text-sm flex space-x-2">
                                        <span>
                                            El documento esta por ser borrado,
                                        </span>
                                        <button @click="() => {
                                            form.photo_status =
                                                'stable';
                                        }
                                        " type="button" class="font-black">
                                            ANULAR
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div v-if="form.admin_state === 'Rechazado'" class="sm:col-span-2 ring-1 ring-red-400 rounded-md p-2">
                                <InputLabel class="text-sm leading-6 text-red-700">
                                    <span class="text-gray-700">
                                        Motivo de rechazo administrativo: 
                                    </span>
                                    {{ form.admin_reject_reason }}
                                </InputLabel>
                                <label class="flex text-sm text-gray-700 gap-2 items-center">
                                    Pedir nueva revisión
                                    <input type="checkbox" v-model="form.ask_admin_review"
                                        class="focus:ring-0 outline-none" />
                                </label>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeEditModal">
                                Cancelar
                            </SecondaryButton>
                            <button type="submit" :disabled="isFetching" :class="{ 'opacity-25': isFetching }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Actualizar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <Modal :show="showImportModal" @close="closeImportModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Importar Excel
                </h2>
                <form @submit.prevent="submitImport">
                    <div class="space-y-12">
                        <div class="border-b grid grid-cols-1 gap-6 border-gray-900/10 pb-12">
                            <div>
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    Archivo
                                </InputLabel>
                                <div class="mt-2">
                                    <InputFile type="file" v-model="importForm.import_file" accept=".xlsx, .csv"
                                        class="block w-full h-24 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="importForm.errors.import_file" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeImportModal">
                                Cancelar
                            </SecondaryButton>
                            <button type="submit" :class="{ 'opacity-25': importForm.processing }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Importar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>
        <!-- ////////////////////////////////////////////// -->
        <Modal :show="showOpNuDatModal" @close="closeOpNuDatModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900 mb-2">
                    Actualización Masiva
                </h2>
                <h4 class="text-sm font-light text-green-900 bg-green-500/10 rounded-lg p-3 ">
                    Los registros con fecha de operación y número de operación, pasarán a automáticamente estar
                    aceptados.
                </h4>
                <form @submit.prevent="submitOpNuDatModal">
                    <div class="space-y-12">
                        <div class="border-b grid grid-cols-1 gap-6 border-gray-900/10 pb-12">
                            <div>
                                <InputLabel for="operation_number" class="font-medium leading-6 text-gray-900">Numero de
                                    Operación
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="opNuDateForm.operation_number" id="operation_number"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="opNuDateForm.errors.operation_number" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="operation_date" class="font-medium leading-6 text-gray-900">Fecha de
                                    Operación
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="date" v-model="opNuDateForm.operation_date" id="operation_date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="opNuDateForm.errors.operation_date" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeOpNuDatModal">
                                Cancelar
                            </SecondaryButton>
                            <button type="submit" :disabled="isFetching" :class="{ 'opacity-25': isFetching }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>



        <Modal :show="showSwapAPModal" @close="closeSwapAPModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900 mb-2">
                    Gastos Variables a Proyecto Adicional
                </h2>
                <h4 class="text-sm font-light text-green-900 bg-green-500/10 rounded-lg p-3 ">
                    Los registros pasarán al proyecto especificado, según el tipo de gasto se insertaran en fijos o
                    variables.
                    Solo se listan los proyectos adicionales que proceden
                </h4>
                <form @submit.prevent="submitSwapAPModal">
                    <div class="space-y-12">
                        <div class="border-b grid grid-cols-1 gap-6 border-gray-900/10 pb-12">
                            <div class="mt-4">
                                <InputLabel for="project_id" class="font-medium leading-6 text-gray-900">
                                    Proyecto Adicional
                                </InputLabel>
                                <div class="mt-2">
                                    <select v-model="additionalProjectForm.project_id" id="project_id"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">
                                            Seleccionar Proyecto
                                        </option>
                                        <option v-for="item in additional_projects" :key="item.id" :value="item.id">
                                            {{ item.description }}
                                        </option>
                                    </select>
                                    <InputError :message="additionalProjectForm.errors.project_id" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeSwapAPModal">
                                Cancelar
                            </SecondaryButton>
                            <button type="submit" :disabled="isFetching" :class="{ 'opacity-25': isFetching }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>


        <Modal :show="showSwapRPModal" @close="closeSwapRPModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900 mb-2">
                    Gastos a Proyecto Mensual / GEP
                </h2>
                <h4 class="text-sm font-light text-green-900 bg-green-500/10 rounded-lg p-3 ">
                    Los registros pasarán a la sección de gastos variables del proyecto seleccionado
                </h4>
                <form @submit.prevent="submitSwapRPModal">
                    <div class="space-y-12">
                        <div class="border-b grid grid-cols-1 gap-6 border-gray-900/10 pb-12">
                            <div class="mt-4">
                                <InputLabel for="project_id" class="font-medium leading-6 text-gray-900">
                                    Proyecto Mensual / GEP
                                </InputLabel>
                                <div class="mt-2">
                                    <select v-model="swapRPForm.project_id" id="project_id"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">
                                            Seleccionar Proyecto
                                        </option>
                                        <option v-for="item in projects_for_swap" :key="item.id" :value="item.id">
                                            {{ item.description }}
                                        </option>
                                    </select>
                                    <InputError :message="swapRPForm.errors.project_id" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeSwapRPModal">
                                Cancelar
                            </SecondaryButton>
                            <button type="submit" :disabled="isFetching" :class="{ 'opacity-25': isFetching }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>



        <Modal :show="showAcceptModal" @close="closeAcceptModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900 mb-2">
                    Aceptar Gasto Adicional
                </h2>
                <form @submit.prevent="submitAcceptModal">
                    <div class="space-y-12">
                        <div class="border-b grid grid-cols-1 gap-6 border-gray-900/10 pb-12">
                            <div>
                                <InputLabel for="operation_number" class="font-medium leading-6 text-gray-900">Numero de
                                    Operación
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="opNuDateForm.operation_number" id="operation_number"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="opNuDateForm.errors.operation_number" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="operation_date" class="font-medium leading-6 text-gray-900">Fecha de
                                    Operación
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="date" v-model="opNuDateForm.operation_date" id="operation_date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="opNuDateForm.errors.operation_date" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeAcceptModal">
                                Cancelar
                            </SecondaryButton>
                            <button type="submit" :disabled="isFetching" :class="{ 'opacity-25': isFetching }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>


        <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Costo Adicional"
            :deleteFunction="deleteAdditional" @closeModal="closeModalDoc" :processing="isFetching" />
        <SuccessOperationModal :confirming="confirmImport" :title="'Datos Importados'"
            :message="'Los datos fueron importados con éxito'" />
        <SuccessOperationModal :confirming="confirmValidation" :title="'Validación'"
            :message="'La validación del gasto fue exitosa.'" />

        <ConfirmateModal :showConfirm="showSwapCostsModal" tittle="Cambio de gastos adicionales a fijos"
            text="La siguiente acción ya no se podrá revertir, ¿Desea continuar?" :actionFunction="swapCosts"
            @closeModal="closeSwapCostsModal" />

        <ConfirmateModal tittle="Descarga de archivos"
            text="La descarga de archivos será en base a los filtros que están activos, si no hay filtros activos se descargarán de todos los registros. PARA AMBOS CASOS SOLO ES PARA REGISTROS ACEPTADOS"
            :showConfirm="showExportArchivesModal" :actionFunction="exportArchives"
            @closeModal="closeExportArchivesModal" />

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ConfirmDeleteModal from "@/Components/ConfirmDeleteModal.vue";
import SuccessOperationModal from "@/Components/SuccessOperationModal.vue";
import ConfirmateModal from "@/Components/ConfirmateModal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import { ref, watch } from "vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import { formattedDate } from "@/utils/utils";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputFile from "@/Components/InputFile.vue";
import Pagination from "@/Components/Pagination.vue";
import TableHeaderFilter from "@/Components/TableHeaderFilter.vue";
import Dropdown from "@/Components/Dropdown.vue";
import { setAxiosErrors, toFormData } from "@/utils/utils";
import { notify, notifyError, notifyWarning } from "@/Components/Notification";
import { Toaster } from "vue-sonner";
import TableDateFilter from "@/Components/TableDateFilter.vue";
import Search from "@/Components/Search.vue";
import qs from 'qs';
import { MenuIcon, EditIcon, DeleteIcon, ShowIcon, ServerIcon, AcceptIcon, ExcelIcon, RejectIcon, SortIcon } from "@/Components/Icons";
import DropdownLink from "@/Components/DropdownLink.vue";
import AdditionalCostsRejectForm from "./components/AdditionalCostsRejectForm.vue";

const props = defineProps({
    additional_costs: Object,
    project_id: Object,
    providers: Object,
    auth: Object,
    searchQuery: String,
    state: String,
    zones: Array,
    expenseTypes: Array,
    docTypes: Array,
    stateTypes: Array,
    additional_projects: Array,
});


const { expenseTypes, docTypes, zones, stateTypes } = props
expenseTypes.sort()
docTypes.sort()
zones.sort()
stateTypes.sort()

const dataToRender = ref(props.additional_costs.data);
const filterMode = ref(false);
const subDropdownOpen = ref(false)

const form = useForm({
    id: "",
    expense_type: "",
    ruc: "",
    zone: "",
    provider_id: "",
    project_id: props.project_id.id,
    type_doc: "",
    operation_number: "",
    operation_date: "",
    doc_number: "",
    doc_date: "",
    description: "",
    photo: "",
    amount: "",
    igv: 0,
    photo_status: "stable",
    admin_state : "",
    admin_reject_reason : "",
    ask_admin_review: false
});

const create_additional = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);
const editAdditionalModal = ref(false);
const editingAdditional = ref(null);

const openCreateAdditionalModal = () => {
    create_additional.value = true;
};

const openEditAdditionalModal = (additional) => {
    editingAdditional.value = JSON.parse(JSON.stringify(additional));
    form.id = editingAdditional.value.id;
    form.expense_type = editingAdditional.value.expense_type;
    form.ruc = editingAdditional.value.ruc;
    form.amount = editingAdditional.value.amount;
    form.type_doc = editingAdditional.value.type_doc;
    form.operation_number = editingAdditional.value.operation_number;
    form.operation_date = editingAdditional.value.operation_date;
    form.doc_number = editingAdditional.value.doc_number;
    form.doc_date = editingAdditional.value.doc_date;
    form.igv = editingAdditional.value.igv;
    form.description = editingAdditional.value.description;
    form.zone = editingAdditional.value.zone;
    form.provider_id = editingAdditional.value.provider_id;
    form.photo_name = editingAdditional.value.photo;
    form.admin_state = editingAdditional.value.admin_state
    form.admin_reject_reason = editingAdditional.value.admin_reject_reason
    editAdditionalModal.value = true;
};

const closeModal = () => {
    form.reset();
    isFetching.value = false;
    create_additional.value = false;
};

const closeEditModal = () => {
    form.reset();
    isFetching.value = false;
    editAdditionalModal.value = false;
};

const isFetching = ref(false);

const submit = async () => {
    try {
        isFetching.value = true;
        const formToSend = toFormData(form.data());
        const res = await axios.post(
            route("projectmanagement.storeAdditionalCost", {
                project_id: props.project_id.id,
            }),
            formToSend
        );
        dataToRender.value.unshift(res.data);
        closeModal();
        notify("Gasto Adicional Guardado");
    } catch (e) {
        isFetching.value = false;
        if (e.response?.data?.errors) {
            setAxiosErrors(e.response.data.errors, form);
        } else {
            notifyError("Server Error");
        }
    }
};

const submitEdit = async () => {
    try {
        isFetching.value = true;
        const formToSend = toFormData(form.data());
        const res = await axios.post(
            route("projectmanagement.updateAdditionalCost", {
                additional_cost: form.id,
            }),
            formToSend
        );
        let index = dataToRender.value.findIndex((item) => item.id == form.id);
        dataToRender.value[index] = res.data;
        closeEditModal();
        notify("Gasto Adicional Actualizado");
    } catch (e) {
        isFetching.value = false;
        if (e.response?.data?.errors) {
            setAxiosErrors(e.response.data.errors, form);
        } else {
            notifyError("Server Error");
        }
    }
};

const confirmDeleteAdditional = (additionalId) => {
    docToDelete.value = additionalId;
    confirmingDocDeletion.value = true;
};

const closeModalDoc = () => {
    confirmingDocDeletion.value = false;
};

const deleteAdditional = async () => {
    const docId = docToDelete.value;
    isFetching.value = true;
    try {
        const res = await axios.delete(
            route("projectmanagement.deleteAdditionalCost", {
                project_id: props.project_id.id,
                additional_cost: docId,
            })
        );
        isFetching.value = false;
        if (res?.data?.msg === "success") {
            closeModalDoc();
            notify("Gasto Adicional Eliminado");
            let index = dataToRender.value.findIndex(
                (item) => item.id == docId
            );
            dataToRender.value.splice(index, 1);
        }
    } catch (e) {
        isFetching.value = false;
    }
};

const handleRucDniAutocomplete = (e) => {
    let ruc = e.target.value;
    let findProv = props.providers.find((item) => item.ruc == ruc);
    if (findProv) {
        form.provider_id = findProv.id;
    } else {
        form.provider_id = "";
    }
};

function handlerPreview(id) {
    const uniqueParam = `timestamp=${new Date().getTime()}`;
    window.open(
        route("additionalcost.archive", { additional_cost_id: id }) +
        "?" +
        uniqueParam,
        "_blank"
    );
}


const initialFilterFormState = {
    search: "",
    selectedZones: zones,
    selectedExpenseTypes: expenseTypes,
    selectedDocTypes: docTypes,
    selectedStateTypes: stateTypes,
    opStartDate: "",
    opEndDate: "",
    opNoDate: false,
    docStartDate: "",
    docEndDate: "",
    docNoDate: false,
};

const filterForm = ref({ ...initialFilterFormState });

watch(
    () => [
        filterForm.value.selectedZones,
        filterForm.value.selectedExpenseTypes,
        filterForm.value.selectedDocTypes,
        filterForm.value.selectedStateTypes,
        filterForm.value.opStartDate,
        filterForm.value.opEndDate,
        filterForm.value.opNoDate,
        filterForm.value.docStartDate,
        filterForm.value.docEndDate,
        filterForm.value.docNoDate,
        filterForm.value.search,
    ],
    () => {
        filterMode.value = true;
        search_advance(filterForm.value);
    }
);

async function search_advance(data) {
    const $search = data || filterForm.value
    try {
        let res = await axios.post(
            route("additionalcost.advance.search", {
                project_id: props.project_id.id,
            }),
            $search
        );
        dataToRender.value = res.data;
        notifyWarning(`Se encontraron ${res.data.length} registro(s)`);
    } catch (error) {
        console.error("Error en la solicitud:", error);
    }
}

// async function handleSearch() {
//     filterMode.value = true;
//     search_advance(filterForm.value);
// }
const uniqueParam = `timestamp=${new Date().getTime()}`;
//import modal
const showImportModal = ref(false);
const confirmImport = ref(false);
const importForm = useForm({
    import_file: undefined,
});
function openImportModal() {
    showImportModal.value = true;
}
function closeImportModal() {
    importForm.reset();
    showImportModal.value = false;
}
function submitImport() {
    importForm.post(
        route("projectmanagement.importAdditionalCost", {
            project_id: props.project_id.id,
        }),
        {
            onSuccess: () => {
                closeImportModal();
                confirmImport.value = true;
                setTimeout(() => {
                    confirmImport.value = false;
                    router.visit(
                        route("projectmanagement.additionalCosts", {
                            project_id: props.project_id.id,
                        })
                    );
                }, 2000);
            },
        }
    );
}

function openExportExcel() {

    const url =
        route("additionalcost.excel.export", {
            project_id: props.project_id.id,
        }) +
        "?" +
        uniqueParam;
    window.location.href = url;
}

const showExportArchivesModal = ref(false)
const openExportArchivesModal = () => { showExportArchivesModal.value = true }
const closeExportArchivesModal = () => { showExportArchivesModal.value = false }

function exportArchives() {
    const uniqueParam = `timestamp=${new Date().getTime()}`;
    const url = route("zip.additional.descargar", { project_id: props.project_id.id }) +
        '?' + qs.stringify({ ...filterForm.value, uniqueParam }, { arrayFormat: 'brackets' });
    window.location.href = url;
    closeExportArchivesModal()
}

watch([() => form.type_doc, () => form.zone], () => {
    if (
        form.type_doc === "Factura" &&
        !["", "MDD1-PM", "MDD2-MAZ"].includes(form.zone)
    ) {
        form.igv = form.igv ? form.igv : 18;
    } else {
        form.igv = 0;
    }
});

const confirmValidation = ref(false);

async function validateRegister(ac_id, is_accepted) {
    try {
        const res = await axios.post(
            route("projectmanagement.validateAdditionalCost", { ac_id }),
            { is_accepted }
        );
        if (!res?.data?.additional_cost) {
            let index = dataToRender.value.findIndex(
                (item) => item.id == ac_id
            );
            dataToRender.value.splice(index, 1);
        }
        if (res?.data?.additional_cost?.is_accepted == true) {
            let index = dataToRender.value.findIndex(
                (item) => item.id == res.data.additional_cost.id
            );
            dataToRender.value[index] = res.data.additional_cost;
        } else if (res?.data?.additional_cost?.is_accepted == false) {
            let index = dataToRender.value.findIndex(
                (item) => item.id == res.data.additional_cost.id
            );
            dataToRender.value.splice(index, 1);
        }
        notify(res.data.msg)
    } catch (e) {
        notifyError('Server Error')
    }
}


//block actions
const actionForm = ref({ ids: [], });
const handleCheckAll = (e) => {
    if (e.target.checked) { actionForm.value.ids = dataToRender.value.map((item) => item.id); }
    else { actionForm.value.ids = []; }
};

watch(
    () => filterForm.value,
    () => { actionForm.value = { ids: [] }; },
    { deep: true }
);


//operation number
const opNuDateForm = useForm({
    operation_date: '',
    operation_number: '',
})
const showOpNuDatModal = ref(false)
const closeOpNuDatModal = () => {
    showOpNuDatModal.value = false
    isFetching.value = false
    opNuDateForm.reset()
    opNuDateForm.clearErrors()
}
const openOpNuDaModal = () => {
    if (actionForm.value.ids.length === 0) {
        notifyWarning("No hay registros selccionados");
        return;
    }
    showOpNuDatModal.value = true
}
const submitOpNuDatModal = async () => {
    isFetching.value = true;
    const res = await axios
        .post(route("projectmanagement.additionalCosts.massiveUpdate"), {
            ...opNuDateForm.data(),
            ...actionForm.value
        })
        .catch((e) => {
            isFetching.value = false;
            if (e.response?.data?.errors) {
                setAxiosErrors(e.response.data.errors, opNuDateForm);
            } else {
                notifyError("Server Error");
            }
        });
    const resIds = res.data.map(item => item.id);
    const rgsToRemove = actionForm.value.ids.filter(id => !resIds.includes(id))
    const originalMap = new Map(dataToRender.value.filter(item => !rgsToRemove.includes(item.id)).
        map(item => [item.id, item]));
    res.data.forEach(update => {
        if (originalMap.has(update.id)) {
            originalMap.set(update.id, update);
        }
    });
    const updatedArray = Array.from(originalMap.values());
    dataToRender.value = updatedArray
    actionForm.value.ids = resIds
    closeOpNuDatModal();
    notify("Registros Seleccionados Actualizados")
    setTimeout(() => {
        if (rgsToRemove.length > 0) notify("Algunos fueron movidos a gastos fijos y/o proyecto GEP")
    }, 1000)
}



const showAcceptModal = ref(false)
const itemToAccept = ref(null)
const closeAcceptModal = () => {
    showAcceptModal.value = false
    isFetching.value = false
    itemToAccept.value = null
    opNuDateForm.reset()
    opNuDateForm.clearErrors()
}
const openAcceptModal = (item) => {
    itemToAccept.value = item
    showAcceptModal.value = true
}
async function submitAcceptModal() {
    isFetching.value = true;
    const res = await axios.post(
        route("projectmanagement.validateAdditionalCost", { ac_id: itemToAccept.value.id }),
        { is_accepted: 1, ...opNuDateForm.data() }
    )
        .catch((e) => {
            isFetching.value = false;
            if (e.response?.data?.errors) {
                setAxiosErrors(e.response.data.errors, opNuDateForm);
            } else {
                notifyError("Server Error");
            }
        });
    if (!res?.data?.additional_cost) {
        let index = dataToRender.value.findIndex(
            (item) => item.id == itemToAccept.value.id
        );
        dataToRender.value.splice(index, 1);
    } else {
        let index = dataToRender.value.findIndex((item) => item.id == res.data.additional_cost.id);
        dataToRender.value[index] = res.data.additional_cost;
    }
    closeAcceptModal();
    notify(res.data.msg)
}


//swap
// showSwapCostsModal
// swapCosts
// closeSwapCostsModal
const showSwapCostsModal = ref(false)
const closeSwapCostsModal = () => {
    showSwapCostsModal.value = false
    isFetching.value = false
}
const openSwapCostsModal = () => {
    if (actionForm.value.ids.length === 0) {
        notifyWarning("No hay registros seleccionados");
        return;
    }
    showSwapCostsModal.value = true
}
const swapCosts = async () => {
    isFetching.value = true;
    const res = await axios
        .post(route("projectmanagement.additionalCosts.swapCosts"), {
            ...actionForm.value
        })
        .catch((e) => {
            isFetching.value = false;
            notifyError("Server Error");
        });
    dataToRender.value = dataToRender.value.filter(
        (item) => !actionForm.value.ids.includes(item.id)
    );
    actionForm.value.ids = []
    closeSwapCostsModal();
    notify("Registros Movidos con éxito");
}

const stateCreateAtSort = ref(false)
function sortValue() {
    if (stateCreateAtSort.value) {
        dataToRender.value.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
    } else {
        dataToRender.value.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    }
    stateCreateAtSort.value = !stateCreateAtSort.value;
}





//swap to other projects

const additionalProjectForm = useForm({
    project_id: '',
})
const showSwapAPModal = ref(false)
const closeSwapAPModal = () => {
    showSwapAPModal.value = false
    isFetching.value = false
    additionalProjectForm.reset()
    additionalProjectForm.clearErrors()
}
const openSwapAPModal = () => {
    if (actionForm.value.ids.length === 0) {
        notifyWarning("No hay registros seleccionados");
        return;
    }
    showSwapAPModal.value = true
}

// const submitSwapAPModal = async () => {
//     isFetching.value = true;
//     const res = await axios
//         .post(route("projectmanagement.addctoaddproject.swapCosts"), {
//             ...additionalProjectForm.data(),
//             ...actionForm.value
//         })
//         .catch((e) => {
//             console.log(e)
//             isFetching.value = false;
//             if (e.response?.data?.errors) {
//                 setAxiosErrors(e.response.data.errors, additionalProjectForm);
//             } else {
//                 notifyError(`Server Error: ${e.response.data}`);
//             }
//         });

//     dataToRender.value = dataToRender.value.filter(
//         (item) => !actionForm.value.ids.includes(item.id)
//     );
//     actionForm.value.ids = []

//     closeSwapAPModal();
//     notify("Registros Movidos con éxito")
// }

const submitSwapAPModal = async () => {
    isFetching.value = true;
    let url = route("projectmanagement.addctoaddproject.swapCosts")
    try {
        let res = await axios.post(url,
            {
                ...additionalProjectForm.data(),
                ...actionForm.value
            }
        );
        if (res?.status === 207) {
            const ids = res.data.idsList;
            dataToRender.value = dataToRender.value.filter(
                item => !ids.includes(item.id)
            );
            notifyWarning(`Algunos registros no fueron movidos`);
        } else {
            dataToRender.value = dataToRender.value.filter(
                (item) => !actionForm.value.ids.includes(item.id)
            );
            notify("Registros movidos con éxito");
        }

    } catch (e) {
        console.log(e);
        isFetching.value = false;
        if (e.response?.data?.errors) {
            setAxiosErrors(e.response.data.errors, additionalProjectForm);
        } else {
            notifyError('Error del servidor');
        }

    } finally {
        actionForm.value.ids = [];
        closeSwapAPModal();
    }
};




//swapp to open mnto and gep
const projects_for_swap = ref([])
function getRegularProjects() {
    axios.get(route('projectmanagement.regularprojects.all'))
        .then(res => {
            projects_for_swap.value = res.data
        })
}

const swapRPForm = useForm({
    project_id: '',
})

const showSwapRPModal = ref(false)
const closeSwapRPModal = () => {
    showSwapRPModal.value = false
    isFetching.value = false
    swapRPForm.reset()
    swapRPForm.clearErrors()
}

const openSwapRPModal = () => {
    if (projects_for_swap.value.length === 0) {
        getRegularProjects()
    }
    if (actionForm.value.ids.length === 0) {
        notifyWarning("No hay registros selccionados");
        return;
    }
    showSwapRPModal.value = true
}

const submitSwapRPModal = async () => {
    isFetching.value = true;
    await axios
        .post(route("projectmanagement.regularproject.swapCosts"), {
            ...swapRPForm.data(),
            ...actionForm.value
        })
        .catch((e) => {
            isFetching.value = false;
            if (e.response?.data?.errors) {
                setAxiosErrors(e.response.data.errors, swapRPForm);
            } else {
                notifyError("Server Error");
            }
        });

    dataToRender.value = dataToRender.value.filter(
        (item) => !actionForm.value.ids.includes(item.id)
    );
    actionForm.value.ids = []

    closeSwapRPModal();
    notify("Registros Movidos con éxito")
}


//Reject modal

const rejectInitState = {  id: null, reject_reason: '',}
const registerToReject = ref({...rejectInitState})
function displayRejectReason (id) {
    closeRejectReason()
    registerToReject.value.id = id
}
function closeRejectReason() {
    registerToReject.value = {...rejectInitState}
}
async function saveRejectReason() {
    try {
        const res = await axios.post(
            route("projectmanagement.rejectAdditionalCost", { ac_id: registerToReject.value.id }),
            { is_accepted:false, ...registerToReject.value }
        );
         let index = dataToRender.value.findIndex(
                (item) => item.id == res.data.additional_cost.id
            );
        dataToRender.value.splice(index, 1);
        notify(res.data.msg)
        closeRejectReason()
    } catch (e) {
        console.log(e)
        notifyError('Server Error')
    }
}

// administrative state
const adminRejectInitState = {  id: null, admin_reject_reason: '', admin_is_accepted:null}
const regToAdminReject = ref({...adminRejectInitState})
function displayAdminRejectReason (id) {
    closeAdminRejectReason()
    regToAdminReject.value.id = id
    regToAdminReject.value.admin_is_accepted = 0
}
function displayAdminAccept (id) {
    const payload = {id, admin_is_accepted:1, admin_reject_reason: ''}
    saveAdminValidate(payload)
}

function closeAdminRejectReason() {
    regToAdminReject.value = {...adminRejectInitState}
}

async function saveAdminReject () {
    await saveAdminValidate(regToAdminReject.value)
}

async function saveAdminValidate(payload) {
    try {
        const res = await axios.post(route('projectmanagement.administrative.validation', {ac_id: payload.id}), payload)
        let index = dataToRender.value.findIndex((item) => item.id == res.data.additional_cost.id);
        dataToRender.value[index] = res.data.additional_cost;
        notify(res.data.msg)
        closeAdminRejectReason()
    } catch (e) {
        console.log(e)
        notifyError('Server Error')
    }
}

</script>
