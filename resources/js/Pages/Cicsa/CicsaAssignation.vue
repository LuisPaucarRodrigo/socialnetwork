<template>

    <Head title="CICSA Asignación" />

    <AuthenticatedLayout :redirectRoute="{ route: 'cicsa.index', params: { type } }">
        <template #header>
            {{ type == 1 ? 'Pint' : 'Pext' }} - Asignación
        </template>

        <div class="min-w-full">
            <div class="flex justify-between">
                <div class="flex items-center mt-4 space-x-3 sm:mt-0">
                    <a :href="route('assignation.export', { type }) + '?' + uniqueParam"
                        class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500">Exportar</a>
                </div>

                <div class="flex items-center mt-4 space-x-3 sm:mt-0">
                    <TextInput data-tooltip-target="search_fields" type="text" @input="search($event.target.value)"
                        placeholder="Buscar ..." />
                    <SelectCicsaComponent currentSelect="Asignación" :type="type" />

                </div>
                <div id="search_fields" role="tooltip"
                    class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Nombre,Codigo,CPE
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            </div>
            <br>
            <TableStructure>
                <template #thead>
                    <TableTitle>Nombre del Proyecto</TableTitle>
                    <TableTitle>Fecha de Asignación</TableTitle>
                    <TableTitle>Cliente</TableTitle>
                    <TableTitle>Centro de Costo</TableTitle>
                    <TableTitle>Codigo de Proyecto</TableTitle>
                    <TableTitle>CPE</TableTitle>
                    <TableTitle>Zonas</TableTitle>
                    <TableTitle>Gestor</TableTitle>
                    <TableTitle>Encargado</TableTitle>
                </template>
                <template #tbody>
                    <tr v-for="item in assignations.data ?? assignations" :key="item.id" class="text-gray-700">
                        <TableRow>{{ item.project_name }}</TableRow>
                        <TableRow>{{ formattedDate(item.assignation_date) }}</TableRow>
                        <TableRow>{{ item.customer }}</TableRow>
                        <TableRow>{{ item.project?.cost_center?.name }}</TableRow>
                        <TableRow>{{ item.project_code }}</TableRow>
                        <TableRow>{{ item.cpe }}</TableRow>
                        <TableRow>{{ item.zone }} {{ item.zone2 }}</TableRow>
                        <TableRow>{{ item.manager }}</TableRow>
                        <TableRow>{{ item.user_name }}</TableRow>
                    </tr>
                </template>
            </TableStructure>

            <div v-if="assignations.data"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="assignation.links" />
            </div>
        </div>
        <Modal :show="showModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{ form.id ? 'Actualizar Asignacion' : 'Crear ' }}
                </h2>
                <form @submit.prevent="submit">
                    <div class="space-y-12 mt-4">
                        <div class="grid sm:grid-cols-2 gap-6">
                            <div v-if="!form.id">
                                <InputLabel for="pre_project_id">Ante Proyectos</InputLabel>
                                <div class="mt-2">
                                    <select id="pre_project_id" v-model="form.pre_project_id" required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="">Seleccionar Ante Proyecto</option>
                                        <option v-for="item in projectsOrPreproject" :key="item.id" :value="item.id">
                                            {{ item.code }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.pre_project_id" />
                                </div>
                            </div>
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
                                    <input type="text" v-model="form_name" autocomplete="off" id="project_name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors_name" />
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
                                <InputLabel for="project_code">Codigo de Proyecto</InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form_code" autocomplete="off" id="project_code"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors_code" />
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
                        <div class="mt-6 flex items-center justify-end gap-x-3">
                            <SecondaryButton @click="createOrEditModal()">
                                Cancelar
                            </SecondaryButton>
                            <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing">
                                Guardar
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import SelectCicsaComponent from '@/Components/SelectCicsaComponent.vue';
import { formattedDate } from '@/utils/utils.js';
import TextInput from '@/Components/TextInput.vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const { assignation, auth, searchCondition, type } = defineProps({
    assignation: Object,
    auth: Object,
    searchCondition: {
        type: String,
        Required: false
    },
    type: Number
})

const showModal = ref(false)
const assignations = ref(assignation);
const uniqueParam = ref(`timestamp=${new Date().getTime()}`);

const search = async ($search) => {
    try {
        const response = await axios.post(route('assignation.index', { type }), { searchQuery: $search });
        assignations.value = response.data.assignation;

    } catch (error) {
        console.error('Error searching:', error);
    }
};

function openModal(){
    showModal.value = !showModal.value
}

if (searchCondition) {
    search(searchCondition)
}

</script>
