<template>
    <Head title="Detalles"/>
    <AuthenticatedLayout :redirectRoute="{ route: 'huawei.inventory.show', params: props.equipment ? { equipment: 1 } : { equipment: null } }">
        <template #header>
            Detalles
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="flex items-center justify-end p-4"> <!-- Añadí justify-end para alinear a la derecha y padding para separación del borde -->
                <form @submit.prevent="search" class="flex items-center">
                    <TextInput type="text" placeholder="Buscar..." v-model="searchForm.searchTerm" class="mr-2" /> <!-- Añadí mr-2 para separación del botón -->
                    <button type="submit"
                        :class="{ 'opacity-25': searchForm.processing }"
                        class="rounded-md bg-indigo-600 px-2 py-2 whitespace-no-wrap text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600 focus-visible:border-transparent">
                        <svg width="30px" height="21px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                                stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </form>
            </div>
            <div>
                <div v-if="props.equipment">
                    <div class="overflow-x-auto mt-3">
                        <table class="w-full whitespace-no-wrap">
                            <thead>
                                <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center min-w-[200px]">
                                        Descripción del Equipo
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                        Estado
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center min-w-[200px]">
                                        Proyecto
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                        OT del Proyecto
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                        DIU Asignada
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                        Número de Guía de Entrada
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                        Fecha de Entrada
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                        Precio Unitario de Entrada
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                        N° Serie Ingresada
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                        Observaciones de Ingreso
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                        Observaciones del Equipo
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in (props.search ? props.entries : entries.data)" :key="item.id" class="text-gray-700">
                                    <td class="border-b border-gray-200 px-5 py-5 text-sm text-center"
                                     :class="{
                                        'bg-green-400': item.antiquation_state === 'Green',
                                        'bg-yellow-400': item.antiquation_state === 'Yellow',
                                        'bg-orange-400': item.antiquation_state === 'Orange',
                                        'bg-red-400': item.antiquation_state === 'Red',
                                        'bg-white': item.antiquation_state === 'none'
                                    }">
                                    {{ item.huawei_equipment_serie?.huawei_equipment?.name }}</td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.state }}</td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                        <template v-if="item.state == 'En Proyecto'">
                                        <Link class="text-blue-600 hover:underline" :href="route('huawei.projects.resources', {huawei_project: item.latest_huawei_project_resource.huawei_project_id, equipment: 1})">
                                            {{ item.latest_huawei_project_resource.huawei_project.name + ' / ' + item.latest_huawei_project_resource.huawei_project.code }}
                                        </Link>
                                        </template>
                                        <template v-else>
                                        <span>-</span>
                                        </template>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                        <template v-if="item.state == 'En Proyecto'">
                                            {{ item.latest_huawei_project_resource.huawei_project.ot }}
                                        </template>
                                        <template v-else>
                                        <span>-</span>
                                        </template>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"><button @click.prevent="openAssignModal(item.id)" class="text-blue-600 font-black hover:underline">{{ item.assigned_diu ? item.assigned_diu : 'Asignar DIU' }}</button></td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry.guide_number }}</td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ formattedDate(item.huawei_entry.entry_date) }}</td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap">{{ item.unit_price ? 'S/. ' + item.unit_price.toFixed(2) : '-' }}</td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_equipment_serie?.serie_number }}</td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry.observation }}</td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.observation }}</td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                        <div v-if="item.state == 'Disponible'" class="flex items-center">
                                            <button @click.prevent="openRefundModal(item.id)" class="text-blue-600 hover:underline mr-2">
                                                <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M4 8L3.29289 8.70711L2.58579 8L3.29289 7.29289L4 8ZM9 20C8.44772 20 8 19.5523 8 19C8 18.4477 8.44772 18 9 18L9 20ZM8.29289 13.7071L3.29289 8.70711L4.70711 7.29289L9.70711 12.2929L8.29289 13.7071ZM3.29289 7.29289L8.29289 2.29289L9.70711 3.70711L4.70711 8.70711L3.29289 7.29289ZM4 7L14.5 7L14.5 9L4 9L4 7ZM14.5 20L9 20L9 18L14.5 18L14.5 20ZM21 13.5C21 17.0898 18.0898 20 14.5 20L14.5 18C16.9853 18 19 15.9853 19 13.5L21 13.5ZM14.5 7C18.0899 7 21 9.91015 21 13.5L19 13.5C19 11.0147 16.9853 9 14.5 9L14.5 7Z" fill="#33363F"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="!props.search" class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                        <pagination :links="props.entries.links" />
                    </div>
                </div>
                <div v-else >
                    <div class="overflow-x-auto mt-3">
                        <table class="w-full whitespace-no-wrap">
                            <thead>
                                <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                        Descripción del Material
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                        Estado
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                        Cantidad Ingresada
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                        Cantidad Devuelta
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                        Cantidad en Proyectos
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                        Cantidad Disponible
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                        Número de Guía de Entrada
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                        Fecha de Entrada
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                        Precio Unitario de Entrada
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                        Observaciones del Material
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                        Observaciones de Ingreso
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="item in (props.search ? props.entries : entries.data)" :key="item.id">
                                    <tr class="text-gray-700">
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_material.name }}</td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.state }}</td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.quantity }}</td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.refund_quantity }}</td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.project_quantity }}</td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.available_quantity }}</td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry.guide_number }}</td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ formattedDate(item.huawei_entry.entry_date) }}</td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap">{{ item.unit_price ? 'S/. ' + item.unit_price.toFixed(2) : '-' }}</td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.observation }}</td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry.observation }}</td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                            <div class="flex items-center">
                                                <div v-if="item.available_quantity !== 0">
                                                    <button @click.prevent="openRefundModal(item.id)" class="text-blue-600 hover:underline mr-2">
                                                        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M4 8L3.29289 8.70711L2.58579 8L3.29289 7.29289L4 8ZM9 20C8.44772 20 8 19.5523 8 19C8 18.4477 8.44772 18 9 18L9 20ZM8.29289 13.7071L3.29289 8.70711L4.70711 7.29289L9.70711 12.2929L8.29289 13.7071ZM3.29289 7.29289L8.29289 2.29289L9.70711 3.70711L4.70711 8.70711L3.29289 7.29289ZM4 7L14.5 7L14.5 9L4 9L4 7ZM14.5 20L9 20L9 18L14.5 18L14.5 20ZM21 13.5C21 17.0898 18.0898 20 14.5 20L14.5 18C16.9853 18 19 15.9853 19 13.5L21 13.5ZM14.5 7C18.0899 7 21 9.91015 21 13.5L19 13.5C19 11.0147 16.9853 9 14.5 9L14.5 7Z" fill="#33363F"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <button type="button" @click="toggleDetails(item)"
                                                    class="text-blue-900 whitespace-no-wrap">
                                                    <svg v-if="huaweiProject?.id !== item.id" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                                    </svg>
                                                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <template v-if="huaweiProject?.id == item.id">
                                    <tr class="border-b text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        <th
                                            class="border-b-2 border-gray-200 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-white px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                            Cantidad en Proyecto
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-white px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                            Proyecto
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-white px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                            OT
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-white px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                            DIU del Proyecto
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-white px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                        </th>
                                    </tr>
                                    <tr v-for="project_resource in huaweiProject?.huawei_project_resources" :key="project_resource.id" class="bg-gray-100">
                                        <th
                                            class="border-b-2 border-gray-200 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400">
                                        </th>

                                        <th
                                            class="border-b-2 border-gray-200 bg-white px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                            {{  project_resource.huawei_project_liquidation ? project_resource.huawei_project_liquidation.liquidated_quantity : project_resource.quantity }}
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-white px-5 py-3 text-left text-xs font-semibold tracking-wider text-gray-600 text-center">
                                            <Link class="text-blue-600 hover:underline" :href="route('huawei.projects.resources', {huawei_project: project_resource.huawei_project_id})">
                                                {{  project_resource.huawei_project.name + ' / ' + project_resource.huawei_project.code  }}
                                            </Link>
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-white px-5 py-3 text-left text-xs font-semibold tracking-wider text-gray-600 text-center">
                                            {{  project_resource.huawei_project.ot  }}
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-white px-5 py-3 text-left text-xs font-semibold tracking-wider text-gray-600 text-center">
                                            {{  project_resource.huawei_project.assigned_diu  }}
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-white px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        </th>
                                    </tr>
                                    </template>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="!props.search" class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                        <pagination :links="props.entries.links" />
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="refundModal">
          <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">Devolver {{ props.equipment ? 'Equipo' : 'Material' }}</h2>
            <form @submit.prevent="refund" class="grid grid-cols-2 gap-3">

              <!-- Tercera Fila -->
              <div class="col-span-2 grid grid-cols-2 gap-3">
                <div v-if="!props.equipment" class="col-span-2">
                    <InputLabel class="mb-1" for="quantity">Cantidad</InputLabel>
                    <input type="number" min="0" v-model="refundForm.quantity" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                </div>

                <div class="col-span-2">
                    <InputLabel class="mb-1" for="observation">Observación</InputLabel>
                    <textarea v-model="refundForm.observation" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                </div>

              </div>

              <!-- Botones de Acción -->
              <div class="col-span-2 mt-6 flex items-center justify-end gap-x-6">
                <SecondaryButton @click="closeRefundModal">Cancelar</SecondaryButton>
                <PrimaryButton type="submit" :class="{ 'opacity-25': refundForm.processing }">Guardar</PrimaryButton>
              </div>
            </form>
          </div>
        </Modal>

        <Modal :show="assignModal">
          <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">Asignar DIU</h2>
            <form @submit.prevent="assignDiu" class="grid grid-cols-2 gap-3">

              <!-- Tercera Fila -->
              <div class="col-span-2 grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <InputLabel class="mb-1" for="quantity">DIU</InputLabel>
                    <input type="text" v-model="assignForm.assigned_diu" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                </div>
              </div>

              <!-- Botones de Acción -->
              <div class="col-span-2 mt-6 flex items-center justify-end gap-x-6">
                <SecondaryButton @click="closeAssignModal">Cancelar</SecondaryButton>
                <PrimaryButton type="submit" :class="{ 'opacity-25': assignForm.processing }">Asignar</PrimaryButton>
              </div>
            </form>
          </div>
        </Modal>

        <SuccessOperationModal :confirming="showRefundConfirm" title="Éxito" message="La devolución se registró correctamente." />
        <ErrorOperationModal :showError="showErrorModal" :title="'Error'" :message="'La cantidad solicitada para devolución excede a la disponible.'" />
        <SuccessOperationModal :confirming="confirmAssign" title="Éxito" message="Se asignó la DIU correctamente." />

    </AuthenticatedLayout>
