<template>

    <Head title="Gestion de Gastos Cuadrilla" />
    <AuthenticatedLayout :redirectRoute="'document.rrhh.status'">
        <template #header>
            {{ employee.name }} {{ employee.lastname }}
        </template>
        <br />
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-10">
            <div class="col-span-1 min-w-full rounded-lg shadow bg-white p-6 gap-6">
                <div v-for="(pd, i) in personalData" :key="i" class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">
                        {{ pd.title }}
                    </p>
                    <p class="text-lg text-gray-900 break-words">
                        {{
                            getProp({
                                obj: employee,
                                path: pd.propName,
                                type: pd.propType,
                            })
                        }}
                    </p>
                </div>
            </div>
            <div class="col-span-3">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div
                        v-for="section in sections"
                        :key="section.id"
                        class="bg-white p-4 rounded-sm shadow-sm border border-gray-300 relative"
                    >
                        <!-- Encabezado de la sección -->
                        <div class="flex items-center justify-between mb-2">
                            <label
                                class="flex items-center justify-between w-full cursor-pointer"
                            >
                                <span
                                    class="text-sm font-semibold text-gray-800 break-words"
                                >
                                    {{ section.name }}
                                </span>
                            </label>
                        </div>

                        <div class="border-t border-gray-200 my-2"></div>

                        <!-- Subdivisiones -->
                        <div class="space-y-2">
                            <div
                                v-for="sub in section.subdivisions"
                                :key="sub.id"
                                class="flex items-center justify-between"
                            >
                                <div class="flex justify-between w-full items-center">
                                    <div>
                                        <label
                                            class="flex items-center justify-between w-full cursor-pointer"
                                        >
                                            <span
                                                class="text-sm break-words font-medium"
                                                :class="[
                                                {
                                                    'text-red-600': employee.document_registers[sub.id] === undefined,
                                                    'text-amber-600': employee.document_registers[sub.id]?.state === 'En Proceso',
                                                    'text-green-600': employee.document_registers[sub.id]?.state === 'Completado',
                                                    'text-gray-400': employee.document_registers[sub.id]?.state === 'No Corresponde',
                                                },
                                            ]"
                                            >
                                                {{ sub.name }}
                                            </span>
                                        </label>
                                    </div>
                                    <div>
                                        <span class="text-sm break-words font-medium" :class="[
                                            employee.document_registers[sub.id]
                                                ?.display && 'text-red-600',
                                        ]">
                                                {{ formattedDate(employee.document_registers[sub.id]?.exp_date) }}
                                        </span>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div v-if="section.id === 9" class="space-y-2">
                            <div class="flex items-center justify-between">
                                <div  class="flex justify-between w-full items-center">
                                    <div>
                                        <label
                                            class="flex items-center justify-between w-full cursor-pointer"
                                        >
                                            <span
                                                class="text-sm break-words font-medium"
                                                :class="[
                                                 (employee.contract?.discount_sctr && employee.sctr_exp_date === null) && 'text-red-600'
                                            ]"
                                            >
                                                SCTR
                                            </span>
                                        </label>
                                    </div>
                                    <div>
                                        <span class="text-sm break-words font-medium" :class="[
                                            employee.sctr_about_to_expire && 'text-red-600'
                                        ]">
                                               {{ formattedDate(employee.sctr_exp_date) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div  class="flex justify-between w-full items-center">
                                    <div>
                                        <label
                                            class="flex items-center justify-between w-full cursor-pointer"
                                        >
                                            <span
                                                class="text-sm break-words font-medium"
                                                :class="[
                                                (employee.l_policy && employee.policy_exp_date === null) && 'text-red-600'
                                            ]"
                                            >
                                                Póliza
                                            </span>
                                        </label>
                                    </div>
                                    <div>
                                        <span class="text-sm break-words font-medium" :class="[
                                             employee.policy_about_to_expire  && 'text-red-600'
                                        ]">
                                               {{  formattedDate(employee.policy_exp_date) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
     
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { formattedDate } from "@/utils/utils";
import { personalData, getProp } from "./constants";


const { employee, sections } = defineProps({
    employee: Object,
    sections: Array,
});

</script>
