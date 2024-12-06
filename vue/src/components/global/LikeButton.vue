<template>
  <div class="LikeButton">
    <v-btn
      variant="text"
      density="comfortable"
      :icon="likeId > 0 ? 'mdi mdi-heart' : 'mdi mdi-heart-outline'"
      color="main-blue"
      @click.prevent="switchLike()"
    >
    </v-btn>
    <span class="LikeButton__count" v-if="count > 0">{{ count }}</span>
  </div>
</template>

<script setup lang="ts">
import { useApplicationStore } from '@/stores/applicationStore'
import { useUserStore } from '@/stores/userStore'
import { computed } from 'vue'
const props = defineProps<{
  id: string
}>()
const appStore = useApplicationStore()
const userStore = useUserStore()

const likeId = computed(() => appStore.likesList?.[props.id]?.likeId || 0)
const count = computed(() => appStore.likesList?.[props.id]?.count || 0)

function switchLike() {
  if (userStore.userIsLogged) {
    const newLike = likeId.value === 0
    if (newLike) {
      appStore.addLike(props.id)
    } else {
      appStore.deleteLike(likeId.value)
    }
  }
}
</script>

<style lang="scss">
.LikeButton {
  display: flex;
  align-items: center;

  .LikeButton__count {
    color: var(--v-theme-main-blue);
    font-size: $font-size-sm;
  }
}
</style>
