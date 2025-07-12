<template>
    <TableStructure :info="providers">
        <template #thead>
            <tr>
                <TableTitle>RUC</TableTitle>
                <TableTitle>Compańia</TableTitle>
                <TableTitle>Contacto</TableTitle>
                <TableTitle>Numero de Cuenta</TableTitle>
                <TableTitle>Zona</TableTitle>
                <TableTitle>Dirección</TableTitle>
                <TableTitle>Telefono</TableTitle>
                <TableTitle>Email</TableTitle>
                <TableTitle>Categoria</TableTitle>
                <TableTitle>Segmento</TableTitle>
                <TableTitle v-if="auth.user.role_id == 1"></TableTitle>
            </tr>
        </template>
        <template #tbody>
            <tr v-for="item in providers.data || providers" :key="item.id">
                <TableRow>{{ item.ruc }}</TableRow>
                <TableRow>{{ item.company_name }}</TableRow>
                <TableRow>{{ item.contact_name }}</TableRow>
                <TableRow>{{ item.account_number }}</TableRow>
                <TableRow>{{ item.zone }}</TableRow>
                <TableRow :width="'w-[500px]'">{{ item.address }}</TableRow>
                <TableRow>{{ item.phone1 }} {{ item.phone2 }}</TableRow>
                <TableRow>{{ item.email }}</TableRow>
                <TableRow>{{ item.category?.name }}</TableRow>
                <TableRow :width="'w-[500px]'">
                    <p v-for="(i, index) in item.segments" :key="index" class="text-gray-900 inline">
                        {{ i.name }}<span v-if="index < item.segments.length - 1">, </span>
                    </p>
                </TableRow>
                <TableRow v-if="auth.user.role_id == 1">
                    <div class="flex space-x-3 justify-center">
                        <button type="button" @click="add_information(item)">
                            <EditIcon />
                        </button>
                        <button type="button" @click="confirmProviderDeletion(item)">
                            <DeleteIcon />
                        </button>
                    </div>
                </TableRow>
            </tr>
        </template>
    </TableStructure>
    <div v-if="providers.data"
        class="flex flex-col items-center border-t bg-white px-3 py-2 xs:flex-row xs:justify-between">
        <Pagination :links="providers.links" />
    </div>
    <ConfirmDeleteModal :confirmingDeletion="confirmingProviderDeletion" itemType="proveedor" :nameText="name"
        :deleteFunction="deleteProvider" @closeModal="closeModal" />
</template>
<script setup>
import TableStructure from '@/Layouts/TableStructure.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';
import Pagination from '@/Components/Pagination.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { ref } from 'vue';
import { notify, notifyError } from '@/Components/Notification';
import { DeleteIcon, EditIcon } from "@/Components/Icons";

const { auth, add_information } = defineProps({
    auth: Object,
    add_information: Function
})

const providers = defineModel('providers')
const confirmingProviderDeletion = ref(false);
const provider_id = ref(null);
const name = ref(null);

const confirmProviderDeletion = (provider_fun) => {
    provider_id.value = provider_fun.id;
    name.value = provider_fun.contact_name;
    confirmingProviderDeletion.value = true;
};

const closeModal = () => {
    confirmingProviderDeletion.value = false;
};

async function deleteProvider() {
    let url = route('providersmanagement.destroy', { id: provider_id.value });
    try {
        await axios.delete(url)
        updateProvider(provider_id.value, 'delete')
        closeModal()
    } catch (error) {
        notifyError(error)
    }
};

function updateProvider(provider, operation) {
    const validations = providers.value.data || providers.value;
    const index = validations.findIndex(item => item.id == provider?.id)
    if (operation === 'update') {
        validations[index] = provider
        notify('Proveedor Actualizado')
    } else if (operation === 'store') {
        validations.unshift(provider);
        notify('Proveedor Creado')
    } else if (operation === 'delete') {
        validations.splice(index, 1)
        notify('Proveedor Eliminado')
    }
}

</script>