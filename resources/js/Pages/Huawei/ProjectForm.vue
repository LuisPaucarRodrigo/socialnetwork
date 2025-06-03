<template>

    <Head title="Proyecto de Huawei" />
    <AuthenticatedLayout :redirectRoute="{
        route: 'huawei.projects',
        params: { status: '1', prefix: 'Claro' },
    }">
        <template v-if="props.huawei_project" #header>
            Edición de proyecto
        </template>

        <template v-else #header> Creación de proyecto </template>
        <Toaster richColors />

        <div class="min-w-full p-3 rounded-lg shadow">
            <form @submit.prevent="submit">
                <div class="pt-1">
                    <div class="border-b border-gray-900/10 mb-2"></div>

                    <div class="border-b border-gray-900 pb-12">
                        <h2 v-if="props.huawei_project" class="text-base font-semibold leading-7 text-lg text-gray-900">
                            {{ props.huawei_project.name }} |
                            {{ props.huawei_project.code }}
                        </h2>
                        <h2 v-else class="text-base font-semibold leading-7 text-lg text-gray-900">
                            Registrar nuevo proyecto
                        </h2>
                        <br />

                        <h2 class="font-black text-lg">Datos Generales:</h2>

                        <div v-if="!props.huawei_project" class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-5">
                            <div class="sm:col-span-3">
                                <InputLabel for="assigned_diu" class="font-medium leading-6 text-gray-900">DU del
                                    Proyecto
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="form.assigned_diu" id="assigned_diu"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.assigned_diu" />
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel for="cost_center_id" class="font-medium leading-6 text-gray-900">Centro de
                                    Costos
                                </InputLabel>
                                <div class="mt-2">
                                    <select required id="cost_center_id" v-model="form.cost_center_id"
                                        :disabled="hasBaseLines"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">
                                            Seleccione uno
                                        </option>
                                        <option v-for="item in props.cost_centers" :key="item.id" :value="item.id">
                                            {{ item.name }}
                                        </option>
                                    </select>
                                </div>
                                <InputError :message="form.errors.huawei_site_id" />
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel for="prefix" class="font-medium leading-6 text-gray-900">Macroproyecto
                                </InputLabel>
                                <div class="mt-2">
                                    <select required id="prefix" v-model="form.macro_project"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">
                                            Seleccione uno
                                        </option>
                                        <option>IPRAN24</option>
                                        <option>DWDM</option>
                                        <option>FTTH</option>
                                        <option>NAZCANEWPECOM</option>
                                    </select>
                                </div>
                                <InputError :message="form.errors.macro_project" />
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel for="prefix" class="font-medium leading-6 text-gray-900">Operador
                                </InputLabel>
                                <div class="mt-2">
                                    <select required id="prefix" @change="
                                        (e) => fetchSites(e.target.value)
                                    " v-model="form.prefix"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">
                                            Seleccione uno
                                        </option>
                                        <option>Claro</option>
                                        <option>Entel</option>
                                        <option>Telefonica</option>
                                    </select>
                                </div>
                                <InputError :message="form.errors.prefix" />
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel for="huawei_site_id" class="font-medium leading-6 text-gray-900">Site
                                </InputLabel>
                                <div class="mt-2">
                                    <select required id="huawei_site_id" v-model="form.huawei_site_id"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">
                                            Seleccione uno
                                        </option>
                                        <option v-for="item in availableSites" :key="item.id" :value="item.id">
                                            {{ item.name }}
                                        </option>
                                    </select>
                                </div>
                                <InputError :message="form.errors.huawei_site_id" />
                            </div>

                            <div class="sm:col-span-3">
                                <InputLabel for="zone" class="font-medium leading-6 text-gray-900">Zona
                                </InputLabel>
                                <div class="mt-2">
                                    <select required id="zone" :disabled="hasBaseLines" v-model="form.zone"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">
                                            Seleccione uno
                                        </option>
                                        <option>B1</option>
                                        <option>B2</option>
                                        <option>B3</option>
                                        <option>B4</option>
                                    </select>
                                </div>
                                <InputError :message="form.errors.zone" />
                            </div>

                            <div class="sm:col-span-3">
                                <InputLabel for="assignation_date" class="font-medium leading-6 text-gray-900">Fecha de
                                    Asignación
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="date" v-model="form.assignation_date" id="assignation_date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.assignation_date" />
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <InputLabel for="name" class="font-medium leading-6 text-gray-900">Descripción
                                </InputLabel>
                                <div class="mt-2">
                                    <textarea v-model="form.description" id="description"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.description" />
                                </div>
                            </div>
                        </div>

                        <div v-else class="grid grid-cols-1 md:grid-cols-2 mt-5 gap-y-2 text-lg">
                            <div class="flex gap-2 items-center">
                                <h2 class="font-black text-black">DU:</h2>
                                <p class="text-gray-600 font-medium">
                                    {{ props.huawei_project.assigned_diu }}
                                </p>
                            </div>
                            <div class="flex gap-2 items-center">
                                <h2 class="font-black text-black">
                                    Centro de Costos:
                                </h2>
                                <p class="text-gray-600 font-medium">
                                    {{ props.huawei_project.cost_center?.name }}
                                </p>
                            </div>
                            <div class="flex gap-2 items-center">
                                <h2 class="font-black text-black">
                                    Macroproyecto:
                                </h2>
                                <p class="text-gray-600 font-medium">
                                    {{ props.huawei_project.macro_project }}
                                </p>
                            </div>
                            <div class="flex gap-2 items-center">
                                <h2 class="font-black text-black">Operador:</h2>
                                <p class="text-gray-600 font-medium">
                                    {{ props.huawei_project.prefix }}
                                </p>
                            </div>
                            <div class="flex gap-2 items-center">
                                <h2 class="font-black text-black">Site:</h2>
                                <p class="text-gray-600 font-medium">
                                    {{ props.huawei_project.huawei_site.name }}
                                </p>
                            </div>
                            <div class="flex gap-2 items-center">
                                <h2 class="font-black text-black">Zona:</h2>
                                <p class="text-gray-600 font-medium">
                                    {{ props.huawei_project.zone }}
                                </p>
                            </div>
                            <div class="flex gap-2 items-start">
                                <h2 class="font-black text-black">
                                    Fecha de Asignación:
                                </h2>
                                <p class="text-gray-600 font-medium">
                                    {{
                                        formattedDate(
                                            props.huawei_project
                                                .assignation_date
                                        )
                                    }}
                                </p>
                            </div>
                            <div class="flex gap-2 items-start">
                                <h2 class="font-black text-black">
                                    Descripción:
                                </h2>
                                <div>
                                    <textarea id="editName" v-model="editForm.description"
                                        class="block w-60 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="editForm.errors.description" />
                                </div>
                            </div>
                        </div>

                        <hr class="border-1 border-gray-500 my-10 black" />
                        <div class="flex flex-space gap-2">
                            <h2 class="font-black">
                                {{
                                    props.huawei_project
                                        ? "POs Asignadas"
                                        : "Agregar PO"
                                }}
                            </h2>
                            <button v-if="!props.huawei_project" type="button" @click="createAssignation" class="px-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </button>
                        </div>
                        <TransitionGroup tag="div" enter-active-class="transition duration-300 ease-out"
                            enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0"
                            leave-active-class="transition duration-200 ease-in"
                            leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-2">
                            <div v-for="(
