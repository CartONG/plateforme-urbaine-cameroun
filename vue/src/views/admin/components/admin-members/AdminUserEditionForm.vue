<template>
  <Modal
    title="Edit User"
    :show="appStore.showEditContentDialog"
    @close="adminStore.userEdition.active = false"
  >
    <template #content>
      <div class="ContentForm__toValidate mt-3" v-if="userToEdit && !userToEdit.isValidated">
        <img src="@/assets/images/actorToValidate.svg" alt="" />
        <span class="ml-2">{{ $t('auth.editForm.newMember') }} 31 janvier 2025 à 11h30.</span>
      </div>
      <v-form @submit.prevent="submitForm" id="user-form" class="Form Form--user">
        <div class="Form__fieldCtn">
          <label class="Form__label required">{{ $t('auth.becomeMember.form.firstName') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.firstName.value.value"
            :error-messages="form.firstName.errorMessage.value"
            :placeholder="$t('auth.becomeMember.form.firstName')"
            @blur="form.firstName.handleChange"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label required">{{ $t('auth.becomeMember.form.lastName') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.lastName.value.value"
            :error-messages="form.lastName.errorMessage.value"
            :placeholder="$t('auth.becomeMember.form.lastName')"
            @blur="form.lastName.handleChange"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label required">{{ $t('auth.becomeMember.form.email') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.email.value.value"
            :error-messages="form.email.errorMessage.value"
            :placeholder="$t('auth.becomeMember.form.email')"
            @blur="form.email.handleChange"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('auth.becomeMemberAskRoles.form.organization') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.organisation.value.value"
            :error-messages="form.organisation.errorMessage.value"
            :placeholder="$t('auth.becomeMemberAskRoles.form.organization')"
            @blur="form.organisation.handleChange"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('auth.becomeMemberAskRoles.form.functions') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.position.value.value"
            :error-messages="form.position.errorMessage.value"
            :placeholder="$t('auth.becomeMemberAskRoles.form.functions')"
            @blur="form.position.handleChange"
          />
        </div>
        <div class="Form__fieldCtn">
          <label class="Form__label">{{ $t('auth.becomeMemberAskRoles.form.telephone') }}</label>
          <v-text-field
            density="compact"
            variant="outlined"
            v-model="form.phone.value.value"
            :error-messages="form.phone.errorMessage.value"
            :placeholder="$t('auth.becomeMemberAskRoles.form.telephone')"
            @blur="form.phone.handleChange"
          />
        </div>
        <div class="ContentForm__rolesRequestCtn">
          <span>{{ $t('auth.editForm.requestedRoles') }}</span>
          <div
            class="ContentForm__rolesRequestItem"
            v-for="(role, index) in requestedRoles"
            :key="index"
          >
            <v-checkbox v-model="role.selected.value" :label="role.label" hide-details="auto" />
            <Chip
              bg-color="main-yellow"
              :text="$t('auth.editForm.waitingValidation')"
              v-if="role.requested.value"
              class="ml-2"
            />
          </div>
        </div>
      </v-form>
    </template>
    <template #footer-left>
      <span class="text-action" @click="adminStore.userEdition.active = false">{{
        $t('forms.cancel')
      }}</span>
    </template>
    <template #footer-right>
      <v-btn type="submit" form="user-form" color="main-red" :loading="isSubmitting">{{
        submitLabel
      }}</v-btn>
    </template>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/global/Modal.vue'
import Chip from '@/components/content/Chip.vue'
import type { User } from '@/models/interfaces/auth/User'
import { UserProfileForm } from '@/services/userAndAuth/forms/UserProfileForm'
import { useAdminStore } from '@/stores/adminStore'
import { useApplicationStore } from '@/stores/applicationStore'
import { onInvalidSubmit } from '@/services/forms/FormService'
import { i18n } from '@/plugins/i18n'
import { computed } from 'vue'
const appStore = useApplicationStore()
const adminStore = useAdminStore()
const userToEdit: User | null = adminStore.userEdition.user
const { form, handleSubmit, isSubmitting } = UserProfileForm.getUserEditionForm(userToEdit)
const requestedRoles = UserProfileForm.getRolesList()

const submitLabel = computed(() => {
  if (userToEdit) {
    return !userToEdit.isValidated ? i18n.t('forms.validate') : i18n.t('forms.edit')
  } else {
    return i18n.t('forms.create')
  }
})

if (userToEdit) {
  requestedRoles.map((x) => {
    if (userToEdit.roles.includes(x.value)) {
      x.selected.value = true
    }
    if (userToEdit.requestedRoles && userToEdit.requestedRoles.includes(x.value)) {
      x.requested.value = true
    }
  })
}

const submitForm = handleSubmit(
  (values) => {
    const userSubmission: Partial<User> = {
      ...values,
      roles: requestedRoles.filter((x) => x.selected.value).map((x) => x.value),
      requestedRoles: [],
      isValidated: true
    }
    if (userToEdit) {
      adminStore.editUser(userSubmission)
    } else {
      adminStore.createUser(userSubmission)
    }
  },
  () => onInvalidSubmit
)
</script>
