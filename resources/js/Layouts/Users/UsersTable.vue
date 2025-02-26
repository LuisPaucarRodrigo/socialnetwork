<template>
    <TableStructure>
        <template #thead>
            <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <TableTitle>Nombre</TableTitle>
                <TableTitle>
                    <TableHeaderFilter labelClass="text-[11px]" label="Platform" :options="platforms"
                        v-model="formSearch.platform" width="w-44" />
                </TableTitle>
                <TableTitle>Email</TableTitle>
                <TableTitle>DNI</TableTitle>
                <TableTitle>Telefono</TableTitle>
                <TableTitle></TableTitle>
            </tr>
        </template>
        <template #tbody>
            <tr v-for="user in users.data || users" :key="user.id" class="text-gray-700">
                <TableRow>{{ user.name }}</TableRow>
                <TableRow>{{ user.platform }}</TableRow>
                <TableRow>{{ user.email }}</TableRow>
                <TableRow>{{ user.dni }}</TableRow>
                <TableRow>{{ user.phone }}</TableRow>
                <TableRow>
                    <div class="flex space-x-3 justify-center">
                        <button v-if="(user.platform === 'Web/Movil' || user.platform === 'Movil')
                            && !user.employee" @click="linkEmployee(user.id)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w=6 h-6 text-green-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                            </svg>
                        </button>
                        <Link class="text-blue-900 whitespace-no-wrap" :href="route('users.details', { id: user.id })">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-teal-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        </Link>
                        <Link class="text-blue-900 whitespace-no-wrap" :href="route('users.edit', { id: user.id })">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-amber-400">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                        </Link>
                        <button v-if="user.id != 1" type="button" @click="confirmUserDeletion(user.id)"
                            class="text-blue-900 whitespace-no-wrap">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6 text-red-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                        </button>
                    </div>
                </TableRow>
            </tr>
        </template>
    </TableStructure>
</template>
<script setup>
import TableTitle from '@/Components/TableTitle.vue';
import TableStructure from '../TableStructure.vue';
import TableRow from '@/Components/TableRow.vue';
import TableHeaderFilter from '@/Components/TableHeaderFilter.vue';

const { users, formSearch, platforms, linkEmployee, confirmUserDeletion } = defineProps({
    users: Object,
    formSearch: Object,
    platforms: Array,
    linkEmployee: Function,
    confirmUserDeletion: Function
})
</script>