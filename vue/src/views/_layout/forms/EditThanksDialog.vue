<template>
  <Dialog @close="closeDialog()">
    <template #title>{{ $t('itemType.new.thanks.title') }}</template>
    <template #content>
      <CheckPoint class="mb-4" :label="$t('itemType.new.thanks.detail')" :highlighted="true" />
      <span class="mb-4 text-center">{{ $t('itemType.new.thanks.message') }}</span>
    </template>
    <template #bottom-content>
      <v-btn color="red" @click.prevent="closeDialog()" block>
        {{ $t('itemType.new.thanks.close') }}
      </v-btn>
    </template>
  </Dialog>
</template>

<script lang="ts" setup>
import Dialog from '@/components/global/Dialog.vue'
import CheckPoint from '@/components/global/CheckPoint.vue'
import { useApplicationStore } from '@/stores/applicationStore'

const closeDialog = () => {
  useApplicationStore().showEditThanksDialog = false
}
</script>
