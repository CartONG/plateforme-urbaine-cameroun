<template>
    <Modal
        :title="$t('resources.form.title.' + type)"
        :show="isShown" @close="$emit('close')">
        <template #content>
            <v-form @submit.prevent="submitForm" id="resource-form" class="Form Form--resource">
                <!--<NewSubmission
                    v-if="type === FormType.VALIDATE && resource"
                    :created-by="resource.createdBy"
                    :created-at="resource.createdAt" />-->
                <div class="Form__fieldCtn">
                    <label class="Form__label required">{{ $t('resources.form.fields.name.label') }}</label>
                    <v-text-field density="compact" variant="outlined" v-model="form.name.value.value"
                    :error-messages="form.name.errorMessage.value"
                    :placeholder="$t('resources.form.fields.name.label')"
                    @blur="form.name.handleChange" />
                </div>
                <div class="Form__fieldCtn">
                    <label class="Form__label required">{{ $t('resources.form.fields.description.label') }}</label>
                    <v-textarea variant="outlined"
                        :placeholder="$t('resources.form.fields.description.label')"
                        v-model="form.description.value.value" :error-messages="form.description.errorMessage.value"
                        @blur="form.description.handleChange"/>
                </div>
                
                <!--<FormSectionTitle :text="$t('resources.form.section.thematics')" />
                <v-select density="compact" variant="outlined" multiple
                    v-model="(form.thematics.value.value as Thematic[])" :items="thematics"
                    :placeholder="$t('resources.form.section.thematics')"
                    item-title="name" item-value="@id" :error-messages="form.thematics.errorMessage.value"
                    @blur="form.thematics.handleChange(form.thematics.value.value)" return-object />-->

                <div class="Form__fieldCtn">
                    <label class="Form__label required">{{ $t('resources.form.fields.type.label') }}</label>
                    <v-select density="compact" variant="outlined"
                        v-model="(form.type.value.value as ResourceType)" :items="Object.values(ResourceType)"
                        :placeholder="$t('resources.form.fields.type.label')"
                        :item-title="item => $t('resources.resourceType.' + item)" :item-value="item => item" :error-messages="form.type.errorMessage.value"
                        @blur="form.type.handleChange(form.type.value.value)"  />
                </div>


                <div class="Form__fieldCtn">
                    <label class="Form__label">{{ $t('resources.form.fields.file.label') }}</label>
                    <FileUploader />
                </div>

                <div class="Form__fieldCtn">
                    <label class="Form__label">{{ $t('resources.form.fields.link.label') }}</label>
                    <v-text-field density="compact" variant="outlined" v-model="form.link.value.value"
                        :placeholder="$t('resources.form.fields.link.placeholder')"
                        :error-messages="form.link.errorMessage.value"
                        @blur="form.link.handleChange" />
                </div>
            </v-form>
        </template>
        <template #footer-left>
            <span class="text-action" @click="$emit('close')">{{ $t('forms.cancel') }}</span>
        </template>
        <template #footer-right>
            <v-btn type="submit" form="resource-form" color="main-red" :loading="isSubmitting">{{ $t('forms.' + type) }}</v-btn>
        </template>
    </Modal>
</template>

<script setup lang="ts">
import { type Resource, type ResourceSubmission } from '@/models/interfaces/Resource';
import { ResourceFormService } from '@/services/resources/ResourceFormService';
import { useResourceStore } from '@/stores/resourceStore';
import { onMounted } from 'vue';
import Modal from '@/components/global/Modal.vue';
import { FormType } from '@/models/enums/app/FormType';
import { nestedObjectsToIri } from '@/services/api/ApiPlatformService';
import { onInvalidSubmit } from '@/services/forms/FormService';
import { ResourceType } from '@/models/enums/contents/ResourceType';
import FileUploader from '@/components/forms/FileUploader.vue';

const resourceStore = useResourceStore();

const props = defineProps<{
  type: FormType,
  resource: Resource | null,
  isShown: boolean,
}>()

const emit = defineEmits(['submitted', 'close'])

const { form, handleSubmit, isSubmitting } = ResourceFormService.getForm(props.resource);

onMounted(async () => {
    // await thematicsStore.getAll()
})

const submitForm = handleSubmit(
    async (values) => {
        const resourceSubmission: ResourceSubmission = nestedObjectsToIri(values)
        if ([FormType.EDIT, FormType.VALIDATE].includes(props.type) && props.resource) {
            resourceSubmission.id = props.resource.id
        }
        
        const submittedResource = await resourceStore.submitResource(resourceSubmission, props.type)
        emit('submitted', submittedResource)
    },
    () => onInvalidSubmit
);
</script>
