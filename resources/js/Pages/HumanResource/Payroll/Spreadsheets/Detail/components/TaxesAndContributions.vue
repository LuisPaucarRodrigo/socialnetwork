<template>
    <div>
        <div class="space-y-4 mb-3">
            <h4 class="text-sm font-light text-green-900 bg-green-500/10 rounded-lg p-3 ">
            Para guardar el registro presionar la tecla enter, el punto púrpura indica que no esta guardado aún.
        </h4>
            <h1>Aportaciones del Trabajador:</h1>
            <TableStructure>
                <template #thead>
                    <TableTitle>Codigo</TableTitle>
                    <TableTitle>Concepto</TableTitle>
                    <TableTitle>Base de Cálculo(S/.)</TableTitle>
                    <TableTitle>Pagado(S/.)</TableTitle>
                </template>
                <template #tbody>
                    <tr v-for="item in taxAndContributionParams.filter(item=>item.type === 'employee')" :key="item.id">
                        <TableRow>{{ item.code }}</TableRow>
                        <TableRow>{{ item.concept }}</TableRow>
                        <TableRow></TableRow>
                        <TableRow>
                            <div class="flex gap-1 items-center justify-center">
                                <input
                                    type="number"
                                    name="amount"
                                    class="block max-w-[100px] rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    required
                                    min="0"
                                    @input="()=>{item.saved = false}"
                                    v-model="taxesContributions[item.id].amount"
                                    @keypress.enter="handleSubmit(taxesContributions[item.id])"
                                />
                                <div class="flex items-center">
                                    <span
                                        v-if="
                                            !item.saved
                                        "
                                        class="absolute inline-flex rounded-full h-2 w-2 bg-fuchsia-500 cursor-pointer"
                                    >
                                    </span>
                                </div>
                            </div>
                        </TableRow>
                    </tr>
                </template>
            </TableStructure>
            <h1>Aportaciones del Empleador:</h1>
            <TableStructure>
                <template #thead>
                    <TableTitle>Codigo</TableTitle>
                    <TableTitle>Concepto</TableTitle>
                    <TableTitle>Base de Cálculo(S/.)</TableTitle>
                    <TableTitle>Pagado(S/.)</TableTitle>
                </template>
                <template #tbody>
                    <tr v-for="item in taxAndContributionParams.filter(item=>item.type === 'employer')" :key="item.id">
                        <TableRow>{{ item.code }}</TableRow>
                        <TableRow>{{ item.concept }}</TableRow>
                        <TableRow></TableRow>
                        <TableRow>
                            <div class="flex gap-1 items-center justify-center">
                                <input
                                    type="number"
                                    name="amount"
                                    class="block max-w-[100px] rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    required
                                    min="0"
                                    @input="()=>{item.saved = false}"
                                    v-model="taxesContributions[item.id].amount"
                                    @keypress.enter="handleSubmit(taxesContributions[item.id])"
                                />
                                <div class="flex items-center">
                                    <span
                                        v-if="
                                            !item.saved
                                        "
                                        class="absolute inline-flex rounded-full h-2 w-2 bg-fuchsia-500 cursor-pointer"
                                    >
                                    </span>
                                </div>
                            </div>
                        </TableRow>
                    </tr>
                </template>
            </TableStructure>
        </div>
    </div>
</template>
<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TableTitle from "@/Components/TableTitle.vue";
import TableRow from "@/Components/TableRow.vue";
import TableStructure from "@/Layouts/TableStructure.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { notify, notifyError, notifyWarning } from "@/Components/Notification";
import { onMounted, ref, watch } from "vue";

const { objectData } = defineProps({
    objectData: Object,
});

const { payroll_detail } = objectData;

const taxAndContributionParams = ref([]);
const taxesContributions = ref({});


async function handleSubmit(item) {
    if (item.value < 0) notifyWarning("Valor no válido");
    const res = await axios
        .post(route("payroll.store.payroll.detail.tax.contribution"), item)
        .catch((e) => notifyError("SERVER ERROR"));
    if (res.status === 200) {
        const tacItem = taxAndContributionParams.value.find(subitem=> subitem.id == item.t_a_c_param_id);
        tacItem.saved = true
        if (!item.id) {
            taxesContributions.value[item.t_a_c_param_id] = res.data;
        }
        notify("Guardado");
    }
}


onMounted(async () => {
    const res = await axios
        .get(
            route("payroll.show.payroll.detail.tax.contribution", {
                payroll_detail_id: payroll_detail.id,
            })
        )
        .catch((e) => notifyWarning("Cant obtain pdtac"));
    if (res.status === 200) {
        if (res.data?.tax_and_contribution_params) {
            taxAndContributionParams.value = res.data.tax_and_contribution_params.map(item=>({...item, saved:true}));
        }

        if (res.data?.taxes_contributions) {
            const original = res.data.taxes_contributions;
            taxAndContributionParams.value.forEach((item) => {
                if (!original[item.id])
                    original[item.id] = {
                        id: null,
                        payroll_detail_id: payroll_detail.id,
                        t_a_c_param_id: item.id,
                        amount: 0,
                    };
            });
            taxesContributions.value = original;
        }
    }
});

</script>