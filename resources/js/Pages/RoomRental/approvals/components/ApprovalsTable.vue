<template>
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
                <TableRow>{{ change.room_document.room.plate }}</TableRow>
                <TableRow>
                    <a v-if="change.ownership_card" target="_blank" :href="route('room.rental.show_approvals_document', {
                        approval_car: change.id,
                        fieldName: 'ownership_card',
                    }) + '?' + uniqueParam
                        ">
                        <ShowIcon />
                    </a>
                </TableRow>
                <TableRow>
                    <a v-if="change.technical_review" target="_blank" :href="route('room.rental.show_approvals_document', {
                        approval_car: change.id,
                        fieldName: 'technical_review',
                    }) + '?' + uniqueParam
                        ">
                        <ShowIcon />
                    </a>
                </TableRow>
                <TableRow>{{ change.technical_review_date }}</TableRow>
                <TableRow>
                    <a v-if="change.soat" target="_blank" :href="route('room.rental.show_approvals_document', {
                        approval_car: change.id,
                        fieldName: 'soat',
                    }) + '?' + uniqueParam
                        ">
                        <ShowIcon />
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
                            <AcceptIcon />
                        </button>
                        <button @click="rejected(change.id)">
                            <RejectIcon />
                        </button>
                    </div>
                </TableRow>
            </tr>
        </template>
    </TableStructure>
</template>
<script setup>
import TableStructure from '@/Layouts/TableStructure.vue';
import TableRow from '@/Components/TableRow.vue';
import TableTitle from '@/Components/TableTitle.vue';
import { notify } from '@/Components/Notification';
import { ref } from 'vue';
import { AcceptIcon, RejectIcon, ShowIcon } from '@/Components/Icons';
const { change } = defineProps({
    change: Object
})

const uniqueParam = `timestamp=${new Date().getTime()}`;
const changes = ref(change)

async function validate(approve_id) {
    let url = route('room.rental.approve.change', { id: approve_id })
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
    let url = route('room.rental.rejected.change', { id: approve_id })
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