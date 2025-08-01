<template>

    <Head title="Roles" />
    <AuthenticatedLayout :redirectRoute="'rols.index'">
        <template #header>
            Roles
        </template>
        <Toaster richColors />
        <div class="min-w-full overflow-hidden">
            <PrimaryButton v-permission="'add_role'" @click="add_rol" type="button">
                + Agregar
            </PrimaryButton>
        </div>
        <RolTable v-model:rols="rols" v-model:loading="loading" :editModalRol="editModalRol" :confirmRolsDeletion="confirmRolsDeletion"
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
import { defineAsyncComponent, onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import RolTable from './components/RolTable.vue';
import { useLazyRefInvoker } from '@/utils/useLazyRefInvoker';
import SuspenseWrapper from '@/Components/SuspenseWrapper.vue';
import { notify, notifyError } from '@/Components/Notification';
import { Toaster } from 'vue-sonner';

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

const rols = ref([])
const loading = ref(true)

const props = defineProps({
    rols: Object,
    permissions: Object,
    modules: Object
});

async function getRols() {
    const res = await axios.get(route('getRols'));
    rols.value = res.data;
    loading.value = false;
}

onMounted(() => getRols())

const confirmRolsDeletion = (rolId) => {
    rolToDelete.value = rolId;
    confirmingRolDeletion.value = true;
};

async function deleteRol() {
    const rolId = rolToDelete.value;
    try {
        let url = route('rols.delete', { id: rolId })
        await axios.delete(url)
        let listData = rols.value.data || rols.value
        let index = listData.findIndex(item => item.id === rolId)
        listData.splice(index, 1)
        closeModalRol()
        notify('Eliminacion Exitosa')
    } catch (error) {
        console.error(error)
        notifyError(error)
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