<template>
  <div class="PageBanner">
    <div class="PageBanner__subtitle" v-if="subtitle">{{ subtitle }}</div>
    <div class="PageBanner__title half-circle half-circle--big">{{ title }}</div>
    <slot name="actions"></slot>
  </div>
</template>
<script setup lang="ts">
defineProps<{
  title: string
  subtitle?: string | null
}>()
</script>
<style scoped lang="scss">
.PageBanner {
  display: flex;
  align-items: flex-start;
  flex-flow: column nowrap;
  width: 100%;
  min-height: 70px;
  position: relative;

  & > * {
    padding-left: 3rem;
  }

  &__title {
    font-size: $font-size-h2;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
    line-height: 1.2em;
    color: rgb(var(--v-theme-main-blue));
  }

  &__subtitle {
    width: 100%;
    font-size: $font-size-h3;
    font-weight: 700;
    color: rgba(var(--v-theme-light-blue), 0.5);
  }
}

@media (max-width: $bp-xl) {
  .PageBanner {
    & > * {
      padding-left: 2rem;
    }
  }
}
</style>
