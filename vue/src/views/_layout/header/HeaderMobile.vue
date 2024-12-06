<template>
  <div class="Header Header--mobile">
    <v-layout>
      <v-app-bar :elevation="2">
        <template v-slot:prepend>
          <v-app-bar-nav-icon
            color="main-blue"
            @click.stop="showMobileMenu = !showMobileMenu"
          ></v-app-bar-nav-icon>
        </template>
        <v-app-bar-title>
          <div class="d-flex align-center">
            <img src="@/assets/images/Logo.png" class="Header__appLogo" />
            <span class="ml-4 text-main-blue">{{ $t('header.title') }}</span>
            <span class="ml-2 text-main-blue font-weight-bold">{{ $t('header.title2') }}</span>
          </div>
        </v-app-bar-title>
      </v-app-bar>
    </v-layout>
    <div v-if="showMobileMenu" class="Header__menu">
      <div class="Header__menu__top">
        <img src="@/assets/images/Logo.png" />
        <div class="Header__menu__closer" @click="showMobileMenu = !showMobileMenu">
          <v-icon icon="mdi mdi-close" />
        </div>
      </div>
      <img src="@/assets/images/Frise.svg" alt="" />
      <div class="Header__menu__content">
        <v-list lines="one" bg-color="light-yellow">
          <v-list-item
            v-for="(tab, index) in NavigationTabsService.getContent()"
            @click="showMobileMenu = !showMobileMenu"
            :key="index"
          >
            <RouterLink :to="tab.route" class="Header__tabsText">
              <span
                :class="{ 'Header__tabsText--active': appStore.activeTab === tab.value }"
                @click="appStore.activeTab = tab.value"
                >{{ tab.name }}</span
              >
            </RouterLink>
          </v-list-item>
          <v-list-item>
            <span class="Header__tabsText">{{ $t('header.help') }}</span>
          </v-list-item>
          <v-list-item>
            <span class="Header__tabsText">{{ $t('header.contact') }}</span>
          </v-list-item>
          <v-list-item>
            <LoginButton />
          </v-list-item>
        </v-list>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { NavigationTabsService } from '@/services/application/NavigationTabsService'
import LoginButton from './LoginButton.vue'
import { useApplicationStore } from '@/stores/applicationStore'
import { ref } from 'vue'

const appStore = useApplicationStore()
const showMobileMenu = ref(false)
</script>

<style lang="scss">
.Header {
  &--mobile {
    height: $header-mobile-height;
    margin-top: $header-mobile-height;

    &::after {
      width: 100%;
      max-height: 30rem;
      height: 60vh;
    }

    .Header__appLogo {
      border: 1px solid rgb(var(--v-theme-main-grey));
      border-radius: 10%;
      padding: 5px;
      height: 40px;
    }

    .Header__tabsText {
      color: rgb(var(--v-theme-main-blue));
      font-weight: 600;
      text-transform: none;
      text-decoration: none;

      &--active {
        color: rgb(var(--v-theme-main-red));
      }
    }

    .Header__menu {
      position: absolute;
      top: 0px;
      left: 0px;
      display: flex;
      flex-direction: column;
      width: 100%;
      height: 100vh;
      z-index: 2000;

      &__top {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
        background-color: white;
        height: 200px;

        img {
          height: 160px;
        }
      }

      &__closer {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 2001;
        color: rgb(var(--v-theme-main-blue));
      }

      &__content {
        display: flex;
        flex-direction: column;
        width: 100%;
        background-color: rgb(var(--v-theme-light-yellow));
        flex-grow: 1;
        padding: $mobile-side-padding;
      }
    }
  }
}
</style>
