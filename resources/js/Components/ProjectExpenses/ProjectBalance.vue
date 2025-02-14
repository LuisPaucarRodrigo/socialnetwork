<template>
    <div class="p-4">
        <div class="overflow-x-auto ring-1 ring-gray-200">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500"
                    >
                        <th
                            colspan="3"
                            class="border-b-2 border-gray-200 bg-gray-700 px-3 py-3 text-center text-sm font-semibold uppercase tracking-wider text-gray-200"
                        >
                            Balance
                        </th>
                    </tr>
                    <tr
                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500"
                    >
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                        ></th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-3 py-3 text-right text-sm font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Sin IGV
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-3 py-3 text-right text-sm font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Con IGV
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-gray-700">
                        <td
                            class="border-b border-gray-200 bg-white px-3 py-3 text-center"
                        >
                            <span class="text-lg text-orange-500 font-semibold"
                                >S/.</span
                            >
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-3 py-3 text-base whitespace-nowrap text-right tabular-nums"
                        >
                            S/.
                            {{
                                (
                                    project?.preproject.quote?.total_amount -
                                    (additionalCosts + staticCosts)
                                ).toFixed(2)
                            }}
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-3 py-3 text-base whitespace-nowrap text-right tabular-nums"
                        >
                            S/.
                            {{
                                (
                                    project?.preproject?.quote?.total_amount *
                                        1.18 -
                                    (additionalCostsIgv + staticCostsIgv)
                                ).toFixed(2)
                            }}
                        </td>
                    </tr>
                    <tr class="text-gray-700">
                        <td
                            class="border-b border-gray-200 bg-white px-3 py-3 text-center"
                        >
                            <span
                                class="text-base text-indigo-500 font-semibold"
                                >IGRESOS</span
                            >
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-3 py-3 text-base whitespace-nowrap text-right tabular-nums"
                        >
                            S/.
                            {{
                                (project?.preproject.quote?.total_amount).toFixed(
                                    2
                                )
                            }}
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-3 py-3 text-base whitespace-nowrap text-right tabular-nums"
                        >
                            S/.
                            {{
                                (
                                    project?.preproject?.quote?.total_amount *
                                    1.18
                                ).toFixed(2)
                            }}
                        </td>
                    </tr>
                    <tr class="text-gray-700">
                        <td
                            class="border-b border-gray-200 bg-white px-3 py-3 text-center"
                        >
                            <span class="text-base text-gray-500 font-semibold"
                                >SALIDAS</span
                            >
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-3 py-3 text-base whitespace-nowrap text-right tabular-nums"
                        >
                            S/. {{ (additionalCosts + staticCosts).toFixed(2) }}
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-3 py-3 text-base whitespace-nowrap text-right tabular-nums"
                        >
                            S/.
                            {{
                                (additionalCostsIgv + staticCostsIgv).toFixed(2)
                            }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
    const { project } = defineProps({ 
        project: {type:Object, required: true},
        additionalCosts: {type:Number, required: true},
        additionalCostsIgv: {type:Number, required: true},
        staticCosts: {type:Number, required: true},
        staticCostsIgv: {type:Number, required: true},
    });

</script>