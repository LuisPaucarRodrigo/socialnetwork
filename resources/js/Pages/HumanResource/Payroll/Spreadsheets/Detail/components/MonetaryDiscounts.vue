<template>
    <div>
        <h1>Descuentos:</h1>
        <hr>
        <form class="block">
            <TableStructure>
                <template #thead>
                    <TableTitle>Codigo</TableTitle>
                    <TableTitle>Concepto</TableTitle>
                    <TableTitle>Monto(S/.)</TableTitle>
                </template>
                <template #tbody>
                    <tr v-for="item in discountParams" :key="item.id">
                        <TableRow>{{ item.code }}</TableRow>
                        <TableRow>{{ item.concept }}</TableRow>
                        <TableRow>
                            <div class="flex gap-1 items-center justify-center">
                                <input
                                    type="number"
                                    name="amount"
                                    class="block max-w-[100px] rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    required
                                    min="0"
                                    @input="()=>{item.saved = false}"
                                    v-model="
                                        monetaryDiscounts[item.id].amount
                                    "
                                    @keypress.enter="
                                        (e) => handleSubmit(e.target, item.id)
                                    "
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
        </form>
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

const discountParams = ref([]);
const monetaryDiscounts = ref({});


async function handleSubmit(item, discount_param_id) {
    const { name, value } = item;
    const id = monetaryDiscounts.value[discount_param_id].id
    const payload = {
        id,
        payroll_detail_id: payroll_detail.id,
        discount_param_id,
        [name]: value,
    };
    if (value < 0) notifyWarning("Valor no válido");
    const res = await axios
        .post(route("payroll.store.payroll.detail.monetary.discount"), payload)
        .catch((e) => notifyError("SERVER ERROR"));
    if (res.status === 200) {
        const discItem = discountParams.value.find(item=> item.id == discount_param_id);
        discItem.saved = true
        if (!id) {
            monetaryDiscounts.value[discount_param_id] = res.data;
        }
        notify("Guardado");
    }
}


function toogle(id, prop) {
    savedArray.value[id][prop] = false;
    return;
}

onMounted(async () => {
    const res = await axios
        .get(
            route("payroll.show.payroll.detail.monetary.discount", {
                payroll_detail_id: payroll_detail.id,
            })
        )
        .catch((e) => notifyWarning("Cant obtain pdmd"));
    if (res.status === 200) {
        if (res.data?.discount_params) {
            discountParams.value = res.data.discount_params.map(item=>({...item, saved:true}));
        }

        if (res.data?.monetary_discounts) {
            const original = res.data.monetary_discounts;
            discountParams.value.forEach((item) => {
                if (!original[item.id])
                    original[item.id] = {
                        id: null,
                        payroll_detail_id: payroll_detail.id,
                        discount_param: item.id,
                        amount: 0,
                    };
            });
            console.log(original)
            monetaryDiscounts.value = original;
        }
    }
});

</script>