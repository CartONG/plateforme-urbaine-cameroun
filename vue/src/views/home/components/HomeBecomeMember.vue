<template>
  <div class="HomeBecomeMember">
    <div class="HomeBecomeMember__content">
      <h3 class="HomeBecomeMember__title">{{ $t('auth.becomeMemberWhy.title') }}</h3>
      <div class="HomeBecomeMember__reasonsCtn">
        <CheckPoint
          v-for="reason in reasons"
          :key="reason"
          :highlighted="true"
          :highlight-color="'white'"
          :label="reason"
        />
      </div>
      <router-link
        append
        :to="{ query: { ...$route.query, dialog: DialogKey.AUTH_BECOME_MEMBER } }"
        class="Link--withoutUnderline"
      >
        <v-btn color="main-red" type="submit" block>{{ $t('home.becomeMember.action') }}</v-btn>
      </router-link>
    </div>
    <div class="HomeBecomeMember__imageCtn">
      <img
        class="HomeBecomeMember__image"
        loading="lazy"
        src="@/assets/images/home_becomemember.jpg"
        alt=""
      />
      <img
        class="HomeBecomeMember__image HomeBecomeMember__image--shape"
        loading="lazy"
        src="@/assets/images/home_becomemember_shape.svg"
        alt=""
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { i18n } from '@/plugins/i18n'
import CheckPoint from '@/components/global/CheckPoint.vue'
import { DialogKey } from '@/models/enums/app/DialogKey'

const reasons = [
  i18n.t('auth.becomeMemberWhy.form.reasons.urbanTransformation'),
  i18n.t('auth.becomeMemberWhy.form.reasons.accessResources'),
  i18n.t('auth.becomeMemberWhy.form.reasons.accessUpdates')
]
</script>

<style lang="scss">
.HomeBecomeMember {
  --reasons-margin-x: 2.5rem;
  background-color: rgb(var(--v-theme-light-yellow));
  display: flex;
  flex-flow: row nowrap;
  border-radius: 0.5rem;
  border-bottom: solid 2px rgb(var(--v-theme-main-green));
  position: relative;
  overflow: hidden;

  .HomeBecomeMember__content {
    display: flex;
    flex-flow: column nowrap;
    align-items: flex-start;
    justify-content: center;
    margin: 6rem 6rem;
    gap: 2rem;
    flex: 1 1 30%;
    z-index: 1;
    max-width: 40%;
    overflow: hidden;

    .HomeBecomeMember__title {
      color: rgb(var(--v-theme-main-red));
      font-size: $font-size-h2;
    }
    .HomeBecomeMember__reasonsCtn {
      display: flex;
      flex-flow: column nowrap;
      gap: 1rem;
    }
  }
  .HomeBecomeMember__imageCtn {
    max-width: 60%;
    overflow: hidden;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    position: absolute;
    right: 0;
    height: 100%;

    .HomeBecomeMember__image {
      height: 100%;

      &--shape {
        transform: translateX(-20rem);
        height: inherit;
        position: absolute;
      }
    }
  }
}

@media (max-width: $bp-md) {
  .HomeBecomeMember {
    --reasons-margin-x: 0.5rem;
  }
}
</style>
