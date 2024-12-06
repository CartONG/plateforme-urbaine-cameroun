<template>
  <span class="PageTitle">{{ title }}</span>
</template>
<script setup lang="ts">
defineProps<{
  title: string
}>()
</script>
<style lang="scss">
.PageTitle {
  white-space: pre-line;
  font-family: $font-primary;
  color: rgb(var(--v-theme-main-blue));
  font-size: 60px;
  font-weight: 700;
  line-height: 60px;
}

@media (max-width: $bp-md) {
  .PageTitle {
    font-size: 3rem;
    line-height: 3rem;
  }
}

@media (max-width: $bp-sm) {
  .PageTitle {
    font-size: 2.5rem;
    line-height: 2.5rem;
  }
}
</style>
