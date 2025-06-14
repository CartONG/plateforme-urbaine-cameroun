<template>
  <Loader v-if="appStore.isLoading" />
  <div class="App" :is-100-vh="appStore.is100vh" :is-light-header="appStore.isLightHeader">
    <Header v-if="!appStore.isFullViewport" />
    <div
      :class="
        'App__content ' +
        (!appStore.isFullViewport ? 'container ' : '') +
        (appStore.mobile ? 'App__content--mobile' : 'App__content--desktop')
      "
    >
      <v-breadcrumbs
        v-if="!appStore.mobile && !appStore.isFullViewport"
        class="Breadcrumbs"
        :items="appStore.breadcrumbs"
      ></v-breadcrumbs>
      <RouterView />
    </div>
    <Footer v-if="!appStore.isFullViewport" />
    <KeepAlive>
      <DialogController />
    </KeepAlive>
    <EditContentDialog />
    <NotificationBox />
  </div>
</template>

<script setup lang="ts">
import DialogController from '@/components/global/DialogController.vue'
import { useApplicationStore } from '@/stores/applicationStore'
import { useUserStore } from '@/stores/userStore'
import Footer from '@/views/_layout/footer/Footer.vue'
import EditContentDialog from '@/views/_layout/forms/EditContentDialog.vue'
import Header from '@/views/_layout/header/Header.vue'
import NotificationBox from '@/views/_layout/notification/NotificationBox.vue'
import { onBeforeMount } from 'vue'
import { RouterView } from 'vue-router'
import Loader from './components/global/Loader.vue'

const appStore = useApplicationStore()
const userStore = useUserStore()
onBeforeMount(async () => {
  await userStore.checkAuthenticated()
  await appStore.getLikesList()
})
</script>

<style lang="scss">
@import '@/assets/styles/global/app.scss';

.App {
  display: flex;
  flex-flow: column nowrap;

  &[is-100-vh='true'] {
    min-height: 100vh;
    .App__content {
      padding: 0;
    }
  }

  &__content {
    flex: 1 1 auto;
  }

  &[is-light-header='true'] {
    --dim-container-w: 1200px;

    .App__content--desktop {
      padding-top: 0;
      padding-bottom: 0;
    }
    .Header--desktop .Header__nav {
      height: var(--dim-header-nav-h);
      border-bottom: solid 1px #d9d9d9;
    }
    .Header--mobile {
      margin-top: 0;
    }
    .Header::after {
      content: '';
      transition: opacity 0.25s ease-in-out;
      opacity: 0;
    }
    .v-breadcrumbs {
      display: none;
    }
  }
}
</style>
