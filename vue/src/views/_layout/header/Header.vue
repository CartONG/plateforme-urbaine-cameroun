<template>
  <HeaderMobile v-if="appStore.mobile" />
  <HeaderDesktop v-else />
</template>

<script setup lang="ts">
import HeaderMobile from './HeaderMobile.vue'
import HeaderDesktop from './HeaderDesktop.vue'
import { useApplicationStore } from '@/stores/applicationStore'
const appStore = useApplicationStore()
</script>
<style lang="scss">
.Header {
  &::after {
    content: '';
    right: 0;
    z-index: -1;
    background-size: contain;
    top: 0;
    background-image: url('/img/Frame.jpg');
    background-position: top right;
    position: absolute;
  }
}
</style>
