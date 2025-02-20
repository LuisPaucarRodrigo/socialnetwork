<template>

    <Head title="Gestion de Empleados" />
    <AuthenticatedLayout :redirectRoute="'fleet.cars.index'">
        <Toaster richColors />
        <template #header> Vehiculos </template>
        <div class="w-full">
            <GeneralOpciones v-model:form="formSearch" :openModalCreate="openModalCreate"
                :userPermission="userPermissions" />
            <TableStructure>
                <template #thead>
                    <tr>
                        <TableTitle v-if="hasPermission('UserManager')">
                            <div class="w-[190px]">
                                <TableHeaderCicsaFilter label="Linea de Negocio" labelClass="text-gray-600"
                                    :options="cost_line" v-model="formSearch.cost_line" />
                            </div>
                        </TableTitle>
                        <TableTitle>Placa</TableTitle>
                        <TableTitle>Modelo</TableTitle>
                        <TableTitle>Marca</TableTitle>
                        <TableTitle>Año</TableTitle>
                        <TableTitle>Tipo</TableTitle>
                        <TableTitle>Foto</TableTitle>
                        <TableTitle>Dueño</TableTitle>
                        <TableTitle></TableTitle>
                    </tr>
                </template>
                <template #tbody>
                    <template v-for="(car, i) in cars.data || cars" :key="car.id">
                        <tr class="text-gray-700">
                            <TableRow v-if="hasPermission('UserManager')">
                                {{ car.costline?.name }}
                            </TableRow>
                            <TableRow>{{ car.plate }}</TableRow>
                            <TableRow>{{ car.model }}</TableRow>
                            <TableRow>{{ car.brand }}</TableRow>
                            <TableRow>{{ car.year }}</TableRow>
                            <TableRow>{{ car.type }}</TableRow>
                            <TableRow>
                                <a v-if="car.photo" :href="route('fleet.cars.show.image', { car: car.id }) + '?' + uniqueParam"
                                    target="_blank">
                                    <EyeIcon class="w-5 h-5 text-green-600"></EyeIcon>
                                </a>
                            </TableRow>

                            <TableRow>{{ car.user.name }}</TableRow>
                            <TableRow>
                                <div class="flex space-x-3 justify-center">
                                    <!-- <p v-if="car.car_document.approvel_car_document">Actualizaciòn Pendiente</p> -->
                                    <button v-if="hasPermission('CarManager')" @click="
                                        openModalCreateDocument(car)
                                        " class="text-blue-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6"
                                            :class="car.car_document.approvel_car_document.length > 0 ? 'text-red-400' : 'text-blue-400'">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                                        </svg>
                                    </button>
                                    <button v-if="hasPermission('CarManager')" @click="
                                        openCreateModalChangelog(
                                            null,
                                            car.id
                                        )
                                        " class="text-blue-900">
                                        <svg viewBox="0 0 1024 1024" class="w-6 h-6 icon" version="1.1"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M550.208 960H209.28A81.792 81.792 0 0 1 128 877.76V146.24A81.92 81.92 0 0 1 209.344 64h613.632a81.92 81.92 0 0 1 81.28 82.432v405.76a29.824 29.824 0 1 1-59.584 0V146.56a22.272 22.272 0 0 0-21.76-22.656H209.408a22.08 22.08 0 0 0-21.696 22.528v731.52a21.76 21.76 0 0 0 21.44 22.464h341.056a29.824 29.824 0 0 1 0.064 59.584z m196.352-600.96H285.824a29.824 29.824 0 1 1 0-59.712h460.8a29.824 29.824 0 1 1 0 59.712z m-204.8 156.8H285.824a29.824 29.824 0 1 1 0-59.712h255.936a29.824 29.824 0 1 1 0 59.648z m179.2 391.936c-101.12 0-183.424-83.84-183.424-186.624a29.824 29.824 0 1 1 59.712 0c0 70.016 55.552 126.976 123.584 126.976 17.408 0 34.24-3.712 50.048-10.88a29.888 29.888 0 0 1 24.768 54.336c-23.552 10.688-48.64 16.192-74.688 16.192z m153.6-156.8a29.824 29.824 0 0 1-29.824-29.824c0-70.016-55.552-126.976-123.648-126.976-16.32 0-32.384 3.2-47.36 9.6a29.888 29.888 0 0 1-23.424-54.912 180.224 180.224 0 0 1 70.784-14.336c101.12 0 183.424 83.84 183.424 186.624a30.016 30.016 0 0 1-29.952 29.824z m-204.8-104.576h-51.264a29.76 29.76 0 0 1-25.28-14.08 30.144 30.144 0 0 1-1.536-28.928l25.6-52.352a29.696 29.696 0 0 1 53.632 0l25.6 52.352a29.696 29.696 0 0 1-1.472 28.928 29.504 29.504 0 0 1-25.28 14.08z m127.552 269.568h-1.024a29.696 29.696 0 0 1-24.896-14.848l-25.6-44.288a29.888 29.888 0 0 1 23.808-44.672l58.048-4.032c11.392-0.704 22.144 5.12 27.904 14.848a30.016 30.016 0 0 1-1.024 31.616l-32.448 48.256a29.824 29.824 0 0 1-24.768 13.12z"
                                                fill="#044d14" />
                                        </svg>
                                    </button>
                                    <button v-if="hasPermission('UserManager')" type="button"
                                        @click="openModalEdit(car)" class="text-blue-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>

                                    <a v-if="
                                        hasPermission('CarManager') &&
                                        car.checklist
                                    " :href="route(
                                        'fleet.cars.show_checklist',
                                        { car: car.id }
                                    )
                                        " class="text-blue-900">
                                        <svg class="w-6 h-6 text-green-600" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M8.04832 2.48826C8.33094 2.79108 8.31458 3.26567 8.01176 3.54829L3.72605 7.54829C3.57393 7.69027 3.36967 7.76267 3.1621 7.74818C2.95453 7.7337 2.7623 7.63363 2.63138 7.4719L1.41709 5.9719C1.15647 5.64996 1.20618 5.17769 1.52813 4.91707C1.85007 4.65645 2.32234 4.70616 2.58296 5.0281L3.29089 5.90261L6.98829 2.45171C7.2911 2.16909 7.76569 2.18545 8.04832 2.48826ZM11.25 5C11.25 4.58579 11.5858 4.25 12 4.25H22C22.4142 4.25 22.75 4.58579 22.75 5C22.75 5.41422 22.4142 5.75 22 5.75H12C11.5858 5.75 11.25 5.41422 11.25 5ZM8.04832 9.48826C8.33094 9.79108 8.31458 10.2657 8.01176 10.5483L3.72605 14.5483C3.57393 14.6903 3.36967 14.7627 3.1621 14.7482C2.95453 14.7337 2.7623 14.6336 2.63138 14.4719L1.41709 12.9719C1.15647 12.65 1.20618 12.1777 1.52813 11.9171C1.85007 11.6564 2.32234 11.7062 2.58296 12.0281L3.29089 12.9026L6.98829 9.45171C7.2911 9.16909 7.76569 9.18545 8.04832 9.48826ZM11.25 12C11.25 11.5858 11.5858 11.25 12 11.25H22C22.4142 11.25 22.75 11.5858 22.75 12C22.75 12.4142 22.4142 12.75 22 12.75H12C11.5858 12.75 11.25 12.4142 11.25 12ZM8.04832 16.4883C8.33094 16.7911 8.31458 17.2657 8.01176 17.5483L3.72605 21.5483C3.57393 21.6903 3.36967 21.7627 3.1621 21.7482C2.95453 21.7337 2.7623 21.6336 2.63138 21.4719L1.41709 19.9719C1.15647 19.65 1.20618 19.1777 1.52813 18.9171C1.85007 18.6564 2.32234 18.7062 2.58296 19.0281L3.29089 19.9026L6.98829 16.4517C7.2911 16.1691 7.76569 16.1855 8.04832 16.4883ZM11.25 19C11.25 18.5858 11.5858 18.25 12 18.25H22C22.4142 18.25 22.75 18.5858 22.75 19C22.75 19.4142 22.4142 19.75 22 19.75H12C11.5858 19.75 11.25 19.4142 11.25 19Z"
                                                fill="#1C274C" />
                                        </svg>
                                    </a>

                                    <button v-if="hasPermission('UserManager')" type="button"
                                        @click="openModalDeleteCars(car.id)" class="text-blue-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                    <button v-if="car.car_changelogs.length > 0" type="button"
                                        @click="toogleChangelog(car)" class="text-blue-900 whitespace-no-wrap">
                                        <svg v-if="carId !== car.id" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
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
                            </TableRow>
                        </tr>
                        <template v-if="carId == car.id">
                            <tr class="border-b text-left text-xs font-semibold tracking-wide text-gray-500">
                                <td colspan="11" class="py-1 px-2">
                                    <table class="w-full">
                                        <thead>
                                            <tr
                                                class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600">
                                                    Fecha
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600">
                                                    Kilometraje
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600">
                                                    Tipo
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600">
                                                    Observación
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600">
                                                    Factura
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600">
                                                    Items
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600">
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="changelog in car.car_changelogs" :key="changelog.id"
                                                class="bg-gray-100">
                                                <th
                                                    class="border-b-2 border-gray-200 bg-white px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                                    {{
                                                        formattedDate(
                                                            changelog.date
                                                        )
                                                    }}
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-white px-5 py-3 text-left text-xs font-semibold tracking-wider text-gray-600 text-center">
                                                    {{ changelog.mileage }}
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-white px-5 py-3 text-left text-xs font-semibold tracking-wider text-gray-600 text-center">
                                                    {{ changelog.type }}
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-white px-5 py-3 text-left text-xs font-semibold tracking-wider text-gray-600 text-center">
                                                    {{ changelog.observation }}
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-white px-5 py-3 text-xs font-semibold tracking-wider text-gray-600">
                                                    <div class="flex justify-center items-center">
                                                        <a target="_blank" :href="route(
                                                            'fleet.cars.show_invoice',
                                                            {
                                                                car_changelog:
                                                                    changelog.id,
                                                            }
                                                        ) + uniqueParam
                                                            ">
                                                            <DocumentIcon class="w-5 h-5 text-blue-600">
                                                            </DocumentIcon>
                                                        </a>
                                                    </div>
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-white px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                    <div class="flex justify-center items-center">
                                                        <button type="button" @click="
                                                            openItemsModal(
                                                                changelog.car_changelog_items
                                                            )
                                                            ">
                                                            <EyeIcon class="w-5 h-5 text-green-600"></EyeIcon>
                                                        </button>
                                                    </div>
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-white px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                    <div class="flex justify-center items-center gap-2">
                                                        <button type="button" @click="
                                                            openEditChangelog(
                                                                changelog
                                                            )
                                                            ">
                                                            <PencilSquareIcon class="w-5 h-5 text-yellow-400">
                                                            </PencilSquareIcon>
                                                        </button>
                                                        <button type="button" @click="
                                                            openModalDeleteChangelog(
                                                                changelog.id
                                                            )
                                                            ">
                                                            <TrashIcon class="w-5 h-5 text-red-600"></TrashIcon>
                                                        </button>
                                                    </div>
                                                </th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </template>
                    </template>
                </template>
            </TableStructure>
            <div v-if="cars.data"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="cars.links" />
            </div>
        </div>
        <Modal :show="showModalCar">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{ form.id ? "Editar UM" : "Nueva UM" }}
                </h2>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div v-if="!form.id && hasPermission('UserManager')" class="mt-2">
                            <InputLabel for="user_id">Proveedores de UM
                            </InputLabel>
                            <div class="mt-2">
                                <select id="user_id" v-model="form.user_id" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">Seleccionar Usuario</option>
                                    <option v-for="item in users" :value="item.id">{{ item.name }} - {{ item.dni }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.user_id" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="cost_line_id">Linea de Negocio
                            </InputLabel>
                            <div class="mt-2">
                                <select id="cost_line_id" v-model="form.cost_line_id" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">
                                        Seleccionar Linea de Costo
                                    </option>
                                    <option v-for="item in costLine" :value="item.id">
                                        {{ item.name }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.cost_line_id" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="plate">Placa </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="plate" v-model="form.plate" />
                                <InputError :message="form.errors.plate" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="model">Modelo </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="model" v-model="form.model" />
                                <InputError :message="form.errors.model" />
                            </div>
                        </div>

                        <div class="mt-6">
                            <InputLabel for="brand">Marca </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="brand" v-model="form.brand" />
                                <InputError :message="form.errors.brand" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="year">Año </InputLabel>
                            <div class="mt-2">
                                <TextInput type="number" id="year" min="1900" :max="new Date().getFullYear()"
                                    v-model="form.year" />
                                <InputError :message="form.errors.year" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="type">Tipo </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="type" v-model="form.type" />
                                <InputError :message="form.errors.type" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="photo">Foto </InputLabel>
                            <div class="mt-2">
                                <InputFile id="photo" accept=".jpeg, .jpg, .png" v-model="form.photo" />
                                <InputError :message="form.errors.photo" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-3">
                        <SecondaryButton @click="openModalCar"> Cancel </SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                            {{ form.id ? "Actualizar" : "Crear" }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
        <Modal :show="showModalDocumentCar">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{ formDocument.id ? "Editar " : "Nueva " }}Documentaciòn UM
                </h2>
                <form @submit.prevent="submitDocument">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div class="mt-2">
                            <InputLabel for="ownership_card">Tarjeta de Propiedad
                            </InputLabel>
                            <div class="mt-2">
                                <InputFile id="ownership_card" accept=".pdf" v-model="formDocument.ownership_card" />
                                <InputError :message="formDocument.errors.ownership_card
                                    " />
                            </div>
                            <div v-if="archivesDocument.ownership_card" class="flex items-center">
                                <span>Archivo: </span>
                                <a target="_blank" :href="route('fleet.cars.show_documents', {
                                    car_document: formDocument.id,
                                    fieldName: 'ownership_card',
                                }) + '?' + uniqueParam
                                    ">
                                    <EyeIcon class="w-5 h-5 text-green-600" />
                                </a>
                            </div>
                        </div>

                        <div class="mt-2">
                            <InputLabel for="technical_review">Revision Tecnica
                            </InputLabel>
                            <div class="mt-2">
                                <InputFile id="technical_review" accept=".pdf"
                                    v-model="formDocument.technical_review" />
                                <InputError :message="formDocument.errors.technical_review
                                    " />
                            </div>
                            <div v-if="archivesDocument.technical_review" class="flex items-center">
                                <span>Archivo: </span>
                                <a target="_blank" :href="route('fleet.cars.show_documents', {
                                    car_document: formDocument.id,
                                    fieldName: 'technical_review',
                                }) + '?' + uniqueParam
                                    ">
                                    <EyeIcon class="w-5 h-5 text-green-600" />
                                </a>
                            </div>
                            <template v-if="date_technical_review">
                                <InputLabel for="technical_review">
                                    Fecha de Revision Tecnica
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput id="technical_review_date" type="date"
                                        v-model="formDocument.technical_review_date" />
                                    <InputError :message="formDocument.errors.technical_review_date
                                        " />
                                </div>
                            </template>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="soat">SOAT </InputLabel>
                            <div class="mt-2">
                                <InputFile id="soat" accept=".pdf" v-model="formDocument.soat" />
                                <InputError :message="formDocument.errors.soat" />
                            </div>

                            <div v-if="archivesDocument.soat" class="flex items-center">
                                <span>Archivo: </span>
                                <a target="_blank" :href="route('fleet.cars.show_documents', {
                                    car_document: formDocument.id,
                                    fieldName: 'soat',
                                }) + '?' + uniqueParam
                                    ">
                                    <EyeIcon class="w-5 h-5 text-green-600" />
                                </a>
                            </div>
                            <InputLabel for="soat_date">
                                Fecha de Soat
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput id="soat_date" type="date" v-model="formDocument.soat_date" />
                                <InputError :message="formDocument.errors.soat_date
                                    " />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="insurance">Seguro </InputLabel>
                            <div class="mt-2">
                                <InputFile id="insurance" accept=".pdf" v-model="formDocument.insurance" />
                                <InputError :message="formDocument.errors.insurance" />
                            </div>

                            <div v-if="archivesDocument.insurance" class="flex items-center">
                                <span>Archivo: </span>
                                <a target="_blank" :href="route('fleet.cars.show_documents', {
                                    car_document: formDocument.id,
                                    fieldName: 'insurance',
                                }) + '?' + uniqueParam
                                    ">
                                    <EyeIcon class="w-5 h-5 text-green-600" />
                                </a>
                            </div>
                            <InputLabel for="insurance_date">
                                Fecha de Seguro
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" id="insurance_date" v-model="formDocument.insurance_date" />
                                <InputError :message="formDocument.errors.insurance_date" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="GPS">GPS</InputLabel>
                            <div class="mt-2">
                                <InputLabel for="insurance">Direccion Web </InputLabel>
                                <TextInput type="text" id="address_web" v-model="formDocument.address_web" />
                                <InputError :message="formDocument.errors.address_web" />

                                <InputLabel for="insurance">Usuario </InputLabel>
                                <TextInput type="text" id="user" v-model="formDocument.user" />
                                <InputError :message="formDocument.errors.user" />

                                <InputLabel for="insurance">Password </InputLabel>
                                <TextInput type="password" id="password" v-model="formDocument.password" />
                                <InputError :message="formDocument.errors.password" />
                            </div>
                        </div>

                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-3">
                        <SecondaryButton @click="openModalDocument"> Cancel </SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': formDocument.processing }">
                            {{ formDocument.id ? "Actualizar" : "Crear" }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <Modal :show="showChangelogModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{ formDocument.id ? "Editar " : "Nuevo " }}Registro de
                    Cambios
                </h2>
                <form @submit.prevent="submitChangelog">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div class="mt-2">
                            <InputLabel for="date">Fecha </InputLabel>
                            <div class="mt-2">
                                <input type="date" id="date" v-model="formChangelog.date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="formChangelog.errors.date" />
                            </div>
                        </div>

                        <div class="mt-2">
                            <InputLabel for="mileage">Kilometraje </InputLabel>
                            <div class="mt-2">
                                <input type="number" step="0.01" id="mileage"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="formChangelog.mileage" />
                                <InputError :message="formChangelog.errors.mileage" />
                            </div>
                        </div>

                        <div class="mt-2">
                            <InputLabel for="type">Tipo </InputLabel>
                            <div class="mt-2">
                                <input type="text" id="type"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="formChangelog.type" />
                                <InputError :message="formChangelog.errors.type" />
                            </div>
                        </div>


                        <div class="mt-2">
                            <InputLabel for="invoice">Factura </InputLabel>
                            <div class="mt-2">
                                <InputFile id="invoice" accept=".pdf" v-model="formChangelog.invoice"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="formChangelog.errors.invoice" />
                            </div>

                            <div v-if="formChangelog.invoice && formChangelog.id" class="flex items-center">
                                <span>Archivo: </span>
                                <a target="_blank" :href="route('fleet.cars.show_invoice', {
                                    car_changelog: formChangelog.id,
                                }) + uniqueParam
                                    ">
                                    <EyeIcon class="w-5 h-5 text-green-600" />
                                </a>
                            </div>
                        </div>

                        <div class="mt-2 md:col-span-2 col-span-1">
                            <InputLabel for="observation">Observación </InputLabel>
                            <div class="mt-2">
                                <textarea id="observation"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="formChangelog.observation" />
                                <InputError :message="formChangelog.errors.observation" />
                            </div>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <InputLabel class="mb-1" for="new_item">Agregar Item</InputLabel>
                            <div class="flex items-center">
                                <input type="text" v-model="newItem"
                                    class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                                <button type="button" @click="addItem" class="ml-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            </div>
                            <InputError :message="formChangelog.errors.items" />
                        </div>

                        <div class="col-span-1 sm:col-span-2 overflow-x-auto mt-3">
                            <table class="w-full whitespace-no-wrap">
                                <thead>
                                    <tr
                                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Nº
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Nombre
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(
item, index
                                        ) in formChangelog.items" :key="index" class="text-gray-700">
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            {{ index + 1 }}
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            {{ item }}
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-right">
                                            <button @click.prevent="() => {
                                                formChangelog.items.splice(
                                                    index,
                                                    1
                                                );
                                            }
                                            " class="text-red-600 hover:underline">
                                                <TrashIcon class="h-5 w-5" />
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-3">
                        <SecondaryButton @click="openModalChangelog">
                            Cancelar
                        </SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': formDocument.processing }">
                            Guardar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <Modal :show="itemModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Items del Registro de Cambios
                </h2>
                <div class="overflow-x-auto mt-3 col-span-2">
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
                                    Nombre
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in showitems" :key="index" class="text-gray-700">
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                    {{ index + 1 }}
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                    {{ item.name }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-span-2 mt-6 flex items-center justify-end gap-x-6">
                    <SecondaryButton @click="closeItemModal">Cerrar</SecondaryButton>
                </div>
            </div>
        </Modal>

        <ConfirmDeleteModal :confirmingDeletion="showModalDeleteCars" itemType="vehiculo" :deleteFunction="deleteCars"
            @closeModal="openModalDeleteCars(null)" />

        <ConfirmDeleteModal :confirmingDeletion="showModalDeleteChangelog" itemType="registro de cambios"
            :deleteFunction="deleteChangelog" @closeModal="openModalDeleteChangelog(null)" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ConfirmDeleteModal from "@/Components/ConfirmDeleteModal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Pagination from "@/Components/Pagination.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import InputError from "@/Components/InputError.vue";
import {
    formattedDate,
    realNumeration,
    setAxiosErrors,
    toFormData,
} from "@/utils/utils";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { notify, notifyError } from "@/Components/Notification";
import TableHeaderCicsaFilter from "@/Components/TableHeaderCicsaFilter.vue";
import { Toaster } from "vue-sonner";
import InputFile from "@/Components/InputFile.vue";
import {
    EyeIcon,
    TrashIcon,
    DocumentIcon,
    PencilSquareIcon,
} from "@heroicons/vue/24/outline";
import TableTitle from "@/Components/TableTitle.vue";
import TableRow from "@/Components/TableRow.vue";
import GeneralOpciones from "@/Layouts/FleetCar/GeneralOpciones.vue";
import TableStructure from "@/Layouts/TableStructure.vue";

const props = defineProps({
    car: Object,
    userPermissions: Array,
    costLine: Object,
    users: Object,
});

const cars = ref(props.car);
console.log(cars)
const showModalCar = ref(false);
const showModalDeleteCars = ref(false);
const car_id = ref(null);
const showModalDocumentCar = ref(false);
const showChangelogModal = ref(false);
const newItem = ref("");
const carId = ref(null);
const itemModal = ref(false);
const showitems = ref([]);
const changelogToDelete = ref(null);
const showModalDeleteChangelog = ref(false);
const archivesDocument = ref({})
const date_technical_review = ref(null)

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
};

