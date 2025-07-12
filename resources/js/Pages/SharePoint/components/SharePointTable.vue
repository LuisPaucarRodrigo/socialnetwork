<template>
    <TableStructure :info="datas">
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
                    <FolderIcon v-if="item.folder" />
                    <DownloadIcon v-if="item.file" />
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
</template>
<script setup>
import { FolderIcon, DownloadIcon } from '@/Components/Icons';
import TableRow from '@/Components/TableRow.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import { formattedDate } from '@/utils/utils';
const { navigationHistory, siteId, driveId, accessToken } = defineProps({
    navigationHistory: Object,
    siteId: String,
    driveId: String,
    accessToken: String,
})

const datas = defineModel('datas')

function enterFolder(folderName, folderId) {
    navigationHistory.push({ name: folderName, id: folderId });
}

async function dd(item) {
    if (item.folder) {
        try {
            const response = await axios.get(`https://graph.microsoft.com/v1.0/sites/${siteId}/drives/${driveId}/items/${item.id}/children`, {
                headers: {
                    Authorization: `Bearer ${accessToken}`
                }
            });
            enterFolder(item.name, item.id)
            datas.value = response.data;
        } catch (error) {
            console.error('Error fetching folder contents:', error);
        }
    } else if (item.file) {
        try {
            const response = await axios.get(`https://graph.microsoft.com/v1.0/sites/${siteId}/drives/${driveId}/items/${item.id}/content`, {
                headers: {
                    Authorization: `Bearer ${accessToken}`
                },
                responseType: 'blob'
            });
            const url = window.URL.createObjectURL(response.data);
            window.open(url, '_blank')
        } catch (error) {
            console.error('Error fetching folder contents:', error);
        }
    }
}

</script>