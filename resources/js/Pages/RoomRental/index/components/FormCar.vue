<template>
    <Modal :show="showModalCar">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                {{ form.id ? "Editar UM" : "Nueva UM" }}
            </h2>
            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                    <div v-permission="'room_actions_manager'" v-if="!form.id" class="mt-2">
                        <InputLabel for="user_id">Proveedores de UM
                        </InputLabel>
                        <div class="mt-2">
                            <select id="user_id" v-model="form.user_id" autocomplete="off"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option value="">
                                    Seleccionar Usuario
                                </option>
                                <option v-for="item in users" :value="item.id">
                                    {{ item.name }} - {{ item.dni }}
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
                    <div class="mt-2">
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
                            <TextInput type="number" id="year" :min="1900" :max="new Date().getFullYear()"
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
                    <SecondaryButton @click="openModalCar">
                        Cancel
                    </SecondaryButton>
                    <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                        {{ form.id ? "Actualizar" : "Crear" }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
<script setup>
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputFile from "@/Components/InputFile.vue";
import InputError from "@/Components/InputError.vue";
import { toFormData } from "@/utils/utils";
import { notify } from "@/Components/Notification";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { useAxiosErrorHandler } from "@/utils/axiosError";

defineExpose({ openModalCreate, openModalEdit })

const { cars, users, costLine } = defineProps({
    cars: Object,
    users: Object,
    costLine: Object,
})

const showModalCar = ref(false);

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

const form = useForm({
    ...initialForm,
});

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

async function submit() {
    let url = form.id
        ? route("room.rental.update", { car: form.id })
        : route("room.rental.store");
    let data = toFormData(form);
    try {
        let response = await axios.post(url, data);
        let action = form.id ? "edit" : "create";
        updateCar(response.data, action);
    } catch (error) {
        console.log(error);
        useAxiosErrorHandler(error, form)
    }
}

function updateCar(data, action) {
    const validations = cars.data || cars;
    if (action === "create") {
        validations.unshift(data);
        openModalCar();
        notify("Creaciòn Exitosa");
    } else if (action === "edit") {
        let index = validations.findIndex((item) => item.id === data.id);
        validations[index] = data;
        openModalCar();
        notify("Actualización Exitosa");
    }
}

</script>