const uniqueParam = `timestamp=${new Date().getTime()}`;

const initialForm = {
    id: "",
    brand: "",
    model: "",
    plate: "",
    year: "",
    type: "",
    photo: "",
    user_id: "",
    cost_line_id: "",
};

const initialFormDocument = {
    id: "",
    ownership_card: "",
    technical_review: "",
    technical_review_date: "",
    soat: "",
    soat_date: "",
    insurance: "",
    insurance_date: "",
    address_web: "",
    user: "",
    password: "",
    car_id: "",
};

const initialFormChangelog = {
    id: "",
    date: "",
    mileage: "",
    type: "",
    invoice: "",
    items: [],
};

const form = useForm({
    ...initialForm,
});

const formDocument = useForm({
    ...initialFormDocument,
});

const formChangelog = useForm({
    ...initialFormChangelog,
});

const cost_line = props.costLine.map((item) => item.name);

const initialFormSearch = {
    cost_line: [...cost_line],
    search: "",
};

const formSearch = ref({ ...initialFormSearch });

function openModalCar() {
    showModalCar.value = !showModalCar.value;
    form.clearErrors();
}

function openModalCreate() {
    openModalCar();
    form.defaults({ ...initialForm });
    form.reset();
}

function openModalEdit(item) {
    openModalCar();
    form.defaults({ ...item });
    form.reset();
}

