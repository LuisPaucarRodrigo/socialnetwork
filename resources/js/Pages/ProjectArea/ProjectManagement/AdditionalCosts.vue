<template>
    <Head title="Gestion de Costos Adicionales" />
    <AuthenticatedLayout
        :redirectRoute="{
            route: 'projectmanagement.purchases_request.index',
            params: { id: project_id.id },
        }"
    >
        <template #header>
            Gastos Variables del Proyecto {{ props.project_id.name }}
        </template>
        <br />
        <div class="inline-block min-w-full mb-4 overflow-hidden">
            <div class="flex gap-4 justify-between">
                <div class="flex space-x-3">
                    <PrimaryButton
                        v-if="
                            project_id.status === null &&
                            hasPermission('ProjectManager')
                        "
                        @click="openCreateAdditionalModal"
                        type="button"
                        class="whitespace-nowrap"
                    >
                        + Agregar
                    </PrimaryButton>
                    <PrimaryButton
                        type="button"
                        @click="
                            router.visit(
                                route('projectmanagement.additionalCosts', {
                                    project_id: project_id.id,
                                })
                            )
                        "
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="h-5 w-5 text-white"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"
                            />
                        </svg>
                    </PrimaryButton>

                    <a
                        type="button"
                        class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500"
                        :href="
                            route('additionalcost.excel.export', {
                                project_id: project_id.id,
                            })
                        "
                    >
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M9.29289 1.29289C9.48043 1.10536 9.73478 1 10 1H18C19.6569 1 21 2.34315 21 4V9C21 9.55228 20.5523 10 20 10C19.4477 10 19 9.55228 19 9V4C19 3.44772 18.5523 3 18 3H11V8C11 8.55228 10.5523 9 10 9H5V20C5 20.5523 5.44772 21 6 21H7C7.55228 21 8 21.4477 8 22C8 22.5523 7.55228 23 7 23H6C4.34315 23 3 21.6569 3 20V8C3 7.73478 3.10536 7.48043 3.29289 7.29289L9.29289 1.29289ZM6.41421 7H9V4.41421L6.41421 7ZM19 12C19.5523 12 20 12.4477 20 13V19H23C23.5523 19 24 19.4477 24 20C24 20.5523 23.5523 21 23 21H19C18.4477 21 18 20.5523 18 20V13C18 12.4477 18.4477 12 19 12ZM11.8137 12.4188C11.4927 11.9693 10.8682 11.8653 10.4188 12.1863C9.96935 12.5073 9.86526 13.1318 10.1863 13.5812L12.2711 16.5L10.1863 19.4188C9.86526 19.8682 9.96935 20.4927 10.4188 20.8137C10.8682 21.1347 11.4927 21.0307 11.8137 20.5812L13.5 18.2205L15.1863 20.5812C15.5073 21.0307 16.1318 21.1347 16.5812 20.8137C17.0307 20.4927 17.1347 19.8682 16.8137 19.4188L14.7289 16.5L16.8137 13.5812C17.1347 13.1318 17.0307 12.5073 16.5812 12.1863C16.1318 11.8653 15.5073 11.9693 15.1863 12.4188L13.5 14.7795L11.8137 12.4188Z"
                                fill="#ffffff"
                            />
                        </svg>
                    </a>

                    <button
                        type="button"
                        class="rounded-md bg-yellow-500 px-4 py-2 text-center text-sm text-white hover:bg-yellow-400"
                        @click="openImportModal()"
                    >
                        <svg
                            class="h-6 w-6 text-white"
                            viewBox="0 0 512 512"
                            version="1.1"
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                        >
                            <title>upload-document-note</title>
                            <g
                                id="Page-1"
                                stroke="none"
                                stroke-width="1"
                                fill="none"
                                fill-rule="evenodd"
                            >
                                <g
                                    id="icon"
                                    fill="#ffffff"
                                    transform="translate(64.000000, 42.666667)"
                                >
                                    <path
                                        d="M298.642975,268.433404 L392.812864,362.603293 L362.642975,392.773183 L319.976085,350.117404 L319.976308,447.956959 L277.309641,447.956959 L277.309085,350.116404 L234.642975,392.773183 L204.473085,362.603293 L298.642975,268.433404 Z M234.666667,7.10542736e-15 L341.333333,106.666667 L341.333,280.469 L298.779055,237.915875 L298.666,238.028 L298.666667,124.339779 L216.993555,42.6666667 L42.6666667,42.6666667 L42.6666667,384 L196.184,384 L234.773299,422.589676 L256.093414,401.26939 L256.101,426.666 L1.42108547e-14,426.666667 L1.42108547e-14,7.10542736e-15 L234.666667,7.10542736e-15 Z M213.333333,298.666667 L213.333,323.361 L195.361,341.333 L64,341.333333 L64,298.666667 L213.333333,298.666667 Z M196,85.3333333 L256,145.333333 L124,277.333333 L64,277.333333 L64,217.333333 L196,85.3333333 Z M195.989333,130.581333 L96,230.570667 L96,245.333333 L110.72,245.333333 L210.730667,145.322667 L195.989333,130.581333 Z"
                                        id="Combined-Shape"
                                    ></path>
                                </g>
                            </g>
                        </svg>
                    </button>
                </div>

                <form
                    @submit.prevent="handleSearch"
                    class="flex items-center w-full sm:w-auto"
                >
                    <TextInput
                        type="text"
                        placeholder="Buscar..."
                        v-model="filterForm.search"
                    />
                    <button
                        type="submit"
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
        <div class="overflow-x-auto h-[85vh]">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="sticky top-0 z-20 border-b bg-gray-50 text-center text-xs font-semibold uppercase tracking-wide text-gray-500"
                    >
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            <TableHeaderFilter
                                labelClass="text-[11px]"
                                label="Zona"
                                :options="zones"
                                v-model="filterForm.selectedZones"
                                width="w-32"
                            />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            <TableHeaderFilter
                                labelClass="text-[11px]"
                                label="Tipo de Gasto"
                                :options="expenseTypes"
                                v-model="filterForm.selectedExpenseTypes"
                                width="w-48"
                            />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            <TableHeaderFilter
                                labelClass="text-[11px]"
                                label="Tipo de Documento"
                                :options="docTypes"
                                v-model="filterForm.selectedDocTypes"
                                width="w-32"
                            />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            RUC
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Proveedor
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Numero de Doc
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Fecha de Documento
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Monto
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Archivo
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Descripción
                        </th>
                        <th
                            v-if="
                                auth.user.role_id === 1 &&
                                project_id.status === null
                            "
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="item in dataToRender"
                        :key="item.id"
                        class="text-gray-700"
                    >
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            {{ item.zone }}
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            <p class="w-48 break-words">
                                {{ item.expense_type }}
                            </p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            {{ item.type_doc }}
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            {{ item.ruc }}
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            {{ item?.provider?.company_name }}
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            {{ item.doc_number }}
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            {{ formattedDate(item.doc_date) }}
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] whitespace-nowrap"
                        >
                            S/. {{ item.amount.toFixed(2) }}
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            <button
                                v-if="item.photo"
                                @click="handlerPreview(item.id)"
                            >
                                <EyeIcon class="w-4 h-4 text-teal-600" />
                            </button>
                            <span v-else>-</span>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            <p class="w-[250px]">
                                {{ item.description }}
                            </p>
                        </td>
                        <td
                            v-if="
                                auth.user.role_id === 1 &&
                                project_id.status === null
                            "
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            <div class="flex items-center">
                                <button
                                    @click="openEditAdditionalModal(item)"
                                    class="text-amber-600 hover:underline mr-2"
                                >
                                    <PencilSquareIcon class="h-4 w-4 ml-1" />
                                </button>
                                <button
                                    @click="confirmDeleteAdditional(item.id)"
                                    class="text-red-600 hover:underline"
                                >
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="sticky bottom-0 z-10 text-gray-700">
                        <td
                            class="font-bold border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            TOTAL
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        ></td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        ></td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        ></td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        ></td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        ></td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        ></td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap"
                        >
                            S/.
                            {{
                                dataToRender
                                    .reduce((a, item) => a + item.amount, 0)
                                    .toFixed(2)
                            }}
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        ></td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        ></td>
                        <td
                            v-if="
                                auth.user.role_id === 1 &&
                                project_id.status === null
                            "
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            <div class="flex items-center"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div
            v-if="!filterMode"
            class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between"
        >
            <pagination :links="additional_costs.links" />
        </div>
        <Modal :show="create_additional">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Agregar Costo adicional
                </h2>
                <form @submit.prevent="submit">
                    <div class="space-y-12 mt-4">
                        <div
                            class="border-b grid sm:grid-cols-2 gap-6 border-gray-900/10 pb-12"
                        >
                            <div>
                                <InputLabel
                                    for="zone"
                                    class="font-medium leading-6 text-gray-900"
                                    >Zona</InputLabel
                                >
                                <div class="mt-2">
                                    <select
                                        v-model="form.zone"
                                        id="zone"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    >
                                        <option disabled value="">
                                            Seleccionar
                                        </option>
                                        <option>Arequipa</option>
                                        <option>Chala</option>
                                        <option>Moquegua</option>
                                        <option>Tacna</option>
                                        <option>MDD</option>
                                    </select>
                                    <InputError :message="form.errors.zone" />
                                </div>
                            </div>
                            <div>
                                <InputLabel
                                    for="expense_type"
                                    class="font-medium leading-6 text-gray-900"
                                    >Tipo de Gasto</InputLabel
                                >
                                <div class="mt-2">
                                    <select
                                        v-model="form.expense_type"
                                        id="expense_type"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    >
                                        <option disabled value="">
                                            Seleccionar Gasto
                                        </option>
                                        <option>Hospedaje</option>
                                        <option>Movilidad</option>
                                        <option>Peaje</option>
                                        <option>Seguros y Pólizas</option>
                                        <option>Herramientas</option>
                                        <option>Fletes</option>
                                        <option>EPPs</option>
                                        <option>
                                            Gastos de Representación
                                        </option>
                                        <option>Consumibles</option>
                                        <option>Equipos</option>
                                        <option>Otros</option>
                                    </select>
                                    <InputError
                                        :message="form.errors.expense_type"
                                    />
                                </div>
                            </div>
                            <div>
                                <InputLabel
                                    for="type_doc"
                                    class="font-medium leading-6 text-gray-900"
                                    >Tipo de Documento</InputLabel
                                >
                                <div class="mt-2">
                                    <select
                                        v-model="form.type_doc"
                                        id="type_doc"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    >
                                        <option disabled value="">
                                            Seleccionar Documento
                                        </option>
                                        <option>Efectivo</option>
                                        <option>Deposito</option>
                                        <option>Factura</option>
                                        <option>Boleta</option>
                                        <option>Voucher de Pago</option>
                                    </select>
                                    <InputError
                                        :message="form.errors.type_doc"
                                    />
                                </div>
                            </div>
                            <div>
                                <InputLabel
                                    for="ruc"
                                    class="font-medium leading-6 text-gray-900"
                                    >RUC / DNI
                                </InputLabel>
                                <div class="mt-2">
                                    <input
                                        type="text"
                                        v-model="form.ruc"
                                        id="ruc"
                                        maxlength="11"
                                        @input="handleRucDniAutocomplete"
                                        autocomplete="off"
                                        list="options"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <datalist id="options">
                                        <option
                                            v-for="item in providers"
                                            :value="item.ruc"
                                        >
                                            {{ item.company_name }}
                                        </option>
                                    </datalist>
                                    <InputError :message="form.errors.ruc" />
                                </div>
                            </div>

                            <div>
                                <InputLabel
                                    for="doc_number"
                                    class="font-medium leading-6 text-gray-900"
                                    >Numero de Documento
                                </InputLabel>
                                <div class="mt-2">
                                    <input
                                        type="text"
                                        v-model="form.doc_number"
                                        id="doc_number"
                                        pattern="^([a-zA-Z0-9]+([-|\/][a-zA-Z0-9]+)*)|([0-9]+)$"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError
                                        :message="form.errors.doc_number"
                                    />
                                </div>
                            </div>

                            <div>
                                <InputLabel
                                    for="doc_date"
                                    class="font-medium leading-6 text-gray-900"
                                    >Fecha de Documento
                                </InputLabel>
                                <div class="mt-2">
                                    <input
                                        type="date"
                                        v-model="form.doc_date"
                                        id="doc_date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError
                                        :message="form.errors.doc_date"
                                    />
                                </div>
                            </div>

                            <div>
                                <InputLabel
                                    for="amount"
                                    class="font-medium leading-6 text-gray-900"
                                    >Monto</InputLabel
                                >
                                <div class="mt-2">
                                    <input
                                        type="number"
                                        step="0.0001"
                                        v-model="form.amount"
                                        id="amount"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError :message="form.errors.amount" />
                                </div>
                            </div>

                            <div>
                                <InputLabel
                                    for="description"
                                    class="font-medium leading-6 text-gray-900"
                                    >Descripción</InputLabel
                                >
                                <div class="mt-2">
                                    <textarea
                                        type="text"
                                        v-model="form.description"
                                        id="description"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError
                                        :message="form.errors.description"
                                    />
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <InputLabel
                                    class="font-medium leading-6 text-gray-900"
                                >
                                    Archivo
                                </InputLabel>
                                <div class="mt-2">
                                    <InputFile
                                        type="file"
                                        v-model="form.photo"
                                        accept=".jpeg, .jpg, .png, .pdf"
                                        class="block w-full h-24 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError :message="form.errors.photo" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeModal">
                                Cancelar
                            </SecondaryButton>
                            <button
                                type="submit"
                                :class="{ 'opacity-25': form.processing }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            >
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <Modal :show="editAdditionalModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Editar Costo Adicional
                </h2>
                <form @submit.prevent="submitEdit">
                    <div class="space-y-12">
                        <div
                            class="border-b grid sm:grid-cols-2 gap-6 border-gray-900/10 pb-12"
                        >
                            <div>
                                <InputLabel
                                    for="zone"
                                    class="font-medium leading-6 text-gray-900"
                                    >Zona</InputLabel
                                >
                                <div class="mt-2">
                                    <select
                                        v-model="form.zone"
                                        id="zone"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    >
                                        <option disabled value="">
                                            Seleccionar
                                        </option>
                                        <option>Arequipa</option>
                                        <option>Chala</option>
                                        <option>Moquegua</option>
                                        <option>Tacna</option>
                                        <option>MDD</option>
                                    </select>
                                    <InputError :message="form.errors.zone" />
                                </div>
                            </div>
                            <div>
                                <InputLabel
                                    for="expense_type"
                                    class="font-medium leading-6 text-gray-900"
                                    >Tipo de Gasto</InputLabel
                                >
                                <div class="mt-2">
                                    <select
                                        v-model="form.expense_type"
                                        id="expense_type"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    >
                                        <option disabled value="">
                                            Seleccionar Gasto
                                        </option>
                                        <option>Hospedaje</option>
                                        <option>Movilidad</option>
                                        <option>Peaje</option>
                                        <option>Seguros y Pólizas</option>
                                        <option>Herramientas</option>
                                        <option>Fletes</option>
                                        <option>EPPs</option>
                                        <option>
                                            Gastos de Representación
                                        </option>
                                        <option>Consumibles</option>
                                        <option>Equipos</option>
                                        <option>Otros</option>
                                    </select>
                                    <InputError
                                        :message="form.errors.expense_type"
                                    />
                                </div>
                            </div>

                            <div>
                                <InputLabel
                                    for="type_doc"
                                    class="font-medium leading-6 text-gray-900"
                                    >Tipo de Documento</InputLabel
                                >
                                <div class="mt-2">
                                    <select
                                        v-model="form.type_doc"
                                        id="type_doc"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    >
                                        <option disabled value="">
                                            Seleccionar Documento
                                        </option>
                                        <option>Efectivo</option>
                                        <option>Deposito</option>
                                        <option>Factura</option>
                                        <option>Boleta</option>
                                        <option>Voucher de Pago</option>
                                    </select>
                                    <InputError
                                        :message="form.errors.type_doc"
                                    />
                                </div>
                            </div>
                            <div>
                                <InputLabel
                                    for="ruc"
                                    class="font-medium leading-6 text-gray-900"
                                    >RUC / DNI
                                </InputLabel>
                                <div class="mt-2">
                                    <input
                                        type="text"
                                        v-model="form.ruc"
                                        id="ruc"
                                        maxlength="11"
                                        @input="handleRucDniAutocomplete"
                                        autocomplete="off"
                                        list="options"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <datalist id="options">
                                        <option
                                            v-for="item in providers"
                                            :value="item.ruc"
                                        >
                                            {{ item.company_name }}
                                        </option>
                                    </datalist>
                                    <InputError :message="form.errors.ruc" />
                                </div>
                            </div>

                            <div>
                                <InputLabel
                                    for="doc_number"
                                    class="font-medium leading-6 text-gray-900"
                                    >Numero de Documento
                                </InputLabel>
                                <div class="mt-2">
                                    <input
                                        type="text"
                                        v-model="form.doc_number"
                                        id="doc_number"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError
                                        :message="form.errors.doc_number"
                                    />
                                </div>
                            </div>

                            <div>
                                <InputLabel
                                    for="doc_date"
                                    class="font-medium leading-6 text-gray-900"
                                    >Fecha de Documento
                                </InputLabel>
                                <div class="mt-2">
                                    <input
                                        type="date"
                                        v-model="form.doc_date"
                                        id="doc_date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError
                                        :message="form.errors.doc_date"
                                    />
                                </div>
                            </div>

                            <div>
                                <InputLabel
                                    for="amount"
                                    class="font-medium leading-6 text-gray-900"
                                    >Monto</InputLabel
                                >
                                <div class="mt-2">
                                    <input
                                        type="number"
                                        step="0.0001"
                                        v-model="form.amount"
                                        id="amount"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError :message="form.errors.amount" />
                                </div>
                            </div>

                            <div>
                                <InputLabel
                                    for="description"
                                    class="font-medium leading-6 text-gray-900"
                                    >Descripción</InputLabel
                                >
                                <div class="mt-2">
                                    <textarea
                                        v-model="form.description"
                                        id="description"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError
                                        :message="form.errors.description"
                                    />
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <InputLabel
                                    class="font-medium leading-6 text-gray-900"
                                >
                                    Archivo
                                </InputLabel>
                                <div class="mt-2">
                                    <InputFile
                                        type="file"
                                        v-model="form.photo"
                                        accept=".jpeg, .jpg, .png, .pdf"
                                        class="block w-full h-24 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError :message="form.errors.photo" />
                                    <div
                                        v-if="
                                            form.photo_name &&
                                            form.photo_status == 'stable'
                                        "
                                        class="text-sm leading-6 text-indigo-700 flex space-x-2 items-center mt-3"
                                    >
                                        <span> Archivo Actual: </span>
                                        <a
                                            :href="
                                                route(
                                                    'additionalcost.archive',
                                                    {
                                                        additional_cost_id:
                                                            form.id,
                                                    }
                                                )
                                            "
                                            target="_blank"
                                            class="hover:underline"
                                        >
                                            {{ form.photo_name }}
                                        </a>
                                        <button
                                            type="button"
                                            @click="
                                                () => {
                                                    form.photo_status =
                                                        'delete';
                                                }
                                            "
                                        >
                                            <TrashIcon
                                                class="text-red-500 h-4 w-4"
                                            />
                                        </button>
                                    </div>
                                    <div
                                        v-if="form.photo_status === 'delete'"
                                        class="text-amber-700 mt-3 text-sm flex space-x-2"
                                    >
                                        <span>
                                            El documento esta por ser borrado,
                                        </span>
                                        <button
                                            @click="
                                                () => {
                                                    form.photo_status =
                                                        'stable';
                                                }
                                            "
                                            type="button"
                                            class="font-black"
                                        >
                                            ANULAR
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeEditModal">
                                Cancelar
                            </SecondaryButton>
                            <button
                                type="submit"
                                :class="{ 'opacity-25': form.processing }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            >
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
                        <div
                            class="border-b grid grid-cols-1 gap-6 border-gray-900/10 pb-12"
                        >
                            <div>
                                <InputLabel
                                    class="font-medium leading-6 text-gray-900"
                                >
                                    Archivo
                                </InputLabel>
                                <div class="mt-2">
                                    <InputFile
                                        type="file"
                                        v-model="importForm.import_file"
                                        accept=".xlsx, .csv"
                                        class="block w-full h-24 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError
                                        :message="importForm.errors.import_file"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeImportModal">
                                Cancelar
                            </SecondaryButton>
                            <button
                                type="submit"
                                :class="{ 'opacity-25': importForm.processing }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            >
                                Importar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <ConfirmDeleteModal
            :confirmingDeletion="confirmingDocDeletion"
            itemType="Costo Adicional"
            :deleteFunction="deleteAdditional"
            @closeModal="closeModalDoc"
        />
        <ConfirmCreateModal
            :confirmingcreation="showModal"
            itemType="Costo Adicional"
        />
        <ConfirmUpdateModal
            :confirmingupdate="showModalEdit"
            itemType="Costo Adicional"
        />
        <SuccessOperationModal
            :confirming="confirmImport"
            :title="'Datos Importados'"
            :message="'Los datos fueron importados con éxito'"
        />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ConfirmCreateModal from "@/Components/ConfirmCreateModal.vue";
