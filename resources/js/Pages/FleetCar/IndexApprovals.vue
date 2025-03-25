<template>

    <Head title="Gestion de Aprobaciones" />
    <AuthenticatedLayout redirectRoute="fleet.cars.index">
        <Toaster richColors />
        <template #header> Aprobaciones de cambios de documentos </template>
        <div class="w-full">
            <TableStructure>
                <template #thead>
                    <tr>
                        <TableTitle>Placa</TableTitle>
                        <TableTitle>Tarjeta de Propiedad</TableTitle>
                        <TableTitle>Revision Tecnica</TableTitle>
                        <TableTitle>Fecha de Revision Tecnica</TableTitle>
                        <TableTitle>SOAT</TableTitle>
                        <TableTitle>Fecha de SOAT</TableTitle>
                        <TableTitle>Seguro</TableTitle>
                        <TableTitle>Fecha de Seguro</TableTitle>
                        <TableTitle>Direccion Web</TableTitle>
                        <TableTitle>Usuario</TableTitle>
                        <TableTitle>Password</TableTitle>
                        <TableTitle>Acciones</TableTitle>
                    </tr>
                </template>

                <template #tbody>
                    <tr v-for="change in changes">
                        <TableRow>{{ change.car_document.car.plate }}</TableRow>
                        <TableRow>
                            <a v-if="change.ownership_card" target="_blank" :href="route('fleet.cars.show_documents', {
                                car_document: change.id,
                                fieldName: 'ownership_card',
                            }) + '?' + uniqueParam
                                ">
                                <EyeIcon class="w-5 h-5 text-green-600" />
                            </a>
                        </TableRow>
                        <TableRow>
                            <a v-if="change.technical_review" target="_blank" :href="route('fleet.cars.show_documents', {
                                car_document: change.id,
                                fieldName: 'technical_review',
                            }) + '?' + uniqueParam
                                ">
                                <EyeIcon class="w-5 h-5 text-green-600" />
                            </a>
                        </TableRow>
                        <TableRow>{{ change.technical_review_date }}</TableRow>
                        <TableRow>
                            <a v-if="change.soat" target="_blank" :href="route('fleet.cars.show_documents', {
                                car_document: change.id,
                                fieldName: 'soat',
                            }) + '?' + uniqueParam
                                ">
                                <EyeIcon class="w-5 h-5 text-green-600" />
                            </a>
                        </TableRow>
                        <TableRow>{{ change.soat_date }}</TableRow>
                        <TableRow>{{ change.insurance }}</TableRow>
                        <TableRow>{{ change.insurance_date }}</TableRow>
                        <TableRow>{{ change.address_web }}</TableRow>
                        <TableRow>{{ change.user }}</TableRow>
                        <TableRow>{{ change.password }}</TableRow>
                        <TableRow>
                            <div class="flex gap-x-2 justify-center">
                                <button @click="validate(change.id)">
                                    <CheckCircleIcon class="w-5 h-5 text-green-500" />
                                </button>
                                <button @click="rejected(change.id)">
                                    <XCircleIcon class="w-5 h-5 text-red-500" />
                                </button>
                            </div>
                        </TableRow>
                    </tr>
                </template>
            </TableStructure>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import { notify } from '@/Components/Notification';
import TableRow from '@/Components/TableRow.vue';
import TableTitle from '@/Components/TableTitle.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import { CheckCircleIcon, EyeIcon, XCircleIcon } from '@heroicons/vue/24/outline';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Toaster } from 'vue-sonner';

const { change } = defineProps({
    change: Object
})
const changes = ref(change)

async function validate(approve_id) {
    let url = route('fleet.cars.approve.change', { id: approve_id })
    try {
        await axios.get(url)
        let validations = changes.value
        changes.value = validations.filter(item => item.id !== approve_id);
        notify("Validaciòn exitosa")
    } catch (error) {
        console.log(error)
    }
}

async function rejected(approve_id) {
    let url = route('fleet.cars.rejected.change', { id: approve_id })
    try {
        await axios.get(url)
        let validations = changes.value
        let index = validations.findIndex(item => item.id == approve_id)
        validations.splice(index, 1)
        notify("Eliminaciòn exitosa")
    } catch (error) {
        console.log(error)
    }
}
</script>