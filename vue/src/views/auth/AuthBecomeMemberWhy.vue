<template>
  <AuthDialog class="AuthBecomeMemberWhy">
    <template #title>{{ $t('auth.becomeMemberWhy.title') }}</template>
    <template #content>
      <img
        loading="lazy"
        class="AuthBecomeMemberWhy__image"
        src="@/assets/images/auth/become-member-why.png"
        alt=""
      />
      <div class="AuthBecomeMemberWhy__reasonsCtn">
        <CheckPoint v-for="reason in reasons" :key="reason" :label="reason" />
      </div>
      <router-link
        append
        :to="{ query: { ...$route.query, dialog: DialogKey.AUTH_BECOME_MEMBER } }"
        class="Link--withoutUnderline"
      >
        <v-btn color="main-red" type="submit" block>{{
          $t('auth.becomeMemberWhy.form.submit')
        }}</v-btn>
      </router-link>
    </template>
  </AuthDialog>
</template>

<script setup lang="ts">
import CheckPoint from '@/components/global/CheckPoint.vue'
import { DialogKey } from '@/models/enums/app/DialogKey'
import { i18n } from '@/plugins/i18n'
import AuthDialog from '@/views/auth/AuthDialog.vue'

const reasons = [
  i18n.t('auth.becomeMemberWhy.form.reasons.urbanTransformation'),
  i18n.t('auth.becomeMemberWhy.form.reasons.accessResources'),
  i18n.t('auth.becomeMemberWhy.form.reasons.accessUpdates')
]
</script>

<style lang="scss">
.AuthBecomeMemberWhy {
  --reasons-margin-x: 2.5rem;

  .AuthBecomeMemberWhy__image {
    width: 100%;
    border-radius: 1rem;
    border-bottom: 3px solid rgb(var(--v-theme-main-green));
  }
  .AuthBecomeMemberWhy__reasonsCtn {
    display: flex;
    flex-flow: column nowrap;
    gap: 1rem;
    margin: 2rem var(--reasons-margin-x);
  }
}

@media (max-width: $bp-md) {
  .AuthBecomeMemberWhy {
    --reasons-margin-x: 0.5rem;
  }
}
</style>
