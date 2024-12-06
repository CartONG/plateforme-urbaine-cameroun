<template>
  <v-btn
    @click.prevent="copyURL"
    variant="text"
    density="comfortable"
    icon="mdi mdi-share-variant"
    color="main-blue"
  ></v-btn>
</template>

<script setup lang="ts">
const props = defineProps<{
  additionnalPath?: string
}>()
function copyURL() {
  if (props.additionnalPath) {
    navigator.clipboard.writeText(encodeURI(window.location.href + '/' + props.additionnalPath))
  } else {
    navigator.clipboard.writeText(window.location.href)
  }
  alert('URL copied to clipboard')
}
</script>
