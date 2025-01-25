<template>

    <Head title="Gestion de Costos Adicionales" />
    <AuthenticatedLayout ::redirectRoute="{
        route: 'projectmanagement.pext.additional.index',
        params: { type }
    }">
        <template #header>
            Gastos {{ fixedOrAdditional ? 'Fijos' : 'Adicionales' }}
        </template>
        <br />
        <Toaster richColors />
        <div class="inline-block min-w-full mb-4">
            <div class="flex gap-4 justify-between">
                <div class="hidden sm:flex sm:items-center space-x-3">
                    <PrimaryButton v-if="hasPermission('ProjectManager') && filterForm.rejected"
                        @click="openCreateAdditionalModal" type="button" class="whitespace-nowrap">
                        + Agregar
                    </PrimaryButton>
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
                                        <button @click="openOpNuDaModal"
                                            class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-gray-200 hover:text-black focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                            Actualizar Operación
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </dropdown>
                    </div>
                    <Link v-if="fixedOrAdditional"
                        class="rounded-md px-4 py-2 text-center text-sm text-white bg-indigo-600 hover:bg-indigo-500"
                        :href="route('pext.additional.expense.general.index', { fixedOrAdditional: false, type })">
                    G.Adicionales
                    </Link>
                    <Link v-else
                        class="rounded-md px-4 py-2 text-center text-sm text-white bg-indigo-600 hover:bg-indigo-500"
                        :href="route('pext.additional.expense.general.index', { fixedOrAdditional: true, type })">
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
                                    <button v-if="hasPermission('ProjectManager') && filterForm.rejected"
                                        @click="openCreateAdditionalModal"
                                        class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        Agregar
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
                            </div>
                        </template>
                    </dropdown>
                </div>
                <div class="flex space-x-3">
                    <TextInput data-tooltip-target="search_fields" type="text" placeholder="Buscar..."
                        v-model="filterForm.search" class="h-auto" />
                    <div id="search_fields" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Ruc,Fecha Documento,Descripción,Monto
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto h-[85vh]">
            <div class="mb-4">
                <ChartsAdditionalExpenses :acExpensesAmounts="acExpensesAmounts"
                    :scExpensesAmounts="scExpensesAmounts" />
            </div>
            <table class="w-full">
                <thead class="sticky top-0 z-20">
                    <tr
                        class=" border-b bg-gray-50 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">

                        <th class="bg-gray-100 border-b-2 border-gray-20 sticky left-0 z-10">
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
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableHeaderFilter labelClass="text-[11px]" label="Zona" :options="zones"
                                v-model="filterForm.selectedZones" width="w-40" />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600 ">
                            <TableHeaderFilter labelClass="text-[11px]" label="Tipo de Gasto" :options="expenseTypes"
                                v-model="filterForm.selectedExpenseTypes" width="w-40" />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableHeaderFilter labelClass="text-[11px]" label="Tipo de Documento" :options="docTypes"
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
                        <td
                            class="sticky left-2 z-10 border-b border-r border-gray-200 bg-amber-100 text-center text-[13px] whitespace-nowrap tabular-nums">
                            <label :for="`check-${item.id}`" class="block w-12 px-2 py-1">
                                <input v-model="actionForm.ids" :value="item.id" :id="`check-${item.id}`"
                                    type="checkbox" />
                            </label>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            {{ item.zone }}
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

                                <div v-if="hasPermission('ProjectManager')" class="flex gap-3 mr-3">
                                    <button v-if="!filterForm.rejected" data-tooltip-target="tooltip-up-ac" @click="() => validateRegister(item.id, true)
                                        " class="flex items-center rounded-xl text-blue-700 hover:bg-green-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15m0-3-3-3m0 0-3 3m3-3V15" />
                                        </svg>
                                    </button>
                                    <button @click="openEditAdditionalModal(item)"
                                        class="text-amber-600 hover:underline">
                                        <PencilSquareIcon class="h-5 w-5 ml-1" />
                                    </button>
                                    <button @click="
                                        confirmDeleteAdditional(item.id)
                                        " class="text-red-600 hover:underline">
                                        <TrashIcon class="h-5 w-5" />
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="sticky bottom-0 z-10 text-gray-700">
                        <td class="font-bold border-b border-gray-200 bg-white">
                        </td>
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
        <Modal :show="create_additional" @close="closeModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{ form.id ? "Actualizar" : "Agregar" }} Gasto
                </h2>
                <form @submit.prevent="submit">
                    <div class="space-y-12 mt-4">
                        <div class="grid sm:grid-cols-2 gap-6 pb-6">
                            <div v-if="!form.id">
                                <InputLabel for="project_id" class="font-medium leading-6 text-gray-900">Zona
                                </InputLabel>
                                <div class="mt-2">
                                    <select id="project_id" v-model="form.zone"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">
                                            Seleccionar Zona
                                        </option>
                                        <option v-for="zone in zones" :value="zone">
                                            {{ zone }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.project_id" />
                                </div>
                            </div>
                            <div v-if="!form.id">
                                <InputLabel for="project_id" class="font-medium leading-6 text-gray-900">Proyectos Cicsa
                                </InputLabel>
                                <div class="mt-2">
                                    <select id="project_id" v-model="form.project_id"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">
                                            Seleccionar Proyecto
                                        </option>
                                        <option v-for="op in cicsaAssignation" :value="op.project_id">
                                            {{ op.project_name }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.project_id" />
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
                                        <option v-for="op in expenseTypes">{{ op }}</option>
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
                                        <option v-for="op in docTypes">{{ op }}</option>
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
                                    Operacion
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.operation_number" id="operation_number"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.operation_number" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="operation_date" class="font-medium leading-6 text-gray-900">Fecha de
                                    Operacion
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
                                    <InputFile type="file" v-model="form.photo" accept=".jpeg, .jpg, .png"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.photo" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeModal">
                                Cancelar
                            </SecondaryButton>
                            <button type="submit" :disabled="form.processing" :class="{ 'opacity-25': form.processing }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

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
                            <button type="submit" :disabled="opNuDateForm.processing"
                                :class="{ 'opacity-25': opNuDateForm.processing }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Gasto"
            :deleteFunction="deleteAdditional" @closeModal="closeModalDoc" />
        <!-- <SuccessOperationModal :confirming="confirmValidation" :title="'Validación'"
            :message="'La validación del gasto fue exitosa.'" /> -->
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ConfirmDeleteModal from "@/Components/ConfirmDeleteModal.vue";
import SuccessOperationModal from "@/Components/SuccessOperationModal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import { ref, watch } from "vue";
import { Head, useForm, router, Link } from "@inertiajs/vue3";
import { TrashIcon, PencilSquareIcon, ServerIcon } from "@heroicons/vue/24/outline";
import { formattedDate } from "@/utils/utils";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputFile from "@/Components/InputFile.vue";
import Pagination from "@/Components/Pagination.vue";
import { EyeIcon } from "@heroicons/vue/24/outline";
import TableHeaderFilter from "@/Components/TableHeaderFilter.vue";
import axios from "axios";
import TextInput from "@/Components/TextInput.vue";
import Dropdown from "@/Components/Dropdown.vue";
import { setAxiosErrors, toFormData } from "@/utils/utils";
import { notify, notifyError, notifyWarning } from "@/Components/Notification";
import { Toaster } from "vue-sonner";
import TableDateFilter from "@/Components/TableDateFilter.vue";
import ChartsAdditionalExpenses from "./ChartsAdditionalExpenses.vue";

const props = defineProps({
    expense: Object,
    providers: Object,
    auth: Object,
    userPermissions: Array,
    cost_center: Object,
    fixedOrAdditional: Boolean,
    type: Number,
    acExpensesAmounts: Array,
    scExpensesAmounts: Array,
});

const expenses = ref(props.expense);
const cicsaAssignation = ref(null);
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

const opNuDateForm = useForm({
    operation_date: '',
    operation_number: '',
})

const create_additional = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);
const showOpNuDatModal = ref(false)

const openCreateAdditionalModal = () => {
    create_additional.value = true;
};

const openEditAdditionalModal = (additional) => {
    Object.assign(form, additional)
    create_additional.value = true;
};

const closeModal = () => {
    form.clearErrors()
    form.reset();
    create_additional.value = false;
};

const openOpNuDaModal = () => {
    if (actionForm.value.ids.length === 0) {
        notifyWarning("No hay registros seleccionados");
        return;
    }
    showOpNuDatModal.value = true
}

const closeOpNuDatModal = () => {
    showOpNuDatModal.value = false
    // isFetching.value = false
    opNuDateForm.reset()
    opNuDateForm.clearErrors()
}

async function submit() {
    const url = route('pext.expenses.storeOrUpdate', { 'expense_id': form.id ?? null })
    try {
        const formData = toFormData(form)
        const response = await axios.post(url, formData);
        const action = form.id ? "update" : "create"
        updateExpense(response.data, action)
        closeModal();
    } catch (error) {
        console.log(error)
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, form)
            } else {
                notifyError('Server Error', error.response.data)
            }
        } else {
            notifyError("Network or other error:", error)
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

async function deleteAdditional() {
    const docId = docToDelete.value;
    const url = route("pext.expenses.delete", {
        expense_id: docId,
    })
    try {
        await axios.delete(url)
        closeModalDoc()
        updateExpense(docId, "delete")
    } catch (e) {
        console.log(e)
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
        route("projectmanagement.pext.expenses.image.show", { expense_id: id }) +
        "?" +
        uniqueParam,
        "_blank"
    );
}

const initialExpenseFixed = [
    'Alquiler de Vehículos',
    'Alquiler de Locales',
    'Combustible',
    'Celulares',
    'Terceros',
    'Viáticos',
    'Seguros y Pólizas',
    'Gastos de Representación',
    'Reposición de Equipo',
    'Herramientas',
    'Equipos',
    'EPPs',
    'Adicionales',
    'Daños de Vehículos',
    'Planilla',
    'Otros',
    'Adicionales',
    'Daños de Vehículos',
    'Planilla',
    'Otros',
]

const initialExpenseAdditional = [
    "Hospedaje",
    "Mensajería",
    "Consumibles",
    "Pasaje Interprovincial",
    "Taxis y Pasajes",
    "Bandeos",
    "Peaje",
    "Herramientas",
    "Equipos",
    "EPPs",
    "Seguros y Pólizas",
    "Otros",
]

const expenseTypes = props.fixedOrAdditional
    ? initialExpenseFixed
    : initialExpenseAdditional;
// const costCenter = props.cost_center.map(item => item.name)

const zones = [
    "Arequipa",
    "Moquegua",
    "Tacna",
    "Cuzco",
    "Puno",
    "MDD"
];

const docTypes = [
    "Efectivo",
    "Deposito",
    "Factura",
    "Boleta",
    "Ticket",
    "Yape-Plin"
];

const stateTypes = [
    "Pendiente",
    "Aceptado",
    "Aceptado - Validado",
];

const initialFilterFormState = {
    fixedOrAdditional: props.fixedOrAdditional,
    rejected: true,
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
}

const filterForm = ref({
    ...initialFilterFormState
});

watch(() => form.zone, () => {
    getProject()
})

async function getProject() {
    let url = route('pext.additional.expense.general.getCicsaAssignation')
    let data = { type: props.type, zone: form.zone}
    try {
        let response = await axios.post(url, data);
        cicsaAssignation.value = response.data
    } catch (error) {
        notifyError(error)
    }
}

watch(() => [
    filterForm.value.fixedOrAdditional,
    filterForm.value.rejected,
    filterForm.value.search,
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
],
    () => {
        search_advance(filterForm.value);
    }
);

async function search_advance(data) {
    let url = route("pext.monthly.additional.expense.general.search_advance")
    try {
        let response = await axios.post(url, data);
        console.log(response.data)
        expenses.value = response.data;
        notifyWarning(`Se encontraron ${response.data.length} registro(s)`);
    } catch (error) {
        console.error('Error en la solicitud:', error);
    }
}

function openExportExcel() {
    const uniqueParam = `timestamp=${new Date().getTime()}`;
    const url =
        route("projectmanagement.pext.expenses.general.export", {
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

// const submitOpNuDatModal = async () => {
//     // isFetching.value = true;
//     const res = await axios
//         .post(route("projectmanagement.pext.massiveUpdate"), {
//             ...opNuDateForm.data(),
//             ...actionForm.value
//         })
//         .catch((e) => {
//             // isFetching.value = false;
//             if (e.response?.data?.errors) {
//                 setAxiosErrors(e.response.data.errors, opNuDateForm);
//             } else {
//                 notifyError("Server Error");
//             }
//         });

//     const originalMap = new Map(dataToRender.value.map(item => [item.id, item]));
//     res.data.forEach(update => {
//         if (originalMap.has(update.id)) {
//             originalMap.set(update.id, update);
//         }
//     });
//     const updatedArray = Array.from(originalMap.values());
//     dataToRender.value = updatedArray
//     closeOpNuDatModal();
//     notify("Registros Seleccionados Actualizados");
// }

async function submitOpNuDatModal() {
    let url = route("projectmanagement.pext.massiveUpdate")
    try {
        let response = await axios.post(url, {
            ...opNuDateForm.data(),
            ...actionForm.value
        })
        updateExpense(response.data, 'masiveUpdate')
    } catch (error) {
        // isFetching.value = false;
        if (error.response?.data?.errors) {
            setAxiosErrors(error.response.data.errors, opNuDateForm);
        } else {
            notifyError("Server Error");
        }
    }
}

function updateExpense(expense, action, state) {
    let listDate = expenses.value.data || expenses.value
    if (action === "create") {
        listDate.unshift(expense)
        notify('Gasto Creado')
    } else if (action === "update") {
        let index = listDate.findIndex(item => item.id == expense.id)
        listDate[index] = expense
        notify('Gasto Actualizado')
    } else if (action === "delete") {
        let index = listDate.findIndex(item => item.id == expense)
        listDate.splice(index, 1);
        notify('Gasto Eliminado')
    } else if (action === "validate") {
        let index = listDate.findIndex(item => item.id == expense)
        if (state) {
            listDate[index].is_accepted = state;
            notify('Gasto Aceptado')
        } else {
            listDate.splice(index, 1);
            notify('Gasto Rechazado')
        }
    } else if (action === "rejectedValidate") {
        let index = listDate.findIndex(item => item.id == expense)
        listDate.splice(index, 1);
        notify('El gasto paso a ser aceptado')
    } else if (action === "masiveUpdate") {
        expense.forEach(update => {
            const index = listDate.findIndex(item => item.id === update.id);
            if (index !== -1) {
                listDate[index] = update;
            }
        });
        closeOpNuDatModal();
        notify("Registros Seleccionados Actualizados");
    }
}

</script>
