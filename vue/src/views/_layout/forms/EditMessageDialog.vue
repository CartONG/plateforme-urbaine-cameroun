<template>
  <Dialog :closeOutside="false">
    <template #title>{{ $t('itemType.new.your_message.title') }}</template>
    <template #content>
      <span class="mb-4 text-center">{{
        $t('itemType.new.your_message.detail', { type: getType() })
      }}</span>
      <Form @submit="onSubmit">
        <v-textarea
          v-model="form.message.value.value"
          :placeholder="$t('itemType.new.your_message.form.message')"
          :error-messages="form.message.errorMessage.value"
          @blur="form.message.handleChange"
        />
        <v-checkbox
          class="align-baseline"
          v-model="form.confidentiality.value.value"
          :error-messages="form.confidentiality.errorMessage.value"
          :label="$t('itemType.new.your_message.confidentiality_policy')"
        />

        <v-btn color="main-red" type="submit">{{
          $t('itemType.new.your_message.form.submit')
        }}</v-btn>
      </Form>
    </template>
    <template #bottom-content>
      <v-btn
        class="text-action"
        variant="plain"
        @click.prevent="actorsStore.resetActorEditionMode()"
        block
      >
        {{ $t('forms.cancel') }}
      </v-btn>
    </template>
  </Dialog>
</template>

<script lang="ts" setup>
import Form from '@/components/forms/Form.vue'
import { useApplicationStore } from '@/stores/applicationStore'
import { z } from 'zod'
import { useField, useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import { ItemType } from '@/models/enums/app/ItemType'
import { i18n } from '@/plugins/i18n'
import Dialog from '@/components/global/Dialog.vue'
import { useActorsStore } from '@/stores/actorsStore'
import { storeToRefs } from 'pinia'

const actorsStore = useActorsStore()
const applicationStore = useApplicationStore()
const { showEditMessageDialog: showEditMessageType } = storeToRefs(applicationStore)

const schema = z.object({
  message: z.string().max(1000).optional(),
  confidentiality: z.boolean()
})

const { handleSubmit } = useForm({
  validationSchema: toTypedSchema(schema)
})

const form = {
  message: useField('message', undefined, { validateOnValueUpdate: false }),
  confidentiality: useField('confidentiality', undefined, { validateOnValueUpdate: false })
}

const onSubmit = handleSubmit(async (values) => {
  if (actorsStore.actorForSubmission) {
    actorsStore.actorForSubmission.creatorMessage = values.message
    await actorsStore.saveActor(
      actorsStore.actorForSubmission,
      actorsStore.actorEdition.actor !== null
    )
    applicationStore.showEditThanksDialog = true
  }
})

const getType = () => {
  switch (showEditMessageType.value) {
    case ItemType.ACTOR:
      return i18n.t('itemType.new.your_message.actor')
    case ItemType.PROJECT:
      return i18n.t('itemType.new.your_message.project')
    case ItemType.RESOURCE:
      return i18n.t('itemType.new.your_message.resource')
    default:
      return ''
  }
}
</script>
