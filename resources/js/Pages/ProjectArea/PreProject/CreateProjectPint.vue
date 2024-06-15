<template>

    <Head title="Anteproyectos" />
    <AuthenticatedLayout :redirectRoute="'preprojects.index'">
        <template #header>
            Anteproyecto y Proyecto Claro CICSA
        </template>
        <div class="min-w-full p-3 rounded-lg shadow">
            <form @submit.prevent="submit">
                <div class="pt-1">
                    <div class="border-b border-gray-900/10 mb-2">
                    </div>

                    <div class="pb-12">

                        <br>
                        <div
                            class="border-b grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-4  border-gray-900/10 pb-12">

                            <div>
                                <InputLabel for="customer" class="font-medium leading-6 text-gray-900">
                                    Plantilla de Proyecto
                                </InputLabel>
                                <div class="mt-2">
                                    <select v-model="form.template"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="">Selecciona una plantilla</option>
                                        <option v-for="item, i in templates" :key="i">
                                            {{ item }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.template" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="customer" class="font-medium leading-6 text-gray-900">
                                    Fecha de Inicio
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="date" v-model="form.date" id="date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.date" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="customer" class="font-medium leading-6 text-gray-900">
                                    CPE
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="number" v-model="form.cpe" id="cpe"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.cpe" />
                                </div>
                            </div>

                            <div class="col-start-1">
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    Contactos de CICSA
                                </InputLabel>
                                <div class="mt-2">
                                    <div v-for="( item, i ) in  form.contacts " :key="i" class="">
                                        <div
                                            class="border-b col-span-8 border-gray-900/10 grid grid-cols-8 items-center my-2">
                                            <p class=" text-sm col-span-7 line-clamp-2">
                                                {{ item.name }}: {{ item.phone }} </p>
                                            <!-- @click="delete_already_employee(member.pivot.id, index)" -->
                                            <button type="button" class="col-span-1 flex justify-end"
                                                @click="deleteContactItem(i)">
                                                <TrashIcon class=" text-red-500 h-4 w-4" />
                                            </button>
                                        </div>
                                    </div>
                                    <InputError :message="form.errors.contacts" />
                                </div>
                            </div>
                            <div class="">
                                <InputLabel for="customer" class="font-medium leading-6 text-gray-900">
                                    Servicios
                                </InputLabel>
                                <div class="mt-2">
                                    <div v-for="( item, i ) in  form.services " :key="i" class="">
                                        <div
                                            class="border-b col-span-8 border-gray-900/10 grid grid-cols-8 items-center my-2">
                                            <p class=" text-sm col-span-7 line-clamp-2">
                                                {{ item.name }} </p>
                                            <button type="button" class="col-span-1 flex justify-end"
                                                @click="deleteServiceItem(i)">
                                                <TrashIcon class=" text-red-500 h-4 w-4" />
                                            </button>
                                        </div>
                                    </div>
                                    <InputError :message="form.errors.services" />
                                </div>
                            </div>
                            <div class="">
                                <InputLabel for="customer" class="font-medium leading-6 text-gray-900">
                                    Servicios
                                </InputLabel>
                                <div class="mt-2">
                                    <div v-for="( item, i ) in  form.services " :key="i" class="">
                                        <div
                                            class="border-b col-span-8 border-gray-900/10 grid grid-cols-8 items-center my-2">
                                            <p class=" text-sm col-span-7 line-clamp-2">
                                                {{ item.name }} </p>
                                            <button type="button" class="col-span-1 flex justify-end"
                                                @click="deleteServiceItem(i)">
                                                <TrashIcon class=" text-red-500 h-4 w-4" />
                                            </button>
                                        </div>
                                    </div>
                                    <InputError :message="form.errors.services" />
                                </div>
                            </div>









                        </div>

                    </div>
                </div>
                <div class="mt-3 flex items-center justify-end gap-x-6">
                    <button type="submit" :class="{ 'opacity-25': form.processing }"
                        class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
                </div>
            </form>
            <!-- <Modal :show="showContactModal">
                <div class="p-6">
                    <h2 class="text-base font-medium leading-7 text-gray-900">
                        Agregando Productos de Almacen
                    </h2>
                    <p class="text-sm text-indigo-500">Primero seleccinar un cliente o cliente final</p>
                    <form @submit.prevent="submitContact">
                        <div class="mt-2">
                            <select v-model="contactItem" required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option disabled value="">Seleccione</option>
                                <option v-for=" item  in contactsList" :value="item.id">
                                    {{ item.name }}
                                </option>
                            </select>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-3">
                            <SecondaryButton @click="closeContactModal">Cerrar</SecondaryButton>
                            <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">Agregar
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </Modal> -->
            <ErrorOperationModal :showError="showErrorContact" title="Error"
                message="El contacto ya fue añadido o es inválido" />

        </div>
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="Anteproyecto" />
        <ConfirmUpdateModal :confirmingupdate="showModalUpdate" itemType="Anteproyecto" />


    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import { ref, watch } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { TrashIcon } from '@heroicons/vue/24/outline';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const showModal = ref(false)
const showModalUpdate = ref(false)
const showErrorContact = ref(false)

const { contacts_cicsa, services, currentProducts } = defineProps({
    contacts_cicsa: Object,
    services: Object,
})

const templates = [
    'Mantenimiento',
    'Instalación'
]

const initial_state = {
    template: '',
    date: '',
    cpe: '',
    contacts: contacts_cicsa,
    services: services
}


const form = useForm({
    ...initial_state
});





const submit = () => {
    let url = route('project.auto_store.pint')
    form.post(url, {
        onSuccess: () => {
            showModal.value = true
            setTimeout(() => {
                showModal.value = false
                router.visit(route('preprojects.index', { preprojects_status: '1' }))
            }, 2000);
        },
        onError: (e) => {
            console.log(e);
        }
    })
}



const deleteContactItem = (i) => {
    form.contacts.splice(i, 1)
}

const deleteServiceItem = (i) => {
    form.services.splice(i, 1)
}


</script>