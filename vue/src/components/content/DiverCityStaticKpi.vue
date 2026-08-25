<!-- src/components/content/DiverCityStaticKpi.vue -->
<template>
  <div class="DiverCityStaticKpi">
    <div class="DiverCityStaticKpi__block DiverCityStaticKpi__block--data">
      <div class="DiverCityStaticKpi__icon">
        <v-icon :icon="icon" size="x-large" color="main-green" />
      </div>
      <div class="DiverCityStaticKpi__infoCtn">
        <div class="DiverCityStaticKpi__count">{{ value }}</div>
        <div class="DiverCityStaticKpi__label">{{ label }}</div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
withDefaults(
  defineProps<{
    label: string
    value: string | number
    icon?: string
  }>(),
  {
    icon: '$chartBar',
  }
)
</script>

<style lang="scss">
.DiverCityStaticKpi {
  display: flex;
  flex-flow: column nowrap;
  gap: 1rem;
  align-items: flex-start;

  .DiverCityStaticKpi__block {
    &--data {
      display: flex;
      width: 100%;
      flex-flow: row nowrap;
      gap: 1.5rem;

      .DiverCityStaticKpi__icon {
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

      .DiverCityStaticKpi__infoCtn {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 0.25rem;

        .DiverCityStaticKpi__count {
          font-family: $font-secondary;
          font-size: 2.75rem;
          line-height: 2rem;
          font-weight: 700;
          color: rgb(var(--v-theme-main-blue));
        }

        .DiverCityStaticKpi__label {
          font-size: 1.5rem;
        }
      }
    }
  }
}
</style>