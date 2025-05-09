<template>

    <Head title="Gestion de Costos Adicionales" />
    <AuthenticatedLayout :redirectRoute="
    {
        route: 'projectmanagement.pext.additional.index',
        params: {type:type}
    }">
        <template #header>
            Gastos {{ fixedOrAdditional ? 'Fijos' : 'Adicionales' }}
        </template>
        <br />
        <Toaster richColors />
        <div class="inline-block min-w-full mb-4">
            <div class="flex gap-4 justify-between">
                <div class="hidden sm:flex sm:items-center space-x-3">
                    <PrimaryButton data-tooltip-target="update_data_tooltip" type="button" @click="() => {
                        filterForm = { ...initialFilterFormState }
                    }
                        ">
                        <ServerIcon class="w-5 h-5 text-white" />
                    </PrimaryButton>
                    <div id="update_data_tooltip" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Todos los Registros
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <button data-tooltip-target="export_tooltip" type="button"
                        class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500"
                        @click="openExportExcel">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9.29289 1.29289C9.48043 1.10536 9.73478 1 10 1H18C19.6569 1 21 2.34315 21 4V9C21 9.55228 20.5523 10 20 10C19.4477 10 19 9.55228 19 9V4C19 3.44772 18.5523 3 18 3H11V8C11 8.55228 10.5523 9 10 9H5V20C5 20.5523 5.44772 21 6 21H7C7.55228 21 8 21.4477 8 22C8 22.5523 7.55228 23 7 23H6C4.34315 23 3 21.6569 3 20V8C3 7.73478 3.10536 7.48043 3.29289 7.29289L9.29289 1.29289ZM6.41421 7H9V4.41421L6.41421 7ZM19 12C19.5523 12 20 12.4477 20 13V19H23C23.5523 19 24 19.4477 24 20C24 20.5523 23.5523 21 23 21H19C18.4477 21 18 20.5523 18 20V13C18 12.4477 18.4477 12 19 12ZM11.8137 12.4188C11.4927 11.9693 10.8682 11.8653 10.4188 12.1863C9.96935 12.5073 9.86526 13.1318 10.1863 13.5812L12.2711 16.5L10.1863 19.4188C9.86526 19.8682 9.96935 20.4927 10.4188 20.8137C10.8682 21.1347 11.4927 21.0307 11.8137 20.5812L13.5 18.2205L15.1863 20.5812C15.5073 21.0307 16.1318 21.1347 16.5812 20.8137C17.0307 20.4927 17.1347 19.8682 16.8137 19.4188L14.7289 16.5L16.8137 13.5812C17.1347 13.1318 17.0307 12.5073 16.5812 12.1863C16.1318 11.8653 15.5073 11.9693 15.1863 12.4188L13.5 14.7795L11.8137 12.4188Z"
                                fill="#ffffff" />
                        </svg>
                    </button>
                    <div id="export_tooltip" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Exportar Excel
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>

                    <button data-tooltip-target="rejected_tooltip" type="button"
                        class="rounded-md bg-gray-100 px-4 py-1 text-center text-lg font-bold ring-2 hover:bg-gray-100/2"
                        :class="filterForm.rejected ? 'text-red-600 ring-red-400' : 'text-green-600 ring-green-400'"
                        @click="rejectedExpenses">
                        {{ filterForm.rejected ? "R" : "A" }}
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
                                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 6H20M4 12H20M4 18H20" stroke="#ffffff" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                                <div id="action_button_tooltip" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700 whitespace-nowrap">
                                    Acciones
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </template>

                            <template #content class="origin-left">
                                <div>
                                    <!-- Alineación a la derecha -->

                                    <div class="">
                                        <button @click="openSwapAPModal"
                                            class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-gray-200 hover:text-black focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                            Swap
                                        </button>
                                        
                                    </div>
                                </div>
                            </template>
                        </dropdown>
                    </div>

                    <Link v-if="fixedOrAdditional"
                        class="rounded-md px-4 py-2 text-center text-sm text-white bg-indigo-600 hover:bg-indigo-500"
                        :href="route('pext.additional.expense.index', { project_id: project_id, fixedOrAdditional: false, type })">
                    G.Adicionales
                    </Link>
                    <Link v-else
                        class="rounded-md px-4 py-2 text-center text-sm text-white bg-indigo-600 hover:bg-indigo-500"
                        :href="route('pext.additional.expense.index', { project_id: project_id, fixedOrAdditional: true, type })">
                    G.Fijos
                    </Link>
                </div>
                <div v-if="hasPermission('HumanResourceManager')" class="sm:hidden">
                    <dropdown align="left">
                        <template #trigger>
                            <button @click="dropdownOpen = !dropdownOpen"
                                class="relative block overflow-hidden rounded-md bg-gray-200 px-2 py-2 text-center text-sm text-white hover:bg-gray-100">
                                <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 6H20M4 12H20M4 18H20" stroke="#000000" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </template>

                        <template #content class="origin-left">
                            <div class="dropdown">
                                <div class="dropdown-menu">
                                    <button data-tooltip-target="update_data_tooltip" type="button" @click="() => {
                                        filterForm = { ...initialFilterFormState }
                                    }
                                        ">
                                        Todos los registros
                                    </button>
                                </div>
                                <div class="dropdown-menu">
                                    <a
                                        class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        Exportar
                                    </a>
                                </div>
                                <div class="dropdown-menu">
                                    <button type="button"
                                        class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-ouy"
                                        @click="rejectedExpenses">
                                        Rechazados
                                    </button>
                                </div>
                                <div class="dropdown-menu">
                                    <Link v-if="fixedOrAdditional"
                                        class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-ouy"
                                        :href="route('pext.additional.expense.index', { project_id: project_id, fixedOrAdditional: false, type })">
                                    G.Adicionales
                                    </Link>
                                    <Link v-else
                                        class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-ouy"
                                        :href="route('pext.additional.expense.index', { project_id: project_id, fixedOrAdditional: true, type })">
                                    G.Fijos
                                    </Link>
                                </div>
                            </div>
                        </template>
                    </dropdown>
                </div>
                <div class="flex space-x-3">
                    <Search v-model:search="filterForm.search" fields="Ruc,Fecha Documento,Descripción,Monto,Numero de Operación"/>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto h-[85vh]">
            <div class="mb-4">
                <ChartsAdditionalExpenses 
                    :acExpensesAmounts="acExpensesAmounts"
                    :scExpensesAmounts="scExpensesAmounts"
                />
            </div>
            <table class="w-full">
                <thead class="sticky top-0 z-20">
                    <tr
                        class=" border-b bg-gray-50 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">

                        <th class="bg-gray-100 border-b-2 border-gray-20">
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
                        <!-- <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableHeaderFilter labelClass="text-[11px]" label="Zona" :options="zones"
                                v-model="filterForm.selectedZones" width="w-40" />
                        </th> -->
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600 ">
                            <TableHeaderFilter labelClass="text-[11px]" label="Tipo de Gasto" :options="expenseTypes"
                                v-model="filterForm.selectedExpenseTypes" width="w-40" />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableHeaderFilter labelClass="text-[11px]" label="Tipo de Documento" :options="documentsType"
                                v-model="filterForm.selectedDocTypes" width="w-40" />
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
                            Numero de Operacion
                        </th>
                        <!-- <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Fecha de Operacion
                        </th> -->
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
                        <!-- <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Fecha de Documento
                        </th> -->
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableDateFilter labelClass="text-[11px]" label="Fecha de Documento"
                                v-model:startDate="filterForm.docStartDate" v-model:endDate="filterForm.docEndDate"
                                v-model:noDate="filterForm.docNoDate" width="w-40" />
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
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in expenses.data || expenses" :key="item.id" class="text-gray-700">
                        <td :class="[
                            'sticky left-0 z-10 border-b border-gray-200',
                            {
                                'bg-indigo-500': item.real_state === 'Pendiente',
                                'bg-green-500': item.real_state == 'Aceptado - Validado',
                                'bg-amber-500': item.real_state == 'Aceptado',
                                'bg-red-500': item.real_state == 'Rechazado',
                            },
                        ]"></td>
                        <!-- <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            {{ item.zone }}
                        </td> -->
                        <td
                            class="sticky left-2 z-10 border-b border-r border-gray-200 bg-amber-100 text-center text-[13px] whitespace-nowrap tabular-nums">
                            <label :for="`check-${item.id}`" class="block w-12 px-2 py-1">
                                <input v-model="actionForm.ids" :value="item.id" :id="`check-${item.id}`"
                                    type="checkbox" />
                            </label>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            <p class="w-48 break-words">
                                {{ item.expense_type }}
                            </p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            {{ item.type_doc }}
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums">
                            {{ item.ruc }}
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            {{ item?.provider?.company_name }}
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums">
                            {{ item.operation_number }}
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap">
                            {{ item.operation_date }}
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums">
                            {{ item.doc_number }}
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            {{ formattedDate(item.doc_date) }}
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap">
                            S/. {{ item.amount.toFixed(2) }}
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap">
                            S/. {{ item.real_amount.toFixed(2) }}
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            <button v-if="item.photo" @click="handlerPreview(item.id)">
                                <EyeIcon class="w-4 h-4 text-teal-600" />
                            </button>
                            <span v-else>-</span>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            <p class="w-[250px]">
                                {{ item.description }}
                            </p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            <div :class="[
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
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            <div class="flex items-center gap-3 w-full">
                                <div v-if="item.is_accepted === null" class="flex gap-3 justify-center w-1/2">
                                    <button @click="() =>
                                        validateRegister(item.id, true)
                                        " class="flex items-center rounded-xl text-blue-500 hover:bg-green-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                    <button @click="() =>
                                        validateRegister(item.id, false)
                                        " type="button"
                                        class="rounded-xl whitespace-no-wrap text-center text-sm text-red-900 hover:bg-red-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </div>
                                <div v-else class="w-1/2"></div>
                            </div>
                        </td>
                    </tr>
                    <tr class="sticky bottom-0 z-10 text-gray-700">
                        <td class="font-bold border-b border-gray-200 bg-white">
                        </td>
                        <td class="font-bold border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            TOTAL
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" colspan="8"></td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                            S/.
                            {{
                                (expenses.data || expenses)
                                    ?.reduce((a, item) => a + item.amount, 0)
                                    .toFixed(2)
                            }}
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                            S/.
                            {{
                                (expenses.data || expenses)
                                    .reduce(
                                        (a, item) => a + item.real_amount, 0)
                                    .toFixed(2)
                            }}
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" colspan="4"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="expenses.data"
            class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
            <pagination :links="expenses.links" />
        </div>
        <Modal :show="showSwapAPModal" @close="closeSwapAPModal">
            <div class="p-6 space-y-3">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Mover gastos
                </h2>
                <h4 class="text-sm font-light text-green-900 bg-green-500/10 rounded-lg p-3 ">
                    Los gastos seleccionados serán movidos al proyecto especificado en el tipo de gasto especificado.
                    Solo se listan los proyectos adicionales que proceden
                </h4>
                <form @submit.prevent="submitSwapAPModal">
                    <div class="space-y-4">
                        <div>
                            <InputLabel for="project_id" class="font-medium leading-6 text-gray-900">
                                Seleccionar movimiento
                            </InputLabel>
                            <div class="w-full flex justify-start gap-4 mt-2">
                                <label class="flex gap-2 items-center text-sm">
                                    <input type="radio" :value="'same_project'" v-model="additionalProjectForm.type_project"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                    Al mismo proyecto ({{ fixedOrAdditional ? 'Adicionales' : 'Fijos' }})
                                </label>
                                <label class="flex gap-2 items-center text-sm">
                                    <input type="radio" :value="'different_project'" v-model="additionalProjectForm.type_project"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                    A otro proyecto adicional
                                </label>
                            </div>
                        </div>
                        <div v-if="additionalProjectForm.type_project === 'different_project'">
                            <InputLabel for="project_id" class="font-medium leading-6 text-gray-900">
                                Seleccionar a que tipo de gasto mover
                            </InputLabel>
                            <div class="w-full flex justify-start gap-4 mt-2">
                                <label class="flex gap-2 items-center text-sm">
                                    <input type="radio" :value="'0'" v-model="additionalProjectForm.type_expense"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                    Gastos variables
                                </label>
                                <label class="flex gap-2 items-center text-sm">
                                    <input type="radio" :value="'1'" v-model="additionalProjectForm.type_expense"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                    Gastos fijos
                                </label>
                            </div>
                        </div>

                        <div v-if="additionalProjectForm.type_project === 'different_project'" class="border-b grid grid-cols-1 gap-6 border-gray-900/10 pb-12">
                            <div>
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
        <!-- <SuccessOperationModal :confirming="confirmValidation" :title="'Validación'"
            :message="'La validación del gasto fue exitosa.'" /> -->
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, watch } from "vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import { ServerIcon } from "@heroicons/vue/24/outline";
import { formattedDate } from "@/utils/utils";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Pagination from "@/Components/Pagination.vue";
import { EyeIcon } from "@heroicons/vue/24/outline";
import TableHeaderFilter from "@/Components/TableHeaderFilter.vue";
import axios from "axios";
import Dropdown from "@/Components/Dropdown.vue";
import { Toaster } from "vue-sonner";
import TableDateFilter from "@/Components/TableDateFilter.vue";
import ChartsAdditionalExpenses from "./ChartsAdditionalExpenses.vue";
import Search from "@/Components/Search.vue";
import Modal from "@/Components/Modal.vue";
import { notify, notifyWarning, notifyError } from "@/Components/Notification";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    expense: Object,
    providers: Object,
    auth: Object,
    userPermissions: Array,
    cost_center: Object,
    project_id: String,
    fixedOrAdditional: Boolean,
    type: Number,
    acExpensesAmounts: Array,
    scExpensesAmounts: Array,
    zones:Array,
    expenseType:Array,
    documentsType:Array,
    expenseTypeFixed:Array,
    additional_projects: Array,
});

