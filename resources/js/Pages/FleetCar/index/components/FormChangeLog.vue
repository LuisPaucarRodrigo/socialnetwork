<template>
    <Modal :show="showChangelogModal">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                {{ formChangelog.id ? "Editar " : "Nuevo " }}
                Registro de
                Cambios
            </h2>
            <h2 class="text-base font-medium leading-7 text-gray-900">
                Dueño: {{ show_owner }}
            </h2>
            <h2 class="text-base font-medium leading-7 text-gray-900 mb-2">
                Placa: {{ show_plate }}
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

                    <div class="mt-2">
                        <InputLabel for="workshop">Taller </InputLabel>
                        <div class="mt-2">
                            <input type="text" id="workshop"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                v-model="formChangelog.workshop" />
                            <InputError :message="formChangelog.errors.workshop" />
                        </div>
                    </div>

                    <div class="mt-2">
                        <InputLabel for="contact_name">Nombre de Contacto </InputLabel>
                        <div class="mt-2">
                            <input type="text" id="contact_name"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                v-model="formChangelog.contact_name" />
                            <InputError :message="formChangelog.errors.contact_name" />
                        </div>
                    </div>

                    <div class="mt-2">
                        <InputLabel for="contact_phone">Teléfono de Contacto </InputLabel>
                        <div class="mt-2">
                            <input type="text" maxlength="9" minlength="9" id="contact_phone"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                v-model="formChangelog.contact_phone" />
                            <InputError :message="formChangelog.errors.contact_phone" />
                        </div>
                    </div>

                    <div class="mt-2">
                        <InputLabel for="observation">Observación
                        </InputLabel>
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
                                <tr v-for="(item, index) in formChangelog.items" :key="index">
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
                                        ">
                                            <TrashIcon class="h-5 w-5 text-red-600" />
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
                    <PrimaryButton type="submit" :class="{ 'opacity-25': formChangelog.processing }">
                        Guardar
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
<script setup>
import InputError from '@/Components/InputError.vue';
import InputFile from '@/Components/InputFile.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { notify, notifyError } from '@/Components/Notification';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { setAxiosErrors } from '@/utils/utils';
import { EyeIcon, TrashIcon } from '@heroicons/vue/24/outline';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineExpose({ openCreateModalChangelog, openEditChangelog })

const { cars } = defineProps({
    cars: Object
})

const car_id = ref(null);
const show_plate = ref(null);
const show_owner = ref(null);
const showChangelogModal = ref(null)
const newItem = ref("");
const uniqueParam = `timestamp=${new Date().getTime()}`;

const initialFormChangelog = {
    id: "",
    date: "",
    mileage: "",
    type: "",
    workshop: "",
    contact_name: "",
    contact_phone: "",
    invoice: "",
    observation: "",
    items: [],
};

const formChangelog = useForm({
    ...initialFormChangelog,
});

function openCreateModalChangelog(item, car) {
    openModalChangelog();
    formChangelog.defaults({ ...(item ?? initialFormChangelog) });
    car_id.value = car.id;
    show_plate.value = car.plate;
    show_owner.value = car.user.name;
    formChangelog.reset();
}

function openEditChangelog(item, car) {
    openModalChangelog();
    formChangelog.defaults({ ...item });
    formChangelog.reset();
    formChangelog.items = item.car_changelog_items?.map((i) => i.name) ?? [];
    show_plate.value = car.plate;
    show_owner.value = car.user.name;
}

function openModalChangelog() {
    formChangelog.clearErrors();
    formChangelog.defaults({ ...initialFormChangelog });
    formChangelog.reset();
    car_id.value = null;
    show_plate.value = null;
    show_owner.value = null;
    showChangelogModal.value = !showChangelogModal.value;
}

function addItem() {
    if (newItem.value.trim() === "") {
        return;
    }
    formChangelog.items.push(newItem.value);
    newItem.value = "";
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
        console.log(error)
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

function updateCar(data, action) {
    const validations = cars.data || cars;
    if (action === "createChangelog") {
        let index = validations.findIndex((item) => item.id === data.id);
        validations[index] = data;
        openModalChangelog();
        notify("Acción Exitosa");
    }
}

</script>