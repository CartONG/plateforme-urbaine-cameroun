<!-- eslint-disable prettier/prettier -->
<template>
  <div class="maplibregl-ctrl maplibregl-ctrl-group MapExport">
    <v-btn
      icon="mdi-comment-text"
      @click.stop="activeMapComment"
      :class="{
        'text-white': commentStore.isAppCommentActive,
        'text-main-red': !commentStore.isAppCommentActive
      }"
      :color="commentStore.isAppCommentActive ? 'main-red' : 'white'"
    ></v-btn>
    <CommentForm
      :map-comment="true"
      v-if="isFormCommentVisible"
      :is-shown="isFormCommentVisible"
      :origin="CommentOrigin.MAP"
      :lng-lat="(lngLat as LngLat)"
      @close="desactiveMapComment"
    />
  </div>
</template>
<script setup lang="ts">
import CommentForm from '@/components/comments/CommentForm.vue'
import { NotificationType } from '@/models/enums/app/NotificationType'
import { CommentOrigin } from '@/models/interfaces/Comment'
import { i18n } from '@/plugins/i18n'
import { addNotification } from '@/services/notifications/NotificationService'
import { useCommentStore } from '@/stores/commentStore'
import { useMyMapStore } from '@/stores/myMapStore'
import { useUserStore } from '@/stores/userStore'
import type { LngLat } from 'maplibre-gl'
import maplibregl from 'maplibre-gl'
import { computed, ref, watch, type Ref } from 'vue'
const myMapStore = useMyMapStore()
const userStore = useUserStore()
const commentStore = useCommentStore()
const map = computed(() => myMapStore.myMap?.map)
const isFormCommentVisible = ref(false)
const lngLat: Ref<LngLat | null> = ref(null)
const marker: Ref<maplibregl.Marker | null> = ref(null)

function activeMapComment() {
  if (userStore.userIsLogged) {
    addNotification(i18n.t('myMap.comment.clickInfo'), NotificationType.INFO)
    commentStore.isAppCommentActive = !commentStore.isAppCommentActive
  } else {
    addNotification(i18n.t('myMap.comment.login'), NotificationType.ERROR)
  }
}

watch(
  () => commentStore.isAppCommentActive,
  (value: boolean) => {
    if (map.value) {
      if (value) {
        map.value.getCanvas().classList.add('cursor-crosshair')
        map.value.on('click', handleMapComment)
      } else {
        map.value.getCanvas().classList.remove('cursor-crosshair')
      }
    }
  }
)

function desactiveMapComment() {
  commentStore.isAppCommentActive = false
  isFormCommentVisible.value = false
  map.value?.off('click', handleMapComment)
  if (marker.value) {
    marker.value.remove()
    marker.value = null
  }
}

function handleMapComment(e: any) {
  lngLat.value = e.lngLat
  marker.value = new maplibregl.Marker().setLngLat(e.lngLat).addTo(map.value as maplibregl.Map)
  if (commentStore.isAppCommentActive) {
    isFormCommentVisible.value = true
  }
}
</script>

<style>
.MapExport {
  flex-flow: row nowrap !important;
  margin-right: 0.3rem !important;
}
</style>
