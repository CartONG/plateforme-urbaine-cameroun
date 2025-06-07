<template>
  <div class="WrongPoint" :highlighted="highlighted" :has-description="!!description">
    <v-icon class="WrongPoint__icon" icon="$close-circle-outline" color="main-red" />
    <div class="WrongPoint__labelCtn">
      <span class="WrongPoint__label">{{ label }}</span>
      <span class="WrongPoint__description" v-if="description">{{ description }}</span>
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
.WrongPoint {
  display: flex;
  flex-flow: row nowrap;
  align-items: center;
  gap: 1.75rem;

  &[highlighted='true'] {
    gap: 1.25rem;
    .WrongPoint__icon {
      position: relative;
      background-color: rgb(var(--v-theme-light-yellow));
      padding: 1.25rem;
      border-radius: 50%;
    }
  }

  &[has-description='true'] {
    align-items: flex-start;
    .WrongPoint__icon {
      transform: translateY(-0.5rem);
    }

    .WrongPoint__labelCtn {
      align-items: flex-start;

      .WrongPoint__label {
        font-weight: bold;
      }
      .WrongPoint__description {
      }
    }
  }

  .WrongPoint__labelCtn {
    display: flex;
    flex-flow: column nowrap;
    align-items: center;
    .WrongPoint__label {
      white-space: pre-line;
    }
    .WrongPoint__description {
    }
  }
}

@media (max-width: $bp-xl) {
  .WrongPoint {
    &[highlighted='true'] {
      gap: 1rem;
    }
  }
}
</style>
