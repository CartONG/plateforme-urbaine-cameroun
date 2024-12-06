<template>
  <div class="UserAccount" v-if="userStore.userIsLogged">
    <SectionBanner :text="$t('account.title')" />
    <v-form @submit.prevent="submitForm">
      <div class="UserAccount__ctn">
        <div class="UserBlock UserBlock--left">
          <div class="UserAccount__avatarBlock">
            <div class="UserAccount__avatarCtn" @click="triggerFileInput">
              <div class="UserAccount__avatar">
                <img
                  v-if="selectedProfileImage.length > 0"
                  :src="selectedProfileImage[0].preview"
                />
                <img
                  v-else-if="userStore.currentUser?.logo"
                  :src="userStore.currentUser.logo.contentUrl"
                />
                <img v-else src="@/assets/images/user/default_avatar.png" alt="" />
              </div>
              <v-btn
                icon="mdi-pencil"
                class="UserAccount__avatarEdit"
                @click.stop="triggerFileInput"
              ></v-btn>
            </div>
            <div class="UserAccount__avatar--rightInputs">
              <v-text-field
                v-model="form.firstName.value.value"
                :error-messages="form.firstName.errorMessage.value"
                :label="$t('auth.becomeMember.form.firstName')"
                @submit="form.firstName.handleChange"
              />
              <v-text-field
                v-model="form.lastName.value.value"
                :error-messages="form.lastName.errorMessage.value"
                :label="$t('auth.becomeMember.form.lastName')"
                @submit="form.lastName.handleChange"
              />
            </div>
          </div>
          <span class="UserAccount--avatarErrorMessage"> {{ avatarErrorMessage }} </span>
          <v-text-field
            v-model="form.email.value.value"
            :error-messages="form.email.errorMessage.value"
            :label="$t('auth.becomeMember.form.email')"
            @submit="form.email.handleChange"
          />
          <v-text-field
            v-model="form.organisation.value.value"
            :error-messages="form.organisation.errorMessage.value"
            :label="$t('auth.becomeMemberThanks.form.organization')"
            @submit="form.organisation.handleChange"
          />
          <v-text-field
            v-model="form.position.value.value"
            :error-messages="form.position.errorMessage.value"
            :label="$t('auth.becomeMemberThanks.form.functions')"
            @submit="form.position.handleChange"
          />
          <v-text-field
            v-model="form.phone.value.value"
            :error-messages="form.phone.errorMessage.value"
            :label="$t('auth.becomeMemberThanks.form.telephone')"
            @submit="form.phone.handleChange"
          />
          <a href="#">{{ $t('account.changePassword') }}</a>
          <div class="UserAccount__rolesBlock">
            <span>{{ $t('account.roles') }}</span>
            <div
              class="UserAccount__rolesItem"
              v-for="(role, index) in requestedRoles"
              :key="index"
            >
              <v-checkbox
                v-model="role.selected.value"
                :label="role.label"
                hide-details="auto"
                :disabled="role.givenByAdmin.value"
              />
              <Chip
                bg-color="main-green"
                :text="$t('auth.editForm.validated')"
                v-if="role.givenByAdmin.value"
                class="ml-2"
              />
              <Chip
                bg-color="main-blue"
                :text="$t('auth.editForm.newRequest')"
                v-if="role.newlyRequested.value"
                class="ml-2"
              />
              <Chip
                bg-color="main-yellow"
                :text="$t('auth.editForm.waitingValidation')"
                v-if="role.requested.value && role.selected.value"
                class="ml-2"
              />
            </div>
          </div>
          <a href="#" class="hide-sm">{{ $t('account.deleteAccount') }}</a>
          <v-btn type="submit" color="main-red hide-sm" :loading="isSubmitting" class="w-100">{{
            $t('account.save')
          }}</v-btn>
        </div>
        <div class="UserBlock UserBlock--right">
          <div class="UserAccount__description">
            <v-textarea
              hide-details
              v-model="form.description.value.value"
              :error-messages="form.description.errorMessage.value"
              :label="$t('auth.becomeMemberThanks.form.description')"
              @submit="form.description.handleChange"
              auto-grow
              row-height="30"
              rows="13"
            />
          </div>
          <a href="#" class="show-sm">{{ $t('account.deleteAccount') }}</a>
          <v-btn type="submit" color="main-red show-sm" :loading="isSubmitting" class="w-100">{{
            $t('account.save')
          }}</v-btn>
          <BasicCard icon="mdi-pencil-outline" class="mt-6">
            <span class="ml-2">{{ $t('header.content') }}</span>
          </BasicCard>
          <v-divider></v-divider>
          <BasicCard
            icon="mdi-plus"
            v-if="userStore.userHasRole(UserRoles.EDITOR_ACTORS)"
            @click="actorsStore.setActorEditionMode(null)"
          >
            <span class="ml-2">{{ $t('header.addActor') }}</span>
          </BasicCard>
          <BasicCard icon="mdi-plus" v-if="userStore.userHasRole(UserRoles.EDITOR_PROJECTS)">
            <span class="ml-2">{{ $t('header.addProject') }}</span>
          </BasicCard>
          <BasicCard icon="mdi-plus" v-if="userStore.userHasRole(UserRoles.EDITOR_DATA)">
            <span class="ml-2">{{ $t('header.addData') }}</span>
          </BasicCard>
          <BasicCard icon="mdi-plus" v-if="userStore.userHasRole(UserRoles.EDITOR_RESSOURCES)">
            <span class="ml-2">{{ $t('header.addResource') }}</span>
          </BasicCard>
        </div>
      </div>
    </v-form>
  </div>
  <input
    type="file"
    ref="fileInput"
    @change="handleFileChange"
    accept="image/*"
    style="display: none"
  />
