<template>
    <Modal
        :title="actorToEdit ? $t('actors.form.editTitle') : $t('actors.form.createTitle')"
        :show="appStore.showEditContentDialog"
        @close="actorsStore.actorEdition.active = false">
        <template #content>
            <div class="ContentForm__toValidate mt-3" v-if="actorToEdit && !actorToEdit.isValidated">
                <img src="@/assets/images/actorToValidate.svg" alt="">
                <span class="ml-2">Nouvelle soumission de Prénom NOM reçue le 31 janvier 2025 à 11h30.</span>
            </div>
            <div class="ContentForm__ctn mt-4">
                <v-form @submit.prevent="submitForm" id="actor-form">
                    <!-- General infos -->
                    <v-text-field density="compact" variant="outlined" v-model="form.name.value.value"
                        :error-messages="form.name.errorMessage.value" :label="$t('actors.form.name')"
                        @blur="form.name.handleChange" />
                        
                    <v-text-field density="compact" variant="outlined" v-model="form.acronym.value.value"
                        :error-messages="form.acronym.errorMessage.value" :label="$t('actors.form.acronym')"
                        @blur="form.acronym.handleChange" />

                    <span>Logo</span>
                    <ImagesLoader @updateFiles="handleLogoUpdate" :existingImages="existingLogo" :uniqueImage="true" :externalImagesLoader="false"/>
                    
                    <v-select density="compact" variant="outlined" :label="$t('actors.form.category')"
                        class="mt-3"
                        v-model="(form.category.value.value as ActorsCategories)" :items="categoryItems"
                        :error-messages="form.category.errorMessage.value" @blur="form.category.handleChange" />

                    <v-select density="compact" variant="outlined" :label="$t('actors.form.expertise')" multiple
                        v-model="(form.expertises.value.value as ActorExpertise[])" :items="expertisesItems"
                        item-title="name" item-value="@id" :error-messages="form.expertises.errorMessage.value"
                        @blur="form.expertises.handleChange(form.expertises.value.value)" return-object />

                    <v-select density="compact" variant="outlined" :label="$t('actors.form.thematic')" multiple
                        v-model="(form.thematics.value.value as Thematic[])" :items="thematicsItems"
                        item-title="name" item-value="@id" :error-messages="form.thematics.errorMessage.value"
                        @blur="form.thematics.handleChange(form.thematics.value.value)" return-object />

                    <v-select density="compact" variant="outlined" :label="$t('actors.form.adminScope')" multiple
                        v-model="(form.administrativeScopes.value.value as AdministrativeScope[])"
                        :items="administrativeScopesItems" item-title="name" item-value="@id"
                        :error-messages="form.administrativeScopes.errorMessage.value"
                        @blur="form.administrativeScopes.handleChange(form.administrativeScopes.value.value)"
                        return-object />

                    <v-textarea :label="$t('actors.form.description')" variant="outlined"
                        v-model="form.description.value.value" :error-messages="form.description.errorMessage.value"
                        @blur="form.description.handleChange" />

                    <v-divider color="main-grey" class="border-opacity-100"></v-divider>

                    <!-- Contact infos -->
                    <FormSectionTitle :text="$t('actors.form.contact')" />
                    <v-text-field density="compact" variant="outlined" v-model="form.website.value.value"
                        :error-messages="form.website.errorMessage.value" @blur="form.website.handleChange"
                        :label="$t('actors.form.website')" class="mt-3" />
                    <v-text-field density="compact" variant="outlined" v-model="form.email.value.value"
                        :error-messages="form.email.errorMessage.value" @blur="form.email.handleChange"
                        label="Email" />
                    <v-text-field density="compact" variant="outlined" v-model="form.phone.value.value"
                        :error-messages="form.phone.errorMessage.value" @blur="form.phone.handleChange" type="tel"
                        :label="$t('actors.form.phone')" />

                    <FormSectionTitle :text="$t('actorPage.contact')" />
                    <v-text-field density="compact" variant="outlined" v-model="form.contactName.value.value"
                        :error-messages="form.contactName.errorMessage.value" @blur="form.contactName.handleChange"
                        :label="$t('actors.form.contactName')" class="mt-3" />
                    <v-text-field density="compact" variant="outlined" v-model="form.contactPosition.value.value"
                        :error-messages="form.contactPosition.errorMessage.value"
                        @blur="form.contactPosition.handleChange" :label="$t('actors.form.contactPosition')"
                        class="mt-3" />

                    <v-divider color="main-grey" class="border-opacity-100"></v-divider>
                    <FormSectionTitle :text="$t('actors.form.office')" />
                    <v-text-field density="compact" variant="outlined" v-model="form.officeName.value.value"
                        :error-messages="form.officeName.errorMessage.value" @blur="form.officeName.handleChange"
                        :label="$t('actors.form.officeName')" class="mt-3" />
                    <v-text-field density="compact" variant="outlined" v-model="form.officeAddress.value.value"
                        :error-messages="form.officeAddress.errorMessage.value"
                        @blur="form.officeAddress.handleChange" :label="$t('actors.form.officeAddress')"
                        class="mt-3" />
                    <v-text-field density="compact" variant="outlined" v-model="form.officeLocation.value.value"
                        :error-messages="form.officeLocation.errorMessage.value"
                        @blur="form.officeLocation.handleChange" :label="$t('actors.form.officeLocation')"
                        class="mt-3" />

                    <v-divider color="main-grey" class="border-opacity-100"></v-divider>
                    <FormSectionTitle :text="$t('actors.form.images')" />
                    <ImagesLoader @updateFiles="handleImagesUpdate" :existingImages="existingImages"/>
                </v-form>
            </div>
        </template>
        <template #footer-left>
            <v-btn color="white" @click="actorsStore.actorEdition.active = false">{{ $t('forms.cancel') }}</v-btn>
        </template>
        <template #footer-right>
            <v-btn type="submit" form="actor-form" color="main-red" :loading="isSubmitting">{{ actorToEdit ? $t('forms.modify') : $t('forms.create') }}</v-btn>
        </template>
    </Modal>
