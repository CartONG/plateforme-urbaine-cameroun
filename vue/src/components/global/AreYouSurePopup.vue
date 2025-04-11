<template>
  <Modal
    class="AreYouSurePopup"
    :show="shown"
    :fit-to-content="true"
    :title="$t('areYouSurePopup.title')"
    @close="$emit('hide')"
  >
    <template #content>
      <div class="AreYouSurePopup__ctn">
        <div class="AreYouSurePopup__text">{{ text }}</div>
      </div>
    </template>
    <template #footer-left>
      <v-btn color="main-grey" label="Annuler" @click="$emit('hide')">{{
        $t('forms.cancel')
      }}</v-btn>
    </template>
    <template #footer-right>
      <v-btn color="main-red" :loading="loading" :disabled="loading" @click="$emit('confirm')">{{
        $t('forms.submit')
      }}</v-btn>
    </template>
  </Modal>
</template>

<script setup lang="ts">
import { i18n } from '@/plugins/i18n'
import Modal from './Modal.vue'

defineEmits(['hide', 'confirm'])

withDefaults(
  defineProps<{
    shown: boolean
    text?: string
    loading?: boolean
  }>(),
  {
    shown: false,
    loading: false,
    text: i18n.t('areYouSurePopup.label')
  }
)
</script>
