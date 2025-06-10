<template>
    <div class="flex flex-col gap-4">
        <h1>Ingresos:</h1>
  
        <h4 class="text-sm font-light text-green-900 bg-green-500/10 rounded-lg p-3 ">
            Para guardar el registro presionar la tecla enter, el punto púrpura indica que no esta guardado aún.
        </h4>

        <div class="mb-10">
            <TableStructure>
                <template #thead>
                    <TableTitle>Codigo</TableTitle>
                    <TableTitle>Concepto</TableTitle>
                    <TableTitle>Desvengado(S/.)</TableTitle>
                    <TableTitle>Pagado(S/.)</TableTitle>
                </template>
                <template #tbody>
                    <tr v-for="item in incomeParams" :key="item.id">
                        <TableRow>{{ item.code }}</TableRow>
                        <TableRow>{{ item.concept }}</TableRow>
                        <TableRow>
                            <div class="flex gap-1 items-center justify-center">
                                <input
                                    type="number"
                                    name="accrued_amount"
                                    class="block max-w-[100px] rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    required
                                    min="0"
                                    @input="toogle(item.id, 'accrued_amount')"
                                    v-model="
                                        monetaryIncomes[item.id].accrued_amount
                                    "
                                    @keypress.enter="
                                        (e) => handleSubmit(e.target, item.id)
                                    "
                                />
                                <div class="relative flex items-center">
                                    <div class="absolute flex items-center">
                                        <span
                                        v-if="
                                            !savedArray[item.id].accrued_amount
                                        "
                                            class="rounded-full h-2 w-2 bg-fuchsia-500 cursor-pointer"
                                        >
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </TableRow>
                        <TableRow>
                            <div class="flex gap-1 items-center justify-center">
                                <input
                                    type="number"
                                    name="paid_amount"
                                    class="w-[100px] rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    required
                                    min="0"
                                    @input="toogle(item.id, 'paid_amount')"
                                    v-model="
                                        monetaryIncomes[item.id].paid_amount
                                    "
                                    @keypress.enter="
                                        (e) => handleSubmit(e.target, item.id)
                                    "
                                />
                                <div class="relative flex items-center">
                                    <div class="absolute flex items-center">
                                        <span
                                            v-if="!savedArray[item.id].paid_amount"
                                            class="rounded-full h-2 w-2 bg-fuchsia-500 cursor-pointer"
                                        >
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </TableRow>
                    </tr>
                    <tr>
                        <TableRow :colspan="2"></TableRow>
                        <TableRow>
                            <p class="my-3 text-indigo-800 font-semibold ">
                                S/. {{ Object.values(monetaryIncomes).reduce((a,b)=>a+Number(b.accrued_amount), 0).toFixed(2) }}
                            </p>
                        </TableRow>
                        <TableRow>
                            <p class="my-3 text-indigo-800 font-semibold ">
                                S/. {{ Object.values(monetaryIncomes).reduce((a,b)=>a+Number(b.paid_amount), 0).toFixed(2) }}
                            </p>
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
const incomeParams = ref([]);
const savedArray = ref({});
const monetaryIncomes = ref({});

async function handleSubmit(item, income_param_id) {
    const { name, value } = item;
    const id = getProp(income_param_id, "id");
    const payload = {
        id,
        payroll_detail_id: payroll_detail.id,
        income_param_id,
        [name]: value,
    };
    if (value < 0) notifyWarning("Valor no válido");
    const res = await axios
        .post(route("payroll.store.payroll.detail.monetary.income"), payload)
        .catch((e) => notifyError("SERVER ERROR"));
    if (res.status === 200) {
        savedArray.value[income_param_id][name] = true;
        if (!id) {
            monetaryIncomes.value[income_param_id] = res.data;
        }
        notify("Guardado");
    }
}

function getProp(income_id, prop) {
    return monetaryIncomes.value[income_id]
        ? monetaryIncomes.value[income_id][prop]
        : null;
}

function toogle(id, prop) {
    savedArray.value[id][prop] = false;
    return;
}

onMounted(async () => {
    const res = await axios
        .get(
            route("payroll.show.payroll.detail.monetary.income", {
                payroll_detail_id: payroll_detail.id,
            })
        )
        .catch((e) => notifyWarning("Cant obtain pdmi"));
    if (res.status === 200) {
        if (res.data?.income_params) {
            incomeParams.value = res.data.income_params;
            savedArray.value = Object.fromEntries(
                incomeParams.value.map((item) => [
                    item.id,
                    { accrued_amount: true, paid_amount: true },
                ])
            );
        }

        if (res.data?.monetary_incomes) {
            const original = res.data.monetary_incomes;
            incomeParams.value.forEach((item) => {
                if (!original[item.id])
                    original[item.id] = {
                        id: null,
                        payroll_detail_id: payroll_detail.id,
                        income_param_id: item.id,
                        accrued_amount: 0,
                        paid_amount: 0,
                    };
            });
            monetaryIncomes.value = original;
        }
    }
});
</script>
