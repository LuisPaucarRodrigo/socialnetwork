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
    zoom: 8,
    center: props.origin,
    mapId: "9633a9a2b0cd074b"
  });

  // Add a marker for the origin
  new google.maps.Marker({
    position: props.origin,
    map: map,
    title: 'Origin'
  });

  // Add markers for the waypoints
  props.waypoints.forEach((point, index) => {
    new google.maps.Marker({
      position: point.location,
      map: map,
      title: `Waypoint ${index + 1}`
    });
  });
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
