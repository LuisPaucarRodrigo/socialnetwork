<template>

    <Head title="Gestión de Categorias y Segmentos" />
    <AuthenticatedLayout :redirectRoute="'providersmanagement.index'">
        <template #header>
            Gestión de Categorias y Segmentos
        </template>
        <Toaster richColors />
        <TableHeader v-model:dataToRender="dataToRender" :createCategoryForm="createCategoryForm" />
        <MCSTable v-model:dataToRender="dataToRender" v-model:loading="loading" :confirmDeletion="confirmDeletion"
            :editCategoryForm="editCategoryForm" />

        <SuspenseWrapper :when="showCategoryForm">
            <template #component>
                <CategoryForm ref="categoryForm" :dataToRender="dataToRender" />
            </template>
        </SuspenseWrapper>

        <SuspenseWrapper :when="showConfirmCategoryDeletion">
            <template #component>
                <ConfirmDeletion ref="confirmCategoryDeletion" :dataToRender="dataToRender"
                    :routeName="'providersmanagement.MCSManagement.category.delete'" />
            </template>
        </SuspenseWrapper>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Toaster } from 'vue-sonner';
import MCSTable from './components/MCSTable.vue';
import { notifyError } from '@/Components/Notification';
import { defineAsyncComponent, onMounted, ref } from 'vue';
import TableHeader from './components/TableHeader.vue';
import { Head } from '@inertiajs/vue3';
import SuspenseWrapper from '@/Components/SuspenseWrapper.vue';
import { useLazyRefInvoker } from '@/utils/useLazyRefInvoker';

const ConfirmDeletion = defineAsyncComponent(() => import('./components/ConfirmDeletion.vue'));
const CategoryForm = defineAsyncComponent(() => import('./components/CategoryForm.vue'));

const dataToRender = ref([])
const loading = ref(true)

const showConfirmCategoryDeletion = ref(false)
const confirmCategoryDeletion = ref(null)

const showCategoryForm = ref(false)
const categoryForm = ref(null)

const { invokeWhenReady: invokeConfirmDeletion } = useLazyRefInvoker(confirmCategoryDeletion, showConfirmCategoryDeletion);
const { invokeWhenReady: invokeCategoryForm } = useLazyRefInvoker(categoryForm, showCategoryForm);

async function getDataToRender() {
    try {
        let url = route('providersmanagement.MCSManagement.getData')
        let res = await axios.get(url)
        loading.value = false
        dataToRender.value = res.data
    } catch (error) {
        notifyError(error)
    }
}

function confirmDeletion(itemId) {
    invokeConfirmDeletion('confirmDeletion', itemId)
}

function createCategoryForm() {
    invokeCategoryForm('createCategoryForm')
}

function editCategoryForm(item) {
    invokeCategoryForm('editCategoryForm', item)
}

onMounted(getDataToRender)
</script>