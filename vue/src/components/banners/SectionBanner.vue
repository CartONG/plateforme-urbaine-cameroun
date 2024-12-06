<template>
  <h3 class="SectionBanner half-circle" :hide-half-circle="hideHalfCircle || false">{{ text }}</h3>
</template>
<script setup lang="ts">
defineProps<{
  text: string
  hideHalfCircle?: boolean
}>()
</script>
<style scoped lang="scss">
.SectionBanner {
  font-size: $font-size-h3;
  font-weight: 700;
  color: rgb(var(--v-theme-main-blue));

  &[hide-half-circle='true'] {
    padding: 0;
    margin-top: 0;
    &::before {
      content: none;
    }
  }
}
</style>