assignation, index
                                ) in props.huawei_project
                                            ? props.huawei_project
                                                .huawei_project_assignations
                                            : form.assignations" :key="index"
                                class="bg-[#eaeded] p-5 mt-5 rounded-md shadow-md">
                                <div class="flex justify-between items-center">
                                    <div class="flex gap-5 items-center">
                                        <h2 class="font-black">
                                            PO Asignada N°
                                            {{ index + 1 }}
                                        </h2>
                                        <button v-if="!props.huawei_project" type="button" class="text-red-600"
                                            @click="deleteAssignation(index)">
                                            <DeleteIcon />
                                        </button>
                                    </div>
                                    <div class="flex flex-space gap-2">
                                        <button type="button" @click="toggleAssignation(index)">
                                            <ChevronDownIcon v-if="isOpen(index)" class="h-7 w-7 text-gray-900" />
                                            <ChevronRightIcon v-else class="h-7 w-7 text-gray-900" />
                                        </button>
                                    </div>
                                </div>
                                <Transition enter-active-class="transition duration-300 ease-out"
                                    enter-from-class="opacity-0 -translate-y-2"
                                    enter-to-class="opacity-100 translate-y-0"
                                    leave-active-class="transition duration-200 ease-in"
                                    leave-from-class="opacity-100 translate-y-0"
                                    leave-to-class="opacity-0 -translate-y-2">
                                    <div v-if="isOpen(index)">
                                        <div v-if="!props.huawei_project"
                                            class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-5">
                                            <div class="sm:col-span-3">
                                                <InputLabel for="po" class="font-medium leading-6 text-gray-900">
                                                    PO
                                                </InputLabel>
                                                <div class="mt-2">
                                                    <input type="text" v-model="form.assignations[
                                                            index
                                                        ].po
                                                        " id="po"
                                                        class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                    <InputError :message="form.errors[
                                                        `assignations.${index}.po`
                                                        ]
                                                        " />
                                                </div>
                                            </div>

                                            <div class="sm:col-span-3">
                                                <InputLabel for="assignation_date_po"
                                                    class="font-medium leading-6 text-gray-900">
                                                    Fecha de Asignación de la PO
                                                </InputLabel>
                                                <div class="mt-2">
                                                    <input type="date" v-model="form.assignations[
                                                            index
                                                        ].assignation_date
                                                        " id="assignation_date_po"
                                                        class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                    <InputError :message="form.errors[
                                                        `assignations.${index}.assignation_date`
                                                        ]
                                                        " />
                                                </div>
                                            </div>

                                            <div class="sm:col-span-3 md:col-span-6">
                                                <InputLabel for="description_po"
                                                    class="font-medium leading-6 text-gray-900">
                                                    Descripción de la PO
                                                </InputLabel>
                                                <div class="mt-2">
                                                    <textarea v-model="form.assignations[
                                                            index
                                                        ].description
                                                        " id="description_po"
                                                        class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                    <InputError :message="form.errors[
                                                        `assignations.${index}.description`
                                                        ]
                                                        " />
                                                </div>
                                            </div>
                                        </div>
                                        <div v-else class="grid grid-cols-1 md:grid-cols-2 mt-5 gap-y-2">
                                            <div class="flex gap-2 items-center">
                                                <h2 class="font-black text-black">
                                                    PO:
                                                </h2>
                                                <p class="text-gray-600 font-medium">
                                                    {{ assignation.po }}
                                                </p>
                                            </div>
                                            <div class="flex gap-2 items-start">
                                                <h2 class="font-black text-black">
                                                    Fecha de Asignación:
                                                </h2>
                                                <p class="text-gray-600 font-medium">
                                                    {{
                                                        formattedDate(
                                                            assignation.assignation_date
                                                        )
                                                    }}
                                                </p>
                                            </div>
                                            <div class="flex gap-2 items-center">
                                                <h2 class="font-black text-black">
                                                    Descripción de la PO:
                                                </h2>
                                                <p class="text-gray-600 font-medium">
                                                    {{
                                                        assignation.description
                                                    }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex flex-space gap-2 mt-5">
                                            <h2 class="font-black">
                                                Líneas Base:
                                            </h2>
                                            <button type="button" v-if="
                                                form.cost_center_id &&
                                                form.zone &&
                                                !props.huawei_project
                                            " @click="openBaseLine(index)" class="px-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor"
                                                    class="w-6 h-6 text-indigo-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>
                                            </button>
                                            <button type="button" v-if="
                                                form.cost_center_id &&
                                                form.zone
                                            " class="rounded-md text-center text-sm text-green-400"
                                                @click="openImportExcel(index)">
                                                <svg class="w-5 h-5" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <title />

                                                    <g id="Complete">
                                                        <g id="upload">
                                                            <g>
                                                                <path d="M3,12.3v7a2,2,0,0,0,2,2H19a2,2,0,0,0,2-2v-7"
                                                                    fill="none" stroke="green" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2" />

                                                                <g>
                                                                    <polyline data-name="Right" fill="none" id="Right-2"
                                                                        points="7.9 6.7 12 2.7 16.1 6.7" stroke="green"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" />

                                                                    <line fill="none" stroke="green"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" x1="12" x2="12" y1="16.3"
                                                                        y2="4.8" />
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="mt-5">
                                            <div class="overflow-x-auto mt-3">
                                                <table class="w-full whitespace-no-wrap">
                                                    <thead>
                                                        <tr
                                                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                            <th
                                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                                                N°
                                                            </th>
                                                            <th
                                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                                                Código
                                                            </th>
                                                            <th
                                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 w-[400px] text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                                                Descripción
                                                            </th>
                                                            <th
                                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                                                Unidad
                                                            </th>
                                                            <th
                                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                                                Precio Unitario
                                                            </th>
                                                            <th
                                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                                                Cantidad
                                                            </th>
                                                            <th
                                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                                                Precio Total
                                                            </th>
                                                            <th
                                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                                                Evidencia
                                                            </th>
                                                            <th
                                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                                                Hito
                                                            </th>
                                                            <th
                                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                                                Observación
                                                            </th>
                                                            <th
                                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(
item, idx
                                                            ) in props.huawei_project
                                                                        ? props
                                                                            .huawei_project
                                                                            .huawei_project_assignations[
                                                                            index
                                                                        ]
                                                                            .huawei_project_earnings
                                                                        : form
                                                                            .assignations[
                                                                            index
                                                                        ].base_lines" :key="idx" class="text-gray-700">
                                                            <td
                                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                                                {{ idx + 1 }}
                                                            </td>
                                                            <td
                                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                                                {{ item.code }}
                                                            </td>
                                                            <td
                                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                                                {{
                                                                    item.description
                                                                }}
                                                            </td>
                                                            <td
                                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                                                {{ item.unit }}
                                                            </td>
                                                            <td
                                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                                                {{
                                                                    item.unit_price
                                                                }}
                                                            </td>
                                                            <td
                                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                                                <input v-if="
                                                                    !props.huawei_project
                                                                " type="number" v-model="form
                                                                            .assignations[
                                                                            index
                                                                        ]
                                                                            .base_lines[
                                                                            idx
                                                                        ]
                                                                            .quantity
                                                                        " @input="
                                                                        updateTotalPrice(
                                                                            index,
                                                                            idx
                                                                        )
                                                                        "
                                                                    class="w-full border border-gray-300 rounded-md text-center p-1 focus:ring focus:ring-indigo-500 focus:border-indigo-500"
                                                                    min="0" />
                                                                <p v-else>
                                                                    {{
                                                                        item.quantity
                                                                    }}
                                                                </p>
                                                            </td>
                                                            <td
                                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                                                {{
                                                                    props.huawei_project
                                                                        ? item.amount.toFixed(
                                                                            2
                                                                        )
                                                                        : item.total_price
                                                                }}
                                                            </td>
                                                            <td
                                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                                                {{
                                                                    item.evidence
                                                                }}
                                                            </td>
                                                            <td
                                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                                                {{ item.goal }}
                                                            </td>
                                                            <td
                                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                                                {{
                                                                    item.observation
                                                                }}
                                                            </td>
                                                            <td
                                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                                                <div v-if="
                                                                    !props.huawei_project
                                                                " class="flex items-center">
                                                                    <button @click.prevent="
                                                                        delete_base_line(
                                                                            index,
                                                                            idx
                                                                        )
                                                                        " class="text-red-600 hover:underline mr-2">
                                                                        <DeleteIcon />
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <InputError :message="form.errors.base_lines
                                                " />
                                        </div>
                                        <InputError :message="form.errors[
                                            `assignations.${index}.base_lines`
                                            ]
                                            " />
                                    </div>
                                </Transition>
                            </div>
                        </TransitionGroup>

                        <InputError class="mt-5" :message="form.errors.assignations" />
                        <div>
                            <hr class="border-1 border-gray-500 my-10 black" />
                            <div class="flex flex-space gap-2">
                                <h2 class="font-black">Cronograma:</h2>
                                <button v-if="!props.huawei_project" type="button" @click="open_schedule" class="px-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-5">
                                <div class="overflow-x-auto mt-3">
                                    <table class="w-full whitespace-no-wrap">
                                        <thead>
                                            <tr
                                                class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                                    N°
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                                    Actividad
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                                    Días
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                                    Fecha de Inicio
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                                    Fecha de Fin
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                                    Empleado
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(
item, index
                                                ) in props.huawei_project
                                                            ? props.huawei_project
                                                                .huawei_project_schedules
                                                            : form.schedule" :key="index" class="text-gray-700">
                                                <td
                                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                                    {{ index + 1 }}
                                                </td>
                                                <td
                                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                                    {{ item.activity }}
                                                </td>
                                                <td
                                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                                    {{ item.days }}
                                                </td>
                                                <td
                                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                                    {{
                                                        formattedDate(
                                                            item.start_date
                                                        )
                                                    }}
                                                </td>
                                                <td
                                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                                    {{
                                                        formattedDate(
                                                            item.end_date
                                                        )
                                                    }}
                                                </td>
                                                <td
                                                    class="border-b whitespace-nowrap border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                                    {{ item.employee }}
                                                </td>
                                                <td
                                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                                    <div v-if="
                                                        !props.huawei_project
                                                    " class="flex items-center">
                                                        <button @click.prevent="
                                                            delete_schedule(
                                                                index
                                                            )
                                                            " class="text-red-600 hover:underline mr-2">
                                                            <DeleteIcon />
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <InputError :message="form.errors.schedule" />
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="auth.user.role_id === 1" class="mt-3 flex items-center justify-end gap-x-6">
                    <button type="submit" :class="{ 'opacity-25': form.processing }"
                        class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Guardar
                    </button>
                </div>
            </form>

            <Modal :show="baseline_modal" @close="openBaseLine">
                <div class="p-6">
                    <h2 class="text-base font-medium leading-7 text-gray-900">
                        Ingresar Línea
                    </h2>
                    <form @submit.prevent="addBaseLine">
                        <div class="space-y-12">
                            <div class="border-b grid grid-cols-1 md:grid-cols-2 gap-6 border-gray-900/10 pb-12">
                                <div>
                                    <InputLabel for="code" class="font-medium leading-6 text-gray-900">
                                        Código de Precio
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input type="text" id="code" v-model="baseForm.code" @input="
                                            (e) => handleAutocomplete(e)
                                        " list="price_guide_list"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                            placeholder="Seleccione un código" />
                                        <datalist id="price_guide_list">
                                            <option v-for="pg in price_guides" :key="pg.id" :value="pg.code">
                                                {{ pg.description }}
                                            </option>
                                        </datalist>
                                        <InputError :message="baseForm.errors.code" />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel class="font-medium leading-6 text-gray-900">
                                        Descripción
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input type="text" v-model="baseForm.description"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                            readonly />
                                        <InputError :message="baseForm.errors.description
                                            " />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel class="font-medium leading-6 text-gray-900">
                                        Unidad
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input type="text" v-model="baseForm.unit"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                            readonly />
                                        <InputError :message="baseForm.errors.unit" />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel class="font-medium leading-6 text-gray-900">
                                        Precio Unitario
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input type="number" step="0.01" v-model="baseForm.unit_price"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                            readonly />
                                        <InputError :message="baseForm.errors.unit_price
                                            " />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="quantity" class="font-medium leading-6 text-gray-900">
                                        Cantidad
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input type="number" min="0" id="quantity" v-model="baseForm.quantity"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        <InputError :message="baseForm.errors.quantity" />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="total_price" class="font-medium leading-6 text-gray-900">
                                        Precio Total
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input type="number" v-model="baseForm.total_price" min="0" id="total_price"
                                            readonly
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="evidence" class="font-medium leading-6 text-gray-900">
                                        Evidencia
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input type="text" id="evidence" v-model="baseForm.evidence"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        <InputError :message="baseForm.errors.evidence" />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="goal" class="font-medium leading-6 text-gray-900">
                                        Hito
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input type="text" id="goal" v-model="baseForm.goal"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        <InputError :message="baseForm.errors.goal" />
                                    </div>
                                </div>

                                <div class="col-span-1 md:col-span-2">
                                    <InputLabel for="baseObservation" class="font-medium leading-6 text-gray-900">
                                        Observación
                                    </InputLabel>
                                    <div class="mt-2">
                                        <textarea id="baseObservation" v-model="baseForm.observation"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        <InputError :message="baseForm.errors.observation
                                            " />
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <SecondaryButton @click="openBaseLine">
                                    Cancelar
                                </SecondaryButton>
                                <button type="submit"
                                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </Modal>

            <Modal :show="show_import" @close="openImportExcel" :closeable="true">
                <div class="p-6">
                    <h2 class="text-base font-black leading-7 text-gray-900">
                        Importar Excel
                    </h2>
                    <h2 class="font-black text-lg mt-4 text-indigo-700">
                        Consideraciones:
                    </h2>
                    <div class="bg-indigo-50 border-l-4 text-sm border-indigo-500 p-4 rounded-lg shadow-sm mt-2">
                        <p class="text-gray-700 flex items-center gap-1">
                            <span class="font-semibold text-indigo-600">•</span>
                            Descargue la
                            <a :href="route('huawei.projects.baselines.template')
                                " class="font-black text-indigo-600 underline inline-flex items-center">
                                ESTRUCTURA DE DATOS
                                <ArrowDownTrayIcon class="w-5 h-5" />
                            </a>
                        </p>

                        <p class="text-gray-700">
                            <span class="font-semibold text-indigo-600">•</span>
                            La importación
                            <span class="font-black text-indigo-600">NO VA A GUARDAR</span>
                            los datos actuales, para guardar todo el proyecto
                            debe hacer click en el botón
                            <span class="font-black text-indigo-600">GUARDAR</span>
                            al final de la página.
                        </p>
                        <p class="text-gray-700">
                            <span class="font-semibold text-indigo-600">•</span>
                            La importación
                            <span class="text-indigo-600 font-semibold">NO REEMPLAZARÁ</span>
                            los elementos actuales.
                        </p>
                        <p class="text-gray-700">
                            <span class="font-semibold text-indigo-600">•</span>

                            Mantenga la cabecera de la tabla, esta se ignorará
                            durante la importación.
                        </p>
                        <p class="text-gray-700">
                            <span class="font-semibold text-indigo-600">•</span>

                            Todos los campos son obligatorios.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-5 text-sm">
                        <div class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm">
                            <span class="font-black text-indigo-700">Columna A:</span>
                            <span class="text-gray-700">Código</span>
                        </div>
                        <div class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm">
                            <span class="font-black text-indigo-700">Columna B:</span>
                            <span class="text-gray-700">Cantidad</span>
                        </div>
                        <div class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm">
                            <span class="font-black text-indigo-700">Columna C:</span>
                            <span class="text-gray-700">Evidencia</span>
                        </div>
                        <div class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm">
                            <span class="font-black text-indigo-700">Columna D:</span>
                            <span class="text-gray-700">Hito</span>
                        </div>
                        <div class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm">
                            <span class="font-black text-indigo-700">Columna E:</span>
                            <span class="text-gray-700">Observación</span>
                        </div>
                    </div>

                    <form @submit.prevent="importExcel">
                        <div class="space-y-12 mt-4">
                            <div class="grid sm:grid-cols-2 gap-6 pb-6">
                                <div class="md:col-span-2 col-span-1">
                                    <InputLabel for="file" class="font-medium leading-6 text-gray-900">Archivo
                                    </InputLabel>
                                    <div class="mt-2">
                                        <InputFile type="file" v-model="formFile.file" accept=".xlsx"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        <InputError :message="formFile.errors.file" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 flex justify-end gap-4">
                            <SecondaryButton @click="openImportExcel">
                                Cancelar
                            </SecondaryButton>
                            <button type="submit"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </Modal>

            <Modal :show="schedule_modal" @close="open_schedule">
                <div class="p-6">
                    <h2 class="text-base font-medium leading-7 text-gray-900">
                        Ingresar Actividad
                    </h2>
                    <form @submit.prevent="add_schedule">
                        <div class="space-y-12">
                            <div class="border-b grid grid-cols-1 md:grid-cols-2 gap-6 border-gray-900/10 pb-12">
                                <div>
                                    <InputLabel for="activity" class="font-medium leading-6 text-gray-900">
                                        Actividad
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input type="text" id="activity" v-model="scheduleForm.activity"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        <InputError :message="scheduleForm.errors.activity
                                            " />
                                    </div>
                                </div>
                                <div>
                                    <InputLabel for="days" class="font-medium leading-6 text-gray-900">
                                        Días
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input type="number" min="0" id="days" v-model="scheduleForm.days"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        <InputError :message="scheduleForm.errors.days" />
                                    </div>
                                </div>
                                <div>
                                    <InputLabel for="start_date" class="font-medium leading-6 text-gray-900">
                                        Fecha de Inicio
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input type="date" id="start_date" v-model="scheduleForm.start_date"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        <InputError :message="scheduleForm.errors.start_date
                                            " />
                                    </div>
                                </div>
                                <div>
                                    <InputLabel for="end_date" class="font-medium leading-6 text-gray-900">
                                        Fecha de Fin
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input type="date" id="end_date" v-model="scheduleForm.end_date"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        <InputError :message="scheduleForm.errors.end_date
                                            " />
                                    </div>
                                </div>
                                <div>
                                    <InputLabel for="employee" class="font-medium leading-6 text-gray-900">
                                        Empleado
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input list="employee-list" id="employee" v-model="scheduleForm.employee"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                            placeholder="Seleccione un empleado" />
                                        <datalist id="employee-list">
                                            <option v-for="(
