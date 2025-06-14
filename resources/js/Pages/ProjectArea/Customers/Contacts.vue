<template>

  <Head title="Gestion de Contactos" />
  <AuthenticatedLayout :redirectRoute="'customers.index'">
    <template #header>
      Contactos del cliente
    </template>
    <div class="inline-block min-w-full overflow-hidden">
      <div class="flex gap-4">
        <button v-permission="'manage_client_contacts'" @click="openCreateContactModal" type="button"
          class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
          + Agregar
        </button>
      </div>
    </div>
    <TableStructure>
      <template #thead>
        <tr>
          <TableTitle>Nombre</TableTitle>
          <TableTitle>Teléfono</TableTitle>
          <TableTitle>Información Adicional</TableTitle>
          <TableTitle></TableTitle>
        </tr>
      </template>
      <template #tbody>
        <tr v-for="contact in props.contacts.data" :key="contact.id">
          <TableRow>{{ contact.name }}</TableRow>
          <TableRow>{{ contact.phone }}</TableRow>
          <TableRow>{{ contact.additional_information }}</TableRow>
          <TableRow>
            <div class="flex justify-center space-x-3">
              <button v-permission="'manage_client_contacts'" @click="openEditContactModal(contact)">
                <EditIcon />
              </button>
              <button v-permission="'delete_client_contact'" @click="confirmDeleteContact(contact.id)">
                <DeleteIcon />
              </button>
            </div>
          </TableRow>
        </tr>
      </template>
    </TableStructure>

    <Modal :show="create_contact || edit_contact">
      <div class="p-6">
        <h2 class="text-base font-medium leading-7 text-gray-900">
          {{ create_contact ? 'Agregar contacto' : 'Actualizar contacto' }}
        </h2>
        <form @submit.prevent="create_contact ? submit() : submitEdit()">
          <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">

              <div>
                <InputLabel for="name" class="font-medium leading-6 text-gray-900">Nombre</InputLabel>
                <div class="mt-2">
                  <input type="text" v-model="form.name" id="name"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.name" />
                </div>
              </div>

              <div>
                <InputLabel for="phone" class="font-medium leading-6 text-gray-900 mt-3">Teléfono</InputLabel>
                <div class="mt-2">
                  <input type="text" v-model="form.phone" id="phone" maxlength="9"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.phone" />
                </div>
              </div>

              <div>
                <InputLabel for="additional_information" class="font-medium leading-6 text-gray-900 mt-3">Información
                  adicional
                </InputLabel>
                <div class="mt-2">
                  <textarea v-model="form.additional_information" id="additional_information"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                </div>
              </div>

              <div class="mt-6 flex items-center justify-end gap-x-6">
                <SecondaryButton @click="create_contact ? close_add_contact() : close_edit_contact()"> Cancelar
                </SecondaryButton>
                <button type="submit" :class="{ 'opacity-25': form.processing }"
                  class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{
                    create_contact ? 'Guardar' : 'Actualizar' }}</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </Modal>

    <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Contacto" :deleteFunction="deleteContact"
      @closeModal="closeModalDoc" />
    <ConfirmCreateModal :confirmingcreation="showModal" itemType="Contacto" />
    <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Contacto" />
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import TableStructure from '@/Layouts/TableStructure.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';
import { DeleteIcon, EditIcon } from "@/Components/Icons";

const props = defineProps({
  contacts: Object,
  customer_id: Number
});

const form = useForm({
  id: '',
  name: '',
  phone: '',
  additional_information: '',
});

const create_contact = ref(false);
const edit_contact = ref(false);
const showModal = ref(false);
const showModalEdit = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);
const editingContact = ref(null);

const openCreateContactModal = () => {
  create_contact.value = true;
};

const openEditContactModal = (contact) => {
  editingContact.value = JSON.parse(JSON.stringify(contact));
  form.id = editingContact.value.id;
  form.name = editingContact.value.name;
  form.phone = editingContact.value.phone;
  form.additional_information = editingContact.value.additional_information;

  edit_contact.value = true;
};

const close_add_contact = () => {
  form.reset();
  create_contact.value = false
}

const close_edit_contact = () => {
  form.reset();
  edit_contact.value = false
}


const submit = () => {
  form.post(route('customers.contacts.store', { customer: props.customer_id }), {
    onSuccess: () => {
      close_add_contact();
      form.reset();
      showModal.value = true
      setTimeout(() => {
        showModal.value = false;
        router.visit(route('customers.contacts.index', { customer: props.customer_id }))
      }, 2000);
    },
  });
};

const submitEdit = () => {
  form.put(route('customers.contacts.update', { customer_contact: form.id, customer: props.customer_id }), {
    onSuccess: () => {
      close_edit_contact();
      form.reset();
      showModalEdit.value = true
      setTimeout(() => {
        showModalEdit.value = false;
        router.visit(route('customers.contacts.index', { customer: props.customer_id }))
      }, 2000);
    },
    onError: () => {
      form.reset();
    },
    onFinish: () => {
      form.reset();
    }
  });
};

const confirmDeleteContact = (contactId) => {
  docToDelete.value = contactId;
  confirmingDocDeletion.value = true;
};

const closeModalDoc = () => {
  confirmingDocDeletion.value = false;
};

const deleteContact = () => {
  const docId = docToDelete.value;
  if (docId) {
    router.delete(route('customers.contacts.destroy', { customer_contact: docId, customer: props.customer_id }), {
      onSuccess: () => closeModalDoc()
    });
  }
};

</script>