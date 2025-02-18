<template>
  <div
    class="image-mosaic"
    :style="{
      gridTemplateRows: `repeat(${Math.ceil(images.length / nbColumns)}, 1fr);`,
      gridTemplateColumns: `repeat(${nbColumns}, 1fr)`
    }"
  >
    <img
      v-for="(image, index) in images"
      :key="index"
      class="mosaic-item"
      :src="typeof image === 'string' ? image : getThumbnailUrl(image)"
      :style="{
        backgroundImage: `url(${typeof image === 'string' ? image : getThumbnailUrl(image)})`,
        cursor: `${hasViewer ? 'pointer' : 'default'}`
      }"
      @click="hasViewer && viewImage(image)"
    />
  </div>
  <v-dialog v-model="showImageViewer" width="auto" v-if="hasViewer">
    <img :src="imageViewerSrc" class="image-viewer" />
  </v-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import type { FileObject as FileObjectType } from '@/models/interfaces/object/FileObject'
import type { MediaObject } from '@/models/interfaces/object/MediaObject'
import { FileObject } from '@/services/files/FileObject'

withDefaults(
  defineProps<{
    images: (string | FileObjectType | MediaObject)[]
    hasViewer?: boolean
    nbColumns?: number
  }>(),
  {
    hasViewer: true,
    nbColumns: 4
  }
)

const imageViewerSrc = ref<string>('')
const showImageViewer = ref<boolean>(false)

function viewImage(image: string | FileObjectType | MediaObject) {
  if (typeof image === 'string') {
    imageViewerSrc.value = image
  } else {
    imageViewerSrc.value = image.contentUrl
  }
  showImageViewer.value = true
}

function getThumbnailUrl(image: FileObjectType | MediaObject) {
  if (FileObject.hasThumbnail(image)) {
    return image.contentsFilteredUrl.thumbnail
  }

  return image.contentUrl
}
</script>

<style scoped>
.image-mosaic {
  display: grid;
  row-gap: 1rem;
  column-gap: 1rem;
}

.mosaic-item {
  background-position: center;
  background-size: cover;
  border-radius: 0.5rem;
  overflow: hidden;
  width: 100%;
  height: 10rem;
}

.image-viewer {
  background-color: white;
  max-width: 90vw;
  max-height: 90vh;
}
</style>
