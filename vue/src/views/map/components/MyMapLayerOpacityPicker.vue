<template>
  <div class="MyMapLayerOpacityPicker">
    <div class="MyMapLayerOpacityPicker__desc">
      <span class="MyMapLayerOpacityPicker__label">{{ $t('myMap.components.opacity') }}</span>
      <span class="MyMapLayerOpacityPicker__value">{{ opacity }}%</span>
    </div>
    <v-slider
      v-model="opacity"
      :min="0"
      :max="100"
      :step="1"
      :thumb-size="16"
      :tick-size="2"
      :track-size="3"
      color="main-blue"
      thumb-color="#fff"
      density="compact"
      hide-details="auto"
    ></v-slider>
  </div>
</template>

<script setup lang="ts">
const opacity = defineModel({
  default: 100
})
</script>

<style lang="scss">
.MyMapLayerOpacityPicker {
  display: flex;
  flex-flow: column nowrap;
  gap: 0.5rem;
  padding: 1rem;
  .MyMapLayerOpacityPicker__desc {
    display: flex;
    flex-flow: row nowrap;
    align-items: center;
    justify-content: space-between;
    .MyMapLayerOpacityPicker__value {
      font-weight: 600;
      color: rgb(var(--v-theme-main-blue));
    }
  }
  .v-slider.v-input--horizontal {
    margin-inline: 5px;
  }
  .v-slider-track__fill {
    height: 4px !important;
  }
}
</style>
