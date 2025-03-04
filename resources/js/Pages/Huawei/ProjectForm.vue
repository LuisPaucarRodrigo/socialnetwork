<template>
    <Head title="Proyecto de Huawei" />
    <AuthenticatedLayout
        :redirectRoute="{
            route: 'huawei.projects',
            params: { status: '1', prefix: 'Claro' },
        }"
    >
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
                        <h2
                            v-if="props.huawei_project"
                            class="text-base font-semibold leading-7 text-gray-900"
                        >
                            {{ props.huawei_project.name }} |
                            {{ props.huawei_project.code }}
                        </h2>
                        <h2
                            v-else
                            class="text-base font-semibold leading-7 text-gray-900"
                        >
                            Registrar nuevo proyecto
                        </h2>
                        <br />
                        <div
                            v-if="props.huawei_project"
                            class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mb-4"
                        >
                            <div class="sm:col-span-3">
                                <InputLabel
                                    class="font-medium leading-6 text-gray-900"
                                >
                                    Site
                                </InputLabel>
                                <div class="mt-2">
                                    <InputLabel
                                        class="font-medium leading-6 text-gray-900"
                                    >
                                        {{
                                            props.huawei_project.huawei_site
                                                .name
                                        }}
                                    </InputLabel>
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel
                                    class="font-medium leading-6 text-gray-900"
                                >
                                    Operador
                                </InputLabel>
                                <div class="mt-2">
                                    <InputLabel
                                        class="font-medium leading-6 text-gray-900"
                                    >
                                        {{ props.huawei_project.prefix }}
                                    </InputLabel>
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel
                                    class="font-medium leading-6 text-gray-900"
                                >
                                    OT
                                </InputLabel>
                                <div class="mt-2">
                                    <InputLabel
                                        class="font-medium leading-6 text-gray-200"
                                    >
                                        {{ props.huawei_project.ot }}
                                    </InputLabel>
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel
                                    class="font-medium leading-6 text-gray-900"
                                >
                                    Macroproyecto
                                </InputLabel>
                                <div class="mt-2">
                                    <InputLabel
                                        class="font-medium leading-6 text-gray-200"
                                    >
                                        {{ props.huawei_project.macro_project }}
                                    </InputLabel>
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel
                                    class="font-medium leading-6 text-gray-900"
                                >
                                    Código
                                </InputLabel>
                                <div class="mt-2">
                                    <InputLabel
                                        class="font-medium leading-6 text-gray-900"
                                    >
                                        {{ props.huawei_project.code }}
                                    </InputLabel>
                                </div>
                            </div>
                        </div>
                        <h2 class="font-black">Datos Generales:</h2>
                        <div
                            class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-5"
                        >
                            <div class="sm:col-span-3">
                                <InputLabel
                                    for="name"
                                    class="font-medium leading-6 text-gray-900"
                                    >Nombre
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput
                                        v-model="form.name"
                                        id="description"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError :message="form.errors.name" />
                                </div>
                            </div>

                            <div
                                v-if="!props.huawei_project"
                                class="sm:col-span-3"
                            >
                                <InputLabel
                                    for="cost_center_id"
                                    class="font-medium leading-6 text-gray-900"
                                    >Centro de Costos
                                </InputLabel>
                                <div class="mt-2">
                                    <select
                                        required
                                        id="cost_center_id"
                                        v-model="form.cost_center_id"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    >
                                        <option disabled value="">
                                            Seleccione uno
                                        </option>
                                        <option
                                            v-for="item in props.cost_centers"
                                            :key="item.id"
                                            :value="item.id"
                                        >
                                            {{ item.name }}
                                        </option>
                                    </select>
                                </div>
                                <InputError
                                    :message="form.errors.huawei_site_id"
                                />
                            </div>

                            <div
                                v-if="!props.huawei_project"
                                class="sm:col-span-3"
                            >
                                <InputLabel
                                    for="prefix"
                                    class="font-medium leading-6 text-gray-900"
                                    >Operador
                                </InputLabel>
                                <div class="mt-2">
                                    <select
                                        required
                                        id="prefix"
                                        v-model="form.prefix"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    >
                                        <option disabled value="">
                                            Seleccione uno
                                        </option>
                                        <option>Claro</option>
                                        <option>Entel</option>
                                        <option>Telefónica</option>
                                    </select>
                                </div>
                                <InputError :message="form.errors.prefix" />
                            </div>

                            <div
                                v-if="!props.huawei_project"
                                class="sm:col-span-3"
                            >
                                <InputLabel
                                    for="prefix"
                                    class="font-medium leading-6 text-gray-900"
                                    >Macroproyecto
                                </InputLabel>
                                <div class="mt-2">
                                    <select
                                        required
                                        id="prefix"
                                        v-model="form.macro_project"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    >
                                        <option disabled value="">
                                            Seleccione uno
                                        </option>
                                        <option>IPRAN24</option>
                                        <option>DWDM</option>
                                        <option>FTTH</option>
                                        <option>NAZCANEWPECOM</option>
                                    </select>
                                </div>
                                <InputError
                                    :message="form.errors.macro_project"
                                />
                            </div>
                        </div>

                        <hr class="border-1 border-gray-500 my-10 black" />
                        <h2 class="font-black">Datos Específicos:</h2>

                        <div
                            class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-5"
                        >
                            <div
                                v-if="!props.huawei_project"
                                class="sm:col-span-3"
                            >
                                <InputLabel
                                    for="ot"
                                    class="font-medium leading-6 text-gray-900"
                                    >PO
                                </InputLabel>
                                <div class="mt-2">
                                    <input
                                        type="text"
                                        v-model="form.ot"
                                        id="ot"
                                        class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError :message="form.errors.ot" />
                                </div>
                            </div>

                            <div
                                v-if="!props.huawei_project"
                                class="sm:col-span-3"
                            >
                                <InputLabel
                                    for="huawei_site_id"
                                    class="font-medium leading-6 text-gray-900"
                                    >Site
                                </InputLabel>
                                <div class="mt-2">
                                    <select
                                        required
                                        id="huawei_site_id"
                                        v-model="form.huawei_site_id"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    >
                                        <option disabled value="">
                                            Seleccione uno
                                        </option>
                                        <option
                                            v-for="item in props.huawei_sites"
                                            :key="item.id"
                                            :value="item.id"
                                        >
                                            {{ item.name }}
                                        </option>
                                    </select>
                                </div>
                                <InputError
                                    :message="form.errors.huawei_site_id"
                                />
                            </div>

                            <div class="sm:col-span-3">
                                <InputLabel
                                    for="assigned_diu"
                                    class="font-medium leading-6 text-gray-900"
                                    >DU del Proyecto
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput
                                        type="text"
                                        v-model="form.assigned_diu"
                                        id="assigned_diu"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError
                                        :message="form.errors.assigned_diu"
                                    />
                                </div>
                            </div>

                            <div
                                v-if="!props.huawei_project"
                                class="sm:col-span-3"
                            >
                                <InputLabel
                                    for="zone"
                                    class="font-medium leading-6 text-gray-900"
                                    >Zona
                                </InputLabel>
                                <div class="mt-2">
                                    <select
                                        required
                                        id="zone"
                                        v-model="form.zone"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    >
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

                            <div
                                :class="
                                    props.huawei_project
                                        ? 'sm:col-span-6'
                                        : 'md:col-span-6 sm:col-span-3'
                                "
                            >
                                <InputLabel
                                    for="name"
                                    class="font-medium leading-6 text-gray-900"
                                    >Descripción
                                </InputLabel>
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

                            <!-- <div class="sm:col-span-3">
                                <div class="flex gap-2">
                                    <InputLabel for="trainings" class="font-medium leading-6 text-gray-900">Miembros del
                                        equipo al proyecto
                                    </InputLabel>
                                    <button @click.prevent="showToAddEmployee" type="button">
                                        <UserPlusIcon class="text-indigo-800 h-6 w-6 hover:text-purple-400" />
                                    </button>
                                </div>

                                <div class="mt-2" v-if="props.huawei_project">
                                    <div v-for="(member, index) in form.employees" :key="index"
                                        class="grid grid-cols-8 items-center my-2">
                                        <p class=" text-sm col-span-7 line-clamp-2">{{ member.employee.name }} {{
                                            member.employee.lastname }}: {{ member.charge }} - S/. {{ member.cost.toFixed(2) }} </p>
                                        <div class="flex gap-2">
                                            <button type="button" @click="edit_employee(member, index)"
                                                class="col-span-1 flex justify-end">
                                                <PencilSquareIcon class=" text-yellow-500 h-5 w-5 " />
                                            </button>
                                            <button type="button" @click="delete_already_employee(member.id, index)"
                                                class="col-span-1 flex justify-end">
                                                <TrashIcon class=" text-red-500 h-5 w-5 " />
                                            </button>
                                        </div>
                                        <div class="border-b col-span-8 border-gray-900/10">
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2" v-else>
                                    <div v-for="(member, index) in form.employees" :key="index"
                                        class="grid grid-cols-8 items-center my-2">
                                        <p class=" text-sm col-span-7 line-clamp-2">{{ member.employee.name }} {{
                                            member.employee.lastname }}: {{ member.charge + ' - ' + member.cost}} </p>
                                        <button type="button" @click="delete_employee(index)"
                                            class="col-span-1 flex justify-end">
                                            <TrashIcon class=" text-red-500 h-4 w-4 " />
                                        </button>
                                        <div class="border-b col-span-8 border-gray-900/10">
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                        <hr class="border-1 border-gray-500 my-10 black" />
                        <div class="flex flex-space gap-2">
                            <h2 class="font-black">Líneas Base:</h2>
                            <button
                                type="button"
                                v-if="form.cost_center_id && form.zone"
                                @click="openBaseLine"
                                class="px-1"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-6 h-6 text-indigo-500"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                    />
                                </svg>
                            </button>
                            <button
                                type="button"
                                v-if="form.cost_center_id && form.zone"
                                class="rounded-md text-center text-sm text-green-400"
                                @click="openImportExcel"
                            >
                                <svg
                                    class="w-5 h-5"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <title />

                                    <g id="Complete">
                                        <g id="upload">
                                            <g>
                                                <path
                                                    d="M3,12.3v7a2,2,0,0,0,2,2H19a2,2,0,0,0,2-2v-7"
                                                    fill="none"
                                                    stroke="green"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                />

                                                <g>
                                                    <polyline
                                                        data-name="Right"
                                                        fill="none"
                                                        id="Right-2"
                                                        points="7.9 6.7 12 2.7 16.1 6.7"
                                                        stroke="green"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                    />

                                                    <line
                                                        fill="none"
                                                        stroke="green"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        x1="12"
                                                        x2="12"
                                                        y1="16.3"
                                                        y2="4.8"
                                                    />
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
                                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500"
                                        >
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                            >
                                                N°
                                            </th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                            >
                                                Código
                                            </th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 w-[400px] text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                            >
                                                Descripción
                                            </th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                            >
                                                Unidad
                                            </th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                            >
                                                Precio Unitario
                                            </th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                            >
                                                Cantidad
                                            </th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                            >
                                                Precio Total
                                            </th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                            >
                                                Evidencia
                                            </th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                            >
                                                Hito
                                            </th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                            >
                                                Observación
                                            </th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                            ></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(
                                                item, index
                                            ) in form.base_lines"
                                            :key="index"
                                            class="text-gray-700"
                                        >
                                            <td
                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                            >
                                                {{ index + 1 }}
                                            </td>
                                            <td
                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                            >
                                                {{ item.code }}
                                            </td>
                                            <td
                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                            >
                                                {{ item.description }}
                                            </td>
                                            <td
                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                            >
                                                {{ item.unit }}
                                            </td>
                                            <td
                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                            >
                                                {{ item.unit_price }}
                                            </td>
                                            <td
                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                            >
                                                <input
                                                    type="number"
                                                    v-model="
                                                        form.base_lines[index]
                                                            .quantity
                                                    "
                                                    @input="
                                                        updateTotalPrice(index)
                                                    "
                                                    class="w-full border border-gray-300 rounded-md text-center p-1 focus:ring focus:ring-indigo-500 focus:border-indigo-500"
                                                    min="0"
                                                />
                                            </td>
                                            <td
                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                            >
                                                {{ item.total_price }}
                                            </td>
                                            <td
                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                            >
                                                {{ item.evidence }}
                                            </td>
                                            <td
                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                            >
                                                {{ item.goal }}
                                            </td>
                                            <td
                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                            >
                                                {{ item.observation }}
                                            </td>
                                            <td
                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                            >
                                                <div class="flex items-center">
                                                    <button
                                                        @click.prevent="
                                                            delete_base_line(
                                                                index
                                                            )
                                                        "
                                                        class="text-red-600 hover:underline mr-2"
                                                    >
                                                        <TrashIcon
                                                            class="h-5 w-5"
                                                        />
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div>
                            <hr class="border-1 border-gray-500 my-10 black" />
                            <div class="flex flex-space gap-2">
                                <h2 class="font-black">Cronograma:</h2>
                                <button
                                    type="button"
                                    @click=""
                                    class="px-1"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-6 h-6 text-indigo-500"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                        />
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-5">
                            <div class="overflow-x-auto mt-3">
                                <table class="w-full whitespace-no-wrap">
                                    <thead>
                                        <tr
                                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500"
                                        >
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                            >
                                                N°
                                            </th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                            >
                                                Actividad
                                            </th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 w-[400px] text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                            >
                                                Días
                                            </th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                            ></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(
                                                item, index
                                            ) in form.schedule"
                                            :key="index"
                                            class="text-gray-700"
                                        >
                                            <td
                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                            >
                                                {{ index + 1 }}
                                            </td>
                                            <td
                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                            >
                                                {{ item.activity }}
                                            </td>
                                            <td
                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                            >
                                                {{ item.days }}
                                            </td>
                                            <td
                                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                            >
                                                <div class="flex items-center">
                                                    <button
                                                        @click.prevent="
                                                            delete_schedule(
                                                                index
                                                            )
                                                        "
                                                        class="text-red-600 hover:underline mr-2"
                                                    >
                                                        <TrashIcon
                                                            class="h-5 w-5"
                                                        />
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div
                    v-if="auth.user.role_id === 1"
                    class="mt-3 flex items-center justify-end gap-x-6"
                >
                    <button
                        type="submit"
                        :class="{ 'opacity-25': form.processing }"
                        class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    >
                        Guardar
                    </button>
                </div>
            </form>
            <Modal :show="showModalMember">
                <form class="p-6" @submit.prevent="add_employee">
                    <h2 class="text-lg font-medium text-gray-900">
                        Agregar un miembro del equipo
                    </h2>
                    <div
                        class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2"
                    >
                        <div class="sm:col-span-3">
                            <InputLabel
                                for="name"
                                class="font-medium leading-6 text-gray-900"
                                >Empleado
                            </InputLabel>
                            <div class="mt-2">
                                <select
                                    required
                                    id="type"
                                    v-model="employeeToAdd.employee"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                >
                                    <option disabled value="">
                                        Seleccione uno
                                    </option>
                                    <option
                                        v-for="item in available_employees"
                                        :key="item.id"
                                        :value="item"
                                    >
                                        {{ item.name }} {{ item.lastname }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel
                                class="font-medium leading-6 text-gray-900"
                                >Rol de la persona</InputLabel
                            >
                            <div class="mt-2">
                                <select
                                    required
                                    id="type"
                                    v-model="employeeToAdd.charge"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                >
                                    <option disabled value="">
                                        Seleccione uno
                                    </option>
                                    <option value="lider">Lider</option>
                                    <option value="sublider">Sublider</option>
                                    <option value="supervisor">
                                        Supervisor
                                    </option>
                                    <option value="trabajador">
                                        Trabajador
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel
                                class="font-medium leading-6 text-gray-900"
                                >Costo de Planilla</InputLabel
                            >
                            <div class="mt-2">
                                <input
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    v-model="employeeToAdd.cost"
                                    required
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3 justify-end">
                        <SecondaryButton type="button" @click="closeModal">
                            Cerrar
                        </SecondaryButton>
                        <PrimaryButton type="submit"> Agregar </PrimaryButton>
                    </div>
                </form>
            </Modal>

            <Modal :show="edit_employee_modal">
                <form class="p-6" @submit.prevent="submit_edit_employee">
                    <h2 class="text-lg font-medium text-gray-900">
                        Editar miembro
                    </h2>
                    <div
                        class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2"
                    >
                        <div class="sm:col-span-3">
                            <InputLabel
                                class="font-medium leading-6 text-gray-900"
                                >Rol de la persona</InputLabel
                            >
                            <div class="mt-2">
                                <select
                                    required
                                    id="type"
                                    v-model="edit_employee_form.charge"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                >
                                    <option disabled value="">
                                        Seleccione uno
                                    </option>
                                    <option value="lider">Lider</option>
                                    <option value="sublider">Sublider</option>
                                    <option value="supervisor">
                                        Supervisor
                                    </option>
                                    <option value="trabajador">
                                        Trabajador
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel
                                class="font-medium leading-6 text-gray-900"
                                >Costo de Planilla</InputLabel
                            >
                            <div class="mt-2">
                                <input
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    v-model="edit_employee_form.cost"
                                    required
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3 justify-end">
                        <SecondaryButton
                            type="button"
                            @click="close_edit_employee"
                        >
                            Cerrar
                        </SecondaryButton>
                        <PrimaryButton type="submit"> Agregar </PrimaryButton>
                    </div>
                </form>
            </Modal>

            <Modal :show="baseline_modal" @close="openBaseLine">
                <div class="p-6">
                    <h2 class="text-base font-medium leading-7 text-gray-900">
                        Ingresar Línea
                    </h2>
                    <form @submit.prevent="addBaseLine">
                        <div class="space-y-12">
                            <div
                                class="border-b grid grid-cols-1 md:grid-cols-2 gap-6 border-gray-900/10 pb-12"
                            >
                                <div>
                                    <InputLabel
                                        for="code"
                                        class="font-medium leading-6 text-gray-900"
                                    >
                                        Código de Precio
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input
                                            type="text"
                                            id="code"
                                            v-model="baseForm.code"
                                            @input="
                                                (e) => handleAutocomplete(e)
                                            "
                                            list="price_guide_list"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                            placeholder="Seleccione un código"
                                        />
                                        <datalist id="price_guide_list">
                                            <option
                                                v-for="pg in price_guides"
                                                :key="pg.id"
                                                :value="pg.code"
                                            >
                                                {{ pg.description }}
                                            </option>
                                        </datalist>
                                        <InputError
                                            :message="baseForm.errors.code"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel
                                        class="font-medium leading-6 text-gray-900"
                                    >
                                        Descripción
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input
                                            type="text"
                                            v-model="baseForm.description"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                            readonly
                                        />
                                        <InputError
                                            :message="
                                                baseForm.errors.description
                                            "
                                        />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel
                                        class="font-medium leading-6 text-gray-900"
                                    >
                                        Unidad
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input
                                            type="text"
                                            v-model="baseForm.unit"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                            readonly
                                        />
                                        <InputError
                                            :message="baseForm.errors.unit"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel
                                        class="font-medium leading-6 text-gray-900"
                                    >
                                        Precio Unitario
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input
                                            type="number"
                                            step="0.01"
                                            v-model="baseForm.unit_price"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                            readonly
                                        />
                                        <InputError
                                            :message="
                                                baseForm.errors.unit_price
                                            "
                                        />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel
                                        for="quantity"
                                        class="font-medium leading-6 text-gray-900"
                                    >
                                        Cantidad
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input
                                            type="number"
                                            min="0"
                                            id="quantity"
                                            v-model="baseForm.quantity"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        />
                                        <InputError
                                            :message="baseForm.errors.quantity"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel
                                        for="total_price"
                                        class="font-medium leading-6 text-gray-900"
                                    >
                                        Precio Total
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input
                                            type="number"
                                            v-model="baseForm.total_price"
                                            min="0"
                                            id="total_price"
                                            readonly
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel
                                        for="evidence"
                                        class="font-medium leading-6 text-gray-900"
                                    >
                                        Evidencia
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input
                                            type="text"
                                            id="evidence"
                                            v-model="baseForm.evidence"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        />
                                        <InputError
                                            :message="baseForm.errors.evidence"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel
                                        for="goal"
                                        class="font-medium leading-6 text-gray-900"
                                    >
                                        Hito
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input
                                            type="text"
                                            id="goal"
                                            v-model="baseForm.goal"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        />
                                        <InputError
                                            :message="baseForm.errors.goal"
                                        />
                                    </div>
                                </div>

                                <div class="col-span-1 md:col-span-2">
                                    <InputLabel
                                        for="baseObservation"
                                        class="font-medium leading-6 text-gray-900"
                                    >
                                        Observación
                                    </InputLabel>
                                    <div class="mt-2">
                                        <textarea
                                            id="baseObservation"
                                            v-model="baseForm.observation"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        />
                                        <InputError
                                            :message="
                                                baseForm.errors.observation
                                            "
                                        />
                                    </div>
                                </div>
                            </div>
                            <div
                                class="mt-6 flex items-center justify-end gap-x-6"
                            >
                                <SecondaryButton @click="openBaseLine">
                                    Cancelar
                                </SecondaryButton>
                                <button
                                    type="submit"
                                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                                >
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </Modal>

            <Modal
                :show="show_import"
                @close="openImportExcel"
                :closeable="true"
            >
                <div class="p-6">
                    <h2 class="text-base font-black leading-7 text-gray-900">
                        Importar Excel
                    </h2>
                    <h2 class="font-black text-lg mt-4 text-indigo-700">
                        Consideraciones:
                    </h2>
                    <div
                        class="bg-indigo-50 border-l-4 text-sm border-indigo-500 p-4 rounded-lg shadow-sm mt-2"
                    >
                        <p class="text-gray-700">
                            <span class="font-semibold text-indigo-600">•</span>
                            Descargue la
                            <a
                                :href="
                                    route('huawei.projects.baselines.template')
                                "
                                class="font-black text-indigo-600 hover:underline"
                                >ESTRUCTURA DE DATOS.</a
                            >
                        </p>
                        <p class="text-gray-700">
                            <span class="font-semibold text-indigo-600">•</span>
                            La importación
                            <span class="font-black text-indigo-600"
                                >NO VA A GUARDAR</span
                            >
                            los datos actuales, para guardar todo el proyecto
                            debe hacer click en el botón
                            <span class="font-black text-indigo-600"
                                >GUARDAR</span
                            >
                            al final de la página.
                        </p>
                        <p class="text-gray-700">
                            <span class="font-semibold text-indigo-600">•</span>
                            La importación
                            <span class="text-indigo-600 font-semibold"
                                >NO REEMPLAZARÁ</span
                            >
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

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-5 text-sm"
                    >
                        <div
                            class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm"
                        >
                            <span class="font-black text-indigo-700"
                                >Columna A:</span
                            >
                            <span class="text-gray-700">Código</span>
                        </div>
                        <div
                            class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm"
                        >
                            <span class="font-black text-indigo-700"
                                >Columna B:</span
                            >
                            <span class="text-gray-700">Cantidad</span>
                        </div>
                        <div
                            class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm"
                        >
                            <span class="font-black text-indigo-700"
                                >Columna C:</span
                            >
                            <span class="text-gray-700">Evidencia</span>
                        </div>
                        <div
                            class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm"
                        >
                            <span class="font-black text-indigo-700"
                                >Columna D:</span
                            >
                            <span class="text-gray-700">Hito</span>
                        </div>
                        <div
                            class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm"
                        >
                            <span class="font-black text-indigo-700"
                                >Columna E:</span
                            >
                            <span class="text-gray-700">Observación</span>
                        </div>
                    </div>

                    <form @submit.prevent="importExcel">
                        <div class="space-y-12 mt-4">
                            <div class="grid sm:grid-cols-2 gap-6 pb-6">
                                <div class="md:col-span-2 col-span-1">
                                    <InputLabel
                                        for="file"
                                        class="font-medium leading-6 text-gray-900"
                                        >Archivo</InputLabel
                                    >
                                    <div class="mt-2">
                                        <InputFile
                                            type="file"
                                            v-model="formFile.file"
                                            accept=".xlsx"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                        />
                                        <InputError
                                            :message="formFile.errors.file"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 flex justify-end gap-4">
                            <SecondaryButton @click="openImportExcel">
                                Cancelar
                            </SecondaryButton>
                            <button
                                type="submit"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            >
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </Modal>
        </div>
        <ConfirmCreateModal
            :confirmingcreation="showModal"
            itemType="Proyecto de Huawei"
        />
        <ConfirmUpdateModal
            :confirmingupdate="showUpdateModal"
            itemType="Proyecto de Huawei"
        />

        <SuccessOperationModal
            :confirming="showPersonalAddModal"
            :title="`Personal creado.`"
            :message="`El personal fue añadido.`"
        />
        <SuccessOperationModal
            :confirming="showPersonalRemoveModal"
            :title="`Personal removido.`"
            :message="`El personal fue removido.`"
        />
        <SuccessOperationModal
            :confirming="show_edit_employee"
            :title="`Personal actualizado.`"
            :message="`El personal fue actualizado.`"
        />
        <ErrorOperationModal
            :showError="alreadyEmployeeInProject"
            title="Empleado ya agregado"
            message="El empleado ya ha sido agregado al proyecto."
        />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ConfirmCreateModal from "@/Components/ConfirmCreateModal.vue";
import ConfirmUpdateModal from "@/Components/ConfirmUpdateModal.vue";
import SuccessOperationModal from "@/Components/SuccessOperationModal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";
import { ref, watch } from "vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import {
    UserPlusIcon,
    TrashIcon,
    EyeIcon,
    PencilSquareIcon,
} from "@heroicons/vue/24/outline";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import ErrorOperationModal from "@/Components/ErrorOperationModal.vue";
import TextInput from "@/Components/TextInput.vue";
import InputFile from "@/Components/InputFile.vue";
import { notify, notifyError } from "@/Components/Notification";
import { Toaster } from "vue-sonner";
import { toFormData } from "@/utils/utils";

