<template>
    <div>
        <form @submit.prevent="submit">
            <h1>Aportaciones del Trabajador:</h1>
            <hr>
            <TableStructure>
                <template #thead>
                    <TableTitle>Codigo</TableTitle>
                    <TableTitle>Concepto</TableTitle>
                    <TableTitle>Base de Cálculo(S/.)</TableTitle>
                    <TableTitle>Pagado(S/.)</TableTitle>
                </template>
                <template #tbody>

                </template>
            </TableStructure>
            <h1>Aportaciones del Empleador:</h1>
            <hr>
            <TableStructure>
                <template #thead>
                    <TableTitle>Codigo</TableTitle>
                    <TableTitle>Concepto</TableTitle>
                    <TableTitle>Base de Cálculo(S/.)</TableTitle>
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