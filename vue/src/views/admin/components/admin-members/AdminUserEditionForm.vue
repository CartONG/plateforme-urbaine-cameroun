<template>
  <Modal
    title="Edit User"
    :show="appStore.showEditContentDialog"
    @close="adminStore.userEdition.active = false"
  >
    <template #content>
      <div class="ContentForm__toValidate mt-3" v-if="userToEdit && !userToEdit.isValidated">
        <img loading="lazy" src="@/assets/images/actorToValidate.svg" alt="" />
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
        <div class="ContentForm__rolesRequestCtn">
          <span>{{ $t('admin.editForm.divercityAccess') }}</span>
          <div class="ContentForm__rolesRequestItem">
            <v-checkbox
              v-model="isSpaceAdmin"
              :label="$t('admin.editForm.spaceAdminLabel')"
              hide-details="auto"
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
import Chip from '@/components/content/Chip.vue'
import Modal from '@/components/global/Modal.vue'
import type { User } from '@/models/interfaces/auth/User'
import { i18n } from '@/plugins/i18n'
import { onInvalidSubmit } from '@/services/forms/FormService'
import { UserProfileForm } from '@/services/userAndAuth/forms/UserProfileForm'
import { useAdminStore } from '@/stores/adminStore'
import { useApplicationStore } from '@/stores/applicationStore'
import { computed, ref, watch } from 'vue'
import { UserRoles } from '@/models/enums/auth/UserRoles'
import { SpaceAdminService } from '@/services/divercity/SpaceAdminService'
import { useSpacesStore } from '@/stores/divercity/spacesStore'

const appStore = useApplicationStore()
const adminStore = useAdminStore()
const userToEdit: User | null = adminStore.userEdition.user
const { form, handleSubmit, isSubmitting } = UserProfileForm.getUserEditionForm(userToEdit)
const requestedRoles = UserProfileForm.getRolesList()
const spacesStore = useSpacesStore()

const isSpaceAdmin = ref(false)
const initialIsSpaceAdmin = ref(false)

const submitLabel = computed(() => {
  if (userToEdit) {
    return !userToEdit.isValidated ? i18n.t('forms.validate') : i18n.t('forms.edit')
  } else {
    return i18n.t('forms.create')
  }
})

watch(
  () => appStore.showEditContentDialog,
  (isOpen) => {
    if (isOpen) {
      const currentUser = adminStore.userEdition.user
      const hasAccess = currentUser?.roles.includes(UserRoles.DIVERCITY_SPACE_ADMIN) ?? false
      isSpaceAdmin.value = hasAccess
      initialIsSpaceAdmin.value = hasAccess
    }
  },
  { immediate: true }
)

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
  async (values) => {
    const userSubmission: Partial<User> = {
      ...values,
      roles: requestedRoles.filter((x) => x.selected.value).map((x) => x.value),
      requestedRoles: [],
      isValidated: true
    }

    let savedUser: User
    if (userToEdit) {
      savedUser = await adminStore.editUser(userSubmission)
    } else {
      savedUser = await adminStore.createUser(userSubmission)
    }

    const spaceIri = spacesStore.mainSpace?.['@id']
    const userIri = `/api/users/${savedUser.id}`
    if (spaceIri && isSpaceAdmin.value !== initialIsSpaceAdmin.value) {
      if (isSpaceAdmin.value) {
        await SpaceAdminService.assign(userIri, spaceIri)
      } else {
        await SpaceAdminService.revoke(userIri, spaceIri)
      }
    }
  },
  () => onInvalidSubmit
)
</script>