function openModalDocument() {
    formDocument.clearErrors();
    formDocument.defaults({ ...initialFormDocument });
    formDocument.reset();
    showModalDocumentCar.value = !showModalDocumentCar.value;
}

function openModalCreateDocument(item) {
    date_technical_review.value = item.year + 4 <= new Date().getFullYear();
    archivesDocument.value = {}
    openModalDocument()
    archivesDocument.value = ({ ...item.car_document ?? initialFormDocument })
    formDocument.defaults({ ...item.car_document ?? initialFormDocument, car_id: item.id })
    formDocument.reset()
}

function openModalChangelog() {
    formChangelog.clearErrors();
    formChangelog.defaults({ ...initialFormChangelog });
    formChangelog.reset();
    car_id.value = null;
    showChangelogModal.value = !showChangelogModal.value;
}

function openCreateModalChangelog(item, id) {
    openModalChangelog();
    formChangelog.defaults({ ...(item ?? initialFormDocument) });
    car_id.value = id;
    formChangelog.reset();
}

function openEditChangelog(item) {
    openModalChangelog();
    formChangelog.defaults({ ...item });
    formChangelog.reset();
    formChangelog.items = item.car_changelog_items?.map((i) => i.name) ?? [];
}

function openModalDeleteCars(id) {
    car_id.value = id;
    showModalDeleteCars.value = !showModalDeleteCars.value;
}

