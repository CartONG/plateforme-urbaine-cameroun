<template>
  <div class="HomeView">
    <div class="HomeView__ctn HomeView__ctn--main">
      <div class="HomeView__mainContent">
        <div class="HomeView__mainContentInfo">
          <PageTitle :title="$t('home.main.title')" />
          <p>{{ $t('home.main.desc') }}</p>
          <v-btn class="HomeView__mainAction" color="main-blue" :to="{ name: 'projects' }">{{
            $t('home.main.action')
          }}</v-btn>
        </div>
        <HomeKpis class="HomeView__mainContentKpis" />
      </div>
      <div class="HomeView__mainImagesCtn">
        <img src="@/assets/images/home_iconography.svg" alt="" />
      </div>
    </div>
    <div class="HomeView__ctn HomeView__ctn--highlights">
      <SectionBanner :text="$t('home.highlights.title')" />
      <HomeHighlights />
    </div>
    <div class="HomeView__ctn HomeView__ctn--map container-fluid">
      <div class="container">
        <HomeMapDescription />
      </div>
    </div>
    <div class="HomeView__ctn HomeView__ctn--agenda">
      <SectionBanner
        :text="$t('home.agenda.title')"
        :action-label="$t('home.agenda.action')"
        :action-link="{ name: 'resources', query: { type: 'events' } }"
      />
      <HomeAgenda />
    </div>
    <div class="HomeView__ctn HomeView__ctn--why-subscribe">
      <HomeBecomeMember />
    </div>
  </div>
</template>
<script setup lang="ts">
import PageTitle from '@/components/text-elements/PageTitle.vue'
import SectionBanner from '@/components/banners/SectionBanner.vue'
import HomeKpis from '@/views/home/components/HomeKpis.vue'
import HomeHighlights from '@/views/home/components/HomeHighlights.vue'
import HomeMapDescription from '@/views/home/components/HomeMapDescription.vue'
import HomeBecomeMember from '@/views/home/components/HomeBecomeMember.vue'
import { useHomeStore } from '@/stores/homeStore'
import { onMounted } from 'vue'
import HomeAgenda from '@/views/home/components/HomeAgenda.vue'

const homeStore = useHomeStore()

onMounted(async () => await homeStore.getGlobalKpis())
</script>
<style lang="scss">
.HomeView {
  .HomeView__ctn {
    display: flex;
    flex-flow: column nowrap;
    margin: 4rem 0 4rem 0;
    gap: 0.5rem;
    &--main {
      flex-flow: row nowrap;
      gap: 3rem;
      margin: 0;

      .HomeView__mainContent {
        display: flex;
        flex-flow: column nowrap;
        gap: 3rem;
        flex: 1 0 50%;
        .HomeView__mainContentInfo {
          display: flex;
          flex-flow: column nowrap;
          gap: 1rem;
          align-items: flex-start;
        }
      }
      .HomeView__mainImagesCtn {
        transform: translateY(-5rem);
        flex: 1 0 45%;

        img {
          width: 100%;
        }
      }
    }
    &--map {
      background-color: rgb(var(--v-theme-light-yellow));
      padding: 4rem 0rem;
      overflow: hidden;
      position: relative;

      &::after {
        content: '';
        right: 0;
        z-index: 1;
        pointer-events: none;
        background-size: contain;
        top: 0;
        background-image: url('@/assets/images/roads_iconography.svg');
        background-position: top right;
        position: absolute;
        width: 70vw;
        min-height: 50rem;
        height: 80vh;
      }

      > * {
        z-index: 2;
      }
    }
  }
}

@media (max-width: $bp-xl) {
  .HomeView {
    .HomeKpis,
    .HomeMapDescription__mapImg,
    .HomeBecomeMember {
      display: none;
    }

    .HomeView__ctn {
      .PageTitle {
        font-size: $font-size-h1;
        line-height: $font-size-h1;
      }
      &--main {
        flex-flow: column-reverse nowrap;
        gap: 1rem;

        .HomeView__mainContent {
          .HomeView__mainAction {
            width: 100%;
          }
        }

        .HomeView__mainImagesCtn {
          width: 50%;
          align-self: center;
          transform: translateY(0);
        }
      }

      &--map {
        $dim-padding-map: 1rem;
        padding-right: $dim-padding-map;
        padding-left: $dim-padding-map;
        margin: 0 !important;

        &::after {
          width: 100%;
          background-size: 40rem;
        }
      }
    }
  }
}
</style>
