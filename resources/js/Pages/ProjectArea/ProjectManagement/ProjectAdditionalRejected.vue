<template>

    <Head title="Proyectos" />
    <AuthenticatedLayout :redirectRoute="{
        route: 'projectmanagement.pext.additional.index',
        params: {type:type}
    }">
        <template #header>
            Proyectos Adicionales - No Proceden
        </template>
        <Toaster richColors />
        <div class="min-w-full">
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <div class="flex space-x-3">
                    <TextInput data-tooltip-target="search_fields" type="text" @input="search($event.target.value)"
                        placeholder="Buscar ..." />
                    <div id="search_fields" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Nombre,Codigo,CPE
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
            </div>
            <br>
           <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <div v-for="item in projects.data || projects" :key="item.id"
                    class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
                    <div class="grid grid-cols-2">
                        <p class="col-start-1 col-span-1 text-sm font-semibold mb-3">
                            Nombre: {{ item.project_name }}
                        </p>
                        <div v-if="hasPermission('ProjectManager')" class="inline-flex justify-end items-start gap-x-2">
                            <button type="button" @click="editProject(item)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-400">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </button>
                        </div>
                        <p class="col-start-1 col-span-2 text-sm font-semibold mb-3">
                            Fecha: {{ formattedDate(item.assignation_date) }}
                        </p>
                        <p class="col-start-1 col-span-2 text-sm font-semibold mb-3">
                            Cliente: {{ item.customer }}
                        </p>
                        <p class="col-start-1 col-span-2 text-sm font-semibold mb-3">
                            Centro de Costos: {{ item.project?.cost_center?.name }}
                        </p>
                        <p class="col-start-1 col-span-2 text-sm font-semibold mb-3">
                            Codigo: {{ item.project_code }}
                        </p>
                        <p class="col-start-1 col-span-2 text-sm font-semibold mb-3">
                            CPE: {{ item.cpe }}
                        </p>
                        <p class="col-start-1 col-span-2 text-sm font-semibold mb-3">
                            Zonas: {{ item.zone }}
                        </p>
                    </div>
                    <div>
                        <div class="grid grid-cols-1 gap-y-1">
                            <Link
                                :href="route('pext.additional.expense.index', { project_id: item.project.id, fixedOrAdditional: false, type })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                            Compras y Gastos
                            </Link>
                            <div class="flex gap-x-3">
                                <button @click="openQuickQuote(item.project)"
                                    class="text-blue-600 underline hover:text-purple-600">
                                    Cotización Rapida
                                </button>
                                <a v-if="item.project.project_quote"
                                    :href="route('projectmanagement.pext.export.pdf.quote', { project_id: item.project.id })"
                                    target="_blank" rel="noopener noreferrer">
                                    <svg class="h-5 w-5 text-green-200" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.29289 1.29289C9.48043 1.10536 9.73478 1 10 1H18C19.6569 1 21 2.34315 21 4V9C21 9.55228 20.5523 10 20 10C19.4477 10 19 9.55228 19 9V4C19 3.44772 18.5523 3 18 3H11V8C11 8.55228 10.5523 9 10 9H5V20C5 20.5523 5.44772 21 6 21H7C7.55228 21 8 21.4477 8 22C8 22.5523 7.55228 23 7 23H6C4.34315 23 3 21.6569 3 20V8C3 7.73478 3.10536 7.48043 3.29289 7.29289L9.29289 1.29289ZM6.41421 7H9V4.41421L6.41421 7ZM19 12C19.5523 12 20 12.4477 20 13V19H23C23.5523 19 24 19.4477 24 20C24 20.5523 23.5523 21 23 21H19C18.4477 21 18 20.5523 18 20V13C18 12.4477 18.4477 12 19 12ZM11.8137 12.4188C11.4927 11.9693 10.8682 11.8653 10.4188 12.1863C9.96935 12.5073 9.86526 13.1318 10.1863 13.5812L12.2711 16.5L10.1863 19.4188C9.86526 19.8682 9.96935 20.4927 10.4188 20.8137C10.8682 21.1347 11.4927 21.0307 11.8137 20.5812L13.5 18.2205L15.1863 20.5812C15.5073 21.0307 16.1318 21.1347 16.5812 20.8137C17.0307 20.4927 17.1347 19.8682 16.8137 19.4188L14.7289 16.5L16.8137 13.5812C17.1347 13.1318 17.0307 12.5073 16.5812 12.1863C16.1318 11.8653 15.5073 11.9693 15.1863 12.4188L13.5 14.7795L11.8137 12.4188Z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div v-if="projects.data" class="flex flex-col items-center px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="projects.links" />
            </div>
        </div>

        <Modal :show="showModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    {{ form.id ? 'Editar Asignación' : 'Nueva Asignación' }}
                </h2>
                <br>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div class="">
                            <InputLabel for="manager">Gestor</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.manager" autocomplete="off" id="manager"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.manager" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="assignation_date">Fecha de Asignación</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.assignation_date" autocomplete="off"
                                    id="assignation_date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.assignation_date" />
                            </div>
                        </div>

                        <div class="">
                            <InputLabel for="project_name">Nombre del Proyecto</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.project_name" autocomplete="off" id="project_name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.project_name" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="customer">Cliente</InputLabel>
                            <div class="mt-2">
                                <select id="customer" v-model="form.customer"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">Seleccionar Cliente</option>
                                    <option>CICSA</option>
                                    <option>STL</option>
                                </select>
                                <InputError :message="form.errors.customer" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="cost_center">Centro de Costos</InputLabel>
                            <div class="mt-2">
                                <select id="cost_center" v-model="form.cost_center_id"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">Seleccionar Centro de Costo</option>
                                    <option v-for="item in cost_line.cost_center" :key="item.id" :value="item.id">{{
                                        item.name
                                    }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.cost_center_id" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="project_code">Codigo de Proyecto</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.project_code" autocomplete="off" id="project_code"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.project_code" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="cpe">CPE</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.cpe" autocomplete="off" id="cpe"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.cpe" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="zone">Zona</InputLabel>
                            <div class="mt-2">
                                <select id="zone" v-model="form.zone" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">Seleccionar Zona</option>
                                    <option>Arequipa</option>
                                    <option>Moquegua</option>
                                    <option>Tacna</option>
                                    <option>Cuzco</option>
                                    <option>Puno</option>
                                    <option>MDD</option>
                                </select>
                                <InputError :message="form.errors.zone" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="zone2">Zona2 (Opcional)</InputLabel>
                            <div class="mt-2">
                                <select id="zone2" v-model="form.zone2" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">Seleccionar Zona</option>
                                    <option>Arequipa</option>
                                    <option>Moquegua</option>
                                    <option>Tacna</option>
                                    <option>Cuzco</option>
                                    <option>Puno</option>
                                    <option>MDD</option>
                                </select>
                                <InputError :message="form.errors.zone2" />
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="createOrEditModal"> Cancelar </SecondaryButton>
                        <PrimaryButton class="ml-3 tracking-widest uppercase text-xs"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                            Guardar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
        <Modal :show="showQuickQuote">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Cotización Rapida
                </h2>
                <form @submit.prevent="submitQuickQuote">
                    <div class="space-y-12 mt-4">
                        <div class="grid sm:grid-cols-2 gap-6 pb-6">
                            <div>
                                <InputLabel for="delivery_place">
                                    Lugar de Entrega
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="formQuote.delivery_place" id="delivery_place" />
                                    <InputError :message="formQuote.errors.delivery_place" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="delivery_time">
                                    Tiempo de Entrega
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="number" v-model="formQuote.delivery_time" id="delivery_time" />
                                    <InputError :message="formQuote.errors.delivery_time" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="observations">
                                    Observaciones
                                </InputLabel>
                                <div class="mt-2">
                                    <textarea class="w-full rounded-xl" v-model="formQuote.observations"
                                        id="observations" />
                                    <InputError :message="formQuote.errors.observations" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="observations">
                                    Tiene Fee?
                                </InputLabel>
                                <div class="mt-2 class flex gap-4">
                                    <label class="flex gap-2 items-center">
                                        Sí
                                        <input type="radio" v-model="formQuote.fee" id="discount_sctr" :value="true"
                                            class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                    </label>
                                    <label class="flex gap-2 items-center">
                                        No
                                        <input type="radio" v-model="formQuote.fee" id="discount_sctr" :value="false"
                                            class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                    </label>
                                    <InputError :message="formQuote.errors.fee" />
                                </div>
                            </div>
                        </div>

                        <div class="pb-6">
                            <div class="flex items-center gap-x-2">
                                <InputLabel for="valuation">
                                    Valoración
                                </InputLabel>
                                <button class="text-green-500" type="button" @click="openModalValuation">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-4 overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr
                                            cclass="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                                Descripción</th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                                Unidad</th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                                Dias</th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                                Metrado</th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                                Valor Unitario</th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                                Valor Total</th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                                Acciones</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in formQuote.project_quote_valuations" :key="index">
                                            <td
                                                class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                                                {{
                                                    item.description }}</td>
                                            <td
                                                class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                                                {{
                                                    item.unit }}</td>
                                            <td
                                                class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                                                {{
                                                    item.days }}</td>
                                            <td
                                                class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                                                {{
                                                    item.metrado }}</td>
                                            <td
                                                class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                                                {{
                                                    item.unit_value }}</td>
                                            <td
                                                class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                                                {{
                                                    item.days * item.metrado * item.unit_value }}</td>
                                            <td
                                                class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                                                <button @click="deleteValoration(index)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-6 h-6 text-red-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <InputError :message="formQuote.errors.project_quote_valuations" />
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-3">
                            <SecondaryButton @click="closeQuickQuote">
                                Cancelar
                            </SecondaryButton>
                            <PrimaryButton type="submit" :class="{ 'opacity-25': formQuote.processing }"
                                :disabled="formQuote.processing">
                                Guardar
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <Modal :show="showValuation">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    Agregar Valoraciòn
                </h2>
                <br>

                <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                    <div class="">
                        <InputLabel for="days">Dias</InputLabel>
                        <div class="mt-2">
                            <input type="number" v-model="valuation.days" autocomplete="off" id="days"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                        </div>
                    </div>
                    <div class="">
                        <InputLabel for="unit">Unidad</InputLabel>
                        <div class="mt-2">
                            <select id="unit" v-model="valuation.unit"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option value="">Seleccionar Unidad</option>
                                <option>UNIDAD</option>
                                <option>METROS</option>
                                <option>CAJA</option>
                                <option>GLB</option>
                                <option>METROS 2</option>
                                <option>METROS 3</option>
                                <option>PIEZA</option>
                                <option>LATA</option>
                                <option>PAQUETE</option>
                                <option>ROLLO</option>
                            </select>
                        </div>
                    </div>
                    <div class="">
                        <InputLabel for="metrado">Metrado</InputLabel>
                        <div class="mt-2">
                            <input type="number" v-model="valuation.metrado" autocomplete="off" id="metrado"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />

                        </div>
                    </div>
                    <div class="">
                        <InputLabel for="unit_value">Valor Unitario</InputLabel>
                        <div class="mt-2">
                            <input type="number" v-model="valuation.unit_value" autocomplete="off" id="unit_value"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />

                        </div>
                    </div>
                    <div class="">
                        <InputLabel for="description">Descripción</InputLabel>
                        <div class="mt-2">
                            <textarea class="w-full rounded-xl" v-model="valuation.description" />
                        </div>
                    </div>
                </div>
                <br>
                <div class="mt-6 flex gap-3 justify-end">
                    <SecondaryButton type="button" @click="openModalValuation"> Cerrar </SecondaryButton>
                    <PrimaryButton type="button" @click="addValuation"> Agregar </PrimaryButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import Dropdown from '@/Components/Dropdown.vue';
import axios from 'axios';
import { ref } from 'vue';
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { formattedDate, setAxiosErrors } from '@/utils/utils';
import { notifyError, notify } from '@/Components/Notification';
import { Toaster } from 'vue-sonner';

const { project, auth, userPermissions, cost_line, type } = defineProps({
    project: Object,
    userPermissions: Array,
    auth: Object,
    cost_line: Object,
    type: Number,
})

const initialState = {
    id: null,
    user_id: auth.user.id,
    assignation_date: '',
    project_name: '',
    cost_line_id: cost_line.id,
    cost_center_id: '',
    customer: '',
    project_code: '',
    cpe: '',
    zone: '',
    zone2: '',
    manager: '',
    user_name: auth.user.name,
}

const initialStateQuote = {
    project_id: '',
    delivery_place: '',
    delivery_time: null,
    observations: '',
    fee: '',
    project_quote_valuations: [],
}

const form = useForm({ ...initialState })
const formQuote = useForm({ ...initialStateQuote })
const valuation = ref({
    days: '',
    unit: '',
    metrado: '',
    unit_value: 0,
    user_id: auth.user.id,
    description: ''
});
// const formExport = ref({
//     startDate: "",
//     endDate: ""
// })
// const modalExport = ref(false)

const hasPermission = (permission) => {
    return userPermissions.includes(permission);
}

const showModal = ref(false)
const projects = ref(project);
const showQuickQuote = ref(false)
const showValuation = ref(false)
// const confirmingProjectDeletion = ref(false);
// const projectToDelete = ref('');

function editProject(pext) {
    form.defaults({ ...pext, cost_center_id: pext.project.cost_center.id })
    form.reset()
    createOrEditModal()
}

// const delete_project = () => {
//     const projectId = projectToDelete.value;
//     router.delete(route('projectmanagement.delete', { project_id: projectId }), {
//         onSuccess: () => {
//             createOrEditModal()
//             router.visit(route('projectmanagement.index'))
//         }
//     });
// }

// const confirmProjectDeletion = (employeeId) => {
//     projectToDelete.value = employeeId;
//     confirmingProjectDeletion.value = true;
// };

const createOrEditModal = () => {
    if (showModal.value) {
        form.defaults({ ...initialState })
        form.reset()
    }
    showModal.value = !showModal.value

};

const search = async ($search) => {
    try {
        const response = await axios.post(route('projectmanagement.pext.additional.index_rejected', {type}), { searchQuery: $search });
        projects.value = response.data;
    } catch (error) {
        notifyError('Error searching:', error);
    }
};

async function submit() {
    let url = route('projectmanagement.pext.additional.store', { 'cicsa_assignation_id': form.id ?? null })
    try {
        let response = await axios.post(url, {...form.data()})
        let action = form.id ? 'update' : 'create'
        updatePext(response.data, action)
    } catch (error) {
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, form)
            } else {
                notifyError("Server error:", error.response.data)
            }
        } else {
            notifyError("Network or other error:", error)
        }
    }
}

