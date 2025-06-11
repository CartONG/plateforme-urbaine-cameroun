<template>
  <AuthDialog class="AuthbecomeMemberAskRoles">
    <template #title>{{ $t('auth.becomeMemberAskRoles.title') }}</template>
    <template #content>
      <span class="mb-4 text-center">{{ $t('auth.becomeMemberAskRoles.form.info') }}</span>
      <Form @submit="onSubmit">
        <v-text-field
          density="compact"
          variant="outlined"
          v-model="form.organisation.value.value"
          :label="$t('auth.becomeMemberAskRoles.form.organization')"
          :error-messages="form.organisation.errorMessage.value"
          @blur="form.organisation.handleChange"
        />
        <v-text-field
          density="compact"
          variant="outlined"
          v-model="form.position.value.value"
          :label="$t('auth.becomeMemberAskRoles.form.functions')"
          :error-messages="form.position.errorMessage.value"
          @blur="form.position.handleChange"
        />
        <vue-tel-input v-model="form.phone.value.value" @validate="phoneValidation"></vue-tel-input>
        <v-text-field
          density="compact"
          variant="outlined"
          v-model="form.signUpMessage.value.value"
          :label="$t('auth.becomeMemberAskRoles.form.message')"
          :error-messages="form.signUpMessage.errorMessage.value"
          @blur="form.signUpMessage.handleChange"
        />
        <span>{{ $t('auth.becomeMemberAskRoles.form.actionsRequest.label') }}</span>
        <v-list>
          <v-list-item v-for="(action, index) in actionList" :key="index">
            <v-checkbox v-model="action.selected.value" :label="action.label" hide-details="auto" />
          </v-list-item>
        </v-list>
        <v-btn color="main-red" type="submit">{{
          $t('auth.becomeMemberAskRoles.form.submit')
        }}</v-btn>
      </Form>
    </template>
  </AuthDialog>
</template>

<script setup lang="ts">
import Form from '@/components/forms/Form.vue'
import { UserProfileForm } from '@/services/userAndAuth/forms/UserProfileForm'
import { useUserStore } from '@/stores/userStore'
import AuthDialog from '@/views/auth/AuthDialog.vue'
const userStore = useUserStore()

const actionList = UserProfileForm.getRolesList()

const { form, handleSubmit } = UserProfileForm.getSignUpThanksForm()

let internationalPhoneNumber: string | null = null
function phoneValidation(phoneObject: any) {
  form.phone.value.value = phoneObject.nationalNumber
  internationalPhoneNumber = phoneObject.number
}

const onSubmit = handleSubmit(
  (values) => {
    console.log(values)
    console.log(internationalPhoneNumber)
    const requestedRoles = actionList
      .filter((action) => action.selected.value)
      .map((action) => action.value)

    userStore.patchUser({
      ...values,
      phone: internationalPhoneNumber as string,
      requestedRoles: requestedRoles
    })
  },
  (errors) => {
    console.error('Form validation failed:', errors)
  }
)
</script>

<style lang="scss">
.AuthbecomeMemberAskRoles {
  .v-checkbox .v-selection-control {
    min-height: 0 !important;
  }
}
</style>