</template>

<script setup lang="ts">
import { type Actor, type ActorSubmission } from '@/models/interfaces/Actor';
import { ActorsFormService } from '@/services/actors/ActorsForm';
import { useActorsStore } from '@/stores/actorsStore';
import { useApplicationStore } from '@/stores/applicationStore';
import FormSectionTitle from '@/components/text-elements/FormSectionTitle.vue';
import { computed, onMounted, ref, type Ref } from 'vue';
import type { ContentImageFromUserFile } from '@/models/interfaces/ContentImage';
import { ActorsCategories } from '@/models/enums/contents/actors/ActorsCategories';
import type { ActorExpertise } from '@/models/interfaces/ActorExpertise';
import type { Thematic } from '@/models/interfaces/Thematic';
import type { AdministrativeScope } from '@/models/interfaces/AdministrativeScope';
import Modal from '@/components/global/Modal.vue';
import type { MediaObject } from '@/models/interfaces/MediaObject';
import ImagesLoader from '@/components/forms/ImagesLoader.vue';
import { useThematicStore } from '@/stores/thematicStore';
const appStore = useApplicationStore();
const actorsStore = useActorsStore();
const thematicsStore = useThematicStore()

const actorToEdit: Actor | null = actorsStore.actorEdition.actor
const { form, handleSubmit, isSubmitting } = ActorsFormService.getActorsForm(actorToEdit);

const categoryItems = Object.values(ActorsCategories)
const expertisesItems = actorsStore.actorsExpertises
const thematicsItems = computed(() => thematicsStore.thematics)
const administrativeScopesItems = actorsStore.actorsAdministrativesScopes

const existingLogo = ref<(MediaObject | string)[]>([]);
const existingImages = ref<(MediaObject | string)[]>([])
let existingHostedImages: MediaObject[] = []
let existingExternalImages: string[] = []
onMounted(async () => {
    await thematicsStore.getAll()
    if (actorToEdit) {
        actorToEdit.logo ? existingLogo.value = [actorToEdit.logo] : existingLogo.value = []
        existingImages.value = [...actorToEdit.images, ...actorToEdit.externalImages]
        existingHostedImages = actorToEdit.images
        existingExternalImages = actorToEdit.externalImages
    }
})

const newLogo: Ref<ContentImageFromUserFile[]> = ref([])
function handleLogoUpdate(list: any) {
    newLogo.value = list.selectedFiles
}

const imagesToUpload: Ref<ContentImageFromUserFile[]> = ref([])
function handleImagesUpdate(lists: any) {
    imagesToUpload.value = lists.selectedFiles
    existingHostedImages = []
    existingExternalImages = []
    lists.existingImages.forEach((image: MediaObject | string) => {
        if (typeof image === 'string') {
            existingExternalImages.push(image)
        } else {
            existingHostedImages.push(image)
        }
    })
}

const submitForm = handleSubmit(
    values => {
        const actorSubmission: ActorSubmission = {
            ...(values as any),
            logoToUpload: newLogo.value[0],
            images: existingHostedImages,
            externalImages: existingExternalImages,
            imagesToUpload: [...imagesToUpload.value]
        }
        actorsStore.createOrEditActor(actorSubmission, actorToEdit !== null)
    },
    errors => {
        console.error('Form validation failed:', errors);
    }
);
</script>
