<template>
    <div>
        <h1>Ingresos:</h1>
        <hr>
        <form @submit.prevent="submit">
            <TableStructure>
                <template #thead>
                    <TableTitle>Codigo</TableTitle>
                    <TableTitle>Concepto</TableTitle>
                    <TableTitle>Desvengado(S/.)</TableTitle>
                    <TableTitle>Pagado(S/.)</TableTitle>
                </template>
                <template #tbody>

                </template>
            </TableStructure>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <PrimaryButton type="submit">
                    Guardar
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>
<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableStructure from '@/Layouts/TableStructure.vue';

async function submit(){

}
</script>