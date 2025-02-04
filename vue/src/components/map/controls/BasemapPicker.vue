<template>
  <div class="maplibregl-ctrl maplibregl-ctrl-group BasemapPicker">
    <div
      class="BasemapPicker__item"
      v-for="b in basemaps"
      :key="b.id"
      :basemap="b.id"
      :active="b.id === basemap?.id"
      @click="basemap = b"
    ></div>
  </div>
</template>

<script setup lang="ts">
import type Basemap from '@/models/interfaces/map/Basemap'

const basemap = defineModel<Basemap | null>({
  default: {
    id: 'openstreetmap',
    name: 'OpenStreetMap',
    style: 'https://api.maptiler.com/maps/openstreetmap/style.json'
  }
})

const basemaps: Basemap[] = [
  {
    id: 'openstreetmap',
    name: 'OpenStreetMap',
    style: 'https://api.maptiler.com/maps/openstreetmap/style.json'
  },
  {
    id: 'satellite',
    name: 'Satellite',
    style: 'https://api.maptiler.com/maps/satellite/style.json'
  }
]
</script>

<style lang="scss">
.BasemapPicker {
  display: flex;
  flex-flow: row nowrap !important;
  gap: 0.5rem;

  .BasemapPicker__item {
    $dim-basemap-w: 3.25rem;
    width: $dim-basemap-w;
    height: $dim-basemap-w;
    background-size: cover;
    background-position: center;
    border-radius: 50%;
    border: solid 2px white;
    cursor: pointer;
    transition: all 0.15s ease-in;

    &[basemap='openstreetmap'] {
      background-image: url(@/assets/images/map/basemap/openstreetmap.jpg);
    }
    &[basemap='satellite'] {
      background-image: url(@/assets/images/map/basemap/satellite.jpg);
    }

    &[active='true'] {
      order: 1;
    }
    &:not([active='true']) {
      transform: scale(0);
      pointer-events: none;
      opacity: 0.5;
    }
  }

  &:hover {
    .BasemapPicker__item {
      transform: scale(1);
      pointer-events: all;

      &:hover {
        opacity: 1;
      }
    }
  }
}
</style>
