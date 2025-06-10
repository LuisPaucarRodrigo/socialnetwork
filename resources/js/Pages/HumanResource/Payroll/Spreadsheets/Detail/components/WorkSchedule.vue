<template>
    <div>
        <form @submit.prevent="submit">
            <div class="md:flex gap-5">
                <div class="md:w-1/2">
                    <h1>Dias de Jornada</h1>
                    <hr />
                    <dl
                        class="flex justify-between lg:pr-4 sm:mb-0 px-4 py-3 sm:px-0"
                    >
                        <dt class="text-sm font-medium leading-6 text-gray-900">
                            Laborados:
                        </dt>
                        <dd
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"
                        >
                            {{  payroll_detail.mod_days -
                                form.subsidized_days.reduce(
                                    (a, b) => a + b.quantity,
                                    0
                                ) -
                                form.non_subsidized_days.reduce(
                                    (a, b) => a + b.quantity,
                                    0
                                ) }}
                        </dd>
                    </dl>
                    <dl
                        class="flex justify-between lg:pr-4 sm:mb-0 px-4 py-3 sm:px-0"
                    >
                        <dt class="text-sm font-medium leading-6 text-gray-900">
                            Subsidiados:
                        </dt>
                        <div class="flex gap-3">
                            <dd
                                class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"
                            >
                                {{
                                    form.subsidized_days.reduce(
                                        (a, b) => a + b.quantity,
                                        0
                                    )
                                }}
                            </dd>
                            <button type="button" @click="openModalSubsidized">
                                <PencilSquareIcon
                                    class="w-6 h-6 text-yellow-400"
                                />
                            </button>
                        </div>
                    </dl>
                    <dl
                        class="flex justify-between lg:pr-4 sm:mb-0 px-4 py-3 sm:px-0"
                    >
                        <dt class="text-sm font-medium leading-6 text-gray-900">
                            No laborados y no Subsidiados:
                        </dt>
                        <div class="flex gap-3">
                            <dd
                                class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"
                            >
                                {{
                                    form.non_subsidized_days.reduce(
                                        (a, b) => a + b.quantity,
                                        0
                                    )
                                }}
                            </dd>
                            <button
                                type="button"
                                @click="openModalNotSubsidized()"
                            >
                                <PencilSquareIcon
                                    class="w-6 h-6 text-yellow-400"
                                />
                            </button>
                        </div>
                    </dl>
                    <dl
                        class="flex justify-between lg:pr-4 sm:mb-0 px-4 py-6 sm:px-0"
                    >
                        <dt class="text-sm font-medium leading-6 text-gray-900">
                            Total:
                        </dt>
                        <dd
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0"
                        >
                            {{
                                payroll_detail.mod_days 
                            }}
                        </dd>
                    </dl>
                </div>
                <div class="md:w-1/2">
                    <h1>Horas Laboradas</h1>
                    <hr />
                    <dl
                        class="flex justify-between lg:pr-4 sm:mb-0 px-4 py-3 sm:px-0"
                    >
                        <dt class="text-sm font-medium leading-6 text-gray-900">
                            Ordinarias(HHH:MM)
                        </dt>
                        <div class="flex gap-3">
                            <input
                                class="w-14 p-1 border border-gray-300 rounded"
                                type="number"
                                min="0"
                                v-model.number="form.regular_hours_0"
                            />
                            :
                            <input
                                class="w-14 p-1 border border-gray-300 rounded"
                                type="number"
                                max="59"
                                min="0"
                                v-model.number="form.regular_hours_1"
                            />
                        </div>
                    </dl>
                    <dl
                        class="flex justify-between lg:pr-4 sm:mb-0 px-4 py-3 sm:px-0"
                    >
                        <dt class="text-sm font-medium leading-6 text-gray-900">
                            SobreTiempo(HHH:MM)
                        </dt>
                        <div class="flex gap-3">
                            <input
                                class="w-14 p-1 border border-gray-300 rounded"
                                type="number"
                                min="0"
                                v-model.number="form.overtime_hours_0"
                            />
                            :
                            <input
                                class="w-14 p-1 border border-gray-300 rounded"
                                type="number"
                                max="59"
                                min="0"
                                v-model.number="form.overtime_hours_1"
                            />
                        </div>
                    </dl>
                    <dl
                        class="flex justify-between lg:pr-4 sm:mb-0 px-4 py-6 sm:px-0"
                    >
                        <dt class="text-sm font-medium leading-6 text-gray-900">
                            Total:
                        </dt>
                        <div class="flex gap-3">
                            <p class="w-14 p-1 border border-gray-300 rounded">
                                {{ totalHours.hours }}
                            </p>
                            :
                            <p class="w-14 p-1 border border-gray-300 rounded">
                                {{ totalHours.minutes }}
                            </p>
                        </div>
                    </dl>
                </div>
            </div>
            <div class="mt-6 flex gap-3 justify-end">
                <PrimaryButton type="submit"> Guardar </PrimaryButton>
            </div>
        </form>
    </div>
    <Subsidized :form="form" ref="subsidized" />
    <NotSubsidized :form="form" ref="notSubsidized" />
