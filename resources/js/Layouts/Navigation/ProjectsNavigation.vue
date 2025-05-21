<template>
    <a
        v-if="
            cicsasubSectionsPorVencer.length +
                cicsasubSectionsPorVencer7.length >
            0
        "
        class="flex items-center mt-4 py-2 px-6 text-gray-100"
        href="#"
        @click="
            showingProyectArea =
                cicsashowingMembers && cicsashowingMembers7
                    ? false
                    : !showingProyectArea;
            cicsashowingMembers = cicsashowingMembers7 = false;
        "
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="red"
            class="w-6 h-6"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z"
            />
        </svg>
        <span class="mx-3">Area de Proyectos</span>
    </a>
    <a
        v-else
        class="flex items-center mt-4 py-2 px-6 text-gray-100"
        href="#"
        @click="showingProyectArea = !showingProyectArea"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-6 h-6"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z"
            />
        </svg>
        <span class="mx-3">Area de Proyectos</span>
    </a>
    <MyTransition
        v-if="
            subModulePermission(submodules.pclients_submodule, userSubModules)
        "
        :transitiondemonstration="showingProyectArea"
    >
        <Link class="w-full" :href="route('customers.index')">Clientes</Link>
    </MyTransition>
    <MyTransition
        v-if="subModulePermission(submodules.ppro_submodule, userSubModules)"
        :transitiondemonstration="showingProyectArea"
    >
        <Link class="w-full" :href="route('preprojects.titles')">PRO</Link>
    </MyTransition>
    <MyTransition
        v-if="
            subModulePermission(submodules.pprepint_submodule, userSubModules)
        "
        :transitiondemonstration="showingProyectArea"
    >
        <Link class="w-full" :href="route('preprojects.index', { type: 1 })"
            >Anteproyectos Pint</Link
        >
    </MyTransition>
    <MyTransition
        v-if="
            subModulePermission(submodules.pprepext_submodule, userSubModules)
        "
        :transitiondemonstration="showingProyectArea"
    >
        <Link class="w-full" :href="route('preprojects.index', { type: 2 })"
            >Anteproyectos Pext</Link
        >
    </MyTransition>
    <MyTransition
        v-if="
            subModulePermission(submodules.ppropint_submodule, userSubModules)
        "
        :transitiondemonstration="showingProyectArea"
    >
        <Link class="w-full" :href="route('projectmanagement.index')"
            >Proyectos Pint</Link
        >
    </MyTransition>
    <MyTransition
        v-if="
            subModulePermission(submodules.ppropext_submodule, userSubModules)
        "
        :transitiondemonstration="showingProyectArea"
    >
        <Link class="w-full" :href="route('projectmanagement.pext.index')"
            >Proyectos Pext</Link
        >
    </MyTransition>
    <MyTransition
        v-if="
            subModulePermission(submodules.padmexpen_submodule, userSubModules)
        "
        :transitiondemonstration="showingProyectArea"
    >
        <Link class="w-full" :href="route('monthproject.index')"
            >G. Administrativos</Link
        >
    </MyTransition>
    <MyTransition
        v-if="
            subModulePermission(submodules.pchecklist_submodule, userSubModules)
        "
        :transitiondemonstration="showingProyectArea"
    >
        <Link class="w-full" :href="route('checklist.index')"> Checklist </Link>
    </MyTransition>
    <MyTransition
        v-if="
            subModulePermission(submodules.pbacklog_submodule, userSubModules)
        "
        :transitiondemonstration="showingProyectArea"
    >
        <Link class="w-full" :href="route('project.backlog.index')">
            Backlog
        </Link>
    </MyTransition>
    <!-- <MyTransition 
        :transitiondemonstration="showingProyectArea">
        <div class="relative">
            <button @click="toggleMembersCicsa">
                <span v-if="cicsasubSectionsPorVencer.length + cicsasubSectionsPorVencer7.length > 0"
                    class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                    {{ cicsasubSectionsPorVencer.length + cicsasubSectionsPorVencer7.length }}
                </span>
            </button>
            <Link class="w-full" :href="route('member.cicsa')">Alarmas Cicsa</Link>
        </div>
    </MyTransition>

    <template v-if="cicsashowingMembers && cicsashowingMembers7">
        <div class="mb-4">
            <MyTransition v-for="item in cicsasubSectionsPorVencer" :key="item.id" class="ml-4"
                :transitiondemonstration="cicsashowingMembers">
                <Link class="w-full flex items-center" :href="route('member.cicsa.show', { subSection: item.id })">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-red-600 dark:text-red" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                    </svg>
                    <span>{{ item.name }}</span>
                </div>
                </Link>
            </MyTransition>
            <MyTransition v-for="item in cicsasubSectionsPorVencer7" :key="item.id" class="ml-4"
                :transitiondemonstration="cicsashowingMembers7">
                <Link class="w-full flex items-center" :href="route('member.cicsa.show', { subSection: item.id })">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-yellow-600 dark:text-yellow" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                    </svg>
                    <span>{{ item.name }}</span>
                </div>

                </Link>
            </MyTransition>
        </div>
    </template> -->
</template>
<script setup>
import MyTransition from "@/Components/MyTransition.vue";
import { subModulePermission } from "@/utils/roles/roles";
import { Link, usePage } from "@inertiajs/vue3";
import { onMounted, ref, onUnmounted } from "vue";

const { submodules } = usePage().props;
const { userSubModules } = usePage().props.auth;

const showingProyectArea = ref(false);
const cicsashowingMembers = ref(false);
const cicsashowingMembers7 = ref(false);
const cicsasubSectionsPorVencer = ref([]);
const cicsasubSectionsPorVencer7 = ref([]);

function toggleMembersCicsa() {
    cicsashowingMembers7.value = !cicsashowingMembers7.value;
    cicsashowingMembers.value = !cicsashowingMembers.value;
}

async function fetchCicsaSubSectionsCount() {
    try {
        const response = await axios.get(route("member.cicsa.alarm"));
        cicsasubSectionsPorVencer.value = response.data.subSections;
        cicsasubSectionsPorVencer7.value = response.data.subSections7;
    } catch (error) {
        console.error("Error al obtener el contador de subsecciones:", error);
    }
}

let intervalId;
const fetchAllAlarms = () => {
    return Promise.all([
        //fetchCicsaSubSectionsCount()
    ]);
};
onMounted(() => {
    fetchAllAlarms();
    intervalId = setInterval(fetchAllAlarms, 60000);
});
onUnmounted(() => {
    clearInterval(intervalId);
});
</script>
