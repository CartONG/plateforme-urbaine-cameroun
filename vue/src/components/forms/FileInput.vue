<template>
  <div class="FileInput">
    <div class="FileInput__btnCtn">
      <v-btn
        class="FileInput__btn text-main-blue align-self-start"
        :extension="fileExtension"
        variant="elevated"
        :loading="isSelecting"
        @click="handleFileImport"
      >
        <span class="FileInput__fileNameLabel">{{
          file ? fileNameWithoutExtension : $t('resources.form.fields.file.browse')
        }}</span>
        <span v-if="file">{{ '.' + fileExtension }}</span>
      </v-btn>
      <v-btn
        v-if="file"
        variant="text"
        density="comfortable"
        icon="mdi-trash-can-outline"
        color="main-blue"
        @click="file = null"
      ></v-btn>
    </div>
    <v-text-field
      :error-messages="errorMessages"
      hide-details="auto"
      class="FileInput__errorMessage"
    />
    <input ref="uploader" class="d-none" type="file" @change="onFileChanged" />
    <!--<div class="FileInput__maxSize" v-if="maxSize">{{ $t('resources.form.fields.file.maxSize', { maxSize }) }}</div>-->
  </div>
</template>

<script lang="ts" setup>
import { computed, ref } from 'vue'
import type { MediaObject } from '@/models/interfaces/MediaObject'

const isSelecting = ref(false)
const file = defineModel<File | MediaObject | null>()

const fileName = computed(() => {
  if (file.value == null) {
    return ''
  } else if (file.value instanceof File) {
    return file.value.name + (file.value.size ? ` (${fileSize.value})` : '')
  } else {
    return file.value.contentUrl.split('/').pop() ?? ''
  }
})

const fileNameWithoutExtension = computed(() => {
  return fileName.value.split('.').slice(0, -1).join('.')
})

const fileExtension = computed(() => {
  return fileName.value.split('.').pop()
})

const fileSize = computed(() => {
  if (file.value == null || !(file.value instanceof File)) return ''
  if (file.value instanceof File) {
    const fileSize = file.value.size ?? null
    if (!fileSize) return ''
    if (fileSize < 1000000) return `${Math.round(+fileSize / 1024).toFixed(2)}KB`
    return `${(Math.round(+fileSize / 1024) / 1000).toFixed(2)}MB`
  }
  return ''
})

const uploader = ref()

withDefaults(
  defineProps<{
    maxSize?: number
    errorMessages?: string
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
  file.value = event.target != null ? event.target.files[0] : null
}
</script>

<style lang="scss">
.FileInput {
  display: flex;
  flex-flow: column nowrap;
  gap: 0.5rem;

  .FileInput__maxSize {
    font-size: $font-size-xs;
  }

  .FileInput__btnCtn {
    position: relative;
    display: flex;
    max-width: 100%;
    gap: 0.5rem;
    .FileInput__btn {
      position: relative;
      flex: 1 0 auto;
      .v-btn__content {
        display: block;
      }
      .FileInput__fileNameLabel {
        max-width: calc(100% - 2rem);
        overflow-x: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        &::after {
          content: attr(extension);
          display: inline;
        }
      }
    }
  }

  .FileInput__errorMessage {
    .v-input__control {
      display: none;
    }
  }
}
</style>
