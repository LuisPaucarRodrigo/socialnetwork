<template>
    <Head title="SocialNetwork SOT" />

    <AuthenticatedLayout :redirectRoute="'users.index'">
        <template #header>
            Social Network SOT
        </template>
        esta es mi plnatilla de acceso
           
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Link, Head, router, useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const confirmingUserDeletion = ref(false);
const usersToDelete = ref(null);
const passwordInput = ref(null);

const props = defineProps({
    users: Object
})

const add_users = () => {
    router.get(route('register'));
}

const form = useForm({
    password: '',
});

const confirmUserDeletion = (userId) => {
    confirmingUserDeletion.value = true;
    usersToDelete.value = userId;
   
};

const deleteUser = () => {
    const userId = usersToDelete.value;
    form.delete(route('users.destroy', { id: userId }), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>
