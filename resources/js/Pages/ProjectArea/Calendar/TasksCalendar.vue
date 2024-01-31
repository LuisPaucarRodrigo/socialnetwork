<template>
  <Head title="Calendario" />
  <AuthenticatedLayout>
    <h1>Calendario de Tareas del Proyecto: {{ project.name }}</h1>
    <FullCalendar :options="calendarOptions" />
    <teleport to="body">
      <div v-if="selectedEvent" class="modal-overlay" @click="closeModal">
        <div class="modal" :class="{ 'show': showModal }" @click.stop>
          <div class="modal-header">
            <button @click="closeModal" class="close-button">&#10006;</button>
          </div>
          <div class="modal-content">
            <h3 class="event-title">{{ selectedEvent.title }}</h3>
            <p>Start: {{ selectedEvent.start }}</p>
            <p>End: {{ selectedEvent.end }}</p>
            <p>Additional Info: {{ selectedEvent.description }}</p>
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
import { ref, defineProps } from 'vue';
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
    start: arg.event.start,
    end: arg.event.end,
    description: arg.event.extendedProps.description || '',
  };
  showModal.value = true;
};

const closeModal = () => {
  selectedEvent.value = null;
  showModal.value = false;
};

document.title = 'Calendario';

const colorSet = ['#0979b0', '#0cb7f2', '#7cdaf9', '#b6ffff'];
let colorIndex = 0;

const getColorFromSet = () => {
  const color = colorSet[colorIndex];
  colorIndex = (colorIndex + 1) % colorSet.length;
  return color;
};

const calendarOptions = {
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  dateClick: handleDateClick,
  eventClick: handleEventClick,
  events: project.tasks.map((task) => ({
    title: task.task,
    start: task.start_date + 'T11:30:00',
    end: task.end_date + 'T11:30:00',
    color: getColorFromSet(),
    description: task.status,
    textColor: 'black',
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