const showModal = ref(false);
const showUpdateModal = ref(false);
const alreadyEmployeeInProject = ref(false);
const props = defineProps({
    huawei_sites: Object,
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
    name: "",
    description: "",
    zone: "",
    huawei_site_id: "",
    cost_center_id: "",
    ot: "",
    prefix: "",
    pre_report: null,
    employees: [],
    base_lines: [],
    schedule: [],
    initial_amount: "",
    assigned_diu: "",
    macro_project: "",
};

const form = useForm({ ...initialState });

if (props.huawei_project) {
    form.name = props.huawei_project.name || "";
    form.huawei_site_id = props.huawei_project.huawei_site_id || "";
    form.description = props.huawei_project.description || "";
    form.ot = props.huawei_project.ot || "";
    form.initial_amount = props.huawei_project.initial_amount || "";
    form.assigned_diu = props.huawei_project.assigned_diu || "";
    form.employees = props.huawei_project.huawei_project_employees
        ? props.huawei_project.huawei_project_employees.map((employee) => ({
              id: employee.id,
              employee: employee.employee,
              charge: employee.role,
              cost: employee.cost,
          }))
        : [];
}

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
        });
    } else {
        form.post(
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
            }
        );
    }
};

const showModalMember = ref(false);
const empInitState = { employee: "", charge: "", cost: "" };
const employeeToAdd = ref(JSON.parse(JSON.stringify(empInitState)));
const edit_employee_modal = ref(false);
const showPersonalAddModal = ref(false);
const showPersonalRemoveModal = ref(false);
const editingMember = ref(null);
const show_edit_employee = ref(false);
const employee_edit_index = ref(null);
const available_employees = ref([...props.employees]);
const baseline_modal = ref(false);

