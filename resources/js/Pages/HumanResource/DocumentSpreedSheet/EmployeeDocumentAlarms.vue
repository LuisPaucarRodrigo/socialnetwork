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

            <div
                class="col-span-1 sm:col-span-3 min-w-full rounded-lg shadow bg-white p-6 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-2">
                <template v-for="sec in sections">
                    <div v-for="(sub, i) in sec.subdivisions" :key="i" class="mb-4 border border-gray-200">
                        <p class="text-sm text-gray-700 font-medium">
                            {{ sub.name }}
                        </p>
                        <div :class="['flex gap-3 justify-center items-center h-12 ',
                            {
                                'bg-red-100': employee.document_registers[sub.id] === undefined,
                                'bg-amber-100': employee.document_registers[sub.id]?.state === 'En Proceso',
                                'bg-green-100': employee.document_registers[sub.id]?.state === 'Completado',
                                'bg-white-100': employee.document_registers[sub.id]?.state === 'No Corresponde',
                            },
                        ]">

                            <div v-if="
                                employee.document_registers[sub.id]
                                    ?.sync_status === false
                            " class="relative group">
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-fuchsia-500 cursor-pointer">
                                </span>
                            </div>
                            <p :class="[
                                'text-sm ',
                                employee.document_registers[sub.id]
                                    ?.display && 'text-red-600',
                            ]">
                                {{
                                    formattedDate(
                                        employee.document_registers[sub.id]
                                            ?.exp_date
                                    )
                                }}

                            </p>
                            <p class="text-sm">
                                {{ employee.document_registers[sub.id]?.state }}
                            </p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <br />
        <br />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { EyeIcon } from "@heroicons/vue/24/outline";
import { Head } from "@inertiajs/vue3";
import { formattedDate } from "@/utils/utils";
import { principalData, personalData, getProp } from "./constants";

console.log(sections);

const { employee, sections } = defineProps({
    employee: Object,
    sections: Array,
});

console.log(employee);
</script>
