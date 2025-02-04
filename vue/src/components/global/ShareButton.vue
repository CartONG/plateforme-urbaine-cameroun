<template>
  <v-btn variant="text" density="comfortable" icon="" color="main-blue" @click.prevent>
    <v-icon icon="mdi mdi-share-variant"></v-icon>
    <ShareMenu :url="url" location="right" :body="body" />
  </v-btn>
</template>

<script setup lang="ts">
import ShareMenu from '@/components/global/ShareMenu.vue'
import { i18n } from '@/plugins/i18n'
const props = withDefaults(
  defineProps<{
    additionnalPath: string
    externalLink?: boolean
  }>(),
  {
    externalLink: false
  }
)
const url = props.externalLink
  ? props.additionnalPath
  : window.location.href + props.additionnalPath
const body = i18n.t('share.genericBody') + ' ' + url
</script>
