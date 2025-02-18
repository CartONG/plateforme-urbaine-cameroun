<template>
  <div class="SectionBanner">
    <h3 class="SectionBanner__title half-circle" :hide-half-circle="hideHalfCircle || false">
      {{ text }}
    </h3>
    <v-btn
      v-if="actionLabel"
      :to="actionLink"
      class="mt-3 px-2"
      variant="text"
      append-icon="mdi-arrow-right"
    >
      {{ actionLabel }}
    </v-btn>
  </div>
</template>
<script setup lang="ts">
import type { RouteLocationAsRelativeGeneric } from 'vue-router'

defineProps<{
  text: string
  hideHalfCircle?: boolean
  actionLabel?: string
  actionLink?: RouteLocationAsRelativeGeneric
}>()
</script>
<style lang="scss">
.SectionBanner {
  display: flex;
  flex-flow: row nowrap;
  gap: 1.5rem;
  align-items: center;

  .SectionBanner__title {
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

  .v-btn {
    .v-btn__content {
      font-weight: 400;
    }
    .v-icon {
      $dim-icon-w: 1.5rem;
      width: $dim-icon-w;
      height: $dim-icon-w;
      background: rgb(var(--v-theme-main-green));
      border-radius: 50%;
      color: white;
    }
  }
}
</style>
