<template>

    <Head title="Usuarios" />
    <AuthenticatedLayout :redirectRoute="'users.index'">
        <template #header>
            Usuarios
        </template>
        <Toaster richColors />
        <div class="min-w-full">
            <TableHeader v-model:formSearch="formSearch" v-model:users="users" />
            <UsersTable :users="users" :formSearch="formSearch" :platforms="platforms"
                v-model:confirmingUserDeletion="confirmingUserDeletion" v-model:usersToDelete="usersToDelete" />
        </div>
        <DeleteModal v-model:confirmingUserDeletion="confirmingUserDeletion" v-model:usersToDelete="usersToDelete"
            v-model:users="users" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Toaster } from 'vue-sonner';
import UsersTable from './components/UsersTable.vue';
import DeleteModal from './components/DeleteModal.vue';
import TableHeader from './components/TableHeader.vue';

const { user } = defineProps({
    user: Object
})

const users = ref(user)
const usersToDelete = ref(null);
const confirmingUserDeletion = ref(false);

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

</script>