import ConfirmUpdateModal from "@/Components/ConfirmUpdateModal.vue";
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

const props = defineProps({
    additional_costs: Object,
    project_id: Object,
    providers: Object,
    auth: Object,
    userPermissions: Array,
    searchQuery: String,
});

const dataToRender = ref(props.additional_costs.data);
const filterMode = ref(false);

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
};

const form = useForm({
    id: "",
    expense_type: "",
    ruc: "",
    zone: "",
    provider_id: "",
    project_id: props.project_id.id,
    type_doc: "",
    doc_number: "",
    doc_date: "",
    description: "",
    photo: "",
    amount: "",
    photo_status: "stable",
});

const create_additional = ref(false);
const showModal = ref(false);
const showModalEdit = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);
const editAdditionalModal = ref(false);
const editingAdditional = ref(null);

const openCreateAdditionalModal = () => {
    create_additional.value = true;
};

const openEditAdditionalModal = (additional) => {
    // Copia de los datos de la subsección existente al formulario
    editingAdditional.value = JSON.parse(JSON.stringify(additional));
    form.id = editingAdditional.value.id;
    form.expense_type = editingAdditional.value.expense_type;
    form.ruc = editingAdditional.value.ruc;
    form.amount = editingAdditional.value.amount;
    form.type_doc = editingAdditional.value.type_doc;
    form.doc_number = editingAdditional.value.doc_number;
    form.doc_date = editingAdditional.value.doc_date;
    form.amount = editingAdditional.value.amount;
    form.description = editingAdditional.value.description;
    form.zone = editingAdditional.value.zone;
    form.provider_id = editingAdditional.value.provider_id;
    form.photo_name = editingAdditional.value.photo;

    editAdditionalModal.value = true;
};

