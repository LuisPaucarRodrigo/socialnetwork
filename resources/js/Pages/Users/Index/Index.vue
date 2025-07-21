<template>

    <Head title="Usuarios" />
    <AuthenticatedLayout :redirectRoute="'users.index'">
        <template #header>
            Usuarios
        </template>
        <Toaster richColors />
        <div class="min-w-full">
            <TableHeader v-model:formSearch="formSearch" v-model:users="users" />
            <UsersTable v-model:users="users" :formSearch="formSearch" :platforms="platforms"
                :confirmUserDeletion="confirmUserDeletion" v-model:loading="loading" />
        </div>
        <SuspenseWrapper :when="showDeleteModal">
            <template #component>
                <DeleteModal ref="deleteModal" v-model:users="users" />
            </template>
        </SuspenseWrapper>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineAsyncComponent, onMounted, ref } from 'vue';
import { Toaster } from 'vue-sonner';
import UsersTable from './components/UsersTable.vue';
import TableHeader from './components/TableHeader.vue';
import { useLazyRefInvoker } from "@/utils/useLazyRefInvoker";
import SuspenseWrapper from '@/Components/SuspenseWrapper.vue';

const DeleteModal = defineAsyncComponent(() => import('./components/DeleteModal.vue'));

const showDeleteModal = ref(false)
const deleteModal = ref(null)
const users = ref([])
const loading = ref(true)

async function getUsers() {
    const res = await axios.get(route('getUsers'));
    users.value = res.data;
    loading.value = false;
}

onMounted(() => getUsers())
const { invokeWhenReady } = useLazyRefInvoker(deleteModal, showDeleteModal);

const platforms = [
    'Web',
    'Web/Movil',
    'Movil'
]

const initialSearch = {
    searchQuery: '',
    platform: [...platforms]
}

const formSearch = ref({ ...initialSearch })

function confirmUserDeletion(id) {
    invokeWhenReady('confirmUserDeletion', id)
}
</script>
