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
        </div>
        <RolTable :rols="rols" :editModalRol="editModalRol" :confirmRolsDeletion="confirmRolsDeletion"
            :showModal="showModal" />
        <SuspenseWrapper :when="showShowRol">
            <template #component>
                <ShowRol ref="showRol" />
            </template>
        </SuspenseWrapper>
        <SuspenseWrapper :when="showFormRol">
            <template #component>
                <FormRol ref="formRol" :permissions="permissions" :modules="modules" />
            </template>
        </SuspenseWrapper>

        <ConfirmDeleteModal :confirmingDeletion="confirmingRolDeletion" itemType="rol" :deleteFunction="deleteRol"
            @closeModal="closeModalRol" />
            
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { defineAsyncComponent, ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import RolTable from './components/RolTable.vue';
import { useLazyRefInvoker } from '@/utils/useLazyRefInvoker';
import SuspenseWrapper from '@/Components/SuspenseWrapper.vue';

const ShowRol = defineAsyncComponent(() => import('./components/ShowRol.vue'));
const FormRol = defineAsyncComponent(() => import('./components/FormRol.vue'));

const showShowRol = ref(false)
const showFormRol = ref(false)

const formRol = ref(null)
const showRol = ref(null)

const { invokeWhenReady: invokeShowRol } = useLazyRefInvoker(showRol, showShowRol);
const { invokeWhenReady: invokeFormRol } = useLazyRefInvoker(formRol, showFormRol);

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
    invokeFormRol('add_rol')
};

function editModalRol(rol) {
    invokeFormRol('editModalRol', rol)
};

function showModal(id) {
    invokeShowRol('showModal', id)
}
</script>