function updatePext(pext, action) {
    const validations = projects.value.data || projects.value
    if (action === "create") {
        validations.unshift(pext);
        createOrEditModal()
        notify('Creacion Exitosa')
    } if (action === "update") {
        let index = validations.findIndex(item => item.id === pext.id)
        validations[index] = pext
        createOrEditModal()
        notify('Actualización Exitosa')
    } if (action === "delete") {
        let index = validations.findIndex(item => item.id === pext.id)
        validations[index].splice(index, 1)
        notify('Eliminación Exitosa')
    } if (action === "updateQuote") {
        let index = validations.findIndex(item => item.project_id === pext.project_id)
        validations[index].project.project_quote = pext
        notify('Cotizacion Exitosa')
    }

    if (validations.length > projects.value.per_page) {
        validations.pop();
    }
}

function openQuickQuote(project) {
    const defaultData = project.project_quote || initialStateQuote;
    defaultData.fee = defaultData.fee ? true : false 
    formQuote.defaults({ ...defaultData, project_id: project.id });
    formQuote.reset();
    showQuickQuote.value = !showQuickQuote.value;
}

function closeQuickQuote() {
    showQuickQuote.value = !showQuickQuote.value;
    formQuote.clearErrors();
    formQuote.defaults({ ...initialStateQuote });
    formQuote.reset();
}

