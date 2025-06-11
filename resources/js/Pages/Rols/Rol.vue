<template>

    <Head title="Roles" />
    <AuthenticatedLayout :redirectRoute="'rols.index'">
        <template #header>
            Roles
        </template>
        <div class="min-w-full overflow-hidden">
            <PrimaryButton v-permission="'add_role'" @click="add_rol" type="button">
                + Agregar
            </PrimaryButton>

            <RolTable :rols="rols" :editModalRol="editModalRol" :confirmRolsDeletion="confirmRolsDeletion" :showModal="showModal"/>
        </div>
        <ShowRol ref="showRol"/>
        <FormRol ref="formRol" :permissions="permissions" :modules="modules"/>
        <ConfirmDeleteModal :confirmingDeletion="confirmingRolDeletion" itemType="rol" :deleteFunction="deleteRol"
            @closeModal="closeModalRol" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref } from 'vue';
import { Head,  router } from '@inertiajs/vue3';
import RolTable from './components/RolTable.vue';
import FormRol from './components/FormRol.vue';
import ShowRol from './components/ShowRol.vue';

const formRol = ref(null)
const showRol = ref(null)

const confirmingRolDeletion = ref(false);
const rolToDelete = ref(null);

const props = defineProps({
    rols: Object,
    permissions: Object,
    modules: Object
});

const confirmRolsDeletion = (rolId) => {
    rolToDelete.value = rolId;
    confirmingRolDeletion.value = true;
};

const deleteRol = () => {
    const rolId = rolToDelete.value;
    if (rolId) {
        router.delete(route('rols.delete', { id: rolId }), {
            onSuccess: () => closeModalRol()
        });
    }
};

const closeModalRol = () => {
    confirmingRolDeletion.value = false;
};

const add_rol = () => {
    formRol.value.add_rol()
};

function editModalRol(rol) {
    formRol.value.editModalRol(rol)
};

function showModal(id){
    showRol.value.showModal(id)
}
</script>