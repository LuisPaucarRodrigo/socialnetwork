<template>
    <Head title="Checklist" />

    <AuthenticatedLayout
        :redirectRoute="'checklist.index'"
    >
        <template #header> Checklist </template>

        <div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <!-- Card 1 -->
                <Link :href="route('checklist.car.index')">
                    <button
                        class="bg-white p-4 rounded-md shadow-md text-left h-full w-full"
                    >
                        <div>
                            <h3 class="text-lg font-semibold">
                                Checklist Vehicular
                            </h3>
                            <div class="mb-2">
                                <!-- Icono de Heroicons para Vue -->
                                <svg
                                    class="w-8"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M2 14.803v6.447c0 .414.336.75.75.75h1.614a.75.75 0 0 0 .74-.627L5.5 19h13l.395 2.373a.75.75 0 0 0 .74.627h1.615a.75.75 0 0 0 .75-.75v-6.447a5.954 5.954 0 0 0-1-3.303l-.78-1.17a1.994 1.994 0 0 1-.178-.33h.994a.75.75 0 0 0 .671-.415l.25-.5A.75.75 0 0 0 21.287 8H19.6l-.31-1.546a2.5 2.5 0 0 0-1.885-1.944C15.943 4.17 14.141 4 12 4c-2.142 0-3.943.17-5.405.51a2.5 2.5 0 0 0-1.886 1.944L4.399 8H2.714a.75.75 0 0 0-.67 1.085l.25.5a.75.75 0 0 0 .67.415h.995a1.999 1.999 0 0 1-.178.33L3 11.5c-.652.978-1 2.127-1 3.303zm15.961-4.799a4 4 0 0 0 .34.997H5.699c.157-.315.271-.65.34-.997l.632-3.157a.5.5 0 0 1 .377-.39C8.346 6.157 10 6 12 6c2 0 3.654.156 4.952.458a.5.5 0 0 1 .378.389l.631 3.157zM5.5 16a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM20 14.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"
                                        fill="rgb(59 130 246)"
                                    />
                                </svg>
                            </div>
                        </div>
                    </button>
                </Link>
                <Link href="#">
                    <!-- :href=""route('tu.ruta') -->
                    <button
                        class="bg-white p-4 rounded-md shadow-md text-left h-full w-full"
                    >
                        <div>
                            <h3 class="text-lg font-semibold">
                                Checklist de Herramientas
                            </h3>
                            <div class="mb-2">
                                <!-- Icono de Heroicons para Vue -->
                                <WrenchScrewdriverIcon
                                    class="h-7 w-7 text-blue-500"
                                />
                            </div>
                        </div>
                    </button>
                </Link>
                <Link :href="route('checklist.dailytoolkit.index')">
                    <button
                        class="bg-white p-4 rounded-md shadow-md text-left h-full w-full"
                    >
                        <div>
                            <h3 class="text-lg font-semibold">
                                Checklist Diario de Herramientas
                            </h3>
                            <div class="mb-2">
                                <WrenchIcon class="h-7 w-7 text-blue-500" />
                            </div>
                        </div>
                    </button>
                </Link>
                <Link href="#">
                    <button
                        class="bg-white p-4 rounded-md shadow-md text-left h-full w-full"
                    >
                        <div>
                            <h3 class="text-lg font-semibold">
                                Checklist EPPS
                            </h3>
                            <div class="mb-2">
                                <svg
                                    fill="rgb(59 130 246)"
                                    class="w-8"
                                    version="1.1"
                                    id="Layer_1"
                                    xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                    viewBox="0 0 503.322 503.322"
                                    xml:space="preserve"
                                >
                                    <g>
                                        <g>
                                            <g>
                                                <path
                                                    d="M494.644,355.797h-17.538c-4.686,0-8.496-3.801-8.496-8.496v-30.199c0-40.552-11.221-79.473-30.894-113.143
				l-104.969,38.487c-0.972,0.364-1.979,0.538-2.985,0.538c-2.195,0-4.365-0.842-6.005-2.412c-2.395-2.3-3.272-5.753-2.265-8.912
				l34.825-108.804c-11.169-6.404-22.892-11.95-35.233-16.289v-2.907c0-14.093-11.464-25.557-25.557-25.557h-35.189v121.422
				c0,4.799-3.888,8.678-8.678,8.678c-4.79,0-8.678-3.879-8.678-8.678V78.102h-35.189c-14.093,0-25.557,11.464-25.557,25.557v2.829
				c-13.286,4.495-25.852,10.388-37.775,17.243l37.298,107.737c1.076,3.124,0.295,6.595-2.031,8.938
				c-1.658,1.675-3.888,2.577-6.17,2.577c-0.92,0-1.84-0.148-2.742-0.451L62.75,206.51c-17.842,31.779-28.039,68.053-28.039,105.897
				v34.894c0,4.695-3.81,8.496-8.496,8.496H8.678c-4.79,0-8.678,3.879-8.678,8.678v52.068c0,4.799,3.888,8.678,8.678,8.678h485.966
				c4.79,0,8.678-3.879,8.678-8.678v-52.068C503.322,359.676,499.434,355.797,494.644,355.797z M399.186,373.153H104.136
				c-4.79,0-8.678-3.879-8.678-8.678s3.888-8.678,8.678-8.678h295.051c4.79,0,8.678,3.879,8.678,8.678
				S403.977,373.153,399.186,373.153z"
                                                />
                                                <path
                                                    d="M129.393,133.243c-22.641,15.646-42.053,35.415-57.309,58.09l87.517,29.175L129.393,133.243z"
                                                />
                                                <path
                                                    d="M428.189,188.974c-15.325-22.077-34.556-41.272-56.711-56.554l-28.039,87.63L428.189,188.974z"
                                                />
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </button>
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import {
    WrenchIcon,
    WrenchScrewdriverIcon,
    PaperAirplaneIcon,
    ClipboardDocumentCheckIcon,
} from "@heroicons/vue/24/solid";

const props = defineProps({
    userPermissions: Array,
});

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
};
</script>