function openModalValuation() {
    showValuation.value = !showValuation.value
}

function addValuation() {
    if (valuation.value.description && valuation.value.metrado && valuation.value.unit && valuation.value.unit_value && valuation.value.days) {
        const newvaluation = {
            description: valuation.value.description,
            metrado: valuation.value.metrado,
            unit: valuation.value.unit,
            unit_value: valuation.value.unit_value,
            days: valuation.value.days
        };
        formQuote.project_quote_valuations.push(newvaluation);
        valuation.value.description = '';
        valuation.value.metrado = '';
        valuation.value.unit = '';
        valuation.value.days = '';
        valuation.value.unit_value = '';
    } else {
        notifyError('Por favor completa todos los campos del formulario.');
    }
}

async function submitQuickQuote() {
    let url = route('projectmanagement.pext.store.quote', { project_quote_id: formQuote.id })
    try {
        let response = await axios.post(url, formQuote)
        closeQuickQuote()
        updatePext(response.data, 'updateQuote')
    } catch (error) {
        console.log(error)
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, formQuote)
            } else {
                notifyError("Server error:", error.response.data)
            }
        } else {
            notifyError("Network or other error:", error)
        }
    }
}

function deleteValoration(index) {
    formQuote.valuations.splice(index, 1)
}



// function modalExportExcel() {
//     modalExport.value = !modalExport.value
// }

// async function exportExcel() {
//     const uniqueParam = `timestamp=${new Date().getTime()}`;
//     let url =
//         route("projectmanagement.pext.export.expenses") +
//         `?start_date=${encodeURIComponent(formExport.startDate)}&end_date=${encodeURIComponent(formExport.endDate)}&${uniqueParam}`;
//     window.location.href = url;
//     modalExportExcel()
// }
</script>