const closeModal = () => {
    form.reset();
    create_additional.value = false;
};

const closeEditModal = () => {
    form.reset();
    editAdditionalModal.value = false;
};

const submit = () => {
    form.post(
        route("projectmanagement.storeAdditionalCost", {
            project_id: props.project_id.id,
        }),
        {
            onSuccess: () => {
                closeModal();
                showModal.value = true;
                setTimeout(() => {
                    showModal.value = false;
                    router.visit(
                        route("projectmanagement.additionalCosts", {
                            project_id: props.project_id.id,
                        })
                    );
                }, 2000);
            },
        }
    );
};

const submitEdit = () => {
    form.post(
        route("projectmanagement.updateAdditionalCost", {
            additional_cost: form.id,
        }),
        {
            onSuccess: () => {
                closeEditModal();
                showModalEdit.value = true;
                setTimeout(() => {
                    showModalEdit.value = false;
                    router.visit(
                        route("projectmanagement.additionalCosts", {
                            project_id: props.project_id.id,
                        })
                    );
                }, 2000);
            },
            onError: (e) => {
                console.log(e);
            },
        }
    );
};

const confirmDeleteAdditional = (additionalId) => {
    docToDelete.value = additionalId;
    confirmingDocDeletion.value = true;
};

const closeModalDoc = () => {
    confirmingDocDeletion.value = false;
};

