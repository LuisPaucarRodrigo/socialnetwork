<template>
    <TableStructure :style="'h-[72vh]'" :info="users">
        <template #thead>
            <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <TableTitle>Nombre</TableTitle>
                <TableTitle>
                    <TableHeaderFilter labelClass="text-[11px]" label="Platform" :options="platforms"
                        v-model="formSearch.platform" width="w-44" />
                </TableTitle>
                <TableTitle>Rol</TableTitle>
                <TableTitle>Email</TableTitle>
                <TableTitle>DNI</TableTitle>
                <TableTitle>Telefono</TableTitle>
                <TableTitle v-permission-or="[
                    'see_user',
                    'edit_user',
                    'delete_user',
                    'link_user',
                ]"></TableTitle>
            </tr>
        </template>
        <template #tbody>
            <tr v-for="user in users.data || users" :key="user.id" class="text-gray-700">
                <TableRow>{{ user.name }}</TableRow>
                <TableRow>{{ user.platform }}</TableRow>
                <TableRow>{{ user.role?.name }}</TableRow>
                <TableRow>{{ user.email }}</TableRow>
                <TableRow>{{ user.dni }}</TableRow>
                <TableRow>{{ user.phone }}</TableRow>
                <TableRow v-permission-or="[
                    'see_user',
                    'edit_user',
                    'delete_user',
                ]">
                    <div class="flex space-x-3 justify-center">
                        <button v-permission="'link_user'" v-if="(user.platform === 'Web/Movil' || user.platform === 'Movil')
                            && !user.employee" @click="linkEmployee(user.id)">
                            <LinkIcon />
                        </button>
                        <Link v-permission="'see_user'" class="text-blue-900 whitespace-no-wrap"
                            :href="route('users.details', { id: user.id })">
                        <ShowIcon />
                        </Link>
                        <Link v-permission="'edit_user'" class="text-blue-900 whitespace-no-wrap"
                            :href="route('users.edit', { id: user.id })">
                        <EditIcon />
                        </Link>
                        <button v-permission="'delete_user'" type="button" @click="confirmUserDeletion(user.id)">
                            <DeleteIcon />
                        </button>
                    </div>
                </TableRow>
            </tr>
        </template>
    </TableStructure>
    <div v-if="users.data"
        class="flex flex-col items-center border-t bg-white px-5 py-3 xs:flex-row xs:justify-between">
        <PaginationAxios :links="users.links" @navigate="fetchExpensesByUrl" />
    </div>
</template>
<script setup>
import TableTitle from '@/Components/TableTitle.vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import TableRow from '@/Components/TableRow.vue';
import TableHeaderFilter from '@/Components/TableHeaderFilter.vue';
import { Link } from '@inertiajs/vue3';
import { notify, notifyError } from '@/Components/Notification';
import { ShowIcon, EditIcon, DeleteIcon, LinkIcon } from '@/Components/Icons';
import PaginationAxios from '@/Components/PaginationAxios.vue';

const { formSearch, platforms, confirmUserDeletion } = defineProps({
    formSearch: Object,
    platforms: Array,
    confirmUserDeletion: Function
})

const users = defineModel('users')
const loading = defineModel('loading')

async function linkEmployee(id) {
    let url = route('users.linkEmployee', { user: id })
    try {
        let response = await axios.get(url)
        updateFrontEnd("updateRelations", response.data)
    } catch (error) {
        console.error("Error al vincular empleado:", error);
        if (error.response) {
            if (error.response.status === 404) {
                notifyError("No se encontró un empleado para vincular.");
            } else if (error.response.status === 500) {
                notifyError("Error en el servidor. Inténtalo más tarde.");
            } else {
                notifyError(`Error ${error.response.status}: ${error.response.data}`);
            }
        } else {
            notifyError("Error inesperado. Verifica tu conexión.");
        }
    }
}

function updateFrontEnd(action, data) {
    const validations = users.data || users
    if (action === "updateRelations") {
        const index = validations.findIndex(item => item.dni === data.dni)
        validations[index].employee = data
        notify(`El vínculo se realizó correctamente con ${data.name}`);
    }
}

async function fetchExpensesByUrl(url) {
    loading.value = true;
    try {
        const res = await axios.get(url);
        users.value = res.data;
    } catch (err) {
        console.error(err);
    } finally {
        loading.value = false;
    }
}
</script>