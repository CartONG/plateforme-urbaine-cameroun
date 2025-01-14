<template>
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
import { RouterView } from 'vue-router'
import { onBeforeMount } from 'vue'
import Header from '@/views/_layout/header/Header.vue'
import Footer from '@/views/_layout/footer/Footer.vue'
import DialogController from '@/components/global/DialogController.vue'
import { useApplicationStore } from '@/stores/applicationStore'
import { useActorsStore } from '@/stores/actorsStore'
import { useUserStore } from '@/stores/userStore'
import EditContentDialog from '@/views/actors/components/EditContentDialog.vue'
import { useThematicStore } from './stores/thematicStore'
import NotificationBox from '@/views/_layout/notification/NotificationBox.vue'

const appStore = useApplicationStore()
const actorsStore = useActorsStore()
const userStore = useUserStore()
const thematicStore = useThematicStore()
onBeforeMount(() => {
  userStore.checkAuthenticated()
  actorsStore.getActors()
  actorsStore.getActorsSelectListContent()
  thematicStore.getAll()
  appStore.getLikesList()
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
