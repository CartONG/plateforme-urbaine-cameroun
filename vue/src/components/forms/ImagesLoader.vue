<template>
  <div
    class="InputImage__dropzone"
    :class="{ 'InputImage__dropzone--dragging': isDragging }"
    @drop.prevent="handleDrop"
    @dragenter.prevent="handleDragEnter"
    @dragover.prevent="handleDragOver"
    @dragleave.prevent="handleDragLeave"
    @click="triggerFileInput"
  >
    <img src="@/assets/images/importFilesIcon.svg" alt="" />
    <p class="ml-2">
      {{ $t('actors.form.drag') }}
      <span class="InputImage__dropzone__uploadLink">{{ $t('actors.form.import') }}</span>
    </p>
    <input
      ref="fileInput"
      type="file"
      multiple
      accept="image/*"
      class="d-none"
      @change="handleFileChange"
    />
  </div>
  <div class="InputImage__divider" v-if="externalImagesLoader">
    <span>ou</span>
  </div>
  <div class="ExternalImages__ctn" v-if="externalImagesLoader">
    <v-text-field
      density="compact"
      variant="outlined"
      bg-color="white"
      label="Copier le lien de l’image"
      hide-details
      v-model="newExternalImageUrl"
    ></v-text-field>
    <v-btn color="main-red ml-2" @click="addNewExternalImage">Ajouter</v-btn>
  </div>

  <div>
    <span class="InputImage__errorMessage">{{ errorMessage }}</span>
  </div>

  <div class="LoadedImages__ctn mt-3">
    <template v-if="existingImages && existingImages.length > 0">
      <div v-for="(existingImage, index) in existingImages" :key="index" class="position-relative">
        <div @click="removeExistingImage(index)" class="LoadedImages__closer">X</div>
        <img
          :src="
            FileObject.hasThumbnail(existingImage as BaseMediaObject)
              ? (existingImage as BaseMediaObject).contentsFilteredUrl.thumbnail
              : (existingImage as BaseMediaObject).contentUrl
          "
          class="LoadedImages__preview ma-2"
        />
      </div>
    </template>
    <div v-for="(selectedFile, index) in selectedFiles" :key="index" class="position-relative">
      <div @click="removeLoadedFile(index)" class="LoadedImages__closer">X</div>
      <img
        :src="selectedFile.preview"
        :alt="(selectedFile as any).name ? (selectedFile as ContentImageFromUserFile).name : ''"
        class="LoadedImages__preview ma-2"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ContentImageType } from '@/models/enums/app/ContentImageType'
import type { ContentImageFromUserFile } from '@/models/interfaces/ContentImage'
import { InputImageValidator } from '@/services/files/InputImageValidator'
import { type Ref, ref } from 'vue'
import type { BaseMediaObject } from '@/models/interfaces/object/MediaObject'
import { FileObject } from '@/services/files/FileObject'

const props = defineProps({
  existingImages: {
    type: Array<BaseMediaObject | string | FileObject>,
    default: () => []
  },
  externalImagesLoader: {
    type: Boolean,
    default: true
  },
  uniqueImage: {
    type: Boolean,
    default: false
  }
})

const newExternalImageUrl = ref('')
const addNewExternalImage = () => {
  props.existingImages.push(newExternalImageUrl.value)
  newExternalImageUrl.value = ''
  emitChange()
}

const selectedFiles: Ref<ContentImageFromUserFile[]> = ref([])
const emit = defineEmits(['updateFiles'])

const fileInput = ref(null)
const triggerFileInput = () => {
  ;(fileInput.value as any).click()
}
const isDragging = ref(false)
const handleDragEnter = () => {
  isDragging.value = true
}
const handleDragOver = (event: Event) => {
  event.preventDefault()
}
const handleDragLeave = (event: any) => {
  if (!event.currentTarget.contains(event.relatedTarget)) {
    isDragging.value = false
  }
}
const handleDrop = (event: any) => {
  const files = event.dataTransfer.files
  handleFileChange({ target: { files } })
}

const errorMessage = ref('')
const handleFileChange = (event: any) => {
  let files: FileList
  if (props.uniqueImage) {
    selectedFiles.value = []
    files = [event.target.files[0]] as unknown as FileList
  } else {
    files = event.target.files
  }
  Array.from(files).forEach((file) => {
    const fileStatus = InputImageValidator.validateImageFromFile(file, selectedFiles.value)
    if (fileStatus.isValid) {
      const preview = URL.createObjectURL(file)
      selectedFiles.value.push({
        type: ContentImageType.CONTENT_IMAGE_FROM_USER_FILE,
        name: file.name,
        preview: preview,
        file: file
      })
    } else {
      errorMessage.value = fileStatus.message
      window.setTimeout(() => {
        errorMessage.value = ''
      }, 3000)
    }
  })
  emitChange()
}
const removeLoadedFile = (index: number) => {
  selectedFiles.value.splice(index, 1)
  emitChange()
}
const removeExistingImage = (index: number) => {
  props.existingImages.splice(index, 1)
  emitChange()
}

const emitChange = () => {
  emit('updateFiles', { existingImages: props.existingImages, selectedFiles: selectedFiles.value })
}
</script>

<style lang="scss">
.InputImage {
  &__dropzone {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    padding: 20px 50px;
    background-color: rgb(var(--v-theme-light-yellow));
    &--dragging {
      border: 2px dashed rgb(var(--v-theme-main-yellow));
    }
    &__uploadLink {
      color: rgb(var(--v-theme-main-blue));
      cursor: pointer;
      text-decoration: underline;
    }
  }
  &__errorMessage {
    color: rgb(var(--v-theme-main-red));
    font-size: $font-size-xs;
  }
  &__divider {
    display: flex;
    align-items: center;
    text-align: center;
    background-color: rgb(var(--v-theme-light-yellow));

    &::before,
    &::after {
      content: '';
      flex: 1;
      border-bottom: 1px solid rgb(var(--v-theme-main-grey));
      margin: 0 1em;
    }
  }
}

.LoadedImages {
  &__ctn {
    display: flex;
    flex-wrap: wrap;
  }
  &__closer {
    position: absolute;
    top: 0;
    left: 0;
    background-color: rgb(var(--v-theme-main-red));
    height: 24px;
    width: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: white;
  }
  &__preview {
    width: 100px;
  }
}
.ExternalImages__ctn {
  display: flex;
  background-color: rgb(var(--v-theme-light-yellow));
  padding: 1em;
}
</style>
