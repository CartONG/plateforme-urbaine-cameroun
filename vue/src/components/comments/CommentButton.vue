<template>
  <template v-if="userStore.userIsLogged">
    <v-btn
      v-if="position === 'Sheet'"
      :icon="isFormCommentVisible ? 'mdi-comment-text' : 'mdi-comment-text-outline'"
      @click.stop="isFormCommentVisible = true"
      :class="{
        'text-white': isFormCommentVisible,
        'text-main-blue': !isFormCommentVisible
      }"
      :color="isFormCommentVisible ? 'main-blue' : 'white'"
    ></v-btn>
    <v-btn
      v-else
      variant="text"
      density="comfortable"
      :icon="isFormCommentVisible ? 'mdi-comment-text' : 'mdi-comment-text-outline'"
      color="main-blue"
      @click.prevent="isFormCommentVisible = true"
    >
    </v-btn>
    <CommentsForm
      v-if="isFormCommentVisible"
      :is-shown="isFormCommentVisible"
      :origin="origin"
      :originSlug="originSlug"
      @close="isFormCommentVisible = false"
    />
  </template>
</template>
<script setup lang="ts">
import CommentsForm from './CommentsForm.vue'
import type { CommentOrigin } from '@/models/interfaces/Comment'
import { useUserStore } from '@/stores/userStore'
import { ref } from 'vue'

defineProps<{
  position: 'Sheet' | 'Card'
  origin: CommentOrigin
  originSlug: string
}>()

const isFormCommentVisible = ref(false)
const userStore = useUserStore()
</script>
