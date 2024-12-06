<template>
  <v-form fast-fail @submit.prevent class="Form">
    <slot></slot>
  </v-form>
</template>

<script setup lang="ts"></script>

<style lang="scss">
.Form {
  width: 100%;
  display: flex;
  flex-flow: column nowrap;
  align-items: center;
  justify-content: center;

  > *:not(button) {
    width: 100%;
  }

  .v-checkbox .v-label {
    opacity: 1 !important;
  }

  .v-selection-control {
    align-items: baseline;
  }
}
</style>
