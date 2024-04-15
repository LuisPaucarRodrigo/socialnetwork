<template>
  <Head title="Calendario" />
  <AuthenticatedLayout :redirectRoute="'sections.subSections'">
    <h1>Calendario de Alarmas</h1>
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
            <p>Descripción: {{ selectedEvent.description }}</p>
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

const { member } = defineProps(['member']);

const selectedEvent = ref(null);
const showModal = ref(false);

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

const colorSet = ['#0979b0', '#0cb7f2', '#7cdaf9', '#b6ffff'];
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
  eventClick: handleEventClick,
  eventTimeFormat: {
    hour: 'numeric',
    minute: '2-digit',
    omitZeroMinute: true,
    meridiem: false
  },
  events: member.map((alarm) => ({
    title: alarm.name + " - " + alarm.description,
    start: new Date(alarm.start_date).toISOString().split('T')[0],
    end: new Date(new Date(alarm.end_date).getTime() + (24 * 60 * 60 * 1000)).toISOString().split('T')[0],
    color: getColorFromSet(),
    description: alarm.description,
    start_date: alarm.start_date + 'T00:00:01',
    end_date: alarm.end_date + 'T00:00:01',
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