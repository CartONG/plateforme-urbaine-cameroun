<template>
  <div class="HomeMapDescription">
    <div class="HomeMapDescription__block">
      <v-btn
        variant="outlined"
        class="show-mobile disabled mb-4 w-100 font-weight-medium"
        color="main-blue"
        >{{ $t('home.map.availableOnDesktop') }}</v-btn
      >
      <PageTitle :title="$t('home.map.title')" />
      <div class="HomeMapDescription__bullets">
        <CheckPoint
          v-for="bullet in bullets"
          :key="bullet"
          :label="$t(`home.map.bullets.${bullet}.label`)"
          :description="$t(`home.map.bullets.${bullet}.description`)"
          :highlighted="true"
          :highlight-color="'white'"
        />
        <v-btn class="mt-4 hide-mobile" color="main-blue" :to="{ name: 'map' }">{{
          $t('home.map.action')
        }}</v-btn>
      </div>
    </div>
    <div class="HomeMapDescription__mapImg">
      <img src="@/assets/images/home_map_comparator.png" />
      <div class="HomeMapDescription__welcomeCtn">
        <h4>{{ $t('home.map.welcome') }}</h4>
        <p>{{ $t('home.map.welcomeDescription') }}</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import CheckPoint from '@/components/global/CheckPoint.vue'
import PageTitle from '@/components/text-elements/PageTitle.vue'

const bullets = ['overview', 'simpleMaps', 'geodata', 'thematics', 'way']
</script>

<style lang="scss">
.HomeMapDescription {
  display: flex;
  flex-flow: row nowrap;
  gap: 2rem;
  padding: 0 1rem;
  .HomeMapDescription__block {
    display: flex;
    flex-flow: column nowrap;
    align-items: flex-start;
    gap: 0.5rem;
  }
  .HomeMapDescription__bullets {
    display: flex;
    flex-flow: column nowrap;
    align-items: flex-start;
    gap: 0.5rem;
    padding: 2rem 0 0 0;
    flex: 1 0 50%;
  }

  .HomeMapDescription__mapImg {
    display: flex;
    flex-flow: column nowrap;
    justify-content: flex-start;
    align-items: center;
    justify-content: center;
    flex: 1 0 40%;
    margin: 1.5rem;

    img {
      max-width: 100%;
      width: 500px;
      border-radius: 0.5rem;
      border: solid 3px #fff;
      box-shadow: 0 0.125rem 0.5rem -0.125rem rgba(0, 0, 0, 0.4);
    }

    .HomeMapDescription__welcomeCtn {
      background: rgb(var(--v-theme-light-yellow)) url(@/assets/images/Frise.svg) no-repeat top
        center;
      background-attachment: local;
      background-size: 100% 1rem;
      display: flex;
      flex-flow: column nowrap;
      align-items: center;
      gap: 0.25rem;
      border-radius: 0.5rem;
      border: solid 2px #fff;
      width: 18rem;
      padding: 2rem 1.5rem 1rem 1.5rem;
      position: absolute;
      transform: translateY(11rem);

      h4 {
        color: rgb(var(--v-theme-main-red));
        font-size: $font-size-h3;
      }

      p {
        text-align: center;
        font-size: $font-size-sm;
      }
    }
  }
}
@media (max-width: $bp-xl) {
  .HomeMapDescription {
    padding: 0;
  }
}
</style>
