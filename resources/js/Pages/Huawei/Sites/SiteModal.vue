<template>
    <Modal :show="show">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                {{ isCreate ? "Agregar Site" : "Actualizar Site" }}
            </h2>
            <form @submit.prevent="submit">
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 pb-12">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="code">Código</InputLabel>
                                <div class="mt-2">
                                    <TextInput
                                        type="text"
                                        v-model="form.code"
                                        id="code"
                                        autocomplete="off"
                                    />
                                    <InputError :message="form.errors.code" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="name">Nombre</InputLabel>
                                <div class="mt-2">
                                    <TextInput
                                        type="text"
                                        v-model="form.name"
                                        id="name"
                                        autocomplete="off"
                                    />
                                    <InputError :message="form.errors.name" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="prefix">Operador</InputLabel>
                                <div class="mt-2">
                                    <select
                                        id="prefix"
                                        v-model="form.prefix"
                                        class="mt-1 block w-full rounded-md border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    >
                                        <option value="" disabled>
                                            Seleccione un operador
                                        </option>
                                        <option
                                            v-for="(op, index) in operators"
                                            :key="index"
                                            :value="op"
                                        >
                                            {{ op }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.prefix" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="address">Dirección</InputLabel>
                                <div class="mt-2">
                                    <TextInput
                                        type="text"
                                        v-model="form.address"
                                        id="address"
                                        autocomplete="off"
                                    />
                                    <InputError
                                        :message="form.errors.address"
                                    />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="latitude">Latitud</InputLabel>
                                <div class="mt-2">
                                    <TextInput
                                        type="text"
                                        v-model="form.latitude"
                                        id="latitude"
                                        autocomplete="off"
                                    />
                                    <InputError
                                        :message="form.errors.latitude"
                                    />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="longitude"
                                    >Longitud</InputLabel
                                >
                                <div class="mt-2">
                                    <TextInput
                                        type="text"
                                        v-model="form.longitude"
                                        id="longitude"
                                        autocomplete="off"
                                    />
                                    <InputError
                                        :message="form.errors.longitude"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="handleClose"
                                >Cancelar</SecondaryButton
                            >
                            <PrimaryButton
                                type="submit"
                                :class="{ 'opacity-25': form.processing }"
                            >
                                {{ isCreate ? "Guardar" : "Actualizar" }}
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </Modal>

    <Modal :show="showConfirmNameModal" :maxWidth="'sm'">
        <div class="p-6">
            <h2
                class="text-base font-medium leading-7 text-gray-900 text-center"
            >
                ¿Está seguro de crear o actualizar el site?
            </h2>
            <p class="mt-1 text-sm text-gray-600 text-wrap">
                Actualmente, hay un site que tiene un nombre similar al que esta
                intentando registrar:
                <span class="font-black">{{ foundName }}</span
                >.
            </p>
            <div class="space-y-12">
                <div class="border-gray-900/10">
                    <div class="mt-6 flex items-center justify-end gap-x-3">
                        <SecondaryButton @click="noAccept">
                            No
                        </SecondaryButton>
                        <PrimaryButton
                            @click="accept"
                            class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            >Si</PrimaryButton
                        >
                    </div>
                </div>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useForm, router } from "@inertiajs/vue3";
import { watch, ref } from "vue";
import axios from "axios";

const { show, isCreate, editForm, operators, close } = defineProps({
    show: Boolean,
    isCreate: Boolean,
    editForm: Object,
    operators: Array,
    close: Function,
});

const emit = defineEmits(["close", "update"]);

const handleClose = () => {
    form.reset();
    showConfirmNameModal.value = false;
    close();
};

const foundName = ref(null);
const showConfirmNameModal = ref(false);
const updateValue = ref(null);

const form = useForm({
    id: "",
    name: "",
    address: "",
    code: "",
    prefix: "",
    latitude: "",
    longitude: "",
});

watch(
    () => editForm,
    (newVal) => {
        if (newVal && Object.keys(newVal).length > 0) {
            form.id = newVal.id ?? "";
            form.name = newVal.name ?? "";
            form.code = newVal.code ?? "";
            form.prefix = newVal.prefix ?? "";
            form.latitude = newVal.latitude ?? "";
            form.longitude = newVal.longitude ?? "";
            form.address = newVal.address ?? "";
        }
    },
    { immediate: true }
);

const noAccept = () => {
    foundName.value = null;
    form.reset();
    showConfirmNameModal.value = false;
    emit("close");
};
const accept = async () => {
    updateValue.value = null;
    try {
        let res;
        if (isCreate) {
            res = await axios.post(route("huawei.sites.post"), form);
        } else {
            res = await axios.put(
                route("huawei.sites.put", { site: form.id }),
                form
            );
        }
        updateValue.value = res.data;
        handleClose();
        emit('update', updateValue.value);
    } catch (error) {
        updateValue.value = "error";
        emit('update', updateValue.value);
        console.error(error);
    }
};

const submit = async () => {
    updateValue.value = null;
    try {
        let res;

        if (isCreate) {
            res = await axios.post(route("huawei.sites.verify"), form);
            if (res.data.message === "found") {
                foundName.value = res.data.name;
                showConfirmNameModal.value = true;
            } else {
                res = await axios.post(route("huawei.sites.post"), form);
                updateValue.value = res.data;
                handleClose();
                emit('update', updateValue.value);
            }
        } else {
            res = await axios.post(
                route("huawei.sites.verify", { site: form.id }),
                form
            );

            if (res.data.message === "found") {
                foundName.value = res.data.name;
                showConfirmNameModal.value = true;
            } else {
                res = await axios.put(
                    route("huawei.sites.put", { site: form.id }),
                    form
                );
                updateValue.value = res.data;
                handleClose();
                emit('update', updateValue.value);
            }
        }
    } catch (error) {
        updateValue.value = "error";
        emit('update', updateValue.value);
        console.error(error);
    }
};


</script>
