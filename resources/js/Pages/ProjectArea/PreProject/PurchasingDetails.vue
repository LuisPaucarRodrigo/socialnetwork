    <template>
    <Head title="Solicitudes" />
    <AuthenticatedLayout :redirectRoute=" { route: 'preprojects.request.index', params: { id: details.preproject.id } } ">
        <template #header>
            {{ details.code }}
        </template>
        <div class="mt-6 border-t border-gray-100">
            <dl class="divide-y divide-gray-100">
                <div v-if="details.project" class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Proyecto</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.project.name }}
                    </dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Solicitud</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.title }}</dd>
                </div>
                <div v-if="details.due_date" class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Fecha Limite de Compra</dt>
                    <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0">
                        <span
                            :class="`uppercase inline-flex items-center gap-x-1 py-0 px-3 text-sm rounded-full font-medium ${setBadgeColor(details.due_date, details.state)} text-white`">
                            {{ formattedDate(details.due_date) }}
                        </span>
                    </dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Estado</dt>
                    <dd class="mt-1 text-sm leading-6 sm:col-span-2 sm:mt-0">
                        <span
                            class="uppercase inline-flex items-center gap-x-1 py-0 px-3 text-sm rounded-full font-medium ">
                            {{ details.state }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>

        <div class="grid grid-cols-6 mt-2">
            <div class="col-span-6 xl:col-span-4 overflow-x-auto mt-8 ring-1 ring-gray-300 rounded-lg shadow-sm">
                <table class="w-full">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                #
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Código
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Nombre del producto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cantidad
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Unidad
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in (details.products)" :key="index"
                            class="text-gray-700 hover:bg-gray-200 bg-white">
                            <td class="border-b border-gray-200  px-5 py-5 text-sm">
                                <p>
                                    {{ index + 1 }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                <p>
                                    {{ item.code }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                <p class="text-gray-900">
                                    {{ item.name }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                <p class="text-gray-900">
                                    {{ item.pivot?.quantity }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                <p class="text-gray-900">
                                    {{ item.unit }}
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { formattedDate } from '@/utils/utils';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    details: Object
});

function setBadgeColor(date, state) {
    const fechaString = date;
    const [year, month, day] = fechaString.split('-');
    const fecha = new Date(year, month - 1, day);
    const hoy = new Date()
    hoy.setUTCHours(hoy.getUTCHours() - 5)
    hoy.setHours(0, 0, 0, 0);
    const diferenciaEnMilisegundos = fecha - hoy;
    const diferenciaEnDias = Math.floor(diferenciaEnMilisegundos / (1000 * 60 * 60 * 24));
    if (state !== 'Completada') {
        if (diferenciaEnDias <= 3) {
            return 'bg-red-600'
        } else if (4 <= diferenciaEnDias && diferenciaEnDias <= 7) {
            return 'bg-yellow-500'
        } else {
            return 'bg-indigo-500'
        }
    } else {
        return 'bg-green-500'
    }
}




</script>