const expenses = ref(props.expense);
// const filterMode = ref(false);
// const subCostCenterZone = ref(null);
// const subCostCenter = ref(null)
const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
};

const form = useForm({
    id: "",
    fixedOrAdditional: props.fixedOrAdditional,
    expense_type: "",
    ruc: "",
    zone: "",
    provider_id: "",
    project_id: "",
    type_doc: "",
    operation_number: "",
    operation_date: "",
    doc_number: "",
    doc_date: "",
    description: "",
    photo: "",
    is_accepted: true,
    amount: "",
    igv: 0,
});

const showOpNuDatModal = ref(false)

const openOpNuDaModal = () => {
    if (actionForm.value.ids.length === 0) {
        notifyWarning("No hay registros seleccionados");
        return;
    }
    showOpNuDatModal.value = true
}

function handlerPreview(id) {
    const uniqueParam = `timestamp=${new Date().getTime()}`;
    window.open(
        route("projectmanagement.pext.expenses.image.show", { expense_id: id }) +
        "?" +
        uniqueParam,
        "_blank"
    );
}

const expenseTypes = props.fixedOrAdditional
    ? props.expenseTypeFixed
    : props.expenseType;

const stateTypes = [
    "Pendiente",
    "Aceptado",
    "Aceptado - Validado",
];

