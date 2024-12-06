<template>
  <Dialog v-bind="$attrs">
    <template v-for="(_, name) in $slots" v-slot:[name]="slotData">
      <slot :name="name" v-bind="slotData" />
    </template>
    <template #bottom-content>
      <slot name="bottom-content">
        <span>{{ $t('auth.becomeMember.question') }}</span>
        <router-link append :to="{ query: { ...$route.query, dialog: DialogKey.AUTH_SIGN_IN } }">
          {{ $t('auth.signIn.title') }}
        </router-link>
      </slot>
    </template>
  </Dialog>
</template>

<script setup lang="ts">
import Dialog from '@/components/global/Dialog.vue'
import { DialogKey } from '@/models/enums/app/DialogKey'
</script>

<style lang="scss">
.AuthDialog {
  &__forgotPassword {
    font-size: $font-size-sm;
    margin-bottom: 1rem;
  }
  &__error {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 1rem;
    width: 100%;
    color: rgb(var(--v-theme-main-red));
    font-weight: 700;
    background-color: rgb(var(--v-theme-light-yellow));
    margin-bottom: 1rem;
  }
}
</style>
