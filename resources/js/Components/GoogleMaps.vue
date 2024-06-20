<template>
  <div>
    <button @click="loadMap">Show Map</button>
    <div v-if="mapVisible" id="map" style="height: 500px;"></div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  origin: {
    type: Object,
    required: true
  },
  destination: {
    type: Object,
    required: true
  },
  waypoints: {
    type: Array,
    required: true
  }
});

const mapVisible = ref(false);

const loadMap = () => {
  mapVisible.value = true;
  const initMap = () => {
    const map = new google.maps.Map(document.getElementById('map'), {
      zoom: 6,
      center: props.origin
    });

    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);

    directionsService.route(
      {
        origin: props.origin,
        destination: props.destination,
        waypoints: props.waypoints.map(waypoint => ({ location: waypoint, stopover: true })),
        optimizeWaypoints: true,
        travelMode: 'DRIVING'
      },
      (response, status) => {
        if (status === 'OK') {
          directionsRenderer.setDirections(response);
        } else {
          console.error('Directions request failed due to ' + status);
        }
      }
    );
  };

  if (window.google && window.google.maps) {
    initMap();
  } else {
    const googleMapsScript = document.createElement('script');
    googleMapsScript.src = `https://maps.googleapis.com/maps/api/js?key=TU_API_KEY&callback=initMap`;
    googleMapsScript.defer = true;
    googleMapsScript.async = true;
    googleMapsScript.onload = initMap;
    document.head.appendChild(googleMapsScript);
  }
};
</script>

<style scoped>
#map {
  height: 100%; /* Ajusta la altura del mapa según necesites */
  width: 100%;
}
</style>
