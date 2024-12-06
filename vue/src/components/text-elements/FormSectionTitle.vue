<template>
  <span class="FormSectionTitle">{{ text }}</span>
</template>
<script setup lang="ts">
defineProps({
  text: String
})
</script>
<style scoped>
.FormSectionTitle {
  display: block;
  color: rgb(var(--v-theme-main-blue));
  font-size: 18px;
  font-weight: 700;
  line-height: 30px;
}
</style>
