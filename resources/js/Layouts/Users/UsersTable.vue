<template>
    <TableStructure :style="'h-[72vh]'">
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
                    'link_employee',
                    'users_details',
                    'user_edit',
                    'user_delete',
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
                    'link_employee',
                    'users_details',
                    'user_edit',
                    'user_delete',
                ]">
                    <div class="flex space-x-3 justify-center">
                        <button v-permissions="'link_employee'" v-if="(user.platform === 'Web/Movil' || user.platform === 'Movil')
                            && !user.employee" @click="linkEmployee(user.id)">
                            <LinkIcon class="w=6 h-6 text-green-500" />
                        </button>
                        <Link  v-permissions="'users_details'" class="text-blue-900 whitespace-no-wrap" :href="route('users.details', { id: user.id })">
                        <EyeIcon class="w-6 h-6 text-teal-500" />
                        </Link>
                        <Link  v-permissions="'user_edit'" class="text-blue-900 whitespace-no-wrap" :href="route('users.edit', { id: user.id })">
                        <PencilSquareIcon class="w-5 h-5 text-yellow-400" />
                        </Link>
                        <button v-permissions="'user_delete'" type="button" @click="confirmUserDeletion(user.id)">
                            <TrashIcon class="w-5 h-5 text-red-500" />
                        </button>
                    </div>
                </TableRow>
            </tr>
        </template>
    </TableStructure>
    <div v-if="users.data"
        class="flex flex-col items-center border-t bg-white px-5 py-3 xs:flex-row xs:justify-between">
        <pagination :links="users.links" />
    </div>
</template>
<script setup>
import TableTitle from '@/Components/TableTitle.vue';
import TableStructure from '../TableStructure.vue';
import TableRow from '@/Components/TableRow.vue';
import TableHeaderFilter from '@/Components/TableHeaderFilter.vue';
import { Link } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import { notify, notifyError } from '@/Components/Notification';
import { EyeIcon, PencilSquareIcon, TrashIcon, LinkIcon } from '@heroicons/vue/24/outline';

const { users, formSearch, platforms } = defineProps({
    users: Object,
    formSearch: Object,
    platforms: Array,
})



const usersToDelete = defineModel('usersToDelete')
const confirmingUserDeletion = defineModel('confirmingUserDeletion')

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

const confirmUserDeletion = (userId) => {
    confirmingUserDeletion.value = true;
    usersToDelete.value = userId;
};

function updateFrontEnd(action, data) {
    const validations = users.value.data || users.value
    if (action === "updateRelations") {
        const index = validations.findIndex(item => item.dni === data.dni)
        validations[index].employee = data
        notify(`El vínculo se realizó correctamente con ${data.name}`);
    }
}
</script>