function addItem() {
    if (newItem.value.trim() === "") {
        return;
    }
    formChangelog.items.push(newItem.value);
    newItem.value = "";
}

function openItemsModal(items) {
    showitems.value = items;
    itemModal.value = true;
}

function closeItemModal() {
    showitems.value = [];
    itemModal.value = false;
}

function openModalDeleteChangelog(id) {
    changelogToDelete.value = id ?? null;
    showModalDeleteChangelog.value = !showModalDeleteChangelog.value;
}

async function deleteChangelog() {
    const docId = changelogToDelete.value;
    if (docId) {
        const response = await axios.delete(
            route("fleet.cars.destroy_changelog", { car_changelog: docId })
        );
        if (response.data) {
            updateCar(response.data, "deleteChangelog");
        } else {
            notifyError("Error al eliminar el registro de cambios");
        }
    }
}

async function deleteCars() {
    let url = route("fleet.cars.destroy", { car: car_id.value });
    try {
        await axios.delete(url);
        updateCar(car_id.value, "delete");
    } catch (error) {
        console.log(error);
    }
}

watch(
    () => [formSearch.value.cost_line, formSearch.value.search],
    () => {
        search();
    },
    { deep: true }
);

async function submit() {
    let url = form.id
        ? route("fleet.cars.update", { car: form.id })
        : route("fleet.cars.store");
    let data = toFormData(form);
    try {
        let response = await axios.post(url, data);
        let action = form.id ? 'edit' : 'create';
        updateCar(response.data, action)
    } catch (error) {
        console.log(error);
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, form);
            } else {
                notifyError("Server error:", error.response.data);
            }
        } else {
            notifyError("Network or other error:", error);
        }
    }
}

