<template>
  <div
    class="image-mosaic"
    :style="{
      gridTemplateRows: `repeat(${Math.ceil(images.length / 4)}, 1fr)`
    }"
  >
    <div
      v-for="(image, index) in images"
      :key="index"
      class="mosaic-item"
      :style="{ backgroundImage: `url(${typeof image === 'string' ? image : image.contentUrl})` }"
      @click="viewImage(image)"
    />
  </div>
  <v-dialog v-model="showImageViewer" width="auto">
    <img :src="imageViewerSrc" class="image-viewer" />
  </v-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import type { MediaObject } from '@/models/interfaces/MediaObject'

defineProps<{
  images: (string | MediaObject)[]
}>()

const imageViewerSrc = ref<string>('')
const showImageViewer = ref<boolean>(false)

function viewImage(image: string | MediaObject) {
  if (typeof image === 'string') {
    imageViewerSrc.value = image
  } else {
    imageViewerSrc.value = image.contentUrl
  }
  showImageViewer.value = true
}
</script>

<style scoped>
.image-mosaic {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  row-gap: 1rem;
  column-gap: 1rem;
}

.mosaic-item {
  background-position: center;
  background-size: cover;
  border-radius: 0.5rem;
  overflow: hidden;
  cursor: pointer;
  width: 100%;
  height: 10rem;
}

.image-viewer {
  background-color: white;
  max-width: 90vw;
  max-height: 90vh;
}
</style>
