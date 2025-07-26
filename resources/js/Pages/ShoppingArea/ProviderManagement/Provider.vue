<template>

    <Head title="Proveedores" />
    <AuthenticatedLayout :redirectRoute="'providersmanagement.index'">
        <template #header>
            Proveedores
        </template>
        <Toaster richColors />
        <ProviderHeader v-model:providers="providers" :createProviderForm="createProviderForm" />
        <ProviderTable :providers="providers" :auth="auth" :editProviderForm="editProviderForm" />
        <SuspenseWrapper :when="showProviderForm">
            <template #component>
                <ProviderModal ref="providerForm" v-model:providers="providers" :category="category"
                    />
            </template>
        </SuspenseWrapper>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { defineAsyncComponent, ref } from 'vue';
import { Toaster } from 'vue-sonner';
import ProviderTable from './components/ProviderTable.vue';
import ProviderHeader from './components/ProviderHeader.vue';
import SuspenseWrapper from '@/Components/SuspenseWrapper.vue';
import { useLazyRefInvoker } from '@/utils/useLazyRefInvoker';

const ProviderModal = defineAsyncComponent(() => import('./components/ProviderModal.vue'));

const { provider, auth, category } = defineProps({
    provider: Object,
    auth: Object,
    category: Object
});

const providers = ref(provider)

const showProviderForm = ref(false)
const providerForm = ref(null)

const { invokeWhenReady: invokeProviderModal } = useLazyRefInvoker(providerForm, showProviderForm);

const createProviderForm = () => {
    invokeProviderModal('createProviderForm')
};

const editProviderForm = (item) => {
    invokeProviderModal('editProviderForm', item)
};

</script>