const initialFilterFormState = {
    fixedOrAdditional: props.fixedOrAdditional,
    rejected: true,
    search: "",
    selectedExpenseTypes: expenseTypes,
    selectedDocTypes: props.documentsType,
    selectedStateTypes: stateTypes,
    opStartDate: "",
    opEndDate: "",
    opNoDate: false,
    docStartDate: "",
    docEndDate: "",
    docNoDate: false,
}

const filterForm = ref({
    ...initialFilterFormState
});

watch(() => [
    filterForm.value.fixedOrAdditional,
    filterForm.value.rejected,
    filterForm.value.search,
    filterForm.value.selectedExpenseTypes,
    filterForm.value.selectedDocTypes,
    filterForm.value.selectedStateTypes,
    filterForm.value.opStartDate,
    filterForm.value.opEndDate,
    filterForm.value.opNoDate,
    filterForm.value.docStartDate,
    filterForm.value.docEndDate,
    filterForm.value.docNoDate,
],
    () => {
        search_advance(filterForm.value);
    }
);

async function search_advance(data) {
    console.log(data)
    let url = route("pext.monthly.additional.expense.search_advance", {
        project_id: props.project_id,
    })
    try {
        let response = await axios.post(url, data);
        expenses.value = response.data;
    } catch (error) {
        console.error('Error en la solicitud:', error);
    }
}

