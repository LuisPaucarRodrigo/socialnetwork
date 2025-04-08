<template>

    <Head title="Gestion de Costos Adicionales" />
    <AuthenticatedLayout :redirectRoute="{
        route: 'projectmanagement.additionalCosts',
        params: { project_id: project_id.id },
    }">
        <template #header>
            Gastos Variables
            <span class="text-red-600"> Rechazados </span>
            del Proyecto {{ props.project_id.name }}
        </template>
        <Toaster richColors />
        <br />
        <div class="inline-block min-w-full mb-4">
            <div class="flex gap-4 justify-end">
                <Search v-model:search="filterForm.search" fields="Por definir"/>
            </div>
        </div>

        <div class="overflow-x-auto h-[85vh]">
            <table class="w-full whitespace-no-wrap">
                <thead class="sticky top-0 z-20">
                    <tr
                        class=" border-b bg-gray-50 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableHeaderFilter labelClass="text-[11px]" label="Zona" :options="zones"
                                v-model="filterForm.selectedZones" width="w-32" />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableHeaderFilter labelClass="text-[11px]" label="Tipo de Gasto" :options="expenseTypes"
                                v-model="filterForm.selectedExpenseTypes" width="w-48" />
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
                            Numero de Doc
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Fecha de Documento
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
                        <th v-if="
                            project_id.status === null
                        "
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in dataToRender" :key="item.id" class="text-gray-700" :class="[
                        'border-l-8',
                        {
                            'border-indigo-500': item.is_accepted === null,
                            'border-green-500': item.is_accepted == true,
                            'border-red-500': item.is_accepted == false,
                        },
                    ]">
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
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            {{ item.ruc }}
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            <p class="line-clamp-2 hover:line-clamp-none">
                                {{ item?.provider?.company_name }}
                            </p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            {{ item.doc_number }}
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            {{ formattedDate(item.doc_date) }}
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] whitespace-nowrap">
                            S/. {{ item.amount.toFixed(2) }}
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] whitespace-nowrap">
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
                        <td v-if="
                            project_id.status === null
                        " class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            <div class="flex items-center gap-3 w-full">
                                <button @click="
                                    () => openAcceptModal(item)
                                " class="flex items-center rounded-xl text-blue-700 hover:bg-green-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15m0-3-3-3m0 0-3 3m3-3V15" />
                                    </svg>
                                </button>


                                <div class="flex gap-3 mr-3">
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
                        <td class="font-bold border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            TOTAL
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm"></td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm"></td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm"></td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm"></td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm"></td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm"></td>
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
                                    ?.reduce(
                                        (a, item) => a + item.real_amount,
                                        0
                                    )
                                    .toFixed(2)
                            }}
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm"></td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm"></td>
                        <td v-if="
                            project_id.status === null
                        " class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <div class="flex items-center"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <Modal :show="create_additional">
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
                                        <option>Arequipa</option>
                                        <option>Chala</option>
                                        <option>Moquegua</option>
                                        <option>Tacna</option>
                                        <option>MDD1</option>
                                        <option>MDD2</option>
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
                                        <option>Efectivo</option>
                                        <option>Deposito</option>
                                        <option>Factura</option>
                                        <option>Boleta</option>
                                        <option>Voucher de Pago</option>
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

                            <div v-if="
                                form.type_doc === 'Factura' &&
                                !['', 'MDD1', 'MDD2'].includes(form.zone)
                            ">
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

                            <div v-if="
                                form.type_doc === 'Factura' &&
                                !['', 'MDD1', 'MDD2'].includes(form.zone)
                            ">
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
                            <button type="submit" :class="{ 'opacity-25': form.processing }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
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
                        <div class="border-b grid sm:grid-cols-2 gap-6 border-gray-900/10 pb-12">
                            <div>
                                <InputLabel for="zone" class="font-medium leading-6 text-gray-900">Zona</InputLabel>
                                <div class="mt-2">
                                    <select v-model="form.zone" id="zone"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">
                                            Seleccionar
                                        </option>
                                        <option>Arequipa</option>
                                        <option>Chala</option>
                                        <option>Moquegua</option>
                                        <option>Tacna</option>
                                        <option>MDD1</option>
                                        <option>MDD2</option>
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
                                        <option>Efectivo</option>
                                        <option>Deposito</option>
                                        <option>Factura</option>
                                        <option>Boleta</option>
                                        <option>Voucher de Pago</option>
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
                            <div v-if="
                                form.type_doc === 'Factura' &&
                                !['', 'MDD1', 'MDD2'].includes(form.zone)
                            ">
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
                            <div v-if="
                                form.type_doc === 'Factura' &&
                                !['', 'MDD1', 'MDD2'].includes(form.zone)
                            ">
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
                                        <button type="button" @click="
                                            () => {
                                                form.photo_status =
                                                    'delete';
                                            }
                                        ">
                                            <TrashIcon class="text-red-500 h-4 w-4" />
                                        </button>
                                    </div>
                                    <div v-if="form.photo_status === 'delete'"
                                        class="text-amber-700 mt-3 text-sm flex space-x-2">
                                        <span>
                                            El documento esta por ser borrado,
                                        </span>
                                        <button @click="
                                            () => {
                                                form.photo_status =
                                                    'stable';
                                            }
                                        " type="button" class="font-black">
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
                            <button type="submit" :disabled="isFetching" :class="{ 'opacity-25': isFetching }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Importar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>
        <Modal :show="showAcceptModal" @close="closeAcceptModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900 mb-2">
                    Ejecutar Levantamiento
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
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="Costo Adicional" />
        <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Costo Adicional" />
        <SuccessOperationModal :confirming="confirmImport" :title="'Datos Importados'"
            :message="'Los datos fueron importados con éxito'" />
        <SuccessOperationModal :confirming="confirmValidation" :title="'Levantamiento'"
            :message="'El gasto paso a ser aceptado'" />
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
import InputFile from "@/Components/InputFile.vue";
import { EyeIcon } from "@heroicons/vue/24/outline";
import TableHeaderFilter from "@/Components/TableHeaderFilter.vue";
import axios from "axios";
import { setAxiosErrors, toFormData } from "@/utils/utils";
import { notify, notifyError } from "@/Components/Notification";
import { Toaster } from "vue-sonner";
import Search from "@/Components/Search.vue";


