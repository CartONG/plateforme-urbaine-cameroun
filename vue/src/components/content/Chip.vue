<template>
  <div :class="bgColorClass" class="Chip">{{ text }}</div>
</template>

<script setup lang="ts">
const props = defineProps({
  text: String,
  bgColor: {
    type: String,
    default: 'light-yellow'
  }
})
const bgColorClass = `bg-${props.bgColor}`
</script>

<style scoped lang="scss">
.Chip {
  color: rgb(var(--v-theme-main-blue));
  font-size: $font-size-sm;
  font-weight: 400;
  display: inline-block;
  padding: 0.375rem 0.75rem;
  border-radius: 1rem;
  text-align: center;
  white-space: nowrap;
}
</style>
