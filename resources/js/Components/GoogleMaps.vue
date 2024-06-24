<template>
  <div v-if="mapVisible" id="map" style="height: 500px;"></div>
</template>

<script setup>
import { watch, onUnmounted } from 'vue';

const props = defineProps({
  origin: {
    type: Object,
    required: true
  },
  destination: {
    type: Object,
    required: false
  },
  waypoints: {
    type: Array,
    required: true
  },
  mapVisible: {
    type: Boolean,
    required: true
  }
});

let map;

const initMap = () => {
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 15,
    center: props.origin,
    mapId: "9633a9a2b0cd074b"
  });

  new google.maps.marker.AdvancedMarkerElement({
    map: map,
    title: 'Uluru',
  });

  const directionsService = new google.maps.DirectionsService();
  const directionsRenderer = new google.maps.DirectionsRenderer();
  directionsRenderer.setMap(map);

  directionsService.route(
    {
      origin: props.origin,
      destination: props.destination,
      waypoints: props.waypoints,
      optimizeWaypoints: true,
      travelMode: 'WALKING'
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

watch(() => props.mapVisible, (newVal) => {
  if (newVal) {
    if (window.google && window.google.maps) {
      initMap();
    } else {
      const googleMapsScript = document.createElement('script');
      googleMapsScript.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyCGdFiWfnd_SLlV4KRR1sgCfBRO17Gaxvs&libraries=marker&callback=initMap`;
      googleMapsScript.defer = true;
      googleMapsScript.async = true;
      window.initMap = initMap;
      document.head.appendChild(googleMapsScript);
    }
  }
});


onUnmounted(() => {
  if (map) {
    map = null;
  }
});
</script>

<style scoped>
#map {
  height: 100%;
  width: 100%;
}
</style>
