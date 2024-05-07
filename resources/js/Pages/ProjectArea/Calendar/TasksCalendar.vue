<template>
  <Head title="Calendario" />
  <AuthenticatedLayout :redirectRoute="'projectmanagement.index'">
    <h1>Calendario de Tareas del Proyecto: {{ project.name }}</h1>
    <p class="mt-3 font-medium text-gray-900">Fecha de Inicio del Proyecto:
                        <span class="text-gray-600">{{ project.start_date }}</span>
                    </p>
                    <p class="font-medium text-gray-900 mb-3">Fecha de Fin del Proyecto:
                        <span class="text-gray-600">{{ project.end_date }}</span>
                    </p>
    <FullCalendar :options="calendarOptions" />
    <teleport to="body">
      <div v-if="selectedEvent" class="modal-overlay" @click="closeModal">
        <div class="modal" :class="{ 'show': showModal }" @click.stop>
          <div class="modal-header">
            <button @click="closeModal" class="close-button">&#10006;</button>
          </div>
          <div class="modal-content">
            <h3 class="event-title">{{ selectedEvent.title }}</h3>
            <p>Fecha de inicio: {{ formatDate(selectedEvent.start) }}</p>
            <p>Fecha de fin: {{ formatDate(selectedEvent.end) }}</p>
            <p>Estado: {{ selectedEvent.description }}</p>
          </div>
        </div>
      </div>
    </teleport>
  </AuthenticatedLayout>
</template>
  
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import FullCalendar from '@fullcalendar/vue3';
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';

const { project } = defineProps(['project']);

const selectedEvent = ref(null);
const showModal = ref(false);

const handleDateClick = (arg) => {
  alert('date click! ' + arg.dateStr);
};

const handleEventClick = (arg) => {
  selectedEvent.value = {
    title: arg.event.title,
    start: arg.event.extendedProps.start_date,
    end: arg.event.extendedProps.end_date,
    description: arg.event.extendedProps.description || '',
  };
  showModal.value = true;
};

const closeModal = () => {
  selectedEvent.value = null;
  showModal.value = false;
};

document.title = 'Calendario';

const colorSet = ['#91918F', '#B3B2AE', '#5D6363', '#293737'];
let colorIndex = 0;

const getColorFromSet = () => {
  const color = colorSet[colorIndex];
  colorIndex = (colorIndex + 1) % colorSet.length;
  return color;
};

const formatDate = (dateStr) => {
  const date = new Date(dateStr);
  return date.toLocaleDateString('es-ES');
};

const calendarOptions = {
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  dateClick: handleDateClick,
  eventClick: handleEventClick,
  eventTimeFormat: {
    hour: 'numeric',
    minute: '2-digit',
    omitZeroMinute: true,
    meridiem: false
  },
  events: project.tasks.map((task) => ({
    title: task.task,
    start: new Date(task.start_date).toISOString().split('T')[0],
    end: new Date(new Date(task.end_date).getTime() + (24 * 60 * 60 * 1000)).toISOString().split('T')[0],
    color: getColorFromSet(),
    description: task.status,
    start_date: task.start_date + 'T00:00:01',
    end_date: task.end_date + 'T00:00:01',
    textColor: 'white',
  })),
  locale: 'ES',
};

</script>
  
  
<style scoped>
/* Estilos del modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  /* Fondo negro sutilmente semitransparente */
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999;
  transition: background-color 0.3s ease;
  /* Transición suave */
}

.modal {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  opacity: 0;
  /* Inicialmente invisible */
  transition: opacity 0.5s ease;
  /* Transición suave */
  z-index: 1000;
}

.modal.show {
  opacity: 1;
  /* Mostrar el modal */
}

.modal-header {
  display: flex;
  justify-content: flex-end;
}

.close-button {
  background: none;
  border: none;
  font-size: 20px;
  cursor: pointer;
  color: #555;
}

.event-title {
  text-align: center;
  /* Centrar el texto */
  font-weight: bold;
  /* Hacer el texto en negrita */
}

h1 {
  font-weight: bold;
  font-size: x-large;
}
</style>