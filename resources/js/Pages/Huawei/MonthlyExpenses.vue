<template>

    <Head title="Gestion de Costos" />
    <AuthenticatedLayout redirectRoute="huawei.monthlyprojects">
        <template #header>
            Gastos del Proyecto
        </template>
        <br />
        <Toaster richColors />
        <div class="inline-block min-w-full mb-4">
            <div class="flex gap-4 justify-between">
                <div class="hidden sm:flex sm:items-center space-x-3">
                    <PrimaryButton @click="openCreateAdditionalModal"
                        type="button" class="whitespace-nowrap">
                        + Agregar
                    </PrimaryButton>
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
                </div>

                <div class="sm:hidden">
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
                                    <button @click="openCreateAdditionalModal"
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
                            </div>
                        </template>
                    </dropdown>
                </div>
                <div class="flex items-center justify-center sm:justify-end w-full sm:w-auto mt-4 sm:mt-0">
                    <form @submit.prevent="search" class="flex items-center w-full sm:w-auto">
                        <TextInput type="text" placeholder="Buscar..." v-model="searchForm.searchTerm" class="mr-2" />
                        <button type="submit" :class="{ 'opacity-25': searchForm.processing }"
                            class="ml-2 rounded-md bg-indigo-600 px-2 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            <svg width="30px" height="21px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                                    stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto h-[85vh]">
            <table class="w-full">
                <thead class="sticky top-0 z-20">
                    <tr
                        class=" border-b bg-gray-50 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">

                        <th class="bg-gray-100 border-b-2 border-gray-20">
                            <div class="w-2"></div>
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Proyecto
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableHeaderFilter labelClass="text-[11px]" label="Tipo de Gasto" :options="expenseTypes"
                                v-model="filterForm.selectedExpenseTypes" width="w-48" />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableHeaderFilter labelClass="text-[11px]" label="Zona" :options="zones"
                                v-model="filterForm.selectedZones" width="w-32" />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableHeaderFilter labelClass="text-[11px]" label="Empleado" :options="employees"
                                v-model="filterForm.selectedEmployees" width="w-32" />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableHeaderFilter labelClass="text-[11px]" label="Comprobante de Pago" :options="cdp_types"
                                v-model="filterForm.selectedCDPTypes" width="w-32" />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Fecha de Gasto
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Número de Documento
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Numero de Operacion
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            RUC
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Descripción del Gasto
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Monto
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Imagen 1
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Imagen 2
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Imagen 3
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Fecha de Depósito E.C.
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            N° de Operación de E.C.
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Monto en E.C.
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Estado de Reembolso
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Estado
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in (props.search ? expenses : expenses.data)" :key="item.id" class="text-gray-700">
                        <td :class="[
                            'border-b border-gray-200',
                            {
                                'bg-indigo-500': item.is_accepted === null,
                                'bg-green-500': item.is_accepted == true,
                                'bg-red-500': item.is_accepted == false,
                            },
                        ]">
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            {{ props.project.description }}
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            <p class="w-48 break-words">
                                {{ item.expense_type }}
                            </p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            {{ item.zone }}
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            {{ item.employee }}
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            {{ item.cdp_type }}
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums">
                            {{ formattedDate(item.expense_date) }}
                        </td>
                        <td class="border-b whitespace-nowrap border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            {{ item.doc_number }}
                        </td>
                        <td class="border-b whitespace-nowrap border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums">
                            {{ item.op_number }}
                        </td>
                        <td
                            class="border-b whitespace-nowrap border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap">
                            {{ item.ruc }}
                        </td>
                        <td class="border-b whitespace-nowrap border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums">
                            {{ item.description }}
                        </td>
                        <td class="border-b whitespace-nowrap border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            S/. {{ item.amount.toFixed(2) }}
                        </td>

                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] whitespace-nowrap">
                            <button @click="openPreviewDocumentModal(item.id, '1')"
                                class="flex items-center justify-center w-full">
                                <EyeIcon class="h-5 w-5 text-green-400" />
                            </button>
                        </td>

                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] whitespace-nowrap">
                            <button @click="openPreviewDocumentModal(item.id, '2')"
                                class="flex items-center justify-center w-full">
                                <EyeIcon class="h-5 w-5 text-green-400" />
                            </button>
                        </td>

                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] whitespace-nowrap">
                            <button @click="openPreviewDocumentModal(item.id, '3')"
                                class="flex items-center justify-center w-full">
                                <EyeIcon class="h-5 w-5 text-green-400" />
                            </button>
                        </td>

                        <td
                            class="border-b whitespace-nowrap border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap">
                            {{ formattedDate(item.ec_expense_date) }}
                        </td>
                        <td
                            class="border-b whitespace-nowrap border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap">
                            {{ item.ec_op_number }}
                        </td>
                        <td
                            class="border-b whitespace-nowrap border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap">
                            {{ item.ec_amount ? "S/. " + item.ec_amount.toFixed(2) : ''}}
                        </td>
                        <td
                            class="border-b whitespace-nowrap border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap">
                            {{ item.refund_status }}
                        </td>
                        <td
                            class="border-b whitespace-nowrap border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap">
                            {{ item.is_accepted === 1 ? 'Aceptado' : (item.is_accepted === null ? 'Pendiente' : 'Rechazado') }}
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            <div class="flex items-center gap-3 w-full justify-center">
                                <div v-if="item.is_accepted === null" class="flex gap-3 justify-center w-1/2">
                                    <button @click="() => validateRegister(item.id, true)"
                                        class="flex items-center rounded-xl text-blue-500 hover:bg-green-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                    <button @click="() => validateRegister(item.id, false)" type="button"
                                        class="rounded-xl whitespace-no-wrap text-center text-sm text-red-900 hover:bg-red-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </div>
                                <div v-else class="w-1/2"></div>

                                <div class="flex gap-3 justify-center w-1/2">
                                    <button @click="openEditAdditionalModal(item)"
                                        class="text-amber-600 hover:underline">
                                        <PencilSquareIcon class="h-5 w-5 ml-1" />
                                    </button>
                                    <button @click="confirmDeleteAdditional(item.id)"
                                        class="text-red-600 hover:underline">
                                        <TrashIcon class="h-5 w-5" />
                                    </button>
                                </div>
                            </div>
                        </td>

                    </tr>
                    <tr class="sticky bottom-0 z-10 text-gray-700">
                        <td class="font-bold border-b border-gray-200 bg-white">
                        </td>
                        <td class="font-bold border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            TOTAL
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" colspan="9"></td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                            S/.
                            {{
                                expenses.data
                                    ?.reduce((a, item) => a + item.amount, 0)
                                    .toFixed(2)
                            }}
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" colspan="5"></td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                            S/.
                            {{
                                expenses.data
                                    ?.reduce((a, item) => a + item.ec_amount, 0)
                                    .toFixed(2)
                            }}
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" colspan="3"></td>

                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="!filterMode && !props.search"
            class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
            <pagination :links="expenses.links" />
        </div>
        <Modal :show="create_additional" @close="closeModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Agregar Gasto
                </h2>
                <form @submit.prevent="form.id ? submit(true) : submit(false)">
                    <div class="space-y-12 mt-4">
                        <div class="grid sm:grid-cols-2 gap-6 pb-6">
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
                                <InputLabel for="zone" class="font-medium leading-6 text-gray-900">Zona</InputLabel>
                                <div class="mt-2">
                                    <select v-model="form.zone" id="zone"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">
                                            Seleccionar Zona
                                        </option>
                                        <option v-for="op in zones">{{ op }}</option>
                                    </select>
                                    <InputError :message="form.errors.zone" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="employee" class="font-medium leading-6 text-gray-900">Empleado
                                </InputLabel>
                                <div class="mt-2">
                                    <select v-model="form.employee" id="employee"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">
                                            Seleccionar Empleado
                                        </option>
                                        <option v-for="emp in employees">{{ emp }}</option>
                                    </select>
                                    <InputError :message="form.errors.employee" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="cdp_type" class="font-medium leading-6 text-gray-900">Tipo de CDP
                                </InputLabel>
                                <div class="mt-2">
                                    <select v-model="form.cdp_type" id="cdp_type"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">
                                            Seleccionar Tipo de CDP
                                        </option>
                                        <option v-for="cdp in cdp_types">{{ cdp }}</option>
                                    </select>
                                    <InputError :message="form.errors.cdp_type" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="expense_date" class="font-medium leading-6 text-gray-900">Fecha de Gasto
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="date" v-model="form.expense_date" id="expense_date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.expense_date" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="doc_number" class="font-medium leading-6 text-gray-900">Número de
                                    Documento
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.doc_number" id="doc_number"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.doc_number" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="op_number" class="font-medium leading-6 text-gray-900">Número de
                                    Operación
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.op_number" id="op_number"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.op_number" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="ruc" class="font-medium leading-6 text-gray-900">RUC
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" maxlength="11" v-model="form.ruc" id="ruc"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.ruc" />
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

                            <div>
                                <InputLabel for="amount" class="font-medium leading-6 text-gray-900">Monto</InputLabel>
                                <div class="mt-2">
                                    <input type="number" step="0.0001" v-model="form.amount" id="amount"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.amount" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="refund_status" class="font-medium leading-6 text-gray-900">Estado de Reembolso</InputLabel>
                                <div class="mt-2">
                                    <select v-model="form.refund_status" id="refund_status"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">
                                            Seleccionar Estado de Reembolso
                                        </option>
                                        <option>PAGADO</option>
                                        <option>RECHAZADO</option>
                                        <option>PENDIENTE</option>
                                    </select>
                                    <InputError :message="form.errors.refund_status" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="ec_expense_date" class="font-medium leading-6 text-gray-900">Fecha de Depósito de E.C.</InputLabel>
                                <div class="mt-2">
                                    <input type="date" v-model="form.ec_expense_date" id="ec_expense_date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.ec_expense_date" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="ec_op_number" class="font-medium leading-6 text-gray-900">N° de Operación de E.C.</InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.ec_op_number" id="ec_op_number"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.ec_op_number" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="ec_amount" class="font-medium leading-6 text-gray-900">Monto de E.C.</InputLabel>
                                <div class="mt-2">
                                    <input type="number" step="0.0001" v-model="form.ec_amount" id="ec_amount"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.ec_amount" />
                                </div>
                            </div>

                            <div>
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    Imagen 1
                                </InputLabel>
                                <div class="mt-2">
                                    <InputFile type="file" v-model="form.image1" accept=".jpeg, .jpg, .png, .pdf"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.image1" />
                                </div>
                            </div>
                            <div>
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    Imagen 2
                                </InputLabel>
                                <div class="mt-2">
                                    <InputFile type="file" v-model="form.image2" accept=".jpeg, .jpg, .png, .pdf"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.image2" />
                                </div>
                            </div>
                            <div>
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    Imagen 3
                                </InputLabel>
                                <div class="mt-2">
                                    <InputFile type="file" v-model="form.image3" accept=".jpeg, .jpg, .png, .pdf"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.image3" />
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

        <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Gasto"
            :deleteFunction="deleteAdditional" @closeModal="closeModalDoc" />
        <SuccessOperationModal :confirming="confirmValidation" :title="'Validación'"
            :message="'La validación del gasto fue exitosa.'" />
        <SuccessOperationModal :confirming="showSuccessModal" :title="'Éxito'"
            :message="successMessage" />
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
import { Head, useForm, router } from "@inertiajs/vue3";
import { TrashIcon, PencilSquareIcon } from "@heroicons/vue/24/outline";
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
import { notify, notifyError } from "@/Components/Notification";
import { Toaster } from "vue-sonner";