const deleteAdditional = () => {
    const docId = docToDelete.value;
    if (docId) {
        router.delete(
            route("projectmanagement.deleteAdditionalCost", {
                project_id: props.project_id.id,
                additional_cost: docId,
            }),
            {
                onSuccess: () => {
                    closeModalDoc();
                    router.visit(
                        route("projectmanagement.additionalCosts", {
                            project_id: props.project_id.id,
                        })
                    );
                },
            }
        );
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
    window.open(
        route("additionalcost.archive", { additional_cost_id: id }),
        "_blank"
    );
}

const filterForm = ref({
    search: "",
    selectedZones: ["Arequipa", "Chala", "Moquegua", "Tacna", "MDD"],
    selectedExpenseTypes: [
        "Hospedaje",
        "Movilidad",
        "Peaje",
        "Seguros y Pólizas",
        "Herramientas",
        "Fletes",
        "EPPs",
        "Gastos de Representación",
        "Consumibles",
        "Equipos",
        "Otros",
    ],
    selectedDocTypes: [
        "Efectivo",
        "Deposito",
        "Factura",
        "Boleta",
        "Voucher de Pago",
    ],
});

const zones = ["Arequipa", "Chala", "Moquegua", "Tacna", "MDD"];
const expenseTypes = [
    "Hospedaje",
    "Movilidad",
    "Peaje",
    "Seguros y Pólizas",
    "Herramientas",
    "Fletes",
    "EPPs",
    "Gastos de Representación",
    "Consumibles",
    "Equipos",
    "Otros",
];
const docTypes = [
    "Efectivo",
    "Deposito",
    "Factura",
    "Boleta",
    "Voucher de Pago",
];

watch(
    () => [
        filterForm.value.selectedZones,
        filterForm.value.selectedExpenseTypes,
        filterForm.value.selectedDocTypes,
    ],
    () => {
        filterMode.value = true;
        search_advance(filterForm.value);
    },
    { deep: true }
);

async function search_advance($data) {
    let res = await axios.post(
        route("additionalcost.advance.search", {
            project_id: props.project_id.id,
        }),
        $data
    );
    dataToRender.value = res.data;
}

async function handleSearch() {
    filterMode.value = true;
    search_advance(filterForm.value);
}

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
                closeImportModal()
                confirmImport.value = true;
                setTimeout(() => {
                    confirmImport.value = false;
                    router.visit(
                        route('projectmanagement.additionalCosts', {
                                    project_id: props.project_id.id,
                                })
                    );
                }, 2000);
            },
        }
    );
}
</script>
