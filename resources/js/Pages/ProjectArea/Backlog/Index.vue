<template>

    <Head title="Backlog" />

    <AuthenticatedLayout :redirectRoute="'socialnetwork.sot'">
        <template #header>
            Backlog
        </template>

        
        <div class="min-w-full rounded-lg shadow">
            
            <TestChildCompo v-model="testProp"/>
            <p>{{testProp}}</p>
        </div>


    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import SelectSNSotComponent from '@/Components/SelectSNSotComponent.vue';
import { formattedDate } from '@/utils/utils';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import { EyeIcon } from '@heroicons/vue/24/outline';
import TestChildCompo from '@/Components/TestChildCompo.vue'

const testProp = ref('')



</script>
