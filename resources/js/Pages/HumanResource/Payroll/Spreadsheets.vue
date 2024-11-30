<template>

    <Head title="Nomina" />
    <AuthenticatedLayout :redirectRoute="'payroll.index'">
        <template #header>
            Nomina de {{ payrolls.month }}
        </template>
        <div class="min-w-full min-h-full overflow-hidden rounded-lg shadow">
            <div class="mt-6 flex flex-col sm:flex-row sm:items-center justify-between sm:gap-x-3 gap-y-4">
                <div class="flex items-center justify-between gap-x-6 w-full">
                    <div class="hidden sm:flex sm:items-center sm:space-x-2 pl-3">
                        <!-- <PrimaryButton v-if="hasPermission('HumanResourceManager')" @click="click_sctr()" type="button"
                            class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                            SCTR
                        </PrimaryButton> -->
                        <button v-if="!payrolls.state" @click="openPayrollApprove()"
                            class="rounded-md px-1 py-2 text-center text-sm text-white hover:bg-green-400">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" fill="#228b22" />
                            </svg>
                        </button>

                        <a :href="route('spreadsheets.payroll.export', { payroll_id: payroll.id })"
                            class="rounded-md px-1 py-2 text-center text-sm text-white hover:bg-green-400">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                stroke-width="1.5">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M9.29289 1.29289C9.48043 1.10536 9.73478 1 10 1H18C19.6569 1 21 2.34315 21 4V9C21 9.55228 20.5523 10 20 10C19.4477 10 19 9.55228 19 9V4C19 3.44772 18.5523 3 18 3H11V8C11 8.55228 10.5523 9 10 9H5V20C5 20.5523 5.44772 21 6 21H7C7.55228 21 8 21.4477 8 22C8 22.5523 7.55228 23 7 23H6C4.34315 23 3 21.6569 3 20V8C3 7.73478 3.10536 7.48043 3.29289 7.29289L9.29289 1.29289ZM6.41421 7H9V4.41421L6.41421 7ZM19 12C19.5523 12 20 12.4477 20 13V19H23C23.5523 19 24 19.4477 24 20C24 20.5523 23.5523 21 23 21H19C18.4477 21 18 20.5523 18 20V13C18 12.4477 18.4477 12 19 12ZM11.8137 12.4188C11.4927 11.9693 10.8682 11.8653 10.4188 12.1863C9.96935 12.5073 9.86526 13.1318 10.1863 13.5812L12.2711 16.5L10.1863 19.4188C9.86526 19.8682 9.96935 20.4927 10.4188 20.8137C10.8682 21.1347 11.4927 21.0307 11.8137 20.5812L13.5 18.2205L15.1863 20.5812C15.5073 21.0307 16.1318 21.1347 16.5812 20.8137C17.0307 20.4927 17.1347 19.8682 16.8137 19.4188L14.7289 16.5L16.8137 13.5812C17.1347 13.1318 17.0307 12.5073 16.5812 12.1863C16.1318 11.8653 15.5073 11.9693 15.1863 12.4188L13.5 14.7795L11.8137 12.4188Z"
                                    fill="#228b22" />
                            </svg>
                        </a>
                    </div>
                    <div class="sm:hidden">
                        <dropdown align='left'>
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
                                <div>
                                    <div class="dropdown">
                                        <div class="dropdown-menu">
                                            <a :href="route('spreadsheets.payroll.export', { payroll_id: payroll.id })"
                                                class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                                Exportar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </dropdown>
                    </div>
                    <div>
                        <TextInput type="text" placeholder="Buscar..." @input="search($event.target.value)" />
                    </div>
                </div>
            </div>
            <div class="overflow-auto h-[70vh]">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="sticky top-0 z-20 border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            <!-- <th
                                class="w-100 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                Estado
                            </th> -->
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                DNI
                            </th>
                            <th
                                class="sticky left-0 z-10 bg-amber-200 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 top-0 z-5">
                                Nombre
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                REG. PEN
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                Fecha Ingreso
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-9 mx-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                Sueldo
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                Vac. Truncas
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                Ingreso
                                Total
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                Tot.B.G.Sis. Pensionario
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                %.SNP
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                SNP/ONP
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                %.COM
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                %.Com. Sobre R.A.
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                % SEG
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                PRIMA SEGURO
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                % APORT. OBLIG.
                            </th>

                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                MONT0 OBLIGA.
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                DESCUENTO TOTAL
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                Viaticos
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-green-200 px-9 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                NETO PAGAR
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-9 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                TOT. BASE GRAV. ESSAL.
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-9 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                SALUD 9%
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-9 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                VIDA LEY
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-9 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                SCTR P
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-9 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                SCTR S
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 sticky top-0 z-5">
                                APORTE TOTAL
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="spreadsheet in spreadsheets" :key="spreadsheet.id" class="text-gray-700">
                            <!-- <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-nowrap"></p>
                            </td> -->
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-nowrap">{{ spreadsheet.employee.dni }}</p>
                            </td>
                            <td class="sticky left-0 border-b bg-amber-200 px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-nowrap">
                                    {{ spreadsheet.employee.name }} {{
                                        spreadsheet.employee.lastname }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-nowrap">
                                    {{ spreadsheet.pension.type }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-nowrap">
                                    {{ formattedDate(spreadsheet.hire_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-nowrap">
                                    S/ {{ spreadsheet.basic_salary.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-nowrap">
                                    S/ {{ spreadsheet.truncated_vacations.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-nowrap">
                                    S/ {{
                                        spreadsheet.total_income.toFixed(2)
                                    }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-nowrap">
                                    S/ {{ spreadsheet.total_pension_base.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-nowrap">
                                    % {{ spreadsheet.snp.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-nowrap">
                                    S/ {{ spreadsheet.snp_onp.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-nowrap">
                                    % {{ spreadsheet.commission.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-nowrap">
                                    S/ {{ spreadsheet.commission_on_ra.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-nowrap">
                                    % {{ spreadsheet.seg.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-nowrap">
                                    S/ {{ spreadsheet.insurance_premium.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-nowrap">
                                    % {{ spreadsheet.mandatory_contribution.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-nowrap">
                                    S/ {{ spreadsheet.mandatory_contribution_amount.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-nowrap">
                                    S/ {{
                                        spreadsheet.total_discount.toFixed(2)
                                    }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex gap-x-3">
                                    <p class="text-gray-900 whitespace-nowrap">
                                        S/ {{
                                            spreadsheet.amount_travel_expenses ?
                                                spreadsheet.amount_travel_expenses.toFixed(2) :
                                                '0.00'
                                        }}
                                    </p>
                                    <template
                                        v-if="!payrolls.state && spreadsheet.amount_travel_expenses && permissions('HumanResourceManager')">
                                        <button
                                            v-if="spreadsheet.payroll_detail_expense.operation_number && spreadsheet.payroll_detail_expense.operation_date"
                                            @click="openPaymentTravelExpenseModal(spreadsheet.payroll_detail_expense[1])">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-teal-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </button>
                                        <button v-else
                                            @click="openPaymentTravelExpenseModal(spreadsheet.payroll_detail_expense[1])">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-amber-400">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </button>
                                    </template>
                                </div>
                            </td>
                            <td class="border-b border-gray-200 bg-green-200 px-5 py-5 text-sm">
                                <div class="flex gap-x-3">
                                    <p class="text-gray-900 whitespace-nowrap">
                                        S/ {{ spreadsheet.net_pay.toFixed(2) }}
                                    </p>
                                    <template v-if="!payrolls.state && permissions('HumanResourceManager')">
                                        <button
                                            v-if="!payrolls.state && permissions('HumanResourceManager') && spreadsheet.payroll_detail_expense[0].operation_number && spreadsheet.payroll_detail_expense[0].operation_date"
                                            @click="openPaymentSalaryModal(spreadsheet.payroll_detail_expense[0])">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-teal-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </button>
                                        <button v-else
                                            @click="openPaymentSalaryModal(spreadsheet.payroll_detail_expense[0])">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-amber-400">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </button>
                                    </template>

                                </div>

                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-nowrap">
                                    S/ {{ spreadsheet.total_pension_base.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-nowrap">
                                    S/ {{ spreadsheet.healths.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-nowrap">
                                    S/ {{ spreadsheet.life_ley?.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    S/ {{ spreadsheet.discount_sctr ? spreadsheet.sctr_p.toFixed(2) : 0.00 }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    S/ {{ spreadsheet.discount_sctr ? spreadsheet.sctr_s.toFixed(2) : 0.00 }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    S/ {{ spreadsheet.total_contribution.toFixed(2) }}
                                </p>
                            </td>
                        </tr>
                        <tr class="sticky bottom-0 z-5">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap"
                                colspan="4">
                                Totales:
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                                S/ {{ totals.sum_salary.toFixed(2) }}
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                                S/ {{ totals.sum_truncated_vacations.toFixed(2) }}
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                                S/ {{ totals.sum_total_income.toFixed(2) }}
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap"
                                colspan="2">
                                S/ {{ totals.sum_total_income.toFixed(2) }}
                            </td>

                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap"
                                colspan="2">
                                S/ {{ totals.sum_snp_onp.toFixed(2) }}
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap"
                                colspan="2">
                                S/ {{ totals.sum_commission_on_ra.toFixed(2) }}
                            </td>

                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap"
                                colspan="2">
                                S/ {{ totals.sum_insurance_premium.toFixed(2) }}
                            </td>

                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                                S/ {{ totals.sum_mandatory_contribution_amount.toFixed(2) }}
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                                S/ {{ totals.sum_total_discount.toFixed(2) }}
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                                S/ {{ totals.sum_amount_travel_expenses.toFixed(2) }}
                            </td>
                            <td class="border-b border-gray-200 bg-green-200 px-5 py-5 text-sm whitespace-nowrap">
                                S/ {{ totals.sum_net_pay.toFixed(2) }}</td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                                S/ {{ totals.sum_total_income.toFixed(2) }}</td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                                S/ {{ totals.sum_health.toFixed(2) }}
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                                S/ {{ totals.sum_life_ley.toFixed(2) }}
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                S/ {{ totals.sum_sctr_p.toFixed(2) }}
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                S/ {{ totals.sum_sctr_s.toFixed(2) }}
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                S/ {{ totals.sum_total_contribution.toFixed(2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- <Modal :show="showSctr">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Sctr
                </h2>
                <form @submit.prevent="submit">
                    <div class="border-b border-gray-900/10 pb-12">
                        <div class="mt-2">
                            <InputLabel for="number_people">Cantidad de Empleados:
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="number" id="number_people" v-model="form.number_people" />
                                <InputError :message="form.errors.number_people" />
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-3">
                            <SecondaryButton @click="click_sctr"> Cancelar </SecondaryButton>
                            <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                                Guardar
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </Modal> -->
        <Modal :show="showPaymentModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{ travelOrSalary ? 'Pago de Viatico ' : 'Pago de salario ' }} del Empleado
                </h2>
                <form @submit.prevent="submit">
                    <div class="border-b border-gray-900/10">
                        <div class="mt-2">
                            <InputLabel for="operation_number">Numero de Operación
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="operation_number" v-model="formPayment.operation_number"
                                    maxlength="6" />
                                <InputError :message="formPayment.errors.operation_number" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="operation_date">Fecha de Operación
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" id="operation_date" v-model="formPayment.operation_date" />
                                <InputError :message="formPayment.errors.operation_date" />
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-3">
                            <SecondaryButton @click="closePaymentModal"> Cancelar </SecondaryButton>
                            <PrimaryButton type="submit" :class="{ 'opacity-25': formPayment.processing }">
                                Guardar
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>
        <ConfirmateModal :showConfirm="showPayrollModal" tittle="Aprobación de la Nómina"
            text="Una vez que la nómina sea aprobada, no se podrán realizar modificaciones ni acciones posteriores. Por favor, asegúrate de revisar toda la información antes de proceder con la aprobación final."
            @closeModal="openPayrollApprove" :actionFunction="payrollApprove" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { formattedDate, setAxiosErrors } from '@/utils/utils';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { notify, notifyError, notifyWarning } from '@/Components/Notification';
import ConfirmateModal from '@/Components/ConfirmateModal.vue';

const { spreadsheet, payroll, total, userPermissions } = defineProps({
    spreadsheet: Object,
    payroll: Object,
    total: Object,
    // search: String,
    // number_people: String,
    userPermissions: Array
})


const spreadsheets = ref(spreadsheet)
const payrolls = ref(payroll)
const totals = ref(total)
console.log(spreadsheet)
const showPaymentModal = ref(false)
const travelOrSalary = ref(false)
const showPayrollModal = ref(false)

const initialStament = {
    id: '',
    operation_date: '',
    operation_number: '',
}

const formPayment = useForm({
    ...initialStament
})

function permissions(permission) {
    return userPermissions.includes(permission)
}
function openPaymentSalaryModal(payroll_detail) {
    formPayment.defaults({ id: payroll_detail.payroll_detail_id, operation_date: payroll_detail.operation_date, operation_number: payroll_detail.operation_number })
    formPayment.reset()
    showPaymentModal.value = true
    travelOrSalary.value = false
}

function openPaymentTravelExpenseModal(payroll_detail) {
    formPayment.defaults({ id: payroll_detail.payroll_detail_id, operation_date: payroll_detail.operation_date, operation_number: payroll_detail.operation_number })
    formPayment.reset()
    showPaymentModal.value = true
    travelOrSalary.value = true
}

function closePaymentModal() {
    showPaymentModal.value = false
    formPayment.clearErrors()
    formPayment.defaults({ ...initialStament })
    formPayment.reset()
}

async function submit() {
    let url = travelOrSalary.value ? route('payroll.payment.travelExpense.store', { payroll_details_id: formPayment.id }) : route('payroll.payment.salary.store', { payroll_details_id: formPayment.id })
    try {
        let response = await axios.put(url, formPayment)
        console.log('dasd', response.data)
        closePaymentModal()
        updatePayrollDetails(response.data)
    } catch (error) {
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, formPayment)
            } else {
                notifyError("Server error:", error.response.data)
            }
        } else {
            notifyError("Network or other error:", error)
        }
    }
}

function updatePayrollDetails(payrollDetail) {
    const validations = spreadsheets.value
    let index = validations.findIndex(item => item.id === payrollDetail[0].payroll_detail_id)
    if (index !== -1) {
        validations[index].payroll_detail_expense = payrollDetail;
        notify('El pago se Registro')
    } else {
        notifyWarning(`No se encontró un elemento con id: ${payrollDetail.id}`);
    }
}

function openPayrollApprove() {
    showPayrollModal.value = !showPayrollModal.value
}

async function payrollApprove() {
    let url = route('payroll.state.update', { payroll_id: payrolls.value.id })
    try {
        let response = await axios.get(url)
        payrolls.value = response.data
        openPayrollApprove()
        notify('Aprobación de Nomina Existosa')
    } catch (error) {
        if (error.response.data) {
            notifyError("Server error:", error.response.data)
        } else {
            notifyError("Network or other error:", error)
        }
    }
}

async function search(employee) {
    let url = route('spreadsheets.index', { payroll_id: payrolls.value.id })
    try {
        let response = await axios.post(url, { searchQuery: employee })
        spreadsheets.value = response.data.spreadsheet
        totals.value = response.data.total
    } catch (error) {
        notifyError(error)
    }
}
// const reentrystate = ref(props.boolean);
// const showSctr = ref(false);

// const form = useForm({
//     number_people:props.number_people,
// })

// const management_pension = () => {
//     router.get(route('pension_system.edit'));
// };

// const reentry = () => {
//     if (props.boolean == true) {
//         reentrystate.value = false
//         router.get(route('spreadsheets.index'))
//     } else {
//         reentrystate.value = true
//         router.get(route('spreadsheets.index', { reentry: reentrystate.value }))
//     }
// };

// const searchForm = useForm({
//     searchTerm: props.search,
// })

// const search = () => {
//     let data = { searchTerm: searchForm.searchTerm }
//     if (!props.boolean == true) {
//         reentrystate.value = false
//         router.get(route('spreadsheets.index'), data)
//     } else {
//         reentrystate.value = true
//         router.get(route('spreadsheets.index', { reentry: reentrystate.value }), data)
//     }

// }

// function click_sctr() {
//     showSctr.value = !showSctr.value
//     form.reset()
// }

// function submit() {
//     form.post(route('management.employees.schedule.update_number_people'),{
//         onSuccess:() => {
//             router.get(route('spreadsheets.index'));
//         }
//     });
// }

</script>