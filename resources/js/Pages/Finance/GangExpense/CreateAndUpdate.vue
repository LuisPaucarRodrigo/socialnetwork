<template>
    <Head title="Gastos Cuadrilla" />
    <AuthenticatedLayout :redirectRoute="'gangexpense.index'">
        <template #header>
            Gasto de Cuadrilla
        </template>
        <form @submit.prevent="submit">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Gastos Cuadrilla</h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <InputLabel for="type_cdp" class="font-medium leading-6 text-gray-900">Tipo de CDP</InputLabel>
                            <div class="mt-2">
                                <select id="type_cdp" v-model="form.type_cdp"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Tipo de CDP</option>
                                    <option>Factura</option>
                                    <option>Ticket</option>
                                    <option>Efectivo</option>
                                    <option>Yape</option>
                                </select>
                                <InputError :message="form.errors.type_cdp" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="gang" class="font-medium leading-6 text-gray-900">Cuadrilla</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.gang" id="gang" autocomplete="gang"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.gang" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="person" class="font-medium leading-6 text-gray-900">Personal</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.person" id="person" autocomplete="person" :disabled="expenses"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.person" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="date" class="font-medium leading-6 text-gray-900">Fecha
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="Date" v-model="form.date" id="date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.date" />
                            </div>
                        </div>

                        <div class="sm:col-span-2 sm:col-start-1">
                            <InputLabel for="number" class="font-medium leading-6 text-gray-900">Numero</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.number" id="number" autocomplete="number"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.number" />
                            </div>
                        </div>

                        <div class="sm:col-span-4">
                            <InputLabel for="series" class="font-medium leading-6 text-gray-900">Serie
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="series" v-model="form.series" autocomplete="series"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.series" />
                            </div>
                        </div>

                        <div class="sm:col-span-4">
                            <InputLabel for="ruc" class="font-medium leading-6 text-gray-900">
                                RUC
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="ruc" v-model="form.ruc" autocomplete="ruc" maxlength="11"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.ruc" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="price" class="font-medium leading-6 text-gray-900">Precio</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.price" id="price" autocomplete="price"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.price" />
                            </div>
                        </div>

                        <div class="col-span-full">
                            <InputLabel for="description_expenses" class="font-medium leading-6 text-gray-900">Descripcion</InputLabel>
                            <div class="mt-2">
                                <textarea v-model="form.description_expenses" id="description_expenses"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                <InputError :message="form.errors.description_expenses" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="type_expenses" class="font-medium leading-6 text-gray-900">Nivel Educativo
                            </InputLabel>
                            <div class="mt-2">
                                <select v-model="form.type_expenses" id="type_expenses" 
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Tipo de Gasto</option>
                                    <option>Combustible</option>
                                    <option>Peaje</option>
                                    <option>Otros</option>
                                </select>
                                <InputError :message="form.errors.type_expenses" />
                            </div>
                        </div>

                        <div v-if="form.type_cdp == 'Factura'" class="sm:col-span-2">
                            <InputLabel for="percentaje" class="font-medium leading-6 text-gray-900">Porcentaje</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.percentaje" id="percentaje"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Tipo de Gasto</option>
                                    <option>10</option>
                                    <option>12</option> 
                                    <option>18</option>
                                </select>
                                <InputError :message="form.errors.percentaje" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="submit" :class="{ 'opacity-25': form.processing }"
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    {{ expenses ? "Actualizar" : "Crear" }}
                </button>
            </div>
        </form>
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="gasto de cuadrilla" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const showModal = ref(false);

const props = defineProps({
    expenses: {
        type: Object,
        requerid: false
    },
})

const form = useForm({
    type_cdp: '',
    gang: '',
    person: '',
    date: '',
    number: '',
    series: '',
    ruc: '',
    price: '',
    description_expenses: '',
    type_expenses: '',
    percentaje: '',
})

if (props.expenses) {
    form.type_cdp = props.expenses.type_cdp;
    form.gang = props.expenses.gang;
    form.person = props.expenses.person;
    form.date = props.expenses.date;
    form.number = props.expenses.number;
    form.series = props.expenses.series;
    form.ruc = props.expenses.ruc;
    form.price = props.expenses.price;
    form.description_expenses = props.expenses.description_expenses;
    form.type_expenses = props.expenses.type_expenses;
    form.percentaje = props.expenses.percentaje;

}

const submit = () => {
    if (props.expenses) {
        form.put(route('gangexpense.update', props.expenses.id), form)
    } else {
        form.post(route('gangexpense.store'), {
            onSuccess: () => {
                showModal.value = true
                setTimeout(() => {
                    showModal.value = false;
                    router.visit(route('gangexpense.index'))
                }, 2000);
            },
            onError: () => {
                alert('Ha ocurrido un error. Por favor, inténtelo de nuevo.');
            }
        })
    }
}

</script>