</template>
<script setup lang="ts">
import SectionBanner from '@/components/banners/SectionBanner.vue'
import BasicCard from '@/components/global/BasicCard.vue'
import Chip from '@/components/content/Chip.vue'
import { ContentImageType } from '@/models/enums/app/ContentImageType'
import { UserRoles } from '@/models/enums/auth/UserRoles'
import type { UserSubmission } from '@/models/interfaces/auth/User'
import type { ContentImageFromUserFile } from '@/models/interfaces/ContentImage'
import { UserProfileForm } from '@/services/userAndAuth/forms/UserProfileForm'
import { InputImageValidator } from '@/services/files/InputImageValidator'
import { useActorsStore } from '@/stores/actorsStore'
import { useUserStore } from '@/stores/userStore'
import { onMounted, ref, watch, type Ref } from 'vue'
const userStore = useUserStore()
const actorsStore = useActorsStore()
let requestedRoles = UserProfileForm.getRolesList()
const { form, handleSubmit, isSubmitting } = UserProfileForm.getUserEditionForm(
  userStore.currentUser
)

watch(
  () => userStore.currentUser,
  () => {
    requestedRoles = UserProfileForm.getRolesList()
    setRolesStatus()
  },
  { deep: true }
)

function setRolesStatus() {
  requestedRoles.map((x) => {
    if (userStore.currentUser && userStore.currentUser.roles.includes(x.value)) {
      x.selected.value = true
      x.givenByAdmin.value = true
    }
    if (
      userStore.currentUser &&
      userStore.currentUser.requestedRoles &&
      userStore.currentUser.requestedRoles.includes(x.value)
    ) {
      x.selected.value = true
      x.requested.value = true
    }
  })
}

onMounted(() => {
  setRolesStatus()
  watch(
    () => requestedRoles,
    () => {
      requestedRoles.map((x) => {
        if (x.selected.value) {
          if (!x.requested.value && !x.givenByAdmin.value) {
            x.newlyRequested.value = true
          }
        } else {
          x.newlyRequested.value = false
        }
      })
    },
    { deep: true }
  )
})

const selectedProfileImage: Ref<ContentImageFromUserFile[]> = ref([])
const fileInput = ref<HTMLInputElement | null>(null)
const avatarErrorMessage = ref('')
const triggerFileInput = () => {
  fileInput.value?.click()
}
const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files && target.files[0]) {
    const file = target.files[0]
    const fileStatus = InputImageValidator.validateImageFromFile(file)
    if (fileStatus.isValid) {
      selectedProfileImage.value = []
      const preview = URL.createObjectURL(file)
      selectedProfileImage.value.push({
        type: ContentImageType.CONTENT_IMAGE_FROM_USER_FILE,
        name: file.name,
        preview: preview,
        file: file
      })
    } else {
      avatarErrorMessage.value = fileStatus.message
      window.setTimeout(() => {
        avatarErrorMessage.value = ''
      }, 3000)
    }
  } else {
    selectedProfileImage.value = []
  }
}

const submitForm = handleSubmit(
  (values) => {
    const userSubmission: Partial<UserSubmission> = {
      ...values,
      requestedRoles: requestedRoles
        .filter((x) => x.selected.value && !x.givenByAdmin.value)
        .map((x) => x.value)
    }
    userStore.patchUser(
      userSubmission,
      selectedProfileImage.value.length > 0,
      selectedProfileImage.value[0]?.file || null
    )
  },
  (errors) => {
    console.error('Form validation failed:', errors)
  }
)
</script>
<style lang="scss">
.UserAccount {
  display: flex;
  flex-flow: column wrap;
  width: 100%;
  &__ctn {
    display: flex;
    flex-flow: row wrap;
  }
}
.UserBlock {
  display: flex;
  flex-flow: column wrap;
  gap: 1rem;
  &--left {
    width: 70%;
    padding-right: 10%;
    @media (max-width: $bp-sm) {
      width: 100%;
      padding-right: 0%;
    }
  }
  &--right {
    width: 30%;
    @media (max-width: $bp-sm) {
      width: 100%;
    }
  }
}
.UserAccount {
  &__avatarBlock {
    display: flex;
    flex-direction: row;
    align-items: center;
    @media (max-width: $bp-sm) {
      flex-direction: column;
      align-items: center;
    }
  }
  &__avatarCtn {
    position: relative;
  }
  &__avatar {
    height: 8rem;
    width: 8rem;
    border-radius: 50%;
    overflow: hidden;
    @media (max-width: $bp-sm) {
      width: 8rem;
      margin-bottom: 1rem;
    }
    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    &--rightInputs {
      display: flex;
      flex-direction: column;
      margin-left: 3rem;
      flex-grow: 1;
      @media (max-width: $bp-sm) {
        margin-left: 0;
        width: 100%;
      }
    }
  }
  &--avatarErrorMessage {
    color: rgb(var(--v-theme-main-red));
    font-size: $font-size-xs;
  }
  &__avatarEdit {
    position: absolute;
    bottom: 0rem;
    right: 0rem;
    z-index: 1000;
  }
  &__rolesBlock {
    background-color: rgb(var(--v-theme-light-yellow));
    padding: 1rem;
  }
  &__rolesItem {
    display: flex;
    align-items: center;
  }
  &__description {
    background-color: white;
    border: 1px solid black;
  }
}
</style>
