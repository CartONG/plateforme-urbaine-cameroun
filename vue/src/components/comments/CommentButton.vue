<template>
  <template v-if="userStore.userIsLogged">
    <v-btn
      v-if="position === 'Sheet'"
      :icon="isFormCommentVisible ? '$commentText' : '$commentTextOutline'"
      @click.stop.prevent="isFormCommentVisible = true"
      :class="{
        'text-white': isFormCommentVisible,
        'text-main-red': !isFormCommentVisible
      }"
      :color="isFormCommentVisible ? 'main-blue' : 'white'"
    ></v-btn>
    <v-btn
      v-else
      variant="text"
      density="comfortable"
      :icon="isFormCommentVisible ? '$commentText' : '$commentTextOutline'"
      color="main-red"
      @click.stop.prevent="isFormCommentVisible = true"
    >
    </v-btn>
    <CommentForm
      v-if="isFormCommentVisible"
      :is-shown="isFormCommentVisible"
      :origin="origin"
      :originSlug="originSlug"
      @close="isFormCommentVisible = false"
    />
  </template>
</template>
<script setup lang="ts">
import CommentForm from '@/components/comments/CommentForm.vue';
import type { CommentOrigin } from '@/models/interfaces/Comment';
import { useUserStore } from '@/stores/userStore';
import { ref } from 'vue';

defineProps<{
  position: 'Sheet' | 'Card'
  origin: CommentOrigin
  originSlug: string
}>()

const isFormCommentVisible = ref(false)
const userStore = useUserStore()
</script>