async function submitDocument() {
    let url = formDocument.id
        ? route("fleet.cars.update.document", { car_document: formDocument.id })
        : route("fleet.cars.store_document");
    let formData = toFormData(formDocument);
    try {
        let response = await axios.post(url, formData);
        console.log("response", response.data)
        if (response.data.length > 0) {
            updateCar(response.data, 'udpateDocument')
        } else {
            updateCar(null, 'changeEntry')
        }
    } catch (error) {
        console.log(error);
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, formDocument);
            } else {
                notifyError("Server error:", error.response.data);
            }
        } else {
            notifyError("Network or other error:", error);
        }
    }
}

async function submitChangelog() {
    let url = formChangelog.id
        ? route("fleet.cars.update_changelog", {
            car_changelog: formChangelog.id,
        })
        : route("fleet.cars.store_changelog", { car: car_id.value });
    let method = "post";
    let formData = new FormData();
    for (const key in formChangelog) {
        if (Object.hasOwnProperty.call(formChangelog, key)) {
            if (Array.isArray(formChangelog[key])) {
                formChangelog[key].forEach((item, index) => {
                    formData.append(`${key}[${index}]`, item);
                });
            } else {
                formData.append(key, formChangelog[key]);
            }
        }
    }
    try {
        let response = await axios({
            url: url,
            method: method,
            data: formData,
        });
        updateCar(response.data, "createChangelog");
    } catch (error) {
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, formChangelog);
            } else {
                notifyError("Server error:", error.response.data);
            }
        } else {
            notifyError("Network or other error:", error);
        }
    }
}

