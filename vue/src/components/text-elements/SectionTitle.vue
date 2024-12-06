<template>
  <span class="SectionTitle">{{ title }}</span>
</template>
<script setup lang="ts">
defineProps<{
  title: string
}>()
</script>
<style lang="scss">
.SectionTitle {
  color: rgb(var(--v-theme-main-blue));
  font-size: $font-size-h3;
  font-weight: 700;
}
</style>
