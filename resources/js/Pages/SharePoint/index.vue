<template>

    <Head title="SharePoint" />
    <AuthenticatedLayout :redirectRoute="'sharepoint.index'">
        <template #header>
            SharePoint
        </template>
        <div>
            <span v-for="(item, index) in navigationHistory" :key="index">
                <a href="#" @click.prevent="goToFolder(index)" class="hover:text-blue-500">
                    {{ item.name }}
                </a>
                <span v-if="index < navigationHistory.length - 1"> / </span>
            </span>
        </div>
        <SharePointTable v-model:datas="datas" :navigationHistory="navigationHistory"
        :siteId="siteId" :driveId="driveId" :accessToken="accessToken"/>

    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import SharePointTable from './components/SharePointTable.vue';

const { siteId, driveId, accessToken, data } = defineProps({
    siteId: String,
    driveId: String,
    accessToken: String,
    data: Object
})
const datas = ref(data)

const navigationHistory = ref([
    { name: 'Documentos', id: siteId }
]);

function goToFolder(index) {
    navigationHistory.value = navigationHistory.value.slice(0, index + 1);
    const item = navigationHistory.value[index];
    fetchFolderContent(item);
}

async function fetchFolderContent(item) {
    let isMain = item.name === "Documentos" ? `root` : `items/${item.id}`
    try {
        const response = await axios.get(`https://graph.microsoft.com/v1.0/sites/${siteId}/drives/${driveId}/${isMain}/children`, {
            headers: {
                Authorization: `Bearer ${accessToken}`
            }
        });
        datas.value = response.data;
    } catch (error) {
        console.error('Error fetching folder contents:', error);
    }
}
</script>