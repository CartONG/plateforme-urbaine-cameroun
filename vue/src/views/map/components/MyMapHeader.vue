<template>
  <nav class="MyMapHeader">
    <div class="MyMapHeader__block MyMapHeader__block--left">
      <v-btn
        variant="elevated"
        elevation="1"
        density="comfortable"
        icon="mdi-arrow-left"
        class="main-blue"
        :to="{ name: 'home' }"
      >
        <v-icon icon="mdi-arrow-left" color="main-blue" />
      </v-btn>
      <img src="@/assets/images/Logo.png" class="MyMapHeader__logo" />
    </div>
    <Geocoding
      :search-type="NominatimSearchType.FREE"
      v-model="myMapStore.mapSearch"
      :placeholder="$t('myMap.header.search.placeholder')"
      :geometry-details="true"
      class="MyMapHeader__search"
      :icon="'mdi-magnify'"
      density="comfortable"
    />

    <div class="MyMapHeader__block MyMapHeader__block--left">
      <v-btn
        class="MyMapHeader__shareBtn"
        prepend-icon="mdi-share-variant"
        variant="elevated"
        elevation="1"
        >{{ $t('myMap.header.share') }}</v-btn
      >
      <LoginButton />
    </div>
  </nav>
</template>

<script setup lang="ts">
import Geocoding from '@/components/forms/Geocoding.vue'
import { NominatimSearchType } from '@/models/enums/geo/NominatimSearchType'
import { useMyMapStore } from '@/stores/myMapStore'
import LoginButton from '@/views/_layout/header/LoginButton.vue'

const myMapStore = useMyMapStore()
</script>

<style lang="scss">
.MyMapHeader {
  display: flex;
  flex-flow: row nowrap;
  align-items: center;
  gap: 1.25rem;
  padding: 0.5rem 1.5rem;
  border-bottom: solid 1px rgb(var(--v-theme-main-grey));

  .MyMapHeader__block {
    display: flex;
    flex-flow: row nowrap;
    align-items: center;
    gap: 0.75rem;
  }
  .MyMapHeader__logo {
    $dim-logo: 3.5rem;

    width: $dim-logo;
    height: $dim-logo;
    min-width: $dim-logo;
    max-width: $dim-logo;
    padding: 0.125rem;
    border-radius: $dim-radius;
    border: solid 1px rgb(var(--v-theme-main-grey));
  }
  .MyMapHeader__search {
    flex: 1 1 auto;

    ::placeholder {
      opacity: 1;
    }
  }

  .MyMapHeader__shareBtn {
    color: rgb(var(--v-theme-main-blue));
    .v-icon {
      color: rgb(var(--v-theme-main-blue));
    }
  }
}
</style>
