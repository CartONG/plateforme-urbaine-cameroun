<template>
  <div class="Kpi">
    <div class="Kpi__block Kpi__block--data">
      <div class="Kpi__icon">
        <v-icon
          :icon="'mdi mdi-' + $t('kpi.' + kpi.key + '.icon')"
          size="x-large"
          color="main-green"
        />
      </div>
      <div class="Kpi__infoCtn">
        <div class="Kpi__count">{{ kpi.count }}</div>
        <div class="Kpi__label">{{ $t('kpi.' + kpi.key + '.label', kpi.count) }}</div>
      </div>
    </div>
    <div class="Kpi__block Kpi__block--description" v-if="showDescription">
      {{ $t('kpi.' + kpi.key + '.description') }}
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Kpi } from '@/models/interfaces/Kpi'

defineProps<{
  kpi: Kpi
  showDescription?: boolean
}>()
</script>

<style lang="scss">
.Kpi {
  display: flex;
  flex-flow: column nowrap;
  gap: 1rem;
  align-items: flex-start;

  .Kpi__block {
    &--data {
      display: flex;
      width: 100%;
      flex-flow: row nowrap;
      gap: 1.5rem;

      .Kpi__icon {
        display: flex;
        justify-content: center;
        align-items: center;
        $dim-icon: 3.5rem;
        background-color: rgb(var(--v-theme-light-yellow));
        width: $dim-icon;
        min-width: $dim-icon;
        height: $dim-icon;
        border-radius: 50%;
      }

      .Kpi__infoCtn {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 0.25rem;

        .Kpi__count {
          font-family: $font-primary;
          font-size: 2.75rem;
          line-height: 2rem;
          font-weight: 700;
          color: rgb(var(--v-theme-main-blue));
        }

        .Kpi__label {
          font-size: 1.5rem;
        }
      }
    }
  }
}
</style>