function toogleChangelog(item) {
    if (carId.value === item.id) {
        carId.value = null;
    } else {
        carId.value = item.id;
    }
}

function updateCar(data, action) {
    const validations = cars.value.data || cars.value;
    if (action === "create") {
        validations.unshift(data);
        openModalCar();
        notify("Creaciòn Exitosa");
    } else if (action === "edit") {
        let index = validations.findIndex((item) => item.id === data.id);
        validations[index] = data;
        openModalCar();
        notify("Actualización Exitosa");
    } else if (action === "delete") {
        let index = validations.findIndex((item) => item.id === data);
        validations.splice(index);
        openModalDeleteCars(null);
        notify("Eliminacion Exitosa");
    } else if (action === "udpateDocument") {
        let index = validations.findIndex(
            (item) => item.id === formDocument.car_id
        );
        validations[index].car_document = data;
        openModalDocument();
        notify("Acciòn Exitosa");
    } else if (action === "createChangelog") {
        let index = validations.findIndex((item) => item.id === data.id);
        validations[index] = data;
        openModalChangelog();
        notify("Acción Exitosa");
    } else if (action === "deleteChangelog") {
        let index = validations.findIndex((item) => item.id === data.id);
        validations[index] = data;
        openModalDeleteChangelog(null);
        if (validations[index].car_changelogs.length === 0) {
            carId.value = null;
        }
        notify("Eliminación Exitosa");
    } else if (action === "changeEntry") {
        openModalDocument();
        notify("Solicitud de actualizacion exitosa");
    }
}

async function search() {
    let url = route("fleet.cars.search");
    try {
        let response = await axios.post(url, formSearch.value);
        cars.value = response.data;
    } catch (error) {
        console.log(error);
        if (error.response.data) {
            notifyError("Server error", error.response.data);
        } else {
            notifyError("Network or other error:", error);
        }
    }
}
</script>