</template>

<script setup>
    import { Head, router, useForm, Link } from '@inertiajs/vue3';
    import Pagination from '@/Components/Pagination.vue';
    import { formattedDate } from '@/utils/utils'
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import TextInput from '@/Components/TextInput.vue';
    import Modal from '@/Components/Modal.vue';
    import { ref } from 'vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
    import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';

    const props = defineProps({
        entries: Object,
        equipment: [String, null],
        search: String,
        id: String
    });

    const refundModal = ref(false);
    const showRefundConfirm = ref(false);
    const showErrorModal = ref(false);
    const huaweiProject = ref(null);
    const assignModal = ref(false);
    const confirmAssign = ref(false);

    const searchForm = useForm({
        searchTerm: props.search ? props.search : '',
    })

    const refundForm = useForm({
        huawei_entry_detail_id: '',
        quantity: '',
        observation: ''
    });

    const openRefundModal = (id) => {
        refundForm.huawei_entry_detail_id = id;
        refundModal.value = true;
    }

    const closeRefundModal = () => {
        refundForm.reset();
        refundModal.value = false;
    }

    const assignForm = useForm({
        huawei_entry_detail_id: '',
        assigned_diu: ''
    })

    const openAssignModal = (id) => {
        assignForm.huawei_entry_detail_id = id;
        assignModal.value = true;
    }

    const closeAssignModal = () => {
        assignForm.reset();
        assignForm.clearErrors();
        assignModal.value = false;
    }

    const assignDiu = () => {
        assignForm.post(route('huawei.inventory.details.assigndiu'), {
            onSuccess: () => {
                closeAssignModal();
                confirmAssign.value = true;
                setTimeout(() => {
                    confirmAssign.value = false;
                }, 2000);
            }
        })
    }

    const toggleDetails = (item) => {
        if (huaweiProject.value && huaweiProject.value.id === item.id){
            huaweiProject.value = null;
        }else{
            huaweiProject.value = item;
        }
    }

    const search = () => {
        if (searchForm.searchTerm == '') {
            if (props.equipment){
                router.visit(route('huawei.inventory.show.details', {id: props.id, equipment: 1}));
            } else {
                router.visit(route('huawei.inventory.show.details', {id: props.id}));
            }
        } else {
            if (props.equipment){
                router.visit(route('huawei.inventory.show.details.search', {id: props.id, request: searchForm.searchTerm, equipment: 1}));
            } else {
                router.visit(route('huawei.inventory.show.details.search', {id: props.id, request: searchForm.searchTerm}));
            }
        }
    }

    const refund = () => {
        const url = props.equipment ? route('huawei.inventory.details.refund', {equipment: 1}) : route('huawei.inventory.details.refund');
        refundForm.post(url, {
            onSuccess: () => {
                closeRefundModal();
                showRefundConfirm.value = true;
                setTimeout(() => {
                    showRefundConfirm.value = false;
                }, 2000);
            },
            onError: (e) => {
                if (e.error_exceed){
                    showErrorModal.value = true;
                    setTimeout(() => {
                        showErrorModal.value = false;
                    }, 3000);
                }
            }
        })
    }
</script>
