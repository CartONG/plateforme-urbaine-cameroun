<template>
  <div class="MapPluginButton">
    <span>{{ title }}</span>
    <v-btn size="small" icon="mdi-arrow-right" class="text-dark-grey"></v-btn>
  </div>
</template>
<script setup lang="ts">
defineProps({
  title: String
})
</script>
<style>
.MapPluginButton {
  display: flex;
  align-items: center;
  width: 100%;
  justify-content: space-between;
  border-radius: 5px;
  padding: 0.5rem 1rem;
  border: 1px solid rgb(var(--v-theme-dark-grey));
  font-weight: 500;
}
</style>
