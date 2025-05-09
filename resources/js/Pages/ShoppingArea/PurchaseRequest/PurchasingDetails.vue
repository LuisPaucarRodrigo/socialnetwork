    <template>

        <Head title="Solicitudes" />
        <AuthenticatedLayout
            :redirectRoute="boolean ? { route: 'projectmanagement.purchases_request.index', params: { id: details.project.id } } : 'purchasesrequest.index'">
            <template #header>
                {{ details.code }}
            </template>
            <div class="mt-6 border-t border-gray-100">
                <dl class="divide-y divide-gray-100">
                    <div v-if="details.project" class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Proyecto</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.project.name
                        }}
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
            <TableStructure>
                <template #thead>
                    <tr>
                        <TableTitle>#</TableTitle>
                        <TableTitle>Código</TableTitle>
                        <TableTitle>Nombre del producto</TableTitle>
                        <TableTitle>Cantidad</TableTitle>
                        <TableTitle>Unidad</TableTitle>
                    </tr>
                </template>
                <template #tbody>
                    <tr v-for="(item, index) in (details.products)" :key="index">
                        <TableRow>{{ index + 1 }}</TableRow>
                        <TableRow>{{ item.code }}</TableRow>
                        <TableRow>{{ item.name }}</TableRow>
                        <TableRow>{{ item.pivot?.quantity }}</TableRow>
                        <TableRow>{{ item.unit }}</TableRow>
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
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    details: Object,
    boolean: {
        type: Boolean,
        required: false
    }
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