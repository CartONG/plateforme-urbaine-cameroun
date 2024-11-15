<template>
  <AuthDialog class="AuthBecomeMemberThanks">
    <template #title>{{ $t('auth.becomeMemberThanks.title') }}</template>
    <template #content>
      <CheckPoint class="mb-4" :label="$t('auth.becomeMemberThanks.subtitle')" :highlighted="true" />
      <span class="mb-4 text-center">{{ $t('auth.becomeMemberThanks.form.info') }}</span>
      <Form @submit="onSubmit">
        <v-text-field 
          v-model="form.organisation.value.value" 
          :label="$t('auth.becomeMemberThanks.form.organization')"
          :error-messages="form.organisation.errorMessage.value"
          @blur="form.organisation.handleChange"
        />
        <v-text-field 
          v-model="form.position.value.value" 
          :label="$t('auth.becomeMemberThanks.form.functions')"
          :error-messages="form.position.errorMessage.value"
          @blur="form.position.handleChange"
        />
        <v-text-field 
          v-model="form.phone.value.value" 
          :label="$t('auth.becomeMemberThanks.form.telephone')"
          :error-messages="form.phone.errorMessage.value"
          @blur="form.phone.handleChange"
        />
        <v-text-field 
          v-model="form.signUpMessage.value.value" 
          :label="$t('auth.becomeMemberThanks.form.message')"
          :error-messages="form.signUpMessage.errorMessage.value"
          @blur="form.signUpMessage.handleChange"
        />
        <span>{{ $t('auth.becomeMemberThanks.form.actionsRequest.label') }}</span>
        <v-list>
          <v-list-item v-for="(action, index) in actionList" :key="index">
            <v-checkbox v-model="action.selected.value" :label="action.label" hide-details="auto" />
          </v-list-item>
        </v-list>
        <v-btn color="main-red" type="submit">{{ $t('auth.becomeMemberThanks.form.submit') }}</v-btn>
      </Form>
    </template>
  </AuthDialog>
</template>

<script setup lang="ts">
import AuthDialog from '@/views/auth/AuthDialog.vue';
import Form from '@/components/forms/Form.vue';
import CheckPoint from '@/components/global/CheckPoint.vue';
import { UserProfileForm } from '@/services/userAndAuth/forms/UserProfileForm';
import { useUserStore } from '@/stores/userStore';
const userStore = useUserStore();

const actionList = UserProfileForm.getRolesList()

const {form, errors, handleSubmit, isSubmitting} = UserProfileForm.getSignUpThanksForm();

const onSubmit = handleSubmit(
  values => {
    const requestedRoles = actionList.filter(action => action.selected.value).map(action => action.value)
    userStore.patchUser({...values, requestedRoles: requestedRoles})
  },
  errors => {
    console.error('Form validation failed:', errors);
  }
);
</script>

<style lang="scss">
.AuthBecomeMemberThanks {
  .v-checkbox .v-selection-control {
    min-height: 0 !important;
  }
}
</style>