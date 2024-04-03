<template>

    <Head title="Cotización Anteproyecto" />
    <AuthenticatedLayout :redirect-route="'preprojects.index'">
        <template v-if="preproject.quote" #header>
            Editando la cotización
        </template>
        <template v-else #header>
            Creación de cotización
        </template>
        <div v-if="auth.user.role_id === 1 && preproject.quote?.state"
            class="inline-flex items-center p-2 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300"
            role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div>
                <span class="font-small">Esta cotización ya fue aceptada, cuidado al modificarla</span>
            </div>
        </div>
        <div class="min-w-full p-3 rounded-lg shadow">
            <form @submit.prevent="submit">
                <div class="pt-1">
                    <div class="border-b border-gray-900 pb-12">

                        <h2 class="text-base font-semibold leading-7 text-gray-900 border-b border-gray-900/10">
                            Cotización
                        </h2>
                        <br>
                        <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">

                            <div class="sm:col-span-3">
                                <InputLabel for="name" class="font-medium leading-6 text-gray-900">Nombre del proyecto
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="form.name" id="name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.name" />
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel for="date" class="font-medium leading-6 text-gray-900">Fecha de Inicio
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="Date" v-model="form.date" id="date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.date" />
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel for="supervisor" class="font-medium leading-6 text-gray-900">Supervisor
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="form.supervisor" id="supervisor"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.supervisor" />
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel for="boss" class="font-medium leading-6 text-gray-900">Jefe
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="form.boss" id="boss"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.boss" />
                                </div>
                            </div>


                            <div class="sm:col-span-3">
                                <InputLabel for="deliverable_time" class="font-medium leading-6 text-gray-900">Tiempo de
                                    entrega (días)
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="number" v-model="form.deliverable_time" id="deliverable_time" min="1"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.deliverable_time" />
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel for="validity_time" class="font-medium leading-6 text-gray-900">Tiempo de
                                    validez (días)
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="number" v-model="form.validity_time" id="validity_time" min="1"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.validity_time" />
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <InputLabel for="rev" class="font-medium leading-6 text-gray-900">Rev.
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="number" v-model="form.rev" id="rev" min="1"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.rev" />
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel for="deliverable_place" class="font-medium leading-6 text-gray-900">Lugar de
                                    entrega
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput v-model="form.deliverable_place" id="deliverable_place"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.deliverable_place" />
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel for="payment_type" class="font-medium leading-6 text-gray-900">Tipo de pago
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput v-model="form.payment_type" id="payment_type"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.payment_type" />
                                </div>
                            </div>


                            <div class="sm:col-span-3">
                                <InputLabel for="observations" class="font-medium leading-6 text-gray-900">Observaciones
                                </InputLabel>
                                <div class="mt-2">
                                    <textarea type="text" v-model="form.observations" id="observations"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.observations" />
                                </div>
                            </div>

                            <div class="col-span-1 sm:col-span-6 xl:col-span-4 mt-4">
                                <div class="flex gap-2 items-center">
                                    <h2 class="text-base font-bold leading-6 text-gray-900 ">Productos
                                    </h2>
                                    
                                    <button v-if="auth.user.role_id === 1 || preproject.quote === null"
                                        @click="openProductModal" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="text-blue-500 hover:text-purple-500 w-7 h-7">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M7.5 7.5h-.75A2.25 2.25 0 0 0 4.5 9.75v7.5a2.25 2.25 0 0 0 2.25 2.25h7.5a2.25 2.25 0 0 0 2.25-2.25v-7.5a2.25 2.25 0 0 0-2.25-2.25h-.75m-6 3.75 3 3m0 0 3-3m-3 3V1.5m6 9h.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-.75" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-sm my-2">Se muestran productos de las solicitudes de compra</p>
                                <div class="mt-2">
                                    <div class="overflow-x-auto mt-8">
                                        <table class="w-full">
                                            <thead>
                                                <tr
                                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Partida
                                                    </th>
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Código
                                                    </th>
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Nombre
                                                    </th>
                                                    <th
                                                        class="border-b-2  min-w-[150px] border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Cantidad
                                                    </th>
                                                    <th
                                                        class="border-b-2 min-w-[150px] border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Precio unitario
                                                    </th>
                                                    <th
                                                        class="border-b-2   min-w-[150px] border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Margen
                                                    </th>
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Valor total
                                                    </th>
                                                    <th v-if="auth.user.role_id === 1 || preproject.quote === null"
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Acciones
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item, index) in (form.products)" :key="index"
                                                    class="text-gray-700">
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p>
                                                            {{ index + 1 }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p>
                                                            {{ item.purchase_product?.code }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p class="text-gray-900">
                                                            {{ item.purchase_product?.name }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p v-if="preproject.quote" class="text-gray-900">
                                                            {{ item.quantity }}
                                                        </p>
                                                        <div v-else class="flex space-x-2 items-center">
                                                            <input  
                                                               required
                                                               type="number" 
                                                               min="0" 
                                                               step="0.01"
                                                               v-model="item.quantity"
                                                               class="block w-full text-center rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                        </div>
                                                    </td>
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p v-if="preproject.quote" class="text-gray-900">
                                                            S/. {{ (item.unitary_price).toFixed(2) }}
                                                        </p>
                                                        <div v-else class="flex space-x-2 items-center">
                                                            <span>S/.</span><input  
                                                               required
                                                               type="number" 
                                                               min="0" 
                                                               step="0.01"
                                                               v-model="item.unitary_price"
                                                               class="block w-full text-center rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                        </div>
                                                    </td>
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p v-if="preproject.quote" class="text-gray-900">
                                                            {{ item.profit_margin }} %
                                                        </p>
                                                        <div v-else class="flex space-x-2 items-center">
                                                            <input  
                                                               required
                                                               type="number" 
                                                               min="0" 
                                                               step="0.01"
                                                               v-model="item.profit_margin"
                                                               class="block w-full text-center rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                               <span>%</span>
                                                        </div>
                                                    </td>
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p class="text-gray-900">
                                                            S/.{{ (item.unitary_price *
                                                            item.quantity *
                                                            (1 + (item.profit_margin) / 100))
                                                            .toFixed(2) }}</p>
                                                    </td>
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <div v-if="auth.user.role_id === 1 || preproject.quote === null"
                                                            class="flex justify-end">
                                                            <button type="button" @click="deleteProduct(index, item.id)"
                                                                class="col-span-1 flex justify-end">
                                                                <TrashIcon class=" text-red-500 h-4 w-4 " />
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <InputError :message="form.errors.products" />
                            </div>


                            <div class="col-span-1 sm:col-span-6 xl:col-span-4 mt-10">
                                <div class="flex gap-2 items-center">
                                    <h2 class="text-base font-bold leading-6 text-gray-900 ">Servicios
                                    </h2>
                                    <button v-if="auth.user.role_id === 1 || preproject.quote === null"
                                        @click="showToAddItem" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="text-blue-500 hover:text-purple-500 w-7 h-7">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M7.5 7.5h-.75A2.25 2.25 0 0 0 4.5 9.75v7.5a2.25 2.25 0 0 0 2.25 2.25h7.5a2.25 2.25 0 0 0 2.25-2.25v-7.5a2.25 2.25 0 0 0-2.25-2.25h-.75m-6 3.75 3 3m0 0 3-3m-3 3V1.5m6 9h.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-.75" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="mt-2">
                                    <div class="overflow-x-auto mt-8">
                                        <table class="w-full">
                                            <thead>
                                                <tr
                                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Partida
                                                    </th>
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Descripción
                                                    </th>
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Unidad
                                                    </th>
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Días
                                                    </th>
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Metrado
                                                    </th>
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Valor unitario
                                                    </th>
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Margen
                                                    </th>
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Valor total
                                                    </th>
                                                    <th v-if="auth.user.role_id === 1 || preproject.quote === null"
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Acciones
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item, index) in (form.items)" :key="index"
                                                    class="text-gray-700">
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p>
                                                            {{ index + 1 }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p>
                                                            {{ item.description }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p class="text-gray-900">
                                                            {{ item.unit }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p class="text-gray-900">{{ item.days }}</p>
                                                    </td>
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p class="text-gray-900">{{ item.quantity }}</p>
                                                    </td>
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p class="text-gray-900">S/.{{ (item.unit_price).toFixed(2) }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p class="text-gray-900">
                                                            {{ (item.profit_margin) }}%
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p class="text-gray-900">
                                                            S/.{{
                                                                (item.unit_price *
                                                                    item.quantity *
                                                                    item.days *
                                                                    (1 + item.profit_margin / 100))
                                                                    .toFixed(2) }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <div v-if="auth.user.role_id === 1 || preproject.quote === null"
                                                            class="flex justify-end">
                                                            <button type="button"
                                                                @click=" preproject.quote ? deleteAlreadyItem(item.id, index) : deleteItem(index)"
                                                                class="col-span-1 flex justify-end">
                                                                <TrashIcon class=" text-red-500 h-4 w-4 " />
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <InputError :message="form.errors.items" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 flex items-center justify-end gap-x-6">
                    <a v-if="preproject.quote" :href="route('preprojects.pdf', { preproject: preproject.id })"
                        target="_blank" rel="noopener noreferrer"
                        class="rounded-md bg-green-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                        Exportar a PDF
                    </a>

                    <button v-if="preproject.quote && !preproject.quote.state" type="button" @click="acceptCotization"
                        :class="{ 'opacity-25': form.processing }"
                        class="rounded-md bg-yellow-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:green-indigo-600">
                        Aceptar Cotización</button>

                    <div v-if="auth.user.role_id === 1 || preproject.quote === null">
                        <button type="submit" :class="{ 'opacity-25': form.processing }"
                            class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
                    </div>
                </div>

            </form>


            <Modal :show="showProductModal">
                <form class="p-6" @submit.prevent="addProduct">
                    <h2 class="text-lg font-medium text-gray-900">
                        Añadir producto
                    </h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">

                        <div class="sm:col-span-3">
                            <InputLabel for="unit" class="font-medium leading-6 text-gray-900">
                                Producto
                            </InputLabel>
                            <div class="mt-2">
                                <input required id="unit" list="options" @input="handleAutocomplete" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />

                                <datalist id="options">
                                    <option v-for="item in products" :value="item.code" :data-value="item">
                                        {{ item.name }}
                                    </option>
                                </datalist>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="quantity" class="font-medium leading-6 text-gray-900">Cantidad
                            </InputLabel>
                            <div class="mt-2">
                                <input required type="number" v-model="productToAdd.quantity" min="1" id="quantity"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel class="leading-6 text-gray-100">Nombre:
                            </InputLabel>
                            <div class="mt-2">
                                <InputLabel class="leading-6 text-gray-100">{{ productToAdd.purchase_product.name }}
                                </InputLabel>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel class="leading-6 text-gray-100">Unidad:
                            </InputLabel>
                            <div class="mt-2">
                                <InputLabel class="leading-6 text-gray-100">{{ productToAdd.purchase_product.unit }}
                                </InputLabel>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="unitary_price" class="font-medium leading-6 text-gray-900">Precio Unitario
                                (sin
                                IGV)
                            </InputLabel>
                            <div class="mt-2">
                                <input required type="number" v-model="productToAdd.unitary_price" min="0" step="0.01"
                                    id="unitary_price"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="profit_margin" class="font-medium leading-6 text-gray-900">Margen de
                                Margen (%)
                            </InputLabel>
                            <div class="mt-2 flex gap-3 items-center">
                                <input required type="number" v-model="productToAdd.profit_margin" min="0" step="0.01"
                                    id="profit_margin"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                    </div>
                    <div class="mt-6 flex gap-3 justify-end">
                        <SecondaryButton type="button" @click="closeProductModal"> Cerrar </SecondaryButton>
                        <PrimaryButton type="submit"> Agregar </PrimaryButton>
                    </div>
                </form>
            </Modal>

            <Modal :show="showModalMember">
                <form class="p-6" @submit.prevent="addItem">
                    <h2 class="text-lg font-medium text-gray-900">
                        Agregar un item a la valorización
                    </h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">

                        <div class="sm:col-span-3">
                            <InputLabel for="description" class="font-medium leading-6 text-gray-900">Descripción
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput required type="text" v-model="itemToAdd.description"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="unit" class="font-medium leading-6 text-gray-900">Unidad
                            </InputLabel>
                            <div class="mt-2">
                                <select required  v-model="itemToAdd.unit"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccione uno</option>
                                    <option>Unidad</option>
                                    <option>Kilos</option>
                                    <option>Metros</option>
                                    <option>GLB</option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="days" class="font-medium leading-6 text-gray-900">Días
                            </InputLabel>
                            <div class="mt-2">
                                <input required type="number" v-model="itemToAdd.days" min="1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="quantity" class="font-medium leading-6 text-gray-900">Cantidad
                            </InputLabel>
                            <div class="mt-2">
                                <input required type="number" v-model="itemToAdd.quantity" min="1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="unit_price" class="font-medium leading-6 text-gray-900">Precio Unitario
                            </InputLabel>
                            <div class="mt-2">
                                <input required type="number" v-model="itemToAdd.unit_price" min="0" step="0.01"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="profit_margin" class="font-medium leading-6 text-gray-900">Margen (%)
                            </InputLabel>
                            <div class="mt-2">
                                <input required type="number" v-model="itemToAdd.profit_margin" min="0" step="0.01"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3 justify-end">
                        <SecondaryButton type="button" @click="closeModal"> Cerrar </SecondaryButton>
                        <PrimaryButton type="submit"> Agregar </PrimaryButton>
                    </div>
                </form>
            </Modal>
        </div>
        <ErrorOperationModal :showError="showErroModal" title="Error"
            message="El producto ya fue añadido o es inválido" />

        <SuccessOperationModal :confirming="showModal" :title="modalVariables.title"
            :message="modalVariables.message" />

        <!-- services's modal -->
        <SuccessOperationModal :confirming="showItemAddModal" :title="`Servicio añadido.`"
            :message="`El servicio fue añadido.`" />
        <SuccessOperationModal :confirming="showItemRemoveModal" :title="`Servicio removido.`"
            :message="`El servicio fue removido.`" />
        <!-- products's modal -->
        <SuccessOperationModal :confirming="showProductAddModal" :title="`Producto añadido.`"
            :message="`El producto fue añadido.`" />
        <SuccessOperationModal :confirming="showProductRemoveModal" :title="`Producto removido.`"
            :message="`El producto fue removido.`" />




        <AcceptModal :acceptFunction="approve" :confirmingAccept="showConfirmAccept" @closeModal="closeConfirmAccept"
            :itemType="`Cotización`" />
        <ConfirmAcceptModal :confirmingaccept="showFinishAccept" :itemType="`Cotización`" />

    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmAcceptModal from '@/Components/ConfirmAcceptModal.vue';
import AcceptModal from '@/Components/AcceptModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { TrashIcon } from '@heroicons/vue/24/outline';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';


const showModal = ref(false)
const showErroModal = ref(false)

const { preproject, auth, products, purchasing_requests } = defineProps({
    products: Object,
    preproject: Object,
    purchasing_requests: Object,
    auth: Object
})


const modalVariables = ref({
    title: `Cotización ${preproject.quote !== null ? 'actualizada' : 'creada'}`,
    message: `La cotización para anteproyecto fue ${preproject.quote !== null ? 'actualizada' : 'creada'}`
})

function productListMaker(purReqArray) {
    let ids = []
    purReqArray.forEach(item => {
        item.products.forEach(prod => {
            if (!ids.includes(prod.id)) {
                ids.push(prod.id)
            }
        })
    });
    return ids
}
let ids = productListMaker(purchasing_requests)

const initalProductState = {
    purchase_product_id: '',
    purchase_product: {
        code: '',
        name: '',
        unit: '',
    },
    quantity: '',
    unitary_price: '',
    profit_margin: '',
}


const initialState = {
    name: '',
    date: '',
    supervisor: '',
    boss: '',
    rev: '',
    deliverable_time: '',
    validity_time: '',
    deliverable_place: '',
    payment_type: '',
    observations: '',
    items: [],
    products: products.map(item => {
        if (ids.includes(item.id)) {
            return { ...initalProductState, 
                    purchase_product_id: item.id,
                    purchase_product: {
                        code: item.code, 
                        name: item.name, 
                        unit:item.unit
                    }
                    };
        } else {
            return null;
        }
    }).filter(item => item !== null),
    preproject_id: preproject.id
}

const form = useForm(
    { ...(preproject.quote ? preproject.quote : initialState) }
)

const submit = () => {
    let url = route('preprojects.quote.store')
    if (preproject.quote) {
        url = route('preprojects.quote.store', { quote_id: preproject.quote.id })
    }
    form.post(url, {
        onSuccess: () => {
            closeModal();
            showModal.value = true
            setTimeout(() => {
                showModal.value = false;
                router.visit(route('preprojects.index'))
            }, 2000);
        },
        onError: (e) => {
            console.log(e)
        }
    })
}

const showModalMember = ref(false);
const showConfirmAccept = ref(false);
const showFinishAccept = ref(false);
const itemInitialState = {
    description: '',
    unit: '',
    days: '',
    quantity: '',
    profit_margin: '',
    unit_price: '',
}
const itemToAdd = ref(JSON.parse(JSON.stringify(itemInitialState)))


const showItemAddModal = ref(false);
const showItemRemoveModal = ref(false);


const acceptCotization = () => {
    showConfirmAccept.value = true;
}

const closeConfirmAccept = () => {
    showConfirmAccept.value = false;
}

const showToAddItem = () => {
    showModalMember.value = true;
}
const closeModal = () => {
    showModalMember.value = false;
};
const addItem = () => {
    if (preproject.quote) {
        axios.post(route('preprojects.quote.item.store'), { ...itemToAdd.value, preproject_quote_id: preproject.quote.id })
            .then(response => {
                if (response.status = 200) {
                    itemToAdd.value.id = response.data.id
                    showItemAddModal.value = true
                    setTimeout(() => {
                        showItemAddModal.value = false;
                    }, 1500);
                    form.items.push({
                        ...itemToAdd.value
                    });
                }
            })
            .catch(e => console.log(e))
    } else {
        form.items.push(JSON.parse(JSON.stringify(itemToAdd.value)))
        itemToAdd.value = JSON.parse(JSON.stringify(itemInitialState))
    }
}


const deleteItem = (index) => {
    form.items.splice(index, 1);
}

const deleteAlreadyItem = (id, index) => {
    router.delete(route('preprojects.quote.item.delete', { quote_item_id: id }), {
        onError: () => {
            alert('SERVER ERROR')
        },
        onSuccess: () => {
            showItemRemoveModal.value = true
            setTimeout(() => {
                showItemRemoveModal.value = false;
            }, 1500);
            form.items.splice(index, 1);
        }
    })
}

const approve = () => {
    let url = route('preprojects.accept', { quote_id: preproject.quote.id });

    router.post(url, {
        state: '1'
    }, {
        onSuccess: () => {
            closeConfirmAccept();
            showFinishAccept.value = true
            setTimeout(() => {
                showFinishAccept.value = false;
                router.visit(route('preprojects.index'))
            }, 2000);
        },
        onError: () => {
            close();
        }
    })
}


//products valorization
const showProductAddModal = ref(false);
const showProductRemoveModal = ref(false);

const showProductModal = ref(false)


const productToAdd = ref(JSON.parse(JSON.stringify(initalProductState)))

const handleAutocomplete = (e) => {
    const code = e.target.value;
    let findedProduct = products.find(item => item.code === code)
    if (findedProduct) {
        productToAdd.value.purchase_product_id = findedProduct.id
        productToAdd.value.purchase_product.name = findedProduct.name
        productToAdd.value.purchase_product.code = findedProduct.code
        productToAdd.value.purchase_product.unit = findedProduct.unit
    } else {
        productToAdd.value.purchase_product_id = ''
        productToAdd.value.purchase_product.name = ''
        productToAdd.value.purchase_product.unit = ''
        productToAdd.value.purchase_product.code = ''
    }
}

function openProductModal() {
    showProductModal.value = true
}

function closeProductModal() {
    showProductModal.value = false
    productToAdd.value = { ...initalProductState }
}


function addProduct() {
    if (productToAdd.value.purchase_product_id && form.products.find(item => item.purchase_product_id == productToAdd.value.purchase_product_id) == undefined) {
        if (preproject.quote) {
            axios.post(route('preprojects.quote.product.store'), { ...productToAdd.value, preproject_quote_id: preproject.quote.id })
                .then(response => {
                    if (response.status === 200) {
                        showProductAddModal.value = true
                        productToAdd.value.id = response.data.id
                        form.products.push(JSON.parse(JSON.stringify(productToAdd.value)))
                        setTimeout(() => {
                            showProductAddModal.value = false;
                        }, 1500);
                        productToAdd.value = JSON.parse(JSON.stringify(initalProductState))
                        closeProductModal()
                    }
                })
                .catch(e => console.log(e))
        } else {
            form.products.push(JSON.parse(JSON.stringify(productToAdd.value)))
            closeProductModal()
            productToAdd.value = JSON.parse(JSON.stringify(initalProductState))
        }
    } else {
        showErroModal.value = true
        setTimeout(() => {
            showErroModal.value = false
        }, 1000)
    }
}


function deleteProduct(index, id) {
    if (id) {
        router.delete(route('preprojects.quote.product.delete', { quote_product_id: id }), {
            onSuccess: () => {
                showProductRemoveModal.value = true
                setTimeout(() => {
                    showProductRemoveModal.value = false
                }, 1000)
                form.products.splice(index, 1)
            }
        })
    } else {
        form.products.splice(index, 1)
    }
}

</script>