employee, index
                                                ) in props.employees" :key="index" :value="employee" />
                                        </datalist>

                                        <InputError :message="scheduleForm.errors.employee
                                            " />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 flex justify-end gap-4">
                            <SecondaryButton @click="open_schedule">
                                Cancelar
                            </SecondaryButton>
                            <button type="submit"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </Modal>
        </div>
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="Proyecto de Huawei" />
        <ConfirmUpdateModal :confirmingupdate="showUpdateModal" itemType="Proyecto de Huawei" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ConfirmCreateModal from "@/Components/ConfirmCreateModal.vue";
import ConfirmUpdateModal from "@/Components/ConfirmUpdateModal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";
import { ref, watch, computed } from "vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import {
    ChevronRightIcon,
    ChevronDownIcon,
    ArrowDownTrayIcon,
} from "@heroicons/vue/24/outline";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputFile from "@/Components/InputFile.vue";
import { notifyError } from "@/Components/Notification";
import { Toaster } from "vue-sonner";
import { formattedDate } from "@/utils/utils";
import axios from "axios";
import DeleteIcon from "@/Components/Icons/DeleteIcon.vue";

const showModal = ref(false);
const showUpdateModal = ref(false);
const props = defineProps({
    cost_centers: Object,
    price_guides: Object,
    huawei_project: Object,
    employees: Object,
    auth: Object,
    userPermissions: Array,
});

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
};

