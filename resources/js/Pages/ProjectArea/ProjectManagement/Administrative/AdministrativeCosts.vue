<template>

    <Head title="Costos Administrativos" />
    <AuthenticatedLayout :redirectRoute="'monthproject.index'">
        <template #header>
            Gastos Administativos del Proyecto {{ month_project_id.name.toUpperCase() }}
        </template>
        <br />
        <Toaster richColors />
        <div class="inline-block min-w-full mb-4">
            <div class="flex gap-4 items-center justify-between">
                <div class="flex space-x-3">
                    <PrimaryButton @click="openCreateAdditionalModal" type="button" class="whitespace-nowrap">
                        + Agregar
                    </PrimaryButton>
                    <PrimaryButton data-tooltip-target="update_data_tooltip" type="button" @click="() => {
                        filterMode = true;
                        search_advance(initialFilterFormState);
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
                        @click="openExportPhoto" data-tooltip-target="export-photo-tooltip">
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
                                    </div>
                                </div>
                            </template>
                        </dropdown>
                    </div>
                </div>
                <Search v-model:search="filterForm.search"
                    fields="Ruc , Numero de Documento, Numero de Operacion, Descripcion, Monto Total" />
            </div>
        </div>
        <AdministrativeTable v-model:dataToRender="dataToRender" :actionForm="actionForm" :filterForm="filterForm"
            :openEditAdditionalModal="openEditAdditionalModal" :confirmDeleteAdditional="confirmDeleteAdditional"
            :expenseTypes="expenseTypes" :docTypes="docTypes" :zones="zones" :stateTypes="stateTypes"
            v-model:loading="loading" />
        <Modal :show="create_additional">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Agregar Costo Administrativo
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
                                        <option v-for="op in zones">{{ op }}</option>
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
                                <InputLabel for="amount" class="font-medium leading-6 text-gray-900">Monto Total
                                </InputLabel>
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
                                    Foto de Factura
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

        <Modal :show="editAdditionalModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Editar Costo Administrativo
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
                                        <option v-for="op in zones">{{ op }}</option>
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
                                    Foto de Factura
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
                                        <a :href="route('administrativeCosts.archive', {
                                            static_cost_id: form.id,
                                        })
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
        <SuspenseWrapper :when="showUpdateMassive">
            <template #component>
                <UpdateMassive ref="updateMassive" v-model:dataToRender="dataToRender" :actionForm="actionForm" />
            </template>
        </SuspenseWrapper>

        <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Gasto Administrativo"
            :deleteFunction="deleteAdditional" @closeModal="closeModalDoc" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ConfirmDeleteModal from "@/Components/ConfirmDeleteModal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import { defineAsyncComponent, onMounted, ref, watch } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputFile from "@/Components/InputFile.vue";
import axios from "axios";
import { setAxiosErrors, toFormData } from "@/utils/utils";
import { notify, notifyError, notifyWarning } from "@/Components/Notification";
import { Toaster } from "vue-sonner";
import Dropdown from "@/Components/Dropdown.vue";
import Search from "@/Components/Search.vue";
import { MenuIcon, DeleteIcon, ServerIcon, ExcelIcon } from "@/Components/Icons";
import SuspenseWrapper from "@/Components/SuspenseWrapper.vue";
import { useLazyRefInvoker } from "@/utils/useLazyRefInvoker";
import AdministrativeTable from "./components/AdministrativeTable.vue";

const UpdateMassive = defineAsyncComponent(() => import('./components/UpdateMassive.vue'));

const props = defineProps({
    month_project_id: Object,
    providers: Object,
    auth: Object,
    searchQuery: String,
    zones: Array,
    expenseTypes: Array,
    docTypes: Array,
    stateTypes: Array,
});

const { expenseTypes, docTypes, zones, stateTypes } = props
expenseTypes.sort()
docTypes.sort()
zones.sort()
stateTypes.sort()

const showUpdateMassive = ref(false)
const updateMassive = ref(null)

const dataToRender = ref([]);
const filterMode = ref(false);
const loading = ref(true)

const { invokeWhenReady: invokeUpdateMassive } = useLazyRefInvoker(updateMassive, showUpdateMassive);


onMounted(async () => {
    const res = await axios.get(route('getExpenses.administrativeCosts', { month_project_id: props.month_project_id.id }));
    dataToRender.value = res.data;
    loading.value = false;
});