</template>
<script setup>
import { PencilSquareIcon } from "@heroicons/vue/24/outline";
import NotSubsidized from "./NotSubsidized.vue";
import { ref, watch, onMounted } from "vue";
import Subsidized from "./Subsidized.vue";
import { useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { notify, notifyError, notifyWarning } from "@/Components/Notification";

const { objectData } = defineProps({
    objectData: Object,
});

const { payroll_detail, employee_id } = objectData;

const subsidized = ref(null);
const notSubsidized = ref(null);
const totalHours = ref({
    hours: 0,
    minutes: 0,
});

function getInitialState(payroll_detail_work_schedule) {
    return {
        id: payroll_detail_work_schedule.id,
        payroll_detail_id: payroll_detail_work_schedule.payroll_detail_id,
        subsidized_days: payroll_detail_work_schedule.subsidized_days,
        non_subsidized_days: payroll_detail_work_schedule.non_subsidized_days,
        regular_hours_0: Number(
            payroll_detail_work_schedule.regular_hours.split(":")[0]
        ),
        regular_hours_1: Number(
            payroll_detail_work_schedule.regular_hours.split(":")[1]
        ),
        overtime_hours_0: Number(
            payroll_detail_work_schedule.overtime_hours.split(":")[0]
        ),
        overtime_hours_1: Number(
            payroll_detail_work_schedule.overtime_hours.split(":")[1]
        ),
    };
}

const form = useForm({
    id: "",
    payroll_detail_id: payroll_detail.id,
    subsidized_days: [],
    non_subsidized_days: [],
    regular_hours_0: 0,
    regular_hours_1: 0,
    overtime_hours_0: 0,
    overtime_hours_1: 0,
});

watch(
    () => [
        form.regular_hours_0,
        form.regular_hours_1,
        form.overtime_hours_0,
        form.overtime_hours_1,
    ],
    totalSum
);

function totalSum() {
    let totalMinutes = form.regular_hours_1 + form.overtime_hours_1;
    let totalHoursSum = form.regular_hours_0 + form.overtime_hours_0;

    if (totalMinutes >= 60) {
        const extraHours = Math.floor(totalMinutes / 60);
        totalMinutes = totalMinutes % 60;
        totalHoursSum += extraHours;
    }

    totalHours.value.hours = totalHoursSum;
    totalHours.value.minutes = totalMinutes;
}

function openModalSubsidized() {
    subsidized.value.toogleModal();
}

function openModalNotSubsidized() {
    notSubsidized.value.toogleModal();
}

async function submit() {
    const res = await axios.post(
        route("payroll.store.payroll.detail.work.schedule"),
        form.data()
    )
    .then(()=>notify('Jornada Laboral Guardada'))
    .catch(e=>notifyError('SERVER ERROR'));
}

onMounted(async () => {
    const res = await axios.get(
        route("payroll.show.payroll.detail.work.schedule", {
            payroll_detail_id: payroll_detail.id,
        })
    ).catch(e=>notifyWarning('Cant obtain pdws'));
    if (res.status === 200 && Object.keys(res.data).length !== 0 ) {
        const payroll_detail_work_schedule = res.data;
        form.defaults({ ...getInitialState(payroll_detail_work_schedule) });
        form.reset();
    }
});
</script>
