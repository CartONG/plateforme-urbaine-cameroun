<template>
  <div class="MyMapWelcomeBlock" v-if="!userStore.userIsLogged && !isClosed">
    <h2>{{ $t('myMap.leftSidebar.welcome.title') }}</h2>
    <p>{{ $t('myMap.leftSidebar.welcome.description') }}</p>
    <div class="MyMapWelcomeBlock__moreAccess">
      <span>{{ $t('myMap.leftSidebar.welcome.moreAccess') }}</span>
      <LoginButton />
    </div>
    <span class="MyMapWelcomeBlock__close" @click="isClosed = true">{{
      $t('myMap.leftSidebar.welcome.close')
    }}</span>
  </div>
</template>

<script setup lang="ts">
import { useUserStore } from '@/stores/userStore'
import LoginButton from '@/views/_layout/header/LoginButton.vue'
import { ref } from 'vue'

const userStore = useUserStore()
const isClosed = ref(false)
</script>

<style lang="scss">
.MyMapWelcomeBlock {
  background: rgb(var(--v-theme-light-yellow)) url(@/assets/images/Frise.svg) no-repeat top center;
  background-size: 130% 1rem;
  // background-attachment: local;
  padding: var(--dialog-padding-top) var(--dialog-padding-x) 3rem var(--dialog-padding-x);
  display: flex;
  flex-flow: column nowrap;
  gap: 1rem;
  padding: 2.5rem 1.5rem;
  width: 100%;
  align-items: center;
  text-align: center;
  min-width: 19rem !important;
  align-self: flex-end;

  h2 {
    color: rgb(var(--v-theme-main-red));
  }

  .MyMapWelcomeBlock__moreAccess {
    display: flex;
    flex-flow: column nowrap;
    gap: 0.75rem;
    padding: 1rem 1rem;
    border-radius: $dim-radius;
    background: white;
  }
  .MyMapWelcomeBlock__close {
    text-decoration: underline;
    cursor: pointer;
  }
}
</style>
