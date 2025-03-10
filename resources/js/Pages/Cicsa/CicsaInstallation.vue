<template>

    <Head title="CICSA Asignación" />

    <AuthenticatedLayout :redirectRoute="{ route: 'cicsa.index', params: { type } }">
        <template #header> {{ type == 1 ? 'Pint' : 'Pext' }} - Instalación PINT y PEXT </template>
        <Toaster richColors />
        <div class="min-w-full">
            <div class="flex justify-between space-x-3">
                <a :href="route('cicsa.installation.export', { type }) + '?' + uniqueParam"
                    class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500">Exportar</a>
                <div class="flex items-center mt-4 space-x-3 sm:mt-0">
                    <TextInput data-tooltip-target="search_fields" type="text" @input="search($event.target.value)"
                        placeholder="Buscar ..." />
                    <SelectCicsaComponent currentSelect="Instalación PINT y PEXT" :type="type" />
                    <div id="search_fields" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Nombre,Codigo,CPE
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
            </div>
            <br />
            <TableStructure>
                <template #thead>
                    <TableTitle>Nombre del Proyecto</TableTitle>
                    <TableTitle>Código del Proyecto</TableTitle>
                    <TableTitle>Centro de Costos</TableTitle>
                    <TableTitle>CPE</TableTitle>
                    <TableTitle>Fecha PEXT</TableTitle>
                    <TableTitle>Fecha PINT</TableTitle>
                    <TableTitle>Acta de Conformidad</TableTitle>
                    <TableTitle>Informe</TableTitle>
                    <TableTitle>Materiales Recibidos</TableTitle>
                    <TableTitle>Materiales Liquidados</TableTitle>
                    <TableTitle>Fecha de Envío de Informe</TableTitle>
                    <TableTitle>Monto Proyectado sin IGV</TableTitle>
                    <TableTitle>Observaciones</TableTitle>
                    <TableTitle>Coordinador</TableTitle>
                    <TableTitle>Encargado</TableTitle>
                    <TableTitle></TableTitle>
                </template>
                <template #tbody>
                    <tr v-for="item in installations.data ?? installations" :key="item.id" class="text-gray-700">
                        <TableRow>{{ item.project_name }}</TableRow>
                        <TableRow>{{ item.project_code }}</TableRow>
                        <TableRow>{{ item.project?.cost_center?.name }}</TableRow>
                        <TableRow>{{ item.cpe }}</TableRow>
                        <TableRow>{{ formattedDate(item.cicsa_installation?.pext_date) }}</TableRow>
                        <TableRow>{{ formattedDate(item.cicsa_installation?.pint_date) }}</TableRow>
                        <TableRow>{{ item.cicsa_installation?.conformity }}</TableRow>
                        <TableRow>{{ item.cicsa_installation?.report }}</TableRow>
                        <TableRow>
                            <button v-if="item?.total_materials?.length > 0"
                                @click="openMaterialsModal(item.total_materials)">
                                <EyeIcon class="w-5 h-5 text-green-600" />
                            </button>
                        </TableRow>
                        <TableRow>
                            <button v-if="item?.cicsa_installation?.cicsa_installation_materials?.length > 0"
                                @click="openInstMaterialsModal(item.cicsa_installation.cicsa_installation_materials)">
                                <EyeIcon class="w-5 h-5 text-green-600" />
                            </button>
                        </TableRow>
                        <TableRow>{{ formattedDate(item.cicsa_installation?.shipping_report_date) }}</TableRow>
                        <TableRow>
                            {{ item.cicsa_installation?.projected_amount ? `S/
                            ${item.cicsa_installation.projected_amount.toFixed(2)}` : '' }}
                        </TableRow>
                        <TableRow>{{ item.cicsa_installation?.observation }}</TableRow>
                        <TableRow>{{ item.cicsa_installation?.coordinator }}</TableRow>
                        <TableRow>{{ item.cicsa_installation?.user_name }}</TableRow>
                        <TableRow>
                            <div class="flex space-x-3 justify-center">
                                <button @click="openEditFeasibilityModal(
                                    item.id,
                                    item.project_name,
                                    item.cpe,
                                    item.cicsa_installation,
                                    item.total_materials,
                                    item?.cicsa_installation
                                        ?.cicsa_installation_materials
                                )">
                                    <PencilSquareIcon class="w-5 h-5 text-amber-400" />
                                </button>
                                <a :href="route('cicsa.export.materials.summary', { ca_id: item.id })" class="w-5">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5 text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                </a>
                            </div>
                        </TableRow>
                    </tr>
                </template>
            </TableStructure>

            <div v-if="installations.data"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="installations.links" />
            </div>
        </div>

        <Modal :show="showAddEditModal" @close="closeAddAssignationModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    {{ form.id ? "Editar Instalación" : "Nueva Instalación" }} {{ ': ' + dateModal.project_name
                        + ' - '
                        + dateModal.cpe }}
                </h2>
                <br />
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <InputLabel for="coordinator">Coordinador</InputLabel>
                            <div class="mt-2">
                                <select id="coordinator" v-model="form.coordinator" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccionar Coordinador</option>
                                    <option v-for="opt in (
                                        type == 2
                                            ? ['Valery Joana',
                                                'Maria Moscoso',
                                                'Angela Mayela']
                                            :
                                            type == 1
                                                ? ['Sheyla Rondón']
                                                : []
                                    )" :key="opt">
                                        {{ opt }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.coordinator" />
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <InputLabel for="pext_date">Fecha de PEXT</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" v-model="form.pext_date" autocomplete="off" id="pext_date" />
                                <InputError :message="form.errors.pext_date" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="pint_date">Fecha de PINT</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" v-model="form.pint_date" autocomplete="off" id="pint_date" />
                                <InputError :message="form.errors.pint_date" />
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <InputLabel for="pint_amount">Monto Pint</InputLabel>
                            <div class="mt-2">
                                <input type="number" v-model="form.pint_amount" id="pint_amount" step="0.01"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.pint_amount" />
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <InputLabel for="pext_amount">Monto Pext</InputLabel>
                            <div class="mt-2">
                                <input type="number" v-model="form.pext_amount" id="pext_amount" step="0.01"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.pext_amount" />
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <InputLabel for="projected_amount">Monto Total</InputLabel>
                            <div class="mt-2">
                                <input type="number" disabled v-model="form.projected_amount" id="projected_amount"
                                    step="0.01"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.projected_amount" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="conformity">Acta de Conformidad</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.conformity" autocomplete="off" id="conformity"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option>Pendiente</option>
                                    <option>Completado</option>
                                </select>
                                <InputError :message="form.errors.conformity" />
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <InputLabel for="report">Informe</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.report" autocomplete="off" id="report"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option>Pendiente</option>
                                    <option>En Proceso</option>
                                    <option>Completado</option>
                                </select>
                                <InputError :message="form.errors.report" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="shipping_report_date">Fecha de envío de Informe</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" v-model="form.shipping_report_date" autocomplete="off"
                                    id="shipping_report_date" />
                                <InputError :message="form.errors.shipping_report_date" />
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <InputLabel for="observation">Observaciones</InputLabel>
                            <div class="mt-2">
                                <textarea v-model="form.observation" id="observation"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.observation" />
                            </div>
                        </div>
                    </div>
                    <br />
                    <br />
                    <div class="ring-1 p-3 text-sm ring-gray-300 rounded-md">
                        <div class="flex gap-2 items-center">
                            <b>Liquidación de Pint</b>
                        </div>
                        <br />
                        <div v-if="pintList.length > 0" class="overflow-auto">
                            <table class="w-full whitespace-no-wrap border-collapse border border-slate-300">
                                <thead>
                                    <tr
                                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                            Material
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 text-center bg-gray-100 px-4 py-2 text-gray-600">
                                            Cantidad Requerida
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 text-center bg-gray-100 px-4 py-2 text-gray-600">
                                            Cantidad Recibida
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 text-center bg-gray-100 px-4 py-2 text-gray-600">
                                            Usados
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 text-center bg-gray-100 px-4 py-2 text-gray-600">
                                            Resto
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 text-center bg-gray-100 px-4 py-2 text-gray-600">
                                            Cantidad de Materiales Conproco
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(
item, i
                                            ) in pintList" :key="i" class="text-gray-700 bg-white text-sm">
                                        <td class="border-b border-slate-300 px-2 py-4">
                                            {{ item?.name }}
                                        </td>
                                        <td class="border-b border-slate-300 text-center px-2 py-4">
                                            {{ item?.total_quantity }}
                                        </td>
                                        <td class="border-b border-slate-300 text-center px-2 py-4">
                                            {{ item?.quantity }}
                                        </td>
                                        <td class="border-b border-slate-300 px-2 py-4">
                                            <input required type="number" min="0" v-model="pintList[i][
                                                'used_quantity'
                                            ]
                                                " autocomplete="off" :max="item.quantity"
                                                class="block w-full text-center rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        </td>
                                        <td class="border-b border-slate-300 text-center px-2 py-4">
                                            {{
                                                item?.quantity -
                                                item.used_quantity
                                            }}
                                        </td>
                                        <td class="border-b border-slate-300 text-center px-2 py-4">
                                            {{ item.total_quantity && item.quantity === item.used_quantity
                                                ? Math.max(0, item.total_quantity - item.quantity)
                                                : 0 }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else>No hay materiales por liquidar</div>
                    </div>

                    <div class="ring-1 p-3 text-sm ring-gray-300 rounded-md">
                        <div class="flex gap-2 items-center">
                            <b>Liquidación de Pext</b>
                        </div>
                        <br />
                        <div v-if="pextList.length > 0" class="overflow-auto">
                            <table class="w-full whitespace-no-wrap border-collapse border border-slate-300">
                                <thead>
                                    <tr
                                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                            Material
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 text-center bg-gray-100 px-4 py-2 text-gray-600">
                                            Cantidad Requerida
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 text-center bg-gray-100 px-4 py-2 text-gray-600">
                                            Cantidad Recibida
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 text-center bg-gray-100 px-4 py-2 text-gray-600">
                                            Usados
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 text-center bg-gray-100 px-4 py-2 text-gray-600">
                                            Resto
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 text-center bg-gray-100 px-4 py-2 text-gray-600">
                                            Cantidad de Materiales Conproco
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, i) in pextList" :key="i" class="text-gray-700 bg-white text-sm">
                                        <td class="border-b border-slate-300 px-2 py-4">
                                            {{ item?.name }}
                                        </td>
                                        <td class="border-b border-slate-300 text-center px-2 py-4">
                                            {{ item?.total_quantity }}
                                        </td>
                                        <td class="border-b border-slate-300 text-center px-2 py-4">
                                            {{ item?.quantity }}
                                        </td>
                                        <td class="border-b border-slate-300 px-2 py-4">
                                            <input required type="number" min="0" v-model="pextList[i][
                                                'used_quantity'
                                            ]
                                                " autocomplete="off" :max="item.quantity"
                                                class="block w-full text-center rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        </td>
                                        <td class="border-b border-slate-300 text-center px-2 py-4">
                                            {{
                                                item?.quantity -
                                                item.used_quantity
                                            }}
                                        </td>
                                        <td class="border-b border-slate-300 text-center px-2 py-4">
                                            {{ item.total_quantity && item.quantity === item.used_quantity
                                                ? Math.max(0, item.total_quantity - item.quantity)
                                                : 0 }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else>No hay materiales por liquidar</div>
                    </div>


                    <InputError :message="form.errors.total_materials" />

                    <br />
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="closeAddAssignationModal">
                            Cancelar
                        </SecondaryButton>
                        <PrimaryButton class="ml-3 tracking-widest uppercase text-xs"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                            Guardar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <Modal :show="showModalMaterial">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Agregar un Material
                </h2>
                <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <InputLabel for="name">Nombre</InputLabel>
                        <div class="mt-2">
                            <TextInput required type="text" v-model="mateiralObject.name" id="name" />
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <InputLabel for="unit">Unidad </InputLabel>
                        <div class="mt-2">
                            <TextInput required type="text" v-model="mateiralObject.unit" id="unit" />
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <InputLabel for="quantity">Cantidad </InputLabel>
                        <div class="mt-2">
                            <TextInput required type="number" v-model="mateiralObject.quantity" id="quantity" />
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex gap-3 justify-end">
                    <SecondaryButton type="button" @click="modalMaterial">
                        Cerrar
                    </SecondaryButton>
                    <PrimaryButton type="button" @click="addMaterial">
                        Agregar
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
        <Modal :show="showMaterials" @close="closeMaterialsModal" :closeable="true">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    Total Materiales Recibidos
                </h2>
                <br />
                <div class="mt-2">
                    <div v-if="materials.length > 0" class="overflow-auto">
                        <table class="w-full whitespace-no-wrap border-collapse border border-slate-300">
                            <thead>
                                <tr
                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        N° Guía
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Material
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Unidad
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Tipo
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Cantidad Requerida
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Cantidad Recibida
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, i) in materials" :key="i" class="text-gray-700 bg-white text-sm">
                                    <td class="border-b border-slate-300 px-4 py-4">
                                        {{ item?.guide_number }}
                                    </td>
                                    <td class="border-b border-slate-300 px-4 py-4">
                                        {{ item?.name }}
                                    </td>
                                    <td class="border-b border-slate-300 px-4 py-4">
                                        {{ item?.unit }}
                                    </td>
                                    <td class="border-b border-slate-300  px-4 py-4">
                                        {{ item?.type }}
                                    </td>
                                    <td class="border-b border-slate-300 px-4 py-4">
                                        {{ item?.total_quantity }}
                                    </td>
                                    <td class="border-b border-slate-300 px-4 py-4">
                                        {{ item?.quantity }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p v-else>No hay materiales asignados</p>
                    <br />
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="closeMaterialsModal">
                            Cerrar
                        </SecondaryButton>
                    </div>
                </div>
            </div>
        </Modal>

        <Modal :show="showInstMaterials" @close="closeInstMaterialsModal" :closeable="true">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    Materiales Liquidados
                </h2>
                <br />
                <div class="mt-2">
                    <div v-if="instMaterials.length > 0" class="overflow-auto">
                        <table class="w-full whitespace-no-wrap border-collapse border border-slate-300">
                            <thead>
                                <tr
                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Material
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Recibidos
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Usados
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Tipo
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Resto
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, i) in instMaterials" :key="i" class="text-gray-700 bg-white text-sm">
                                    <td class="border-b border-slate-300 px-4 py-4">
                                        {{ item?.name }}
                                    </td>
                                    <td class="border-b border-slate-300 px-4 py-4">
                                        {{ item?.quantity }}
                                    </td>
                                    <td class="border-b border-slate-300 px-4 py-4">
                                        {{ item?.used_quantity }}
                                    </td>
                                    <td class="border-b border-slate-300 px-4 py-4">
                                        {{ item?.type }}
                                    </td>
                                    <td class="border-b border-slate-300 px-4 py-4">
                                        {{
                                            item?.quantity - item?.used_quantity
                                        }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p v-else>No hay materiales asignados</p>
                    <br />
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="closeInstMaterialsModal">
                            Cerrar
                        </SecondaryButton>
                    </div>
                </div>
            </div>
        </Modal>
        <!-- <SuccessOperationModal :confirming="confirmAssignation" :title="'Nueva Instalación PINT y PEXT creada'"
            :message="'La Asignacion fue creada con éxito'" /> -->
        <!-- <SuccessOperationModal :confirming="confirmUpdateAssignation" :title="'Instalación PINT y PEXT Actualizada'"
            :message="'La Asignacion fue actualizada'" /> -->
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { Head, useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SelectCicsaComponent from "@/Components/SelectCicsaComponent.vue";
// import SuccessOperationModal from "@/Components/SuccessOperationModal.vue";
import { formattedDate } from "@/utils/utils.js";
import TextInput from "@/Components/TextInput.vue";
import { EyeIcon, PencilSquareIcon } from "@heroicons/vue/24/outline";
import { setAxiosErrors } from "@/utils/utils";
import { ref, watch } from "vue";
import { notify, notifyError } from "@/Components/Notification";
import { Toaster } from "vue-sonner";
import TableStructure from "@/Layouts/TableStructure.vue";
import TableTitle from "@/Components/TableTitle.vue";
import TableRow from "@/Components/TableRow.vue";


const { installation, auth, searchCondition, type } = defineProps({
    installation: Object,
    auth: Object,
    searchCondition: {
        type: String,
        required: false
    },
    type: Number
});

const uniqueParam = ref(`timestamp=${new Date().getTime()}`);
const installations = ref(installation);
const pintList = ref([])
const pextList = ref([])
const initialState = {
    id: '',
    user_id: auth.user.id,
    user_name: auth.user.name,
    coordinator: '',
    observation: '',
    cicsa_assignation_id: '',
    pext_date: '',
    pint_date: '',
    pext_amount: '',
    pint_amount: '',
    projected_amount: '',
    conformity: 'Pendiente',
    report: 'Pendiente',
    shipping_report_date: '',
};

const form = useForm({ ...initialState });

const materialArray = ref([]);
const mateiralObject = ref({
    id: "",
    name: "",
    unit: "",
    quantity: 0,
    cicsa_feasibility_id: "",
});

const showAddEditModal = ref(false);
const confirmAssignation = ref(false);
const showModalMaterial = ref(false);
const dateModal = ref({})

function modalMaterial() {
    showModalMaterial.value = !showModalMaterial.value;
}

function closeAddAssignationModal() {
    showAddEditModal.value = false;
    form.defaults({ ...initialState });
    form.reset();
    form.clearErrors();
}

const confirmUpdateAssignation = ref(false);

function openEditFeasibilityModal(
    ca_id,
    project_name,
    cpe,
    item,
    total_materials,
    cicsa_installation_materials
) {
    dateModal.value = { 'project_name': project_name, 'cpe': cpe }

    total_materials =
        cicsa_installation_materials?.length > 0
            ? cicsa_installation_materials
            : total_materials.map((item) => ({ ...item, used_quantity: 0 }));
    if (item) {
        form.defaults({ ...item, total_materials });
    } else {
        form.defaults({
            ...initialState,
            total_materials,
            cicsa_assignation_id: ca_id,
        });
    }
    form.reset();
    showAddEditModal.value = true;

}

watch(() => form.total_materials, (newVal) => {
    pintList.value = [];
    pextList.value = [];
    newVal.forEach(item => {
        if (item.type === "Pint") {
            pintList.value.push(item);
        } else {
            pextList.value.push(item);
        }
    })
});

watch(() => [form.pint_amount, form.pext_amount], (newVal) => {
    if (form.pint_amount || form.pext_amount) {
        form.projected_amount = form.pint_amount + form.pext_amount
    } else if (!form.pint_amount && !form.pext_amount) {
        form.projected_amount = ''
    }
});

async function submit() {
    form.total_materials = pintList.value.concat(pextList.value);
    let url = route("cicsa.installation.store", { ci_id: form?.id });
    try {
        const response = await axios.post(url, form.data());
        updateInstallations(form.cicsa_assignation_id, response.data)
        closeAddAssignationModal();
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

function addMaterial() {
    if (
        mateiralObject.value.name &&
        mateiralObject.value.unit &&
        mateiralObject.value.quantity
    ) {
        const newMaterial = {
            name: mateiralObject.value.name,
            unit: mateiralObject.value.unit,
            quantity: mateiralObject.value.quantity,
        };
        materialArray.value.push(newMaterial);

        mateiralObject.value.name = "";
        mateiralObject.value.unit = "";
        mateiralObject.value.quantity = "";
    } else {
        console.error("Por favor completa todos los campos del formulario.");
    }
}

//materiasls
const showMaterials = ref(false);
const materials = ref([]);
function openMaterialsModal(arrayMaterials) {
    materials.value = arrayMaterials ? arrayMaterials : [];
    showMaterials.value = true;
}
function closeMaterialsModal() {
    showMaterials.value = false;
}

//installation materials
const showInstMaterials = ref(false);
const instMaterials = ref([]);

function openInstMaterialsModal(arrayMaterials) {
    instMaterials.value = arrayMaterials ? arrayMaterials : [];
    showInstMaterials.value = true;
}

function closeInstMaterialsModal() {
    showInstMaterials.value = false;
}

const search = async ($search) => {
    try {
        const response = await axios.post(route("cicsa.installation.index", { type }), {
            searchQuery: $search,
        });
        installations.value = response.data;
    } catch (error) {
        console.error("Error searching:", error);
    }
};

function updateInstallations(cicsa_assignation_id, installation) {
    const validations = installations.value.data || installations.value;
    const index = validations.findIndex(item => item.id === cicsa_assignation_id);
    validations[index].cicsa_installation = installation
    notify('Se actualizo Correctamente')
}

if (searchCondition) {
    search(searchCondition)
}

</script>
