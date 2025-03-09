<template>
  <Modal
    :title="$t('comments.title')"
    :show="isShown"
    @close="((commentStore.isAppCommentActive = false), $emit('close'))"
  >
    <template #content>
      <v-form @submit.prevent="submitForm" id="comment-form" class="Form Form--project">
        <div class="Form__fieldCtn">
          <label class="Form__label required">{{ $t('comments.form.message') }}</label>
          <v-textarea
            density="compact"
            variant="outlined"
            v-model="form.message.value.value"
            :error-messages="form.message.errorMessage.value"
            :placeholder="$t('comments.form.message')"
            @blur="form.message.handleChange"
          />
        </div>
        <div v-if="originSlug" class="Form__fieldCtn">
          <label class="Form__label">{{ $t('comments.form.originURL') }}</label>
          <v-text-field disabled :placeholder="`${origin}: ${originSlug}`"></v-text-field>
        </div>
        <div v-if="mapComment" class="Form__fieldCtn">
          <label class="Form__label">{{ $t('comments.form.location') }}</label>
          <v-text-field
            disabled
            :placeholder="`${lngLat?.lng.toString()}, ${lngLat?.lat.toString()}`"
          ></v-text-field>
        </div>
      </v-form>
    </template>
    <template #footer-left>
      <span
        class="text-action"
        @click="((commentStore.isAppCommentActive = false), $emit('close'))"
        >{{ $t('forms.cancel') }}</span
      >
    </template>
    <template #footer-right>
      <v-btn type="submit" form="comment-form" color="main-red" :loading="isSubmitting">{{
        $t('forms.create')
      }}</v-btn>
    </template>
  </Modal>
</template>

<script setup lang="ts">
import type { LngLat } from 'maplibre-gl'
import Modal from '../global/Modal.vue'
import type { AppComment, CommentOrigin } from '@/models/interfaces/Comment'
import { CommentsFormService } from '@/services/comments/CommentsForm'
import { addNotification } from '@/services/notifications/NotificationService'
import { i18n } from '@/plugins/i18n'
import { NotificationType } from '@/models/enums/app/NotificationType'
import { onInvalidSubmit } from '@/services/forms/FormService'
import { useCommentStore } from '@/stores/commentStore'

const props = withDefaults(
  defineProps<{
    isShown: boolean
    origin: CommentOrigin
    originSlug?: string
    mapComment?: boolean
    lngLat?: LngLat
  }>(),
  {
    mapComment: false
  }
)

const commentStore = useCommentStore()
const { form, handleSubmit, isSubmitting } = CommentsFormService.getCommentsForm()
const emit = defineEmits(['close'])

const submitForm = handleSubmit(
  (values) => {
    const commentSubmission: AppComment = {
      ...(values as any),
      origin: props.origin
    }
    if (props.lngLat) {
      commentSubmission.location = `${props.lngLat.lng.toString()}, ${props.lngLat.lat.toString()}`
    }
    if (props.originSlug) {
      if (props.origin === 'Actor' || props.origin === 'Project') {
        commentSubmission.originURL = window.location + '/' + props.originSlug
      } else {
        commentSubmission.originURL = props.originSlug
      }
    }
    commentStore.createComment(commentSubmission)
    emit('close')
  },
  () => {
    addNotification(i18n.t('forms.errors'), NotificationType.ERROR)
    onInvalidSubmit()
  }
)
</script>
