<template>
    <Head title="Checklist" />
    <AuthenticatedLayout :redirectRoute="'checklist.index'">
        <template #header> Checklist </template>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <!-- <CheckListCard title="Checklist Vehicular" href="checklist.car.index">
                <template #icon>
                    <VehicleIcon />
                </template>
            </CheckListCard> -->
            <CheckListCard title="Checklist de Herramientas" href="checklist.toolkit.index">
                <template #icon>
                    <ToolIcon />
                </template>
            </CheckListCard>
            <CheckListCard title="Checklist Diario de Herramientas" href="checklist.dailytoolkit.index">
                <template #icon>
                    <LlaveIcon />
                </template>
            </CheckListCard>
            <CheckListCard title="Checklist EPPS" href="checklist.epp.index">
                <template #icon>
                    <SecurityIcon />
                </template>
            </CheckListCard>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import CheckListCard from "./CheckListCard.vue";
import { LlaveIcon, SecurityIcon, ToolIcon, VehicleIcon } from "@/Components/Icons";

const props = defineProps({
    userPermissions: Array,
});
</script>
