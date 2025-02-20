<template>

    <Head title="Gestion de Aprovaciones" />
    <AuthenticatedLayout redirectRoute="fleet.cars.index.approvel">
        <Toaster richColors />
        <template #header> Aprovaciones de cambios de documentos </template>
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
                        <TableRow>{{ change.ownership_card }}</TableRow>
                        <TableRow>{{ change.technical_review }}</TableRow>
                        <TableRow>{{ change.technical_review_date }}</TableRow>
                        <TableRow>{{ change.soat }}</TableRow>
                        <TableRow>{{ change.soat_date }}</TableRow>
                        <TableRow>{{ change.insurance }}</TableRow>
                        <TableRow>{{ change.insurance_date }}</TableRow>
                        <TableRow>{{ change.address_web }}</TableRow>
                        <TableRow>{{ change.user }}</TableRow>
                        <TableRow>{{ change.password }}</TableRow>
                        <TableRow>
                            <div class="flex gap-x-2 justify-center">
                                <button @click="validate(change.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                                <button @click="rejected(change.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
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