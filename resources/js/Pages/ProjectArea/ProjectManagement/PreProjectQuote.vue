<template>
    <Head title="Proyectos" />
    <AuthenticatedLayout :redirect-route="'preprojects.index'">
        <template v-if="preproject.quote" #header>
            Editando la cotización
        </template>
        <template v-else #header>
            Creación de cotización
        </template>
        <div v-if="auth.user.role_id === 1 && preproject.quote?.state" class="inline-flex items-center p-2 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300" role="alert">
          <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
          </svg>
          <span class="sr-only">Info</span>
          <div>
            <span class="font-small">Esta cotización ya fue aceptada, cuidado al modificarla</span> 
          </div>
        </div>
        <div class="min-w-full p-3 rounded-lg shadow">
            <form @submit.prevent="submit">
                <div class="pt-1">
                    <div class="border-b border-gray-900 pb-12">

                        <h2 class="text-base font-semibold leading-7 text-gray-900 border-b border-gray-900/10">Cotización
                        </h2>
                        <br>
                        <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">

                            <div class="sm:col-span-3">
                                <InputLabel for="name" class="font-medium leading-6 text-gray-900">Nombre del proyecto
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput required type="text" v-model="form.name" id="name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.name" />
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel for="date" class="font-medium leading-6 text-gray-900">Fecha de Inicio
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="Date" v-model="form.date" id="date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.date" />
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel for="supervisor" class="font-medium leading-6 text-gray-900">Supervisor
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput required type="text" v-model="form.supervisor" id="supervisor"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.supervisor" />
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel for="boss" class="font-medium leading-6 text-gray-900">Jefe
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput required type="text" v-model="form.boss" id="boss"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.boss" />
                                </div>
                            </div>


                            <div class="sm:col-span-3">
                                <InputLabel for="deliverable_time" class="font-medium leading-6 text-gray-900">Tiempo de
                                    entrega (días)
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput required type="number" v-model="form.deliverable_time" id="deliverable_time"
                                        min="1"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.deliverable_time" />
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel for="validity_time" class="font-medium leading-6 text-gray-900">Tiempo de
                                    validez (días)
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput required type="number" v-model="form.validity_time" id="validity_time"
                                        min="1"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.validity_time" />
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <InputLabel for="rev" class="font-medium leading-6 text-gray-900">Rev.
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput required type="number" v-model="form.rev" id="rev" min="1"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.rev" />
                                </div>
                            </div>


                            <div class="sm:col-span-3">
                                <InputLabel for="observations" class="font-medium leading-6 text-gray-900">Observaciones
                                </InputLabel>
                                <div class="mt-2">
                                    <textarea type="text" v-model="form.observations" id="observations"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.observations" />
                                </div>
                            </div>



                            <div class="col-span-1 sm:col-span-6 xl:col-span-4 mt-4">
                                <div class="flex gap-2 items-center">
                                    <h2 class="text-base font-bold leading-6 text-gray-900 ">Valorización
                                    </h2>
                                    <button v-if="auth.user.role_id === 1 || preproject.quote === null" @click="showToAddItem" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="text-blue-500 hover:text-purple-500 w-7 h-7">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M7.5 7.5h-.75A2.25 2.25 0 0 0 4.5 9.75v7.5a2.25 2.25 0 0 0 2.25 2.25h7.5a2.25 2.25 0 0 0 2.25-2.25v-7.5a2.25 2.25 0 0 0-2.25-2.25h-.75m-6 3.75 3 3m0 0 3-3m-3 3V1.5m6 9h.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-.75" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- <div class="mt-2" v-if="project">
                                    <div v-for="(member, index) in form.employees" :key="index"
                                        class="grid grid-cols-8 items-center my-2">
                                        <p class=" text-sm col-span-7 line-clamp-2">{{ member.name }} {{
                                            member.lastname }}: {{ member.pivot.charge }} </p>
                                        <button type="button" @click="deleteAlreadyItem(member.pivot.id, index)"
                                            class="col-span-1 flex justify-end">
                                            <TrashIcon class=" text-red-500 h-4 w-4 " />
                                        </button>
                                        <div class="border-b col-span-8 border-gray-900/10">
                                        </div>
                                    </div>
                                </div> -->
                                <div class="mt-2">
                                    <div class="overflow-x-auto mt-8">
                                        <table class="w-full">
                                            <thead>
                                                <tr
                                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Partida
                                                    </th>
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Descripción
                                                    </th>
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Unidad
                                                    </th>
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Días
                                                    </th>
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Metrado
                                                    </th>
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Valor unitario
                                                    </th>
                                                    <th
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Valor total
                                                    </th>
                                                    <th v-if="auth.user.role_id === 1 || preproject.quote === null"
                                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                        Acciones
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item, index) in (form.items)" :key="index"
                                                    class="text-gray-700">
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p>
                                                            {{ index }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p>
                                                            {{ item.description }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p class="text-gray-900">
                                                            {{ item.unit }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p class="text-gray-900">{{ item.days }}</p>
                                                    </td>
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p class="text-gray-900">{{ item.quantity }}</p>
                                                    </td>
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p class="text-gray-900">S/.{{ item.unit_price }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p class="text-gray-900">S/.{{
                                                            (item.unit_price * item.quantity).toFixed(2) }}</p>
                                                    </td>
                                                    <td
                                                        class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <div v-if="auth.user.role_id === 1 || preproject.quote === null" class="flex justify-end">
                                                            <button  type="button"
                                                                @click=" preproject.quote ? deleteAlreadyItem(item.id, index) : deleteItem(index)"
                                                                class="col-span-1 flex justify-end">
                                                                <TrashIcon class=" text-red-500 h-4 w-4 " />
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 flex items-center justify-end gap-x-6">
                        <a v-if="preproject.quote" :href="route('preprojects.pdf', { preproject: preproject.id })"
                            target="_blank" rel="noopener noreferrer"
                            class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Exportar a PDF
                        </a>
                    
                        <PrimaryButton v-if="preproject.quote && !preproject.quote.state" type="button" @click="acceptCotization" :class="{ 'opacity-25': form.processing }"
                        class="rounded-md bg-green-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:green-indigo-600">Aceptar Cotización</PrimaryButton>

                        <div v-if="auth.user.role_id === 1 || preproject.quote === null ">
                            <button  type="submit" :class="{ 'opacity-25': form.processing }"
                            class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
                        </div>
<!--                     
                        <button v-if=" !preproject.quote?.state" type="submit" :class="{ 'opacity-25': form.processing }"
                        class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button> -->
                </div>

            </form>

            <Modal :show="showModalMember">
                <form class="p-6" @submit.prevent="addItem">
                    <h2 class="text-lg font-medium text-gray-900">
                        Agregar un item a la valorización
                    </h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">

                        <div class="sm:col-span-3">
                            <InputLabel for="description" class="font-medium leading-6 text-gray-900">Descripción
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput required type="text" v-model="itemToAdd.description"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="unit" class="font-medium leading-6 text-gray-900">Unidad
                            </InputLabel>
                            <div class="mt-2">
                                <select required id="unit" v-model="itemToAdd.unit"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccione uno</option>
                                    <option value="Alta">Unidad</option>
                                    <option value="Media">Metros</option>
                                    <option value="Baja">Otro</option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="days" class="font-medium leading-6 text-gray-900">Días
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput required type="number" v-model="itemToAdd.days" min="1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="quantity" class="font-medium leading-6 text-gray-900">Cantidad
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput required type="number" v-model="itemToAdd.quantity" min="1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="unit_price" class="font-medium leading-6 text-gray-900">Precio Unitario
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput required type="number" v-model="itemToAdd.unit_price" min="0" step="0.01"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3 justify-end">
                        <SecondaryButton type="button" @click="closeModal"> Cerrar </SecondaryButton>
                        <PrimaryButton type="submit"> Agregar </PrimaryButton>
                    </div>
                </form>
            </Modal>
        </div>
        <SuccessOperationModal :confirming="showModal" :title="modalVariables.title"
            :message="modalVariables.message" />

        <SuccessOperationModal :confirming="showItemAddModal" :title="`Item de valorización añadido.`"
            :message="`El item de valorización fue añadido.`" />
        <SuccessOperationModal :confirming="showItemRemoveModal" :title="`Item de valorización removido.`"
            :message="`El item de valorización fue removido.`" />

        <AcceptModal :acceptFunction="approve" :confirmingAccept="showConfirmAccept" @closeModal="closeConfirmAccept"
            :itemType="`Cotización`" />
        <ConfirmAcceptModal :confirmingaccept="showFinishAccept" :itemType="`Cotización`" />

    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmAcceptModal from '@/Components/ConfirmAcceptModal.vue';
import AcceptModal from '@/Components/AcceptModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { TrashIcon } from '@heroicons/vue/24/outline';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';


const showModal = ref(false)

const { preproject, auth } = defineProps({
    preproject: Object,
    auth: Object
})

const modalVariables = ref({
    title: `Cotización ${preproject.quote !== null ? 'actualizada' : 'creada'}`,
    message: `La cotización para proyecto fue ${preproject.quote !== null ? 'actualizada' : 'creada'}`
})


const initialState = {
    name: '',
    date: '',
    supervisor: '',
    boss: '',
    rev: '',
    deliverable_time: '',
    validity_time: '',
    observations: '',
    items: [],
    preproject_id: preproject.id
}

const form = useForm(
    { ...(preproject.quote ? preproject.quote : initialState) }
)

const submit = () => {
    let url = route('preprojects.quote.store')
    if (preproject.quote) {
        url = route('preprojects.quote.store', { quote_id: preproject.quote.id })
    }
    form.post(url, {
        onSuccess: () => {
            closeModal();
            showModal.value = true
            setTimeout(() => {
                showModal.value = false;
                router.visit(route('preprojects.index'))
            }, 2000);
        },
        onError: () => {
            close();
        }
    })
}



const showModalMember = ref(false);
const showConfirmAccept = ref(false);
const showFinishAccept = ref(false);
const itemInitialState = {
    description: '',
    unit: '',
    days: '',
    quantity: '',
    unit_price: '',
}
const itemToAdd = ref(JSON.parse(JSON.stringify(itemInitialState)))


const showItemAddModal = ref(false);
const showItemRemoveModal = ref(false);


const acceptCotization = () => {
    showConfirmAccept.value = true;
}

const closeConfirmAccept = () => {
    showConfirmAccept.value = false;
}

const showToAddItem = () => {
    showModalMember.value = true;
}
const closeModal = () => {
    showModalMember.value = false;
};
const addItem = () => {
    if (preproject.quote) {
        router.post(route('preprojects.quote.item.store'), { ...itemToAdd.value, preproject_quote_id: preproject.quote.id },
            {
                onError: () => {
                    alert('SERVER ERROR')
                },
                onSuccess: () => {
                    showItemAddModal.value = true
                    setTimeout(() => {
                        showItemAddModal.value = false;
                    }, 1500);
                    form.items.push({
                        ...itemToAdd.value
                    });
                }
            }
        )
    } else {
        form.items.push(JSON.parse(JSON.stringify(itemToAdd.value)))
        itemToAdd.value = JSON.parse(JSON.stringify(itemInitialState))
    }
}


const deleteItem = (index) => {
    form.items.splice(index, 1);
}

const deleteAlreadyItem = (id, index) => {
    router.delete(route('preprojects.quote.item.delete', { quote_item_id: id }), {
        onError: () => {
            alert('SERVER ERROR')
        },
        onSuccess: () => {
            showItemRemoveModal.value = true
            setTimeout(() => {
                showItemRemoveModal.value = false;
            }, 1500);
            form.employees.splice(index, 1);
        }
    })
}

const approve = () => {
    let url = route('preprojects.accept', { quote_id: preproject.quote.id });

    router.post(url, {
        state: '1'
    }, {
        onSuccess: () => {
            closeConfirmAccept();
            showFinishAccept.value = true
            setTimeout(() => {
                showFinishAccept.value = false;
                router.visit(route('preprojects.index'))
            }, 2000);
        },
        onError: () => {
            close();
        }
    })
}

</script>