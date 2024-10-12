<template>
    <Head title="Estado de Cuenta" />
    <AuthenticatedLayout
    :redirectRoute="{
            route: 'projectmanagement.purchases_request.index',
        }"
    >
        <template #header>
            Estado de Cuenta 
        </template>
        <br />
        <div class="inline-block min-w-full mb-4">

        </div>
        <div class="overflow-x-auto h-[85vh]">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="sticky top-0 z-20 border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500"
                    >
                        
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                        Foto de Factura
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Descripción
                        </th>
                        <th
                            v-if="
                                auth.user.role_id === 1
                            "
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        class="text-gray-700"
                    >
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            k
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            gv
                        </td>
                        <td
                            v-if="
                                auth.user.role_id === 1
                            "
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                        dd
                            <!-- <div class="flex items-center">
                                <button
                                    @click="openEditAdditionalModal(item)"
                                    class="text-amber-600 hover:underline mr-2"
                                >
                                    <PencilSquareIcon class="h-4 w-4 ml-1" />
                                </button>
                                <button
                                    @click="confirmDeleteAdditional(item.id)"
                                    class="text-red-600 hover:underline"
                                >
                                    <TrashIcon class="h-4 w-4" />
                                </button>
                            </div> -->
                        </td>
                    </tr>
                    <tr class="sticky bottom-0 z-10 text-gray-700">
                        <td
                            class="font-bold border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            TOTAL
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        ></td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        ></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, router } from "@inertiajs/vue3";

const { auth } = defineProps({
    auth: Object,
});








</script>