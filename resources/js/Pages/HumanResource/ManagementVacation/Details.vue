<template>
    <Head title="Vacaciones y Permisos" />
    <AuthenticatedLayout>
        <template #header>
            Vacaciones y Permisos
        </template>
        <div class="mt-6 border-t border-gray-100">
            <div class="sm:flex lg:justify-between lg:gap-8">
                <div class="sm:w-1/2 lg:pr-4 sm:mb-0">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Empleado</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.employee.name }}
                        </dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Tipo</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.type }}</dd>
                    </div>
                </div>
                <div v-if="type == 'Vacaciones'" class="sm:w-1/2 sm:pl-4">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Fecha Inicio</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.start_date }}</dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Fecha Culminacion</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.end_date }}
                        </dd>
                    </div>
                </div>
                <div v-else class="sm:w-1/2 lg:pr-4 sm:mb-0">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Hora Inicio</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.start_permissions
                        }}
                        </dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Hora Final</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.end_permissions }}
                        </dd>
                    </div>
                </div>
            </div>

            <div class="sm:flex lg:justify-between lg:gap-8">

                <!-- <div v-else class="sm:w-1/2 lg:pr-4 sm:mb-0">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Fecha Inicio</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.reason }}</dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Fecha Culminacion</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.status ?
                            'Revisado' :
                            'Pendiente' }}
                        </dd>
                    </div>
                </div> -->
            </div>

            <div class="sm:flex lg:justify-between lg:gap-8">
                <div class="sm:w-1/2 lg:pr-4 sm:mb-0">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Razon</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.reason }}
                        </dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Estado</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.status }}
                        </dd>
                    </div>
                </div>
            </div>

            <div v-if="type == 'Vacaciones' && details.start_date_accepted !== null"
                class="sm:flex lg:justify-between lg:gap-8">
                <div class="sm:w-1/2 lg:pr-4 sm:mb-0">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Fecha Inicial Modificada</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{
                            details.start_date_accepted
                        }}
                        </dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Fecha Final Modificada</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.end_date_accepted
                        }}
                        </dd>
                    </div>
                </div>
            </div>
        </div>
        <form @submit.prevent="submit">
            <div v-if="vacation && type == 'Vacaciones'" class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="mb-5 text-base font-semibold leading-7 text-gray-900 mt-5">Aceptar o rechazar solicitud
                    </h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <InputLabel for="start_date_accepted" class="font-medium leading-6 text-gray-900">Fecha de
                                Inicio
                                Aceptada</InputLabel>
                            <div class="mt-2">
                                <TextInput type="Date" v-model="form.start_date_accepted" id="start_date_accepted"
                                    autocomplete="address-level1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.start_date_accepted" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="end_date_accepted" class="font-medium leading-6 text-gray-900">Fecha de
                                Culminación
                                Aceptada</InputLabel>
                            <div class="mt-2">
                                <TextInput type="Date" v-model="form.end_date_accepted" id="end_date_accepted"
                                    autocomplete="address-level1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.end_date_accepted" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="submit" :class="{ 'opacity-25': form.processing }"
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Aceptar</button>
                <button @click="decline" type="button" :class="{ 'opacity-25': form.processing }"
                    class="rounded-md bg-red-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Rechazar</button>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    details: Object,
    vacation: {
        type: Object,
        required: true
    }
});

const type = props.details ? props.details.type : props.vacation.type;

const form = useForm({
    start_date_accepted: '',
    end_date_accepted: ''
})

const submit = () => {
    if (props.vacation) {
        form.put(route('management.vacation.information.reviewed', props.vacation.id), form);
    }
}

const decline = () => {
    if (props.vacation.id) {
        router.get(route('management.vacation.information.decline', { id: props.vacation.id }))
    }
};
</script>