const initialState = {
    description: "",
    zone: "",
    huawei_site_id: "",
    cost_center_id: "",
    prefix: "",
    //pre_report: null,
    //employees: [],
    schedule: [],
    //initial_amount: "",
    assigned_diu: "",
    assignation_date: "",
    macro_project: "",
    assignations: [],
};

const editForm = useForm({
    description: props.huawei_project ? props.huawei_project.description : "",
});

const form = useForm({ ...initialState });

const submit = () => {
    if (!props.huawei_project) {
        form.post(route("huawei.projects.store"), {
            onSuccess: () => {
                showModal.value = true;
                setTimeout(() => {
                    showModal.value = false;
                    router.visit(
                        route("huawei.projects", {
                            status: "1",
                            prefix: "Claro",
                        })
                    );
                }, 2000);
            },
            onError: (e) => {
                console.error(e);
            },
        });
    } else {
        editForm.put(
            route("huawei.projects.update", {
                huawei_project: props.huawei_project.id,
            }),
            {
                onSuccess: () => {
                    showUpdateModal.value = true;
                    setTimeout(() => {
                        showUpdateModal.value = false;
                        router.visit(
                            route("huawei.projects", {
                                status: 1,
                                prefix: "Claro",
                            })
                        );
                    }, 2000);
                },
                onError: (e) => {
                    console.error(e);
                },
            }
        );
    }
};

