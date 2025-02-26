<template>

    <Head title="Estado de Cuenta" />
    <AuthenticatedLayout :redirectRoute="{
        route: 'finance.account_statement',
    }">
        <template #header> Estado de Cuenta </template>
        <Toaster richColors />

        <div class="inline-block min-w-full gap-10">
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-0 justify-between">
                <div class="flex sm:items-center space-x-3">
                    <PrimaryButton v-if="hasPermission('FinanceManager')" @click="openFormModal" type="button"
                        class="whitespace-nowrap">
                        + Agregar
                    </PrimaryButton>
                    <!-- <div>
                        <button data-tooltip-target="all_register_tooltip" type="button" @click="() => {
                            isFetchingAll = true
                            filterForm = { ...initialFilterFormState,month: '', search: '' }
                            handleSearch(null, true);
                        }
                            " class="p-2 bg-gray-100 ring-1 ring-slate-400 rounded-md text-slate-900 hover:bg-white">
                            <ServerIcon class="h-5 w-5 font-bold" />
                        </button>
                        <div id="all_register_tooltip" role="tooltip"
                            class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Todos los Registros
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div> -->
                    <div>
                        <button data-tooltip-target="import_register_tooltip" type="button" @click="openImportModal"
                            class="p-2 bg-yellow-300 rounded-md text-slate-900 hover:bg-yellow-200">
                            <CloudArrowUpIcon class="text-white h-5 w-5 font-bold" />
                        </button>
                        <div id="import_register_tooltip" role="tooltip"
                            class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Importar Excel
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div>
                    <!-- <div>
                        <button
                            data-tooltip-target="export_register_tooltip"
                            type="button"
                            @click=""
                            class="p-2 bg-green-500 rounded-md text-slate-900 hover:bg-green-400"
                        >
                            <CloudArrowDownIcon
                                class="text-white h-5 w-5 font-bold"
                            />
                        </button>
                        <div
                            id="export_register_tooltip"
                            role="tooltip"
                            class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
                        >
                            Exportar Excel
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div> -->

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
                                        <button @click="handleBlockDelete"
                                            class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-gray-200 hover:text-black focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                            Eliminar
                                        </button>
                                        <!-- <button @click=""
                                            class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-gray-200 hover:text-black focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                            Swap
                                        </button> -->
                                    </div>
                                </div>
                            </template>
                        </dropdown>
                    </div>

                    <div>
                        <button data-tooltip-target="ds_dowload_tooltip" type="button" @click="downloadDataStructure"
                            class="p-2 bg-slate-800 rounded-md text-white-900 hover:bg-slate-700">
                            <CloudArrowDownIcon class="text-white h-5 w-5 font-bold" />
                        </button>
                        <div id="ds_dowload_tooltip" role="tooltip"
                            class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Estructura de Datos
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 items-center w-full sm:w-auto">
                    <div class="flex gap-4 bg-white rounded-md border border-gray-300 h-full">
                        <div
                            class="bg-gray-50 rounded-md border-b border-gray-200 text-[11px] font-semibold uppercase tracking-wider text-gray-600 flex items-center justify-center px-3">
                            <p class="text-center whitespace-nowrap">
                                Comisiones Bancarias
                            </p>
                        </div>
                        <div class="flex items-center justify-center text-[13px] whitespace-nowrap tabular-nums px-3">
                            S/. {{ dataToRender.totalITFM.toFixed(2) }}
                        </div>
                    </div>
                    <input type="month" @input="
                        (e) => {
                            isFetchingAll = true;
                            yearInput = '';
                            filterForm = {
                                ...initialFilterFormState,
                                month: e.target.value,
                            };
                            handleSearch(e.target.value);
                        }
                    " v-model="filterForm.month"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    <select v-model="yearInput"
                        class="block pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option disabled value="">Año</option>
                        <option>2023</option>
                        <option>2024</option>
                        <option>2025</option>
                        <option>2026</option>
                        <option>2027</option>
                    </select>
                    <TextInput data-tooltip-target="search_fields" type="text" placeholder="Buscar..."
                        v-model="filterForm.search" />
                    <div id="search_fields" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Numero de Operación,Fecha de
                        Operación,Descripción,cargo,Abono,Saldo Contable
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-8 grid grid-cols-2 sm:grid-cols-5 gap-2">
            <div class="grid grid-rows-2 h-20 bg-white rounded-md border border-gray-300">
                <div
                    class="bg-gray-50 rounded-md border-b border-gray-200 text-[11px] font-semibold uppercase tracking-wider text-gray-600 flex items-center justify-center">
                    <p class="text-center">Saldo Contable (Inicio)</p>
                </div>
                <div class="flex items-center justify-center text-[13px] whitespace-nowrap tabular-nums">
                    S/. {{ dataToRender.previousBalance.toFixed(2) }}
                </div>
            </div>

            <div class="grid grid-rows-2 h-20 bg-white rounded-md border border-gray-300">
                <div
                    class="bg-gray-50 rounded-md border-b border-gray-200 text-[11px] font-semibold uppercase tracking-wider text-gray-600 flex items-center justify-center">
                    <p class="text-center">Abonos (Depósitos)</p>
                </div>
                <div class="flex items-center justify-center text-[13px] whitespace-nowrap tabular-nums">
                    S/. {{ dataToRender.totalPayment.toFixed(2) }}
                </div>
            </div>
            <div class="grid grid-rows-2 h-20 bg-white rounded-md border border-gray-300">
                <div
                    class="bg-gray-50 rounded-md border-b border-gray-200 text-[11px] font-semibold uppercase tracking-wider text-gray-600 flex items-center justify-center">
                    <p class="text-center">Cargos (Retiros)</p>
                </div>
                <div class="flex items-center justify-center text-[13px] whitespace-nowrap tabular-nums">
                    S/. {{ dataToRender.totalCharge.toFixed(2) }}
                </div>
            </div>
            <div class="grid grid-rows-2 h-20 bg-white rounded-md border border-gray-300">
                <div
                    class="bg-gray-50 rounded-md border-b border-gray-200 text-[11px] font-semibold uppercase tracking-wider text-gray-600 flex items-center justify-center">
                    <p class="text-center">Saldo Contable (Final)</p>
                </div>
                <div class="flex items-center justify-center text-[13px] whitespace-nowrap tabular-nums">
                    S/. {{ dataToRender.currentBalance.toFixed(2) }}
                </div>
            </div>
            <div class="grid grid-rows-2 h-20 bg-white rounded-md border border-gray-300">
                <div
                    class="bg-gray-50 rounded-md border-b border-gray-200 text-[11px] font-semibold uppercase tracking-wider text-gray-600 flex items-center justify-center">
                    <p class="text-center">Abono - Cargos</p>
                </div>
                <div class="flex items-center justify-center text-[13px] whitespace-nowrap tabular-nums" :class="{
                    'text-green-600':
                        dataToRender.totalPayment -
                        dataToRender.totalCharge >
                        0,
                    'text-black-600':
                        dataToRender.totalPayment -
                        dataToRender.totalCharge ===
                        0,
                    'text-red-600':
                        dataToRender.totalPayment -
                        dataToRender.totalCharge <
                        0,
                }">
                    <!-- S/. {{ (dataToRender.balanceMedia).toFixed(2) }} -->
                    S/.
                    {{
                        (
                            dataToRender.totalPayment - dataToRender.totalCharge
                        ).toFixed(2)
                    }}
                </div>
            </div>
        </div>

        <div class="overflow-x-auto h-[60vh] rounded-md border border-gray-300">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="sticky top-0 z-20 border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                        </th>
                        <th
                            class="border-b-2 border-r border-gray-200 bg-gray-100 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600 w-20">
                            <label :for="`check-all`" class="flex gap-3 justify-center w-full px-2 py-1">
                                <input @change="handleCheckAll" :id="`check-all`" :checked="actionForm.ids.length > 0"
                                    type="checkbox" />
                                {{ actionForm.ids.length ?? "" }}
                            </label>
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600 w-46">
                            <TableDateFilter labelClass="text-[11px]" label="Fecha de Operación"
                                v-model:startDate="filterForm.opStartDate" v-model:endDate="filterForm.opEndDate"
                                v-model:noDate="filterForm.opNoDate" width="w-full" />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Número de Operación
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Descripción
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-right text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <div class="flex space-x-1 items-center justify-end">
                                <p>Cargo</p>
                                <button @click="sortValue">
                                    <ArrowsUpDownIcon class="h-5 w-5" />
                                </button>
                            </div>
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-right text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Abono
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-right text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Saldo Contable
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-[11px] font-semibold uppercase tracking-wider text-gray-600 w-40">
                            <TableHeaderFilter labelClass="text-[11px]" label="Estado" :options="stateOptions"
                                v-model="filterForm.stateOptions" width="w-full" />
                        </th>
                        <th v-if="auth.user.role_id === 1"
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-right text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Acciones
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-right text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="item in dataToShow" class="text-gray-700" :key="item.id">
                        <tr class="text-gray-700 bg-white hover:bg-slate-200">
                            <td class="w-2 border-b border-gray-200 text-center text-[13px] whitespace-nowrap tabular-nums"
                                :class="[
                                    item.state === 'Abono' && 'bg-gray-500',
                                    item.state === 'Validado' && 'bg-green-500',
                                    item.state === 'Por validar' &&
                                    'bg-yellow-400',
                                    item.state === 'No validado' &&
                                    'bg-red-500',
                                ]"></td>
                            <td
                                class="border-b border-r border-gray-200 text-center text-[13px] whitespace-nowrap tabular-nums">
                                <label :for="`check-${item.id}`" class="block w-full px-2 py-1">
                                    <input v-model="actionForm.ids" :value="item.id" :id="`check-${item.id}`"
                                        type="checkbox" />
                                </label>
                            </td>
                            <td
                                class="border-b border-gray-200 px-2 py-1 text-center text-[13px] whitespace-nowrap tabular-nums">
                                {{ formattedDate(item.operation_date) }}
                            </td>
                            <td
                                class="border-b border-gray-200 px-2 py-1 text-center text-[13px] whitespace-nowrap tabular-nums">
                                {{ item.operation_number }}
                            </td>
                            <td class="border-b border-gray-200 px-2 py-1 text-center text-[13px] whitespace-nowrap">
                                {{ item.description }}
                            </td>
                            <td
                                class="border-b border-gray-200 px-2 py-1 text-right text-[13px] tabular-nums whitespace-nowrap">
                                {{
                                    item.charge &&
                                    ` S/. ${item.charge.toFixed(2)}`
                                }}
                            </td>
                            <td
                                class="border-b border-gray-200 px-2 py-1 text-right text-[13px] tabular-nums whitespace-nowrap">
                                {{
                                    item.payment &&
                                    ` S/. ${item.payment.toFixed(2)}`
                                }}
                            </td>
                            <td
                                class="border-b border-gray-200 px-2 py-1 text-right text-[13px] tabular-nums whitespace-nowrap">
                                S/.
                                {{ item.balance ? item.balance.toFixed(2) : 0 }}
                            </td>
                            <td class="border-b border-gray-200 px-2 py-1 text-right text-[13px] tabular-nums whitespace-nowrap"
                                :class="[
                                    item.state === 'Abono' && 'text-gray-500',
                                    item.state === 'Validado' &&
                                    'text-green-500',
                                    item.state === 'Por validar' &&
                                    'text-yellow-400',
                                    item.state === 'No validado' &&
                                    'text-red-500',
                                ]">
                                {{ item.state }}
                            </td>
                            <td v-if="auth.user.role_id === 1"
                                class="border-b border-gray-200 px-2 py-1 text-right text-[13px]">
                                <div class="flex items-center justify-end">
                                    <button type="button" @click="openFormModal(item)"
                                        class="rounded-full text-amber-600 hover:bg-amber-300 mr-2">
                                        <PencilSquareIcon class="h-4 w-4 ml-1" />
                                    </button>
                                    <button type="button" @click="openDeleteModal(item.id)"
                                        class="rounded-full text-red-600 hover:bg-red-300">
                                        <TrashIcon class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                            <td class="border-b border-gray-200 px-2 py-1 text-sm">
                                <button type="button" @click="toggleDetails(item.id)"
                                    :class="`flex items-center text-blue-900 rounded-full hover:bg-blue-300`">
                                    <svg v-if="row !== item.id" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" :class="`w-4 h-4`">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                    </svg>
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <template v-if="row == item.id">
                            <tr class="bg-white h-16">
                                <td colspan="11" class="py-1 px-2">
                                    <table class="w-full">
                                        <thead>
                                            <tr
                                                class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600">
                                                    Zona
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600">
                                                    Tipo de Gasto
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600">
                                                    Ubicación
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-right text-[9px] font-semibold uppercase tracking-wider text-gray-600">
                                                    Monto
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="text-gray-700" v-for="(
item, i
                                                ) in costsFounded.scData" :key="i">
                                                <td
                                                    class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]">
                                                    {{ item.zone }}
                                                </td>
                                                <td
                                                    class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]">
                                                    {{ item.expense_type }}
                                                </td>
                                                <td
                                                    class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]">
                                                    {{ item.project.name }}
                                                </td>
                                                <td
                                                    class="border-b border-gray-200 bg-white px-1 py-1 text-right text-[12px] tabular-nums">
                                                    S/.
                                                    {{ item.amount.toFixed(2) }}
                                                </td>
                                            </tr>
                                            <tr class="text-gray-700" v-for="(