const props = defineProps({
    expense: Object,
    project: Object,
    search: String
});

const expenses = ref(props.expense);
const filterMode = ref(false);
const showSuccessModal = ref(false);
const successMessage = ref('');

const form = useForm({
    id: "",
    expense_type: "",
    zone: "",
    employee: "",
    expense_date: "",
    cdp_type: "",
    doc_number: "",
    op_number: "",
    ruc: "",
    description: "",
    amount: "",
    image1: '',
    image2: '',
    image3: '',
    refund_status: "",
    ec_expense_date: '',
    ec_op_number: '',
    ec_amount: '',
    huawei_monthly_project_id: props.project.id
});

const create_additional = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);

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

function submit(update) {
    if (!update){
        const url = route('huawei.monthlyexpenses.expenses.store');
        form.post(url, {
            onSuccess: () => {
                closeModal();
                successMessage.value = 'Se creó el registro correctamente';
                showSuccessModal.value = true;
                setTimeout(() => {
                    showSuccessModal.value = false;
                    successMessage.value = '';
                    router.visit(route('huawei.monthlyexpenses.expenses', {project: props.project.id}));
                }, 2000);
            },
            onError: (e) => {
                console.error(e);
            }
        })
    }else{
        const url = route('huawei.monthlyexpenses.expenses.update', {expense: form.id});
        form.post(url, {
            onSuccess: () => {
                closeModal();
                successMessage.value = 'Se actualizó el registro correctamente';
                showSuccessModal.value = true;
                setTimeout(() => {
                    showSuccessModal.value = false;
                    successMessage.value = '';
                    router.visit(route('huawei.monthlyexpenses.expenses', {project: props.project.id}));
                }, 2000);
            },
            onError: (e) => {
                console.error(e);
            }
        })
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
    if (docId) {
    router.delete(route('huawei.monthlyexpenses.expenses.delete', { expense: docId }), {
      onSuccess: () => {
        closeModalDoc();
        router.visit(route('huawei.monthlyexpenses.expenses', {project: props.project.id}));
      }
    });
  }
};

const openPreviewDocumentModal = (expense, img) => {
    const routeToShow = route('huawei.monthlyexpenses.expenses.showimage', {expense: expense, image: img});
    window.open(routeToShow, '_blank');
};

const employees = props.project.huawei_monthly_employees.map(employee => {
    return `${employee.name} ${employee.lastname}`; // Combina nombre y apellido
});

const zones = [
    "DESAGUADERO",
    "HUAWEI",
    "HUAWEI-AQP",
    "HUAWEI-PUNO",
    "HUAWEI-TACNA",
    "HUAWEI-CHALA",
    "HUAWEI-CUSCO",
    "HUAWEI-ILO",
    "HUAWEI-JULIACA",
    "HUAWEI-LA PUNTA",
    "HUAWEI-ORCOPAMPA",
    "HUAWEI-ABANCAY",
    "HUAWEI-BANOSPAMPA",
    "HUAWEI-EL PALOMAR",
    "IP HUAWEI",
    "PDI AQP",
    "PERAL"
];

const expenseTypes = [
    "Combustible",
    "Consumibles",
    "Fletes",
    "Hospedaje",
    "Materiales",
    "Movilidad",
    "Planilla",
    "Herramientas",
    "Otros",
];


const cdp_types = [
    "Efectivo",
    "Depósito",
    "Factura",
    "Boleta",
    "RH",
    "Yape",
    "Pendiente Factura"
];


const filterForm = ref({
    search: "",
    selectedEmployees: employees,
    selectedZones: zones,
    selectedExpenseTypes: expenseTypes,
    selectedCDPTypes: cdp_types
});



watch(() => [
    filterForm.value.search,
    filterForm.value.selectedEmployees,
    filterForm.value.selectedZones,
    filterForm.value.selectedExpenseTypes,
    filterForm.value.selectedCDPTypes,
],
    () => {
        filterMode.value = true,
        search_advance(filterForm.value);
    },
    {deep: true}
);

async function search_advance($data) {
    let url = route("huawei.monthlyexpenses.expenses.searchadvance", {
        project: props.project.id,
    })
    try {
        let response = await axios.post(url, $data);
        expenses.value.data = response.data.expenses;
    } catch (error) {
        console.error('Error en la solicitud:', error);
    }
}


function openExportExcel() {
    const url = route('huawei.monthlyexpenses.expenses.export', {project: props.project.id});
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

const confirmValidation = ref(false);

async function validateRegister(expense_id, is_accepted) {
    const url = route("huawei.monthlyexpenses.expenses.validate", { 'expense': expense_id })
    try {
        await axios.put(url, { 'is_accepted': is_accepted });
        updateExpense(expense_id, "validate", is_accepted)
        confirmValidation.value = true;
        setTimeout(() => {
            confirmValidation.value = false;
        }, 1000);
    } catch (e) {
        console.log(e);
    }
}

function updateExpense(expense, action, state) {
    if (action === "create") {
        expenses.value.data.unshift(expense)
        notify('Gasto Guardado')
    } else if (action === "update") {
        let index = expenses.value.data.findIndex(item => item.id == expense.id)
        expenses.value.data[index] = expense
        notify('Gasto Actualizado')
    } else if (action === "delete") {
        let index = expenses.value.data.findIndex(item => item.id == expense)
        expenses.value.data.splice(index, 1);
        notify('Gasto Eliminado')
    } else if (action === "validate") {
        let index = expenses.value.data.findIndex(item => item.id == expense)
        expenses.value.data[index].is_accepted = state;
    }
}

const searchForm = useForm({
    searchTerm: props.search ? props.search : '',
})

const search = () => {
    if (searchForm.searchTerm == ''){
        router.visit(route('huawei.monthlyexpenses.expenses', {project: props.project.id}))
    }else{
        router.visit(route('huawei.monthlyexpenses.expenses.search', { project: props.project.id, request: searchForm.searchTerm}));
    }
}

</script>
