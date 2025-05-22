<template>
  <div class="CheckPoint" :highlighted="highlighted" :has-description="description ? true : false">
    <v-icon
      class="CheckPoint__icon"
      icon="$checkCircle"
      :style="
        highlighted ? 'background:' + (highlightColor ?? 'rgb(var(--v-theme-light-yellow)') : ''
      "
      color="main-green"
    />
    <div class="CheckPoint__labelCtn">
      <span class="CheckPoint__label">{{ label }}</span>
      <span class="CheckPoint__description" v-if="description">{{ description }}</span>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  label: string
  description?: string
  highlighted?: boolean
  highlightColor?: string
}>()
</script>

<style lang="scss">
.CheckPoint {
  display: flex;
  flex-flow: row nowrap;
  align-items: center;
  gap: 1.75rem;

  &[highlighted='true'] {
    gap: 1.25rem;
    .CheckPoint__icon {
      position: relative;
      background-color: rgb(var(--v-theme-light-yellow));
      margin: 1.25rem;
      border-radius: 50%;
    }
  }

  &[has-description='true'] {
    align-items: flex-start;
    .CheckPoint__icon {
      transform: translateY(-0.5rem);
    }

    .CheckPoint__labelCtn {
      align-items: flex-start;

      .CheckPoint__label {
        font-weight: bold;
      }
      .CheckPoint__description {
      }
    }
  }

  .CheckPoint__labelCtn {
    display: flex;
    flex-flow: column nowrap;
    align-items: center;
    .CheckPoint__label {
      white-space: pre-line;
    }
    .CheckPoint__description {
    }
  }
}

@media (max-width: $bp-xl) {
  .CheckPoint {
    &[highlighted='true'] {
      gap: 1rem;
    }
  }
}
</style>