const form = useForm({
    id: "",
    expense_type: "",
    ruc: "",
    zone: "",
    provider_id: "",
    month_project_id: props.month_project_id.id,
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
    form.igv = editingAdditional.value.igv;
    form.type_doc = editingAdditional.value.type_doc;
    form.operation_number = editingAdditional.value.operation_number
    form.operation_date = editingAdditional.value.operation_date
    form.doc_number = editingAdditional.value.doc_number;
    form.doc_date = editingAdditional.value.doc_date;
    form.description = editingAdditional.value.description;
    form.zone = editingAdditional.value.zone;
    form.provider_id = editingAdditional.value.provider_id;
    form.photo_name = editingAdditional.value.photo;

    editAdditionalModal.value = true;
};

const closeModal = () => {
    form.reset();
    form.clearErrors()
    isFetching.value = false
    create_additional.value = false;
};

const closeEditModal = () => {
    form.reset();
    form.clearErrors()
    isFetching.value = false
    editAdditionalModal.value = false;
};

const isFetching = ref(false)

const submit = async () => {
    try {
        isFetching.value = true
        const formToSend = toFormData(form.data())
        const res = await axios.post(
            route("projectmanagement.storeAdministrativeCost", {
                month_project_id: props.month_project_id.id,
            }), formToSend)
        let listData = dataToRender.value.data || dataToRender.value
        listData.unshift(res.data)
        closeModal();
        notify('Gasto Administrativo Guardado')
    } catch (e) {
        isFetching.value = false
        if (e.response?.data?.errors) {
            setAxiosErrors(e.response.data.errors, form)
        }
        notifyError('Server Error')
    }
};


const submitEdit = async () => {
    try {

        isFetching.value = true
        const formToSend = toFormData(form.data())
        const res = await axios.post(
            route("projectmanagement.updateAdministrativeCost", {
                additional_cost: form.id,
            }), formToSend)
        let listData = dataToRender.value.data || dataToRender.value
        let index = listData.findIndex(item => item.id == form.id)
        listData[index] = res.data
        closeEditModal();
        notify('Gasto Administrativo Actualizado')
    } catch (e) {
        isFetching.value = false
        if (e.response?.data?.errors) {
            setAxiosErrors(e.response.data.errors, form)
        }
        notifyError('Server Error')
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
    if (docId) {
        const res = await axios.delete(
            route("projectmanagement.deleteAdministrativeCost", {
                month_project_id: props.month_project_id.id,
                additional_cost: docId,
            }))
        if (res?.data?.msg === 'success') {
            closeModalDoc()
            notify('Gasto Administrativo Eliminado')
            let listData = dataToRender.value.data || dataToRender.value
            let index = listData.findIndex(item => item.id == docId)
            listData.splice(index, 1);
        }
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



function openExportPhoto() {
    const uniqueParam = `timestamp=${new Date().getTime()}`;
    const url =
        route("zip.administrative.descargar", { month_project_id: props.month_project_id.id }) +
        "?" +
        uniqueParam;
    window.location.href = url;
}


const initialFilterFormState = {
    search: "",
    selectedZones: [...zones],
    selectedExpenseTypes: [...expenseTypes],
    selectedDocTypes: [...docTypes],
    selectedStateTypes: [...stateTypes],
    opStartDate: "",
    opEndDate: "",
    opNoDate: false,
    docStartDate: "",
    docEndDate: "",
    docNoDate: false,
}

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
    },
    { deep: true }
);


async function search_advance(data) {
    const $search = data || filterForm.value
    let res = await axios.post(
        route("administrativeCosts.advance.search", {
            month_project_id: props.month_project_id.id,
        }), $search);
    dataToRender.value = res.data;
    notifyWarning(`Se encontraron ${res.data.length} registro(s)`)
}


function openExportExcel() {
    const uniqueParam = `timestamp=${new Date().getTime()}`;
    const url =
        route("administrativeCosts.excel.export", {
            month_project_id: props.month_project_id.id,
        }) +
        "?" +
        uniqueParam;
    window.location.href = url;
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

//block actions

const actionForm = ref({
    ids: [],
});


watch(
    () => filterForm.value,
    () => {
        actionForm.value = { ids: [] };
    },
    { deep: true }
);

const openOpNuDaModal = () => {
    invokeUpdateMassive('openOpNuDaModal')
}


</script>
