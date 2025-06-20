<template>
  <div
    class="AdminTable__row"
    :style="{ gridTemplateColumns: ['5%', '45%', '15%', '15%', '20%'].join(' ') }"
  >
    <div class="AdminTable__item">
      {{ atlas.position }}
    </div>
    <div class="AdminTable__item d-flex align-center">
      <img
        loading="lazy"
        :src="atlas.logo.contentUrl"
        v-if="atlas.logo?.contentUrl"
        class="AdminTable__item__logo"
      />
      {{ atlas.name }}
    </div>
    <div class="AdminTable__item">
      {{ atlas.maps.length }} {{ $t('atlas.data', atlas.maps.length) }}
    </div>
    <div class="AdminTable__item">{{ new Date(atlas.updatedAt).toLocaleDateString() }}</div>
    <div class="AdminTable__item--last d-flex">
      <v-btn
        density="comfortable"
        :icon="showDetails ? '$arrowUpBoldHexagonOutline' : '$arrowDownBoldHexagonOutline'"
        class="mr-2"
        @click="showDetails = !showDetails"
      ></v-btn>
      <v-btn
        density="comfortable"
        icon="$pencilOutline"
        class="mr-2"
        @click="
          ((formType = FormType.EDIT), (atlasToEdit = atlas), (atlasStore.isFormShown = true))
        "
      ></v-btn>
      <v-btn density="comfortable" icon="$dotsVertical">
        <v-icon icon="$dotsVertical"></v-icon>
        <v-menu activator="parent" location="left">
          <v-list class="AdminPanel__additionnalMenu">
            <v-list-item @click="atlasStore.deleteAtlas(atlas)">{{
              $t('actors.admin.delete')
            }}</v-list-item>
          </v-list>
        </v-menu>
      </v-btn>
    </div>
  </div>
  <div v-if="showDetails" class="AdminTable__item">
    <div
      v-for="map in atlas.maps"
      :key="map['@id']"
      class="AdminTable__atlasDetails"
      :style="{ gridTemplateColumns: ['30%', '70%'].join(' ') }"
    >
      <div class="AdminTable__item">
        <img
          loading="lazy"
          class="AdminTable__mapImg"
          :src="map.logo.contentUrl"
          v-if="map.logo?.contentUrl"
        />
        {{ map.name }}
      </div>
      <div class="AdminTable__item">
        <v-icon icon="$layersOutline"></v-icon>
        {{ map.qgisProject.layers?.join(', ') }}
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { FormType } from '@/models/enums/app/FormType'
import type { Atlas } from '@/models/interfaces/Atlas'
import { useAtlasStore } from '@/stores/atlasStore'
import { ref, type ModelRef } from 'vue'
const atlasStore = useAtlasStore()
const formType: ModelRef<FormType | undefined> = defineModel('formType')
const atlasToEdit: ModelRef<Atlas | undefined> = defineModel('atlasToEdit')
const showDetails = ref(false)

defineProps<{ atlas: Atlas }>()
</script>

<style>
.AdminTable__atlasDetails {
  display: grid;
  height: 2.5rem;
  align-items: center;
  padding-left: 30px;
  border-bottom: 1px dashed rgb(var(--v-theme-main-grey));
  background-color: rgba(var(--v-theme-main-yellow), 0.2);
}
.AdminTable__mapImg {
  height: 1rem;
  width: 1rem;
  margin-right: 0.5rem;
}
</style>