const baseline_modal = ref(false);

//baselines
const price_guides = ref(null);
const show_import = ref(false);

watch(
    () => form.cost_center_id,
    (newVal) => {
        price_guides.value = props.price_guides.filter(
            (pg) => pg.cost_center_id === newVal
        );
    }
);

const handleAutocomplete = () => {
    if (!baseForm.code || !price_guides.value?.length) return;
    const selectedGuide = price_guides.value.find(
        (guide) => guide.code === baseForm.code
    );
    if (selectedGuide) {
        baseForm.description = selectedGuide.description || "";
        baseForm.unit = selectedGuide.unit || "";
        baseForm.quantity = selectedGuide.quantity || "";
        baseForm.evidence = selectedGuide.evidence || "";
        baseForm.goal = selectedGuide.goal || "";
        const zoneKey = form.zone.toLowerCase();
        baseForm.unit_price = selectedGuide[zoneKey] || 0;
    } else {
        baseForm.description = "";
        baseForm.unit = "";
        baseForm.quantity = "";
        baseForm.evidence = "";
        baseForm.goal = "";
        baseForm.unit_price = 0;
    }
};

const baseForm = useForm({
    code: "",
    description: "",
    unit: "",
    unit_price: "",
    total_price: "",
    quantity: "",
    observation: "",
    evidence: "",
    goal: "",
});

