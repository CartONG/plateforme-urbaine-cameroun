<template>
  <div class="Header Header--desktop">
    <div class="Header__banner">
      <div class="Header__bannerContent container container--transition">
        <div class="Header__bannerLink">
          <v-icon icon="mdi-help-circle-outline" />
          <span class="mr-6">{{ $t('header.help') }}</span>
        </div>
        <a :href="whatsappLink" target="_blank" class="Header__bannerLink">
          <v-icon icon="mdi-email-outline" />
          <span>{{ $t('header.contact') }}</span>
        </a>
      </div>
    </div>
    <div class="Header__nav">
      <div class="Header__navContent container container--transition">
        <div class="Header__navBlock Header__navBlock--left">
          <router-link :to="{ name: 'home' }" class="Header__bannerLink">
            <img src="@/assets/images/Logo.png" alt="Accueil" class="Header__appLogo" />
          </router-link>
        </div>
        <nav class="Header__navBlock Header__navBlock--right">
          <v-tabs v-model="appStore.activeTab" align-tabs="end" color="main-red">
            <v-tab
              v-for="(tab, index) in NavigationTabsService.getContent()"
              :value="tab.value"
              :to="tab.route"
              :key="index"
            >
              <span class="Header__tabsText">{{ tab.name }}</span>
            </v-tab>
          </v-tabs>
          <v-btn base-color="white" class="text-main-blue mr-3 gap-5" to="/map" flat>
            <img
              src="@/assets/images/icons/add_location_alt.svg"
              alt="Accueil"
              class="Header__appLogo mr-1"
            />
            {{ $t('header.map') }}
          </v-btn>
          <LoginButton />
        </nav>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { NavigationTabsService } from '@/services/application/NavigationTabsService'
import { useApplicationStore } from '@/stores/applicationStore'
import LoginButton from './LoginButton.vue'

const appStore = useApplicationStore()
const whatsappLink = `https://wa.me/${'+237652266618'.replace(/\D/g, '')}`
</script>

<style lang="scss">
.Header {
  &--desktop {
    $dim-logo: 10rem;

    &::after {
      width: 50vw;
      min-height: 50rem;
      height: 80vh;
    }

    .Header__banner {
      background: rgb(var(--v-theme-main-yellow));

      .Header__bannerContent {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        padding: 0 1rem;
        color: rgb(var(--v-theme-main-blue));
        height: var(--dim-banner-h);

        .Header__bannerLink {
          display: flex;
          flex-flow: row nowrap;
          align-items: center;
          text-decoration: none;
          color: rgb(var(--v-theme-main-blue));
          gap: 0.375rem;
          font-size: $font-size-xs;
          cursor: pointer;

          span {
            margin-top: 0.125rem;
          }
        }
      }
    }

    .Header__nav {
      background: linear-gradient(to top, transparent 0%, rgb(var(--v-theme-light-yellow)) 100%);
      height: $dim-logo;
      display: flex;
      justify-content: space-between;
      align-items: flex-start;

      .Header__navContent {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-flow: row nowrap;

        .Header__navBlock {
          &--left {
            .Header__appLogo {
              z-index: 10;
              position: relative;
              height: $dim-logo;
              transform: translateY(calc(-1 * var(--dim-banner-h)));
            }
          }

          &--right {
            flex: 1 0 auto;
            display: flex;
            flex-flow: row nowrap;
            justify-content: flex-end;
            align-items: center;
            padding: 20px 0 10px 0;
          }
        }
      }
    }
  }
}
</style>