item, i
                                                ) in costsFounded.geData" :key="i">
                                                <td
                                                    class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]">
                                                    {{ item.zone }}
                                                </td>
                                                <td
                                                    class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]">
                                                    {{ item.expense_type }}
                                                </td>
                                                <td
                                                    class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]">
                                                    {{ item.location }}
                                                </td>
                                                <td
                                                    class="border-b border-gray-200 bg-white px-1 py-1 text-right text-[12px] tabular-nums">
                                                    S/. {{ item.amount }}
                                                </td>
                                            </tr>
                                            <tr class="text-gray-700">
                                                <td
                                                    class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]">
                                                    Total:
                                                </td>
                                                <td
                                                    class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]">
                                                </td>
                                                <td
                                                    class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]">
                                                </td>
                                                <td
                                                    class="border-b border-gray-200 bg-white px-1 py-1 text-[12px] tabular-nums text-right font-medium">
                                                    S/.
                                                    {{
                                                        costsFounded.geData
                                                            .reduce(
                                                                (a, b) =>
                                                                    a +
                                                                    b.amount,
                                                                0
                                                            )
                                                            .toFixed(2)
                                                    }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </template>
                    </template>
                </tbody>
            </table>
        </div>

        <Modal :show="showFormModal" @close="closeFormModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{
                        form.id
                            ? "Editar Estado de Cuenta"
                            : "Añadir Estado de Cuenta"
                    }}
                </h2>
                <form @submit.prevent="submit">
                    <div class="space-y-12 mt-4">
                        <div class="border-b grid sm:grid-cols-2 gap-6 border-gray-900/10 pb-12">
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
                                <InputLabel for="operation_number" class="font-medium leading-6 text-gray-900">Numero de
                                    Operación
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.operation_number" id="operation_number"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.operation_number" />
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <div v-if="costsFounded.geData.length > 0">
                                    <p class="text-sm font-medium leading-6 text-gray-600">
                                        Registros coincidentes ({{
                                            costsFounded.geData.length
                                        }})
                                    </p>
                                    <div class="rounded-md border border-gray-300 overflow-auto max-h-40">
                                        <table class="w-full">
                                            <thead>
                                                <tr
                                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600">
                                                        Zona
                                                    </th>
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600">
                                                        Tipo de Gasto
                                                    </th>
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600">
                                                        Ubicación
                                                    </th>
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600">
                                                        Monto
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="text-gray-700" v-for="(
item, i
                                                    ) in costsFounded.geData" :key="i">
                                                    <td
                                                        class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]">
                                                        {{ item.zone }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]">
                                                        {{ item.expense_type }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]">
                                                        {{ item.location }}
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px] tabular-nums">
                                                        S/. {{ item.amount }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div v-else>
                                    <p class="text-sm font-medium leading-6 text-gray-600">
                                        No hay registros coincidentes
                                    </p>
                                </div>
                                <InputError :message="form.errors.geData" />
                            </div>

                            <div>
                                <InputLabel for="description" class="font-medium leading-6 text-gray-900">Descripción
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.description" id="description"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.description" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="charge" class="font-medium leading-6 text-gray-900">Cargo</InputLabel>
                                <div class="mt-2">
                                    <input type="number" step="0.0001" v-model="form.charge" id="charge"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.charge" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="payment" class="font-medium leading-6 text-gray-900">Abono</InputLabel>
                                <div class="mt-2">
                                    <input type="number" step="0.0001" v-model="form.payment" id="payment"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.payment" />
                                </div>
                            </div>
                            <!-- <div>
                                <InputLabel
                                    for="balance"
                                    class="font-medium leading-6 text-gray-900"
                                    >Saldo Contable</InputLabel
                                >
                                <div class="mt-2">
                                    <input
                                        type="number"
                                        step="0.0001"
                                        v-model="form.balance"
                                        id="balance"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError
                                        :message="form.errors.balance"
                                    />
                                </div>
                            </div> -->
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeFormModal">
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

        <Modal :show="showImportModal" @close="closeImportModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Importar Excel
                </h2>
                <form @submit.prevent="submitImport">
                    <div class="space-y-12 mt-4">
                        <div class="border-b grid sm:grid-cols-2 gap-6 border-gray-900/10 pb-12">
                            <div>
                                <InputLabel for="excel_file" class="font-medium leading-6 text-gray-900">Archivo Excel
                                </InputLabel>
                                <div class="mt-2">
                                    <InputFile type="file" v-model="importForm.excel_file" id="excel_file"
                                        accept=".xlsx"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="importForm.errors.excel_file" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeImportModal">
                                Cancelar
                            </SecondaryButton>
                            <button type="submit" :disabled="isFetching" :class="{ 'opacity-25': isFetching }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Importar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <ConfirmDeleteModal :confirmingDeletion="showDeleteModal" itemType="Estado de Cuenta"
            :deleteFunction="deleteAccountStatement" @closeModal="closeDeleteModal" :processing="isFetching" />
        <DeleteOperationModal :confirmingDeletion="showMasiveDeleteModal" title="ELIMINACIÓN MASIVA"
            message="Todos los registros seleccionados se eliminarán" :deleteFunction="deleteMasiveAS"
            @closeModal="closeMasiveDeleteModal" :processing="isFetching" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ConfirmDeleteModal from "@/Components/ConfirmDeleteModal.vue";
import { Head, useForm } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DeleteOperationModal from "@/Components/DeleteOperationModal.vue";
import TableHeaderFilter from "@/Components/TableHeaderFilter.vue";
import TableDateFilter from "@/Components/TableDateFilter.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputFile from "@/Components/InputFile.vue";
import TextInput from "@/Components/TextInput.vue";
import Dropdown from "@/Components/Dropdown.vue";
import Modal from "@/Components/Modal.vue";
import { formattedDate, setAxiosErrors } from "@/utils/utils";
import { notify, notifyError, notifyWarning } from "@/Components/Notification";
import { Toaster } from "vue-sonner";
import { ref, watch } from "vue";
import {
    ServerIcon,
    CloudArrowUpIcon,
    CloudArrowDownIcon,
    TrashIcon,
    PencilSquareIcon,
    ArrowsUpDownIcon,
} from "@heroicons/vue/24/outline";

const {
    accountStatements,
    previousBalance,
    currentBalance,
    totalCharge,
    totalITFM,
    totalPayment,
    balanceMedia,
    auth,
    userPermissions,
} = defineProps({
    accountStatements: Object,
    previousBalance: Number,
    currentBalance: Number,
    balanceMedia: Number,
    totalCharge: Number,
    totalPayment: Number,
    totalITFM: Number,
    auth: Object,
    userPermissions: Array,
});

const now = new Date();
const defaultMonth = now.toISOString().slice(0, 7);
const hasPermission = (per) => {
    return userPermissions.includes(per);
};
const initStateCostsFounded = {
    geData: [],
};
const stateOptions = ["Abono", "Validado", "Por validar", "No validado"];
const dataToRender = ref({
    accountStatements,
    previousBalance,
    currentBalance,
    balanceMedia,
    totalCharge,
    totalPayment,
    totalITFM,
});
const costsFounded = ref(initStateCostsFounded);
const initialFilterFormState = {
    month: defaultMonth,
    search: "",
    stateOptions: stateOptions,
    opStartDate: "",
    opEndDate: "",
    opNoDate: false,
};
const isFetchingAll = ref(false);
const filterForm = ref({ ...initialFilterFormState });
const form = useForm({
    id: null,
    operation_date: "",
    operation_number: "",
    description: "",
    charge: "",
    payment: "",
    geData: [],
});
const importForm = useForm({
    excel_file: null,
});
const actionForm = ref({
    ids: [],
});
const showFormModal = ref(false);
const showImportModal = ref(false);
const showDeleteModal = ref(false);
const showMasiveDeleteModal = ref(false);
const isFetching = ref(false);
const asToDeleteId = ref(null);
const dataToShow = ref(accountStatements);
const row = ref(0);
const stateSort = ref(false);

//Edit and create
function openFormModal(item = null) {
    row.value = 0;
    showFormModal.value = true;
    costsFounded.value = initStateCostsFounded;
    if (item) {
        form.id = item.id;
        form.operation_date = item.operation_date;
        form.operation_number = item.operation_number;
        form.description = item.description;
        form.charge = item.charge;
        form.payment = item.payment;
    }
}

function closeFormModal() {
    showFormModal.value = false;
    form.clearErrors();
    form.reset();
    isFetching.value = false;
    costsFounded.value = initStateCostsFounded;
}

async function submit() {
    isFetching.value = true;
    const res = await axios
        .post(route("finance.account_statement.store", { as_id: form.id }), {
            ...form.data(),
            month: filterForm.value.month,
            endMonth: null,
            all: filterForm.value.month ? false : true,
        })
        .catch((e) => {
            isFetching.value = false;
            if (e.response?.data?.errors) {
                setAxiosErrors(e.response.data.errors, form);
            } else {
                notifyError("Server Error");
            }
        });
    dataToRender.value = res.data.dataToRender;
    closeFormModal();
    notify("Estado de Cuenta Guardado");
}

//search month
const handleSearch = async (month = null, endMonth = null, all = null) => {
    const res = await axios
        .get(
            route("finance.account_statement.search", { month, endMonth, all })
        )
        .catch((e) => console.error(e));
    notifyWarning(`Registros Encontrados ${res.data.accountStatements.length}`);
    dataToRender.value = res.data;
};

//search costs
async function searchCosts(data) {
    const res = await axios.get(route("finance.search_costs", data));
    return res.data;
}

//search in data that was obtained
function handleSearchClient() {
    let filterData = dataToRender.value.accountStatements.filter((item, i) => {
        let condition = true;
        if (filterForm.value.search) {
            let search = filterForm.value.search.toLowerCase();
            condition =
                condition &&
                ((item.operation_number
                    ? item.operation_number.toLowerCase().includes(search)
                    : false) ||
                    (item.description
                        ? item.description
                            .toString()
                            .toLowerCase()
                            .includes(search)
                        : false) ||
                    (item.charge
                        ? item.charge.toString().toLowerCase().includes(search)
                        : false) ||
                    (item.payment
                        ? item.payment.toString().toLowerCase().includes(search)
                        : false) ||
                    (item.operation_date
                        ? formattedDate(item.operation_date).includes(search)
                        : false) ||
                    (item.balance
                        ? item.balance.toString().toLowerCase().includes(search)
                        : false));
        }
        if (filterForm.value.stateOptions) {
            let stateOptions = filterForm.value.stateOptions;
            condition = condition && stateOptions.includes(item.state);
        }
        if (filterForm.value.opNoDate) {
            condition =
                condition &&
                (item.operation_date === null ||
                    item.operation_date === undefined);
        }
        if (!filterForm.value.opNoDate && item.operation_date) {
            const itemOpDate = new Date(item.operation_date);
            let startDate = filterForm.value.opStartDate;
            let endDate = filterForm.value.opEndDate;
            if (startDate) {
                const start = new Date(startDate);
                condition = condition && itemOpDate >= start;
            }
            if (endDate) {
                const end = new Date(endDate);
                condition = condition && itemOpDate <= end;
            }
        }

        //add filter another one
        return condition;
    });
    dataToShow.value = filterData;
}

function sortValue() {
    if (stateSort.value) {
        dataToShow.value.sort((a, b) => a.charge - b.charge);
    } else {
        dataToShow.value.sort((a, b) => b.charge - a.charge);
    }
    stateSort.value = !stateSort.value;
}

//delete
const openDeleteModal = (id) => {
    showDeleteModal.value = true;
    asToDeleteId.value = id;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    asToDeleteId.value = null;
    isFetching.value = false;
};

const deleteAccountStatement = async () => {
    isFetching.value = true;
    const res = await axios
        .post(
            route("finance.account_statement.delete", {
                as_id: asToDeleteId.value,
            }),
            {
                month: filterForm.value.month,
                endMonth: null,
                all: filterForm.value.month ? false : true,
            }
        )
        .catch((e) => {
            isFetching.value = false;
            notifyError("Server Error");
        });
    dataToRender.value = res.data.dataToRender;
    closeDeleteModal();
    notify("Registro Eliminado");
};

//import excel
const openImportModal = () => {
    showImportModal.value = true;
};

const closeImportModal = () => {
    showImportModal.value = false;
    importForm.clearErrors();
    isFetching.value = false;
};

async function submitImport() {
    isFetching.value = true;
    try {
        const formData = new FormData();
        formData.append("excel_file", importForm.excel_file);
        await axios.post(route("finance.account_statement.import"), formData);
        closeImportModal();
        handleSearch(filterForm.value.month);
        notify("Datos Importados");
    } catch (e) {
        if (e.response?.data?.errors) {
            setAxiosErrors(e.response.data.errors, importForm);
        } else {
            notifyError(e?.response.data.error);
        }
    } finally {
        isFetching.value = false;
    }
}

const toggleDetails = async (id) => {
    if (row.value == 0 || row.value != id) await handleExpansible(id);
    if (row.value === costsFounded.value?.geData[0]?.account_statement_id) {
        row.value = 0;
    } else {
        row.value = 0;
        if (costsFounded.value?.geData.length > 0) {
            row.value = costsFounded.value?.geData[0].account_statement_id;
        }
    }
};

const handleExpansible = async (id) => {
    const res = await axios
        .get(route("finance.account_statement.costs", { as_id: id }))
        .catch((e) => {
            notifyError("Server Error");
        });
    costsFounded.value = res.data;
    notifyWarning(`Gastos Encontrados ${costsFounded.value.geData.length}`);
};

//block actions

const handleCheckAll = (e) => {
    if (e.target.checked) {
        actionForm.value.ids = dataToShow.value.map((item) => item.id);
    } else {
        actionForm.value.ids = [];
    }
};

//delete block

const handleBlockDelete = () => {
    if (actionForm.value.ids.length === 0) {
        notifyWarning("No hay registros selccionados");
        return;
    }
    showMasiveDeleteModal.value = true;
};

const closeMasiveDeleteModal = () => {
    showMasiveDeleteModal.value = false;
    isFetching.value = false;
    actionForm.value.ids = [];
};

const deleteMasiveAS = async () => {
    isFetching.value = true;
    const res = await axios
        .post(route("finance.account_statement.masivedelete"), {
            ...actionForm.value,
            month: filterForm.value.month,
            all: filterForm.value.month ? false : true,
        })
        .catch((e) => {
            isFetching.value = false;
            notifyError("Server Error");
        });
    dataToRender.value = res.data.dataToRender;
    closeMasiveDeleteModal();
    notify("Registros Seleccionados Eliminados");
};

const downloadDataStructure = () => {
    const uniqueParam = `timestamp=${new Date().getTime()}`;
    const url =
        route("finance.account_statement.dsdownload") + "?" + uniqueParam;
    window.location.href = url;
};

//search for costs in all tables
watch([() => form.operation_number, () => form.operation_date], async () => {
    if (form.operation_date && form.operation_number) {
        const res = await searchCosts({
            operation_date: form.operation_date,
            operation_number: form.operation_number,
        });
        costsFounded.value = res;
        form.geData = res.geData.map((val) => val?.id);
    }
});

//to no charge and payment at the same time
watch(
    () => [form.payment],
    () => {
        if (form.payment) {
            form.charge = "";
        }
    }
);
watch(
    () => [form.charge],
    () => {
        if (form.charge) {
            form.payment = "";
        }
    }
);

//to handle change in filterform.values except month
watch(
    () => [
        filterForm.value.search,
        filterForm.value.stateOptions,
        filterForm.value.opStartDate,
        filterForm.value.opEndDate,
        filterForm.value.opNoDate,
    ],
    () => {
        if (!isFetchingAll.value) {
            handleSearchClient();
            notifyWarning(`Registros Encontrados ${dataToShow.value.length}`);
        } else {
            isFetchingAll.value = false;
        }
    }
);
watch(
    () => dataToRender.value.accountStatements,
    (val) => {
        dataToShow.value = val;
    }
);
watch(
    () => filterForm.value,
    () => {
        actionForm.value = { ids: [] };
    },
    { deep: true }
);

//Year filter

const yearInput = ref("");
watch(yearInput, () => {
    if (yearInput.value) {
        isFetchingAll.value = true;
        filterForm.value.month = "";
        handleSearch(`${yearInput.value}-01`, `${yearInput.value}-12`, false);
    }
});
</script>