watch(
    () => baseForm.quantity,
    (newVal) => {
        baseForm.total_price = parseFloat(
            (baseForm.unit_price * newVal).toFixed(2)
        );
    }
);

const assigantionIndex = ref(null);
const openBaseLine = (index) => {
    baseForm.reset();
    baseline_modal.value
        ? (assigantionIndex.value = null)
        : (assigantionIndex.value = index);
    baseline_modal.value = !baseline_modal.value;
};

const addBaseLine = () => {
    if (
        !baseForm.code ||
        !baseForm.description ||
        !baseForm.unit ||
        !baseForm.unit_price ||
        !baseForm.quantity ||
        !baseForm.evidence ||
        !baseForm.goal
    ) {
        notifyError("Todos los campos son requeridos");
        return;
    }
    form.assignations[assigantionIndex.value].base_lines.push({ ...baseForm });
    baseForm.reset();
    assigantionIndex.value = null;
    baseline_modal.value = false;
};

const delete_base_line = (index, idx) => {
    form.assignations[index].base_lines.splice(idx, 1);
};
const updateTotalPrice = (index, idx) => {
    const line = form.assignations[index].base_lines[idx];
    line.total_price = (line.unit_price * line.quantity).toFixed(2);
};

const formFile = useForm({
    file: "",
});

