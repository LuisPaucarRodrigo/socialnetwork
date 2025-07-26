<template>
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
</template>