function openExportExcel() {
    const uniqueParam = `timestamp=${new Date().getTime()}`;
    const url =
        route("projectmanagement.pext.expenses.export", {
            project_id: props.project_id,
            fixedOrAdditional: filterForm.value.fixedOrAdditional
        }) +
        "?" +
        uniqueParam;
    window.location.href = url;
}

watch([() => form.type_doc, () => form.zone], () => {
    if (
        form.type_doc === "Factura" &&
        !["", "MDD"].includes(form.zone)
    ) {
        form.igv = form.igv ? form.igv : 18;
    } else {
        form.igv = 0;
    }
});

// const confirmValidation = ref(false);
async function validateRegister(expense_id, is_accepted) {
    const url = route("projectmanagement.pext.expenses.validate", { 'expense_id': expense_id })
    try {
        await axios.put(url, { 'is_accepted': is_accepted });
        if (filterForm.value.rejected) {
            updateExpense(expense_id, "validate", is_accepted)
        } else {
            updateExpense(expense_id, "rejectedValidate")
        }
    } catch (e) {
        console.log(e);
    }
}

function rejectedExpenses() {
    filterForm.value.rejected = !filterForm.value.rejected
}

const actionForm = ref({ ids: [], });

const handleCheckAll = (e) => {
    if (e.target.checked) { actionForm.value.ids = expenses.value.map((item) => item.id); }
    else { actionForm.value.ids = []; }
};