const openImportExcel = (index) => {
    formFile.reset();
    show_import.value
        ? (assigantionIndex.value = null)
        : (assigantionIndex.value = index);
    show_import.value = !show_import.value;
};

const importExcel = async () => {
    const url = route("huawei.projects.import.baselines", {
        zone: form.zone.toLowerCase(),
    });

    try {
        const formData = new FormData();
        formData.append("file", formFile.file);

        const response = await axios.post(url, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
                Accept: "application/json",
            },
        });
        form.assignations[assigantionIndex.value].base_lines.push(
            ...response.data.lines
        );
        openImportExcel();
    } catch (error) {
        if (error.response.data.error) {
            notifyError(error.response.data.error);
        }
    }
};

//schedule
const schedule_modal = ref(false);

const open_schedule = () => {
    schedule_modal.value = !schedule_modal.value;
};

const delete_schedule = (index) => {
    form.schedule.splice(index, 1);
};

const scheduleForm = useForm({
    activity: "",
    days: "",
    start_date: "",
    end_date: "",
    employee: "",
});

const add_schedule = () => {
    if (
        !scheduleForm.activity ||
        !scheduleForm.days ||
        !scheduleForm.start_date ||
        !scheduleForm.end_date ||
        !scheduleForm.employee
    ) {
        notifyError("Todos los campos son requeridos");
        return;
    }
    if (!props.employees.includes(scheduleForm.employee)) {
        notifyError("Debe seleccionar un empleado válido de la lista");
        return;
    }
    form.schedule.push({ ...scheduleForm });
    scheduleForm.reset();
};

