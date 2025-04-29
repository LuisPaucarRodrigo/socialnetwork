<template>
    <Modal :show="create_rol" maxWidth="6xl">
        <div class="p-6 space-y-3">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                {{ stateCreateUpdate == true ? "Crear" : "Actualizar" }} Rol
            </h2>
            <form @submit.prevent="submit">
                <div class="space-y-3">
                    <!-- <div class="shadow border-gray-300/10">
                            <InputLabel for="name">Aviso</InputLabel>
                            <div class="mt-4 p-4 border border-red-300 rounded-md bg-red-50">
                                <p class="text-red-600">
                                    Aviso Importante:<br>
                                    Si no se siguen las indicaciones mencionadas, podrían surgir fallos en el
                                    aplicativo.
                                </p>
                            </div>
                            <div class="mt-2 p-4 border border-gray-300 rounded-md bg-gray-50">
                                <p>
                                    Los roles administradores tienen únicamente permisos de gerente.<br>
                                    Un rol gerente posee solo permisos de gerente.<br>
                                    Un rol que solo puede visualizar tendrá permisos normales, pero no de gerente.
                                </p>
                            </div>
                        </div> -->
                    <div>
                        <InputLabel for="name">Nombre</InputLabel>
                        <div class="mt-2">
                            <TextInput
                                type="text"
                                v-model="form.name"
                                id="name"
                            />
                            <InputError :message="form.errors.name" />
                        </div>
                    </div>
                    <div>
                        <InputLabel for="description">Descripcion</InputLabel>
                        <div class="mt-2">
                            <TextInput
                                type="text"
                                v-model="form.description"
                                id="description"
                            />
                            <InputError :message="form.errors.description" />
                        </div>
                    </div>
                    <div>
                        <InputLabel for="permission">Permisos</InputLabel>
                        <select
                            required
                            multiple
                            v-model="form.permission"
                            id="permission"
                            size="15"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        >
                            <option disabled>Selecciona uno o varios</option>
                            <option
                                v-for="permission in permissions"
                                :key="permission.id"
                                :value="permission.id"
                                :selected="
                                    form.permission.includes(permission.id)
                                "
                            >
                                {{ permission.name }}|
                                {{ permission.description }}
                            </option>
                        </select>
                        <InputError :message="form.errors.permission" />
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-3">
                        <SecondaryButton @click="closeModal">
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton
                            type="submit"
                            :class="{ 'opacity-25': form.processing }"
                        >
                            {{
                                stateCreateUpdate === true
                                    ? "Crear"
                                    : "Actualizar"
                            }}
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </div>
    </Modal>
    <ConfirmCreateModal :confirmingcreation="showModal" itemType="rol" />
</template>
<script setup>
import ConfirmCreateModal from "@/Components/ConfirmCreateModal.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    permissions: Object,
});

const showModal = ref(false);
const stateCreateUpdate = ref(true);
const create_rol = ref(false);
const rol_id_update = ref(null);

const form = useForm({
    name: "",
    description: "",
    permission: [],
});

const add_rol = () => {
    create_rol.value = true;
};

const closeModal = () => {
    create_rol.value = false;
    stateCreateUpdate.value = true;
};

const submit = () => {
    let url = stateCreateUpdate.value
        ? route("rols.store")
        : route("rols.update", { id: rol_id_update.value });
    form.post(url, {
        onSuccess: () => {
            closeModal();
            showModal.value = true;
            setTimeout(() => {
                showModal.value = false;
                router.visit(route("rols.index"));
            }, 2000);
        },
        onError: () => {
            close();
        },
    });
};

function editModalRol(rol) {
    rol_id_update.value = rol.id;
    stateCreateUpdate.value = false;
    form.name = rol.name;
    form.description = rol.description;
    form.permission = rol.permissions.map((permission) => permission.id);
    create_rol.value = true;
}

defineExpose({ add_rol, editModalRol });
</script>
