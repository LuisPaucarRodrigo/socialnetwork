<template>
  <Head title="Calendario" />
  <AuthenticatedLayout :redirectRoute="'projectmanagement.index'">
    <h1>Calendario de Proyectos</h1>
    <FullCalendar :options="calendarOptions" />
    <teleport to="body">
      <div v-if="selectedEvent" class="modal-overlay" @click="closeModal">
        <div class="modal" :class="{ 'show': showModal }" @click.stop>
          <div class="modal-header">
            <button @click="closeModal" class="close-button">&#10006;</button>
          </div>
          <div class="modal-content">
            <h3 class="event-title">{{ selectedEvent.title }}</h3><br>
            <p>Fecha de inicio: {{ formatDate(selectedEvent.start) }}</p>
            <p>Fecha de fin: {{ formatDate(selectedEvent.end) }}</p>
            <p>Descripción: {{ selectedEvent.description }}</p>
            <div class="center-link">
              <Link :href="route('projectscalendar.show', { project: selectedEvent.project_id })"
                class="inline-flex items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white">
              Ir a Calendario de Tareas del Proyecto
              </Link>
            </div>
          </div>
        </div>
      </div>
    </teleport>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import interactionPlugin from '@fullcalendar/interaction';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const selectedEvent = ref(null);
const showModal = ref(false);

const props = defineProps({
  projects: Object,
});


const handleDateClick = (arg) => {
  alert('date click! ' + arg.dateStr);
};

const handleEventClick = (arg) => {
  selectedEvent.value = {
    title: arg.event.title,
    start: arg.event.extendedProps.start_date,
    end: arg.event.extendedProps.end_date,
    description: arg.event.extendedProps.description || '',
    project_id: arg.event.extendedProps.project_id || '',
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
  colorIndex = (colorIndex + 1) % colorSet.length; // Incrementa el índice y vuelve a 0 cuando se alcanza la longitud del conjunto
  return color;
};

const events = ref(props.projects.map((project) => {
  // Convertir las fechas al formato correcto (DD/MM/YYYY a YYYY-MM-DD)
  const startDateParts = project.start_date.split('/');
  const endDateParts = project.end_date.split('/');
  const startDateFormatted = `${startDateParts[2]}-${startDateParts[1]}-${startDateParts[0]}`;
  const endDateFormatted = `${endDateParts[2]}-${endDateParts[1]}-${endDateParts[0]}`;

  return {
    title: project.name,
    start: new Date(startDateFormatted).toISOString().split('T')[0],
    end: new Date(new Date(endDateFormatted).getTime() + (24 * 60 * 60 * 1000)).toISOString().split('T')[0],
    color: getColorFromSet(),
    start_date: startDateFormatted + 'T00:00:01',
    end_date: endDateFormatted + 'T00:00:01',
    description: project.description,
    project_id: project.id,
    textColor: 'black',
  };
}));


const formatDate = (dateStr) => {
  const date = new Date(dateStr);
  return date.toLocaleDateString('es-ES');
};

const calendarOptions = ref({
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  dateClick: handleDateClick,
  eventClick: handleEventClick,
  events: events.value,
  locale: 'ES',
});
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

.center-link {
  display: flex;
  justify-content: center;
  margin-top: 20px;
  /* Puedes ajustar el margen según sea necesario */
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