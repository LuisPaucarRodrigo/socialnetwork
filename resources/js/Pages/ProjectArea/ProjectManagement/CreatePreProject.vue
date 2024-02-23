<template>
    <Head title="Anteproyectos" />
    <AuthenticatedLayout>
        <template v-if="preproject" #header>
            Edición de Anteproyecto
        </template>
        <template v-else #header>
            Creación de Anteproyecto
        </template>
        <div class="min-w-full p-3 rounded-lg shadow">
            <form @submit.prevent="submit">
                <div class="pt-1">
                    <div class="border-b border-gray-900/10 mb-2">
                    </div>

                    <div class="border-b border-gray-900 pb-12">
                        <h2 v-if="preproject" class="text-base font-semibold leading-7 text-gray-900">{{ preproject.name }}-{{
                            preproject.code }}</h2>
                        <h2 v-else class="text-base font-semibold leading-7 text-gray-900">Registrar nuevo Anteproyecto</h2>
                        <br>
                        <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <InputLabel for="name" class="font-medium leading-6 text-gray-900">Nombre del proyecto
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput required type="text" v-model="form.name" id="name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.name" />
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel class="font-medium leading-6 text-gray-900">Código del proyecto (Siglas):
                                </InputLabel>
                                <div class="mt-2 flex justify-center items-center gap-2">
                                    <InputLabel class="font-medium leading-6 text-indigo-900">
                                        {{ formatearFecha(current_date) }}
                                    </InputLabel>
                                    <TextInput required type="text" v-model="form.code" id="name"
                                        placeholder="Ejemplos: MPr, ITD, etc."
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                </div>
                                <InputError :message="form.errors.code" />
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel for="priority" class="font-medium leading-6 text-gray-900">Prioridad
                                </InputLabel>
                                <div class="mt-2">
                                    <select required id="priority" v-model="form.priority"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">Seleccione uno</option>
                                        <option value="Alta">Alta</option>
                                        <option value="Media">Media</option>
                                        <option value="Baja">Baja</option>
                                        <option value="otros">otros</option>
                                    </select>
                                    <InputError :message="form.errors.priority" />
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <InputLabel for="description" class="font-medium leading-6 text-gray-900">Descripción
                                </InputLabel>
                                <div class="mt-2">
                                    <textarea required v-model="form.description" id="description"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                    <InputError :message="form.errors.description" />
                                </div>
                            </div>


                            <div class="sm:col-span-3">
                                <div class="flex gap-2">
                                    <InputLabel for="trainings" class="font-medium leading-6 text-gray-900">Miembros del
                                        equipo al proyecto
                                    </InputLabel>
                                    <button @click="showToAddEmployee" type="button">
                                        <UserPlusIcon class="text-indigo-800 h-6 w-6 hover:text-purple-400" />
                                    </button>
                                </div>

                                <div class="mt-2" v-if="preproject">
                                    <div v-for="(member, index) in form.employees" :key="index"
                                        class="grid grid-cols-8 items-center my-2">
                                        <p class=" text-sm col-span-7 line-clamp-2">{{ member.name }} {{
                                            member.lastname }}: {{ member.pivot.charge }} </p>
                                        <button type="button" @click="delete_already_employee(member.pivot.id, index)"
                                            class="col-span-1 flex justify-end">
                                            <TrashIcon class=" text-red-500 h-4 w-4 " />
                                        </button>
                                        <div class="border-b col-span-8 border-gray-900/10">
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2" v-else>
                                    <div v-for="(member, index) in form.employees" :key="index"
                                        class="grid grid-cols-8 items-center my-2">
                                        <p class=" text-sm col-span-7 line-clamp-2">{{ member.employee.name }} {{
                                            member.employee.lastname }}: {{ member.charge }} </p>
                                        <button type="button" @click="delete_employee(index)"
                                            class="col-span-1 flex justify-end">
                                            <TrashIcon class=" text-red-500 h-4 w-4 " />
                                        </button>
                                        <div class="border-b col-span-8 border-gray-900/10">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 flex items-center justify-between gap-x-6">
                    <a :href="route('preproject.index')"
                        class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Atras
                    </a>
                    <button type="submit" :class="{ 'opacity-25': form.processing }"
                        class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
                </div>
            </form>
        </div>
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="Anteproyecto" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { UserPlusIcon, TrashIcon } from '@heroicons/vue/24/outline';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const showModal = ref(false)

const { preproject } = defineProps({
    preproject: Object
})

const initialState = {
    name: '',
    code: '',
    start_date: '',
    end_date: '',
    priority: '',
    description: '',
    employees: []
}

const form = useForm(
    preproject ?
        { ...preproject, code: preproject.code.substring(7) }
        :
        { ...initialState }
)

const submit = () => {
    form.code = formatearFecha(form.start_date) + '-' + form.code
    form.post(route('preproject.store'), {
        onSuccess: () => {
            closeModal();
            showModal.value = true
            setTimeout(() => {
                showModal.value = false;
                router.visit(route('preproject.index'))
            }, 2000);
        },
        onError: () => {
            close();
        }
    })
}

</script>