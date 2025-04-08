<template>
    <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#" @click="showDocs = !showDocs">
        <svg v-if="archiveAlarms.length + archiveAlarms7.length > 0" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" stroke-width="1.5" stroke="red" class="w-6 h-6 text-white">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776" />
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="w-6 h-6 text-white">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776" />
        </svg>

        <span class="mx-3">Gestion Documentaria</span>
    </a>
    <MyTransition :transitiondemonstration="showDocs">
        <div class="relative">
            <button @click="toogleArchives"><span v-if="archiveAlarms.length + archiveAlarms7.length > 0"
                    class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                    {{ archiveAlarms.length + archiveAlarms7.length }}</span>
            </button>
            <Link class="w-full" :href="route('documment.management.folders')">CCIP</Link>
        </div>
    </MyTransition>

    <template v-if="showArchivesAlarms">
        <div class="mb-4">
            <MyTransition v-for="item in archiveAlarms" :key="item.id" class="ml-4"
                :transitiondemonstration="showArchivesAlarms">
                <Link class="w-full flex items-center"
                    :href="route('archives.show', { folder: item.archive.folder_id })">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-red-600 dark:text-red" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                    </svg>
                    <span>{{ item.archive.folder.name + '/' + item.archive.name + '.' +
                        item.archive.extension }}</span>
                </div>
                </Link>
            </MyTransition>
            <MyTransition v-for="item in archiveAlarms7" :key="item.id" class="ml-4"
                :transitiondemonstration="showArchivesAlarms">
                <Link class="w-full flex items-center"
                    :href="route('archives.show', { folder: item.archive.folder_id })">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-yellow-600 dark:text-yellow" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                    </svg>
                    <span>{{ item.archive.folder.name + '/' + item.archive.name + '.' +
                        item.archive.extension }}</span>
                </div>
                </Link>
            </MyTransition>
        </div>
    </template>
    <MyTransition :transitiondemonstration="showDocs">
        <div class="relative">
            <Link class="w-full" :href="route('local.drive.index', { root: 1 })">Local Drive</Link>
        </div>
    </MyTransition>
    <MyTransition v-if="auth.user.role_id === 1" :transitiondemonstration="showDocs">
        <Link class="w-full" :href="route('documment.management.folders.validation')">Aprobación</Link>
    </MyTransition>

</template>

<script setup>
import MyTransition from '@/Components/MyTransition.vue';
import { Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const { userPermissions, auth } = defineProps({
    userPermissions: Array,
    auth: Object
})
const showDocs = ref(false)
const archiveAlarms = ref(false)
const archiveAlarms7 = ref(false)
const showArchivesAlarms = ref(false)

function hasPermission(permission) {
    return userPermissions.includes(permission)
}

async function fetchArchiveRequest(params) {
    try {
        const response = await axios.get(route('archives.alarms'));
        archiveAlarms.value = Object.values(response.data.alarms3);
        archiveAlarms7.value = Object.values(response.data.alarms7);
    } catch (error) {
        console.error('Error al obtener el contador de archivos:', error);
    }
}

function toogleArchives() {
    showArchivesAlarms.value = !showArchivesAlarms.value;
}

onMounted(() => {
    if (hasPermission('DocumentGestion')) {
        fetchArchiveRequest();
        setInterval(() => {
            fetchArchiveRequest();
        }, 60000);
    }
})
</script>