const filterAvailableEmployees = () => {
    available_employees.value = props.employees.filter(
        (employee) =>
            !form.employees.some((emp) => emp.employee.id === employee.id)
    );
};

filterAvailableEmployees();

watch(
    () => form.employees,
    () => {
        filterAvailableEmployees();
    },
    { deep: true }
);

const showToAddEmployee = () => {
    showModalMember.value = true;
};
const closeModal = () => {
    employeeToAdd.value = JSON.parse(JSON.stringify(empInitState));
    showModalMember.value = false;
};

const add_employee = () => {
    if (props.huawei_project) {
        const employeeExists =
            props.huawei_project.huawei_project_employees.some((employee) => {
                return employee.employee_id === employeeToAdd.value.employee.id;
            });
        if (employeeExists) {
            alreadyEmployeeInProject.value = true;
            setTimeout(() => {
                alreadyEmployeeInProject.value = false;
            }, 3000);
        } else {
            router.post(
                route("huawei.projects.addemployee", {
                    huawei_project: props.huawei_project.id,
                }),
                { ...employeeToAdd.value },
                {
                    onError: () => {
                        alert("SERVER ERROR");
                    },
                    onSuccess: () => {
                        showPersonalAddModal.value = true;
                        setTimeout(() => {
                            showPersonalAddModal.value = false;
                        }, 1500);
                        router.visit(
                            route("huawei.projects.toupdate", {
                                huawei_project: props.huawei_project.id,
                            })
                        );
                    },
                }
            );
            showModalMember.value = false;
        }
    } else {
        const employeeExists = form.employees.some((employee) => {
            return employee.employee.id === employeeToAdd.value.employee.id;
        });
        if (employeeExists) {
            alreadyEmployeeInProject.value = true;
            setTimeout(() => {
                alreadyEmployeeInProject.value = false;
            }, 3000);
        } else {
            form.employees.push(
                JSON.parse(JSON.stringify(employeeToAdd.value))
            );
            employeeToAdd.value = JSON.parse(JSON.stringify(empInitState));
            showModalMember.value = false;
        }
    }
};
const delete_employee = (index) => {
    form.employees.splice(index, 1);
};

