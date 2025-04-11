<template>

    <Head title="SharePoint" />
    <AuthenticatedLayout :redirectRoute="'sharepoint.index'">
        <template #header>
            SharePoint
        </template>
        <TableStructure>
            <template #thead>
                <tr>
                    <TableTitle></TableTitle>
                    <TableTitle>Nombre</TableTitle>
                    <TableTitle>Modificado</TableTitle>
                    <TableTitle>Modificado por</TableTitle>
                    <TableTitle>Creacion</TableTitle>
                </tr>
            </template>
            <template #tbody>
                <tr v-for="item in datas.value">
                    <TableRow>
                        <FolderIcon v-if="item.folder" class="h-6 w-6 text-blue-600" />
                        <DocumentIcon v-if="item.file" class="h-6 w-6 text-red-600" />
                    </TableRow>
                    <TableRow>
                        <button @click="dd(item)" class="hover:text-blue-500">
                            {{ item.name }}
                        </button>
                    </TableRow>
                    <TableRow>{{ formattedDate(item.lastModifiedDateTime) }}</TableRow>
                    <TableRow>{{ item.lastModifiedBy.user.displayName }}</TableRow>
                    <TableRow>{{ formattedDate(item.createdDateTime) }}</TableRow>
                </tr>
            </template>
        </TableStructure>
    </AuthenticatedLayout>
</template>
<script setup>
import TableRow from '@/Components/TableRow.vue';
import TableTitle from '@/Components/TableTitle.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import { formattedDate } from '@/utils/utils';
import { DocumentIcon, FolderIcon } from '@heroicons/vue/24/outline';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const { siteId, driveId, accessToken, data } = defineProps({
    siteId: String,
    driveId: String,
    accessToken: String,
    data: Object
})
const datas = ref(data)
console.log(datas.value)
async function dd(item) {
    if (item.folder) {
        try {
            const response = await axios.get(`https://graph.microsoft.com/v1.0/sites/${siteId}/drives/${driveId}/items/${item.id}/children`, {
                headers: {
                    Authorization: `Bearer ${accessToken}`
                }
            });
            datas.value = response.data;
        } catch (error) {
            console.error('Error fetching folder contents:', error);
        }
    } else if (item.file) {
        console.log("archivo")
    }
}
</script>