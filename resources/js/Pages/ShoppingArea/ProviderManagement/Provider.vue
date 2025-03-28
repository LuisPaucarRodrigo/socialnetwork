<template>

    <Head title="Proveedores" />
    <AuthenticatedLayout :redirectRoute="'providersmanagement.index'">
        <template #header>
            Proveedores
        </template>
        <Toaster richColors />
        <ProviderHeader v-model:providers="providers" :permissions="userPermissions"
            :add_information="add_information" />
        <ProviderTable :providers="providers" :auth="auth" :add_information="add_information" />
        <ProviderModal :showModalStoreOrUpdate="showModalStoreOrUpdate" v-model:providers="providers" :form="form"
            :category="category" :closeModalStoreOrUpdate="closeModalStoreOrUpdate" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Toaster } from 'vue-sonner';
import ProviderTable from '@/Layouts/Provider/ProviderTable.vue';
import ProviderHeader from '@/Layouts/Provider/ProviderHeader.vue';
import ProviderModal from '@/Layouts/Provider/ProviderModal.vue';

const { provider, auth, userPermissions, category } = defineProps({
    provider: Object,
    auth: Object,
    userPermissions: Array,
    category: Object
});

const providers = ref(provider)
const showModalStoreOrUpdate = ref(false)


const initialStateForm = {
    company_name: '',
    contact_name: '',
    address: '',
    phone1: '',
    phone2: '',
    email: '',
    category_id: '',
    segments: [],
    zone: '',
    ruc: '',
    id: '',
}

const form = useForm({ ...initialStateForm });

const add_information = (item) => {
    form.defaults(item ? { ...item, segments: item.segments.map(item => item.id) } : { ...item })
    form.reset()
    showModalStoreOrUpdate.value = true
};

function closeModalStoreOrUpdate() {
    form.clearErrors()
    form.defaults({ ...initialStateForm })
    form.reset()
    showModalStoreOrUpdate.value = false
}

</script>
