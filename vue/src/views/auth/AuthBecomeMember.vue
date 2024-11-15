<template>
  <AuthDialog>
    <template #title>{{ $t('auth.becomeMember.title') }}</template>
    <template #subtitle>
      <i18n-t keypath="auth.becomeMember.subtitle.label" >
        <template v-slot:url>
          <router-link
            append :to="{ query: { ...$route.query, dialog: DialogKey.AUTH_BECOME_MEMBER_WHY } }">
            {{ $t('auth.becomeMember.subtitle.link') }}
          </router-link>
        </template>
      </i18n-t>
    </template>
    <template #content>
      <Form @submit.prevent="onSubmit">
        <v-text-field 
          v-model="form.firstName.value.value"
          :label="$t('auth.becomeMember.form.firstName')"
          :error-messages="form.firstName.errorMessage.value"
          @blur="form.firstName.handleChange"
        />
        <v-text-field
          v-model="form.lastName.value.value"
          :label="$t('auth.becomeMember.form.lastName')"
          :error-messages="form.lastName.errorMessage.value"
          @blur="form.lastName.handleChange"
        />
        <v-text-field
          type="email"
          v-model="form.email.value.value"
          :label="$t('auth.becomeMember.form.email')"
          :error-messages="form.email.errorMessage.value"
          @blur="form.email.handleChange"
        />
        <v-text-field
          type="password"
          v-model="form.plainPassword.value.value"
          :label="$t('auth.becomeMember.form.password')"
          :error-messages="form.plainPassword.errorMessage.value"
          @blur="form.plainPassword.handleChange"
        />
        <v-text-field
          type="password"
          v-model="form.confirmPassword.value.value"
          :label="$t('auth.becomeMember.form.confirmPassword')"
          :error-messages="form.confirmPassword.errorMessage.value"
          @blur="form.confirmPassword.handleChange"
        />
        <v-checkbox class="align-baseline"
          v-model="form.acceptTerms.value.value"
          :error-messages="form.acceptTerms.errorMessage.value"
        >
          <template v-slot:label>
            <i18n-t keypath="auth.becomeMember.form.privacyPolicy.label" tag="span" >
              <template v-slot:url1>
                <router-link
                  append :to="{ query: { ...$route.query, dialog: DialogKey.AUTH_BECOME_MEMBER } }">
                  {{ $t('auth.becomeMember.form.privacyPolicy.confidentialPolicy') }}
                </router-link>
              </template>
              <template v-slot:url2>
                <router-link
                  append :to="{ query: { ...$route.query, dialog: DialogKey.AUTH_BECOME_MEMBER } }">
                  {{ $t('auth.becomeMember.form.privacyPolicy.usePolicy') }}
                </router-link>
              </template>
            </i18n-t>
          </template>
        </v-checkbox>      
        <div class="AuthDialog__error" v-if="userStore.errorWhileSignInOrSignUp">{{ $t('auth.becomeMember.error') }}</div>
        <v-btn color="main-red" type="submit" block>{{ $t('auth.becomeMember.form.submit') }}</v-btn>
      </Form>
    </template>
  </AuthDialog>
</template>

<script setup lang="ts">
import AuthDialog from '@/views/auth/AuthDialog.vue';
import Form from '@/components/forms/Form.vue';
import { DialogKey } from '@/models/enums/app/DialogKey';
import { I18nT } from 'vue-i18n';
import { UserProfileForm } from '@/services/userAndAuth/forms/UserProfileForm';
import { useUserStore } from '@/stores/userStore';

const {form, errors, handleSubmit, isSubmitting} = UserProfileForm.getSignUpForm();
const userStore = useUserStore();

const onSubmit = handleSubmit(
  values => {
    userStore.signUp(values);
  },
  errors => {
    console.error('Form validation failed:', errors);
  }
);
</script>