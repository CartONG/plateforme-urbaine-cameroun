<template>
  <div class="SheetContentBanner">
    <PageBanner :title="title" :subtitle="subtitle" :isEditable="isEditable" :updatedAt="updatedAt">
      <template #actions>
        <div class="SheetContentBanner__actionsBar mt-1">
          <div class="SheetContentBanner__shareBar">
            <slot name="custom-actions"></slot>
            <ShareButton />
            <HighlightButton :item-id="id" />
            <LikeButton :id="id" />
            <v-btn
              variant="elevated"
              :to="{ name: 'map' }"
              class="elevation-1 text-main-blue px-3 mx-2 hide-sm"
              ><img src="@/assets/images/icons/add_location_alt.svg" class="mr-1" />{{
                $t('content.createAMap')
              }}</v-btn
            >
            <UpdatedAtLabel :date="updatedAt" class="show-sm" />
          </div>
          <div class="SheetContentBanner__editBar">
            <v-btn
              class="PageBanner__editBtn fixed-btn"
              prepend-icon="mdi-pencil-outline"
              color="main-red"
              variant="flat"
              v-if="isEditable"
              @click="$emit('edit')"
            >
              {{ $t('content.edit') }}
            </v-btn>
          </div>
        </div>
      </template>
    </PageBanner>
    <SheetContactActions :website="website" :email="email" :phone="phone" />
  </div>
</template>

<script setup lang="ts">
import PageBanner from '@/components/banners/PageBanner.vue'
import UpdatedAtLabel from '@/views/_layout/sheet/UpdatedAtLabel.vue'
import ShareButton from '@/components/global/ShareButton.vue'
import LikeButton from '@/components/global/LikeButton.vue'
import SheetContactActions from './SheetContactActions.vue'
import HighlightButton from '@/components/global/HighlightButton.vue'

defineProps<{
  id: string
  title: string
  subtitle: string
  email: string
  phone: string
  website: string
  updatedAt: string | Date
  isEditable?: boolean
}>()
</script>

<style lang="scss">
.SheetContentBanner {
  display: flex;
  flex-flow: column nowrap;
  gap: 2rem;
  height: fit-content;

  .SheetContentBanner__actionsBar {
    display: flex;
    flex-flow: row nowrap;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    .SheetContentBanner__shareBar {
      display: flex;
      flex-flow: row nowrap;
      align-items: center;
    }

    .SheetContentBanner__editBar {
      display: flex;
      flex-flow: row nowrap;
      align-items: center;
    }
  }

  > * {
    height: fit-content;
  }
}
</style>