//assignations
const createAssignation = () => {
    const newIndex = form.assignations.length;
    form.assignations.push({
        index: "",
        assignation_date: "",
        po: "",
        description: "",
        base_lines: [],
    });
    assignationForm.index = newIndex;
    openAssignations.value.push(newIndex);
};

const assignationForm = useForm({
    index: "",
    assignation_date: "",
    po: "",
    description: "",
    base_lines: [],
});

const addAssignation = () => {
    const index = assignationForm.index;

    if (index !== "" && form.assignations[index] !== undefined) {
        form.assignations[index] = {
            assignation_date: assignationForm.assignation_date,
            po: assignationForm.po,
            description: assignationForm.description,
            base_lines: [...assignationForm.base_lines],
        };
    }
    assignationForm.reset();
    assignationForm.base_lines = [];
};

const deleteAssignation = (index) => {
    form.assignations.splice(index, 1);
};

//toggle
const openAssignations = ref([]);

function toggleAssignation(index) {
    const i = openAssignations.value.indexOf(index);
    if (i === -1) {
        openAssignations.value.push(index);
    } else {
        openAssignations.value.splice(i, 1);
    }
}

function isOpen(index) {
    return openAssignations.value.includes(index);
}

const hasBaseLines = computed(() => {
    return form.assignations.some((assignation) => {
        return assignation.base_lines && assignation.base_lines.length > 0;
    });
});

const availableSites = ref([]);

const fetchSites = async (prefix) => {
    try {
        const res = await axios.post(
            route("huawei.projects.create.fetchsites"),
            { prefix: prefix }
        );
        availableSites.value = res.data;
    } catch (error) {
        console.error(res);
    }
};
</script>
