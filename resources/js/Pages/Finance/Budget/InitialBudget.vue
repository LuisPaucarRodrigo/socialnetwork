<template>
    <Head title="Información del Proyecto" />

    <AuthenticatedLayout>
        <template #header>
            Información del Proyecto
        </template>
        <div class="mt-4">
            <p v-if="props.project.initial_budget === 0" class="text-xl font-semibold text-gray-700">No hay presupuesto
                definido</p>
            <p v-else class="text-xl font-semibold text-gray-700">
                Presupuesto Inicial: S/. {{ props.project.initial_budget }}
            </p>
            <br>
            <p v-if="props.budgetUpdate" class="text-xl font-semibold text-gray-700">Presupuesto Actual: S/. {{
                props.budgetUpdate.new_budget }}</p>
            <p v-else></p>
        </div>

        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
            <!-- Dos opciones adicionales -->
            <div v-if="props.project.initial_budget === 0" class="flex justify-end px-5 py-3">
                <button @click="openModal" class="text-blue-600 hover:underline mr-4">Definir Presupuesto</button>
            </div>
            <div v-else class="flex justify-end px-5 py-3">
                <button @click="openModal2" class="text-blue-600 hover:underline mr-4">Actualizar Presupuesto</button>
                <Link :href="route('budgetupdates.index', { project: props.project.id })"
                    class="text-blue-600 hover:underline mr-4">Ver Historial de Actualizaciones</Link>
            </div>

            <Modal :show="isModalOpen">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold mb-4">Definir Presupuesto Inicial</h2>
                    <form @submit.prevent="submit">
                        <div class="mb-4">
                            <label for="initial_budget" class="block text-sm font-medium text-gray-700">Presupuesto
                                Inicial</label>
                            <input type="number" id="initial_budget" name="initial_budget" v-model="form.initial_budget"
                                required class="mt-1 p-2 border rounded-md w-full">
                        </div>
                        <div class="flex justify-end">
                            <SecondaryButton type="button" @click="closeModal" class="text-gray-500 mr-2">Cancelar
                            </SecondaryButton>
                            <PrimaryButton type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </Modal>

            <Modal :show="isModalOpen2">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold mb-4">Definir Presupuesto Inicial</h2>
                    <!-- Formulario para definir el presupuesto -->
                    <form @submit.prevent="submit2">
                        <div class="mb-4">
                            <label for="initial_budget" class="block text-sm font-medium text-gray-700">Nuevo
                                Presupuesto</label>
                            <input type="number" step="0.01" id="initial_budget" name="initial_budget"
                                v-model="form2.new_budget" required class="mt-1 p-2 border rounded-md w-full">
                        </div>
                        <div class="mb-4">
                            <label for="reason" class="block text-sm font-medium text-gray-700">Razón</label>
                            <textarea id="reason" name="reason" v-model="form2.reason" required
                                class="mt-1 p-2 border rounded-md w-full" />
                        </div>
                        <div class="mb-4">
                            <label for="update_date" class="block text-sm font-medium text-gray-700">Fecha de
                                Actualización</label>
                            <input type="date" id="update_date" name="update_date" v-model="form2.update_date" required
                                class="mt-1 p-2 border rounded-md w-full">
                        </div>
                        <div class="mb-4">
                            <label for="approved_update_date" class="block text-sm font-medium text-gray-700">Fecha de
                                Actualización Aprobada</label>
                            <input type="date" id="approved_update_date" name="approved_update_date"
                                v-model="form2.approved_update_date" required class="mt-1 p-2 border rounded-md w-full">
                        </div>
                        <div class="flex justify-end">
                            <SecondaryButton type="button" @click="closeModal2" class="text-gray-500 mr-2">Cancelar</SecondaryButton>
                            <PrimaryButton type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar</PrimaryButton>
                        </div>
                    </form>
                </div>
            </Modal>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    project: Object,
    budgetUpdate: Object
});

const isModalOpen = ref(false);
const isModalOpen2 = ref(false);

const form = useForm({
    initial_budget: null,
});

const form2 = useForm({
    new_budget: null,
    project_id: null,
    reason: '',
    update_date: '',
    user_id: null,
    approved_update_date: '',
});

const openModal = () => {
    isModalOpen.value = true;
};

const openModal2 = () => {
    isModalOpen2.value = true;
};
const closeModal = () => {
    isModalOpen.value = false;
    form.initial_budget = null;
};

const closeModal2 = () => {
    isModalOpen2.value = false;
    form2.new_budget = null;
    form2.project_id = null;
    form2.reason = '';
    form2.update_date = '';
    form2.user_id = null;
    form2.approved_update_date = '';
};

const submit = () => {
    form.put(route('initialbudget.update', { project: props.project.id }));
    closeModal();
};

const submit2 = () => {
    form2.project_id = props.project.id;
    form2.user_id = 1;
    form2.post(route('budgetupdates.create', { project: props.project.id }, form2));
    closeModal2();
};
</script>