watch(
    () => filterForm.value,
    () => { actionForm.value = { ids: [] }; },
    { deep: true }
);




//swap to other projects
const isFetching = ref(false)
const additionalProjectForm = useForm({
    project_id: '',
    type_project: 'same_project',
    type_expense: '',
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

const submitSwapAPModal = async () => {
    isFetching.value = true;
    const res = await axios
        .post(route("projectmanagement.additionalToAdditional.swapCosts"), {
            ...additionalProjectForm.data(),
            ...actionForm.value
        })
        .catch((e) => {
            isFetching.value = false;
            if (e.response?.data?.errors) {
                setAxiosErrors(e.response.data.errors, additionalProjectForm);
            } else {
                notifyError("Server Error");
            }
        });
        if (expenses.value.data) {
            expenses.value.data = expenses.value.data.filter((item) => 
                !actionForm.value.ids.includes(item.id));
        } 
        else {
            expenses.value = expenses.value.filter((item) => 
            !actionForm.value.ids.includes(item.id));
        }

    actionForm.value.ids = []

    closeSwapAPModal();
    notify("Registros Movidos con éxito")
}

watch(()=>additionalProjectForm.type_project, ()=> {
    additionalProjectForm.type_expense = ''
    additionalProjectForm.project_id = ''
})


</script>