const openPreviewPreReport = (projectId) => {
    const routeToShow = route("huawei.projects.prereport", {
        huawei_project: projectId,
    });
    window.open(routeToShow, "_blank");
};

const delete_already_employee = (pivot_id, index) => {
    router.delete(route("huawei.projects.deleteemployee", { id: pivot_id }), {
        onError: () => {
            alert("SERVER ERROR");
        },
        onSuccess: () => {
            showPersonalRemoveModal.value = true;
            setTimeout(() => {
                showPersonalRemoveModal.value = false;
            }, 1500);
            form.employees.splice(index, 1);
        },
    });
};

const edit_employee_form = useForm({
    id: "",
    charge: "",
    cost: "",
});

const edit_employee = (item, index) => {
    editingMember.value = JSON.parse(JSON.stringify(item));
    employee_edit_index.value = index;
    edit_employee_form.id = editingMember.value.id;
    edit_employee_form.charge = editingMember.value.charge;
    edit_employee_form.cost = editingMember.value.cost;
    edit_employee_modal.value = true;
};

const close_edit_employee = () => {
    editingMember.value = null;
    edit_employee_form.reset();
    edit_employee_form.clearErrors(), (edit_employee_modal.value = false);
};
const submit_edit_employee = () => {
    edit_employee_form.put(
        route("huawei.projects.editemployee", {
            huawei_project: props.huawei_project.id,
            id: edit_employee_form.id,
        }),
        {
            onSuccess: () => {
                form.employees[employee_edit_index.value].charge =
                    edit_employee_form.charge;
                form.employees[employee_edit_index.value].cost =
                    edit_employee_form.cost;
                close_edit_employee();
                show_edit_employee.value = true;
                setTimeout(() => {
                    show_edit_employee.value = false;
                }, 2000);
            },
        }
    );
};

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

        const zoneKey = form.zone.toLowerCase();
        baseForm.unit_price = selectedGuide[zoneKey] || 0;
    } else {
        baseForm.description = "";
        baseForm.unit = "";
        baseForm.quantity = "";
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

const openBaseLine = () => {
    baseForm.reset();
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
    form.base_lines.push({ ...baseForm });
    baseForm.reset();
    baseline_modal.value = false;
};

const delete_base_line = (index) => {
    form.base_lines.splice(index, 1);
};
const updateTotalPrice = (index) => {
    const line = form.base_lines[index];
    line.total_price = (line.unit_price * line.quantity).toFixed(2);
};

const formFile = useForm({
    file: "",
});

const openImportExcel = () => {
    formFile.reset();
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
        form.base_lines.push(...response.data.lines);
        openImportExcel();
    } catch (error) {
        if (error.response.data.error) {
            notifyError(error.response.data.error);
        }
    }
};

//schedule
const delete_schedule = (index) => {
    form.schedule.splice(index, 1);
};
</script>