const props = defineProps({
    additional_costs: Object,
    project_id: Object,
    providers: Object,
    auth: Object,
    userPermissions: Array,
    searchQuery: String,
    state: String,
});
console.log(props.additional_costs)
const dataToRender = ref(props.additional_costs);
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
    igv: 0,
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
    form.igv = editingAdditional.value.igv;
    form.description = editingAdditional.value.description;
    form.zone = editingAdditional.value.zone;
    form.provider_id = editingAdditional.value.provider_id;
    form.photo_name = editingAdditional.value.photo;

    editAdditionalModal.value = true;
};

const closeModal = () => {
    form.reset();
    isFetching.value = false
    create_additional.value = false;
};

const closeEditModal = () => {
    form.reset();
    isFetching.value = false
    editAdditionalModal.value = false;
};

const isFetching = ref(false)

const submit = async () => {
    try {
        isFetching.value = true
        const formToSend = toFormData(form.data())
        const res = await axios.post(
            route("projectmanagement.storeAdditionalCost", {
                project_id: props.project_id.id,
            }), formToSend)
        dataToRender.value.unshift(res.data)
        closeModal();
        notify('Gasto Adicional Guardado')
    } catch (e) {
        isFetching.value = false
        if (e.response?.data?.errors) {
            setAxiosErrors(e.response.data.errors, form)
        } else {
            notifyError('Server Error')
        }
    }
};

const submitEdit = async () => {
    try {
        isFetching.value = true
        const formToSend = toFormData(form.data())
        const res = await axios.post(
            route("projectmanagement.updateAdditionalCost", {
                additional_cost: form.id,
            }), formToSend)
        let index = dataToRender.value.findIndex(item => item.id == form.id)
        dataToRender.value[index] = res.data
        closeEditModal();
        notify('Gasto Adicional Actualizado')
    } catch (e) {
        isFetching.value = false
        if (e.response?.data?.errors) {
            setAxiosErrors(e.response.data.errors, form)
        } else {
            notifyError('Server Error')
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
    isFetching.value = true
    try {
        const res = await axios.delete(
            route("projectmanagement.deleteAdditionalCost", {
                project_id: props.project_id.id,
                additional_cost: docId,
            }))
        isFetching.value = false
        if (res?.data?.msg === 'success') {
            closeModalDoc()
            notify('Gasto Adicional Eliminado')
            let index = dataToRender.value.findIndex(item => item.id == docId)
            dataToRender.value.splice(index, 1);
        }
    } catch (e) {
        isFetching.value = false
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
    selectedZones: ["Arequipa", "Chala", "Moquegua", "Tacna", "MDD1", "MDD2"],
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

const zones = ["Arequipa", "Chala", "Moquegua", "Tacna", "MDD1", "MDD2"];
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
        filterForm.value.search,
    ],
    () => {
        filterMode.value = true;
        search_advance(filterForm.value);
    },
    { deep: true }
);

async function search_advance(data) {
    let res = await axios.post(
        route("additionalcost.advance.search", {
            project_id: props.project_id.id,
        }),
        { ...data, state: false }
    );
    dataToRender.value = res.data;
}

// async function handleSearch() {
//     filterMode.value = true;
//     search_advance(filterForm.value);
// }

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
                        route("projectmanagement.additionalCosts.rejected", {
                            project_id: props.project_id.id,
                        })
                    );
                }, 2000);
            },
        }
    );
}

function openExportExcel() {
    const uniqueParam = `timestamp=${new Date().getTime()}`;
    const url =
        route("additionalcost.excel.export", {
            project_id: props.project_id.id,
        }) +
        "?" +
        uniqueParam;
    window.location.href = url;
}

watch([() => form.type_doc, () => form.zone], () => {
    if (
        form.type_doc === "Factura" &&
        !["", "MDD1", "MDD2"].includes(form.zone)
    ) {
        form.igv = form.igv ? form.igv : 18;
    } else {
        form.igv = 0;
    }
});

const confirmValidation = ref(false);

// async function validateRegister(ac_id, is_accepted) {
//     try {
//         const res = await axios.post(
//             route("projectmanagement.validateAdditionalCost", { ac_id }),
//             { is_accepted }
//         );
//         if (res?.status === 200) {
//             let index = dataToRender.value.findIndex(
//                 (item) => item.id == res.data.additional_cost.id
//             );
//             dataToRender.value.splice(index, 1);
//         }
//         confirmValidation.value = true;
//         setTimeout(() => {
//             confirmValidation.value = false;
//         }, 1000);
//     } catch (e) {
//         console.log(e);
//     }
// }



const opNuDateForm = useForm({
    operation_date: '',
    operation_number: '',
})
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
    let index = dataToRender.value.findIndex((item) => item.id == res.data.additional_cost.id);
    dataToRender.value.splice(index, 1);
    closeAcceptModal();
    notify(res.data.msg)
}



</script>
