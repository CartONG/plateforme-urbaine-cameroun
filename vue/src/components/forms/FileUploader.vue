<template>
  <div class="FileUploader">
    <div class="FileUploader__maxSize" v-if="maxSize">
      {{ $t('resources.form.fields.file.maxSize', { maxSize }) }}
    </div>
    <v-btn
      class="text-main-blue align-self-start"
      variant="elevated"
      :loading="isSelecting"
      @click="handleFileImport"
    >
      {{ $t('resources.form.fields.file.browse') }}</v-btn
    >
    <input ref="uploader" class="d-none" type="file" @change="onFileChanged" />
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'

const isSelecting = ref(false)
const selectedFile = ref(null)
const uploader = ref()

withDefaults(
  defineProps<{
    maxSize?: number
  }>(),
  {
    maxSize: 5
  }
)

const handleFileImport = () => {
  isSelecting.value = true

  window.addEventListener(
    'focus',
    () => {
      isSelecting.value = false
    },
    { once: true }
  )

  uploader.value.click()
}
const onFileChanged = (event: any) => {
  if (event.target != null) {
    selectedFile.value = event.target.files[0]
  }
}
</script>

<style lang="scss">
.FileUploader {
  display: flex;
  flex-flow: column nowrap;
  gap: 0.5rem;

  .FileUploader__maxSize {
    font-size: $font-size-xs;
  }
}
</style>
