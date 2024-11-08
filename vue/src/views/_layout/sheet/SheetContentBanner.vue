<template>
    <div class="SheetContentBanner">
        <PageBanner :title="title" :subtitle="subtitle" :isEditable="isEditable" :updatedAt="updatedAt">
            <template #actions>
                <div class="SheetContentBanner__shareBar mt-1">
                    <v-btn 
                        class="PageBanner__editBtn fixed-btn mr-2"
                        prepend-icon="mdi-pencil-outline"
                        color="main-red"
                        variant="flat"
                        v-if="isEditable"
                        @click="$emit('edit')"
                    >
                        {{ $t("content.edit") }}
                    </v-btn>
                    <slot name="custom-actions"></slot>
                    <ShareButton />
                    <LikeButton :id="id" />
                    <v-btn :to="{ name: 'map' }" class="text-main-blue px-2 hide-sm ml-3" ><img src="@/assets/images/icons/add_location_alt.svg" class="mr-1">{{ $t("content.createAMap") }}</v-btn>
                    <UpdatedAtLabel :date="updatedAt" class="show-sm" />
                </div>
            </template>
        </PageBanner>
        <SheetContactActions :website="website" :email="email" :phone="phone" />
    </div>
</template>

<script setup lang="ts">
import PageBanner from '@/components/banners/PageBanner.vue';
import UpdatedAtLabel from '@/views/_layout/sheet/UpdatedAtLabel.vue';
import ShareButton from '@/components/global/ShareButton.vue';
import LikeButton from '@/components/global/LikeButton.vue';
import SheetContactActions from './SheetContactActions.vue';

defineProps<{
    id: string,
    title: string,
    subtitle: string,
    email: string,
    phone: string,
    website: string,
    updatedAt: string | Date,
    isEditable?: boolean,
}>()
</script>

<style lang="scss">
.SheetContentBanner {
    display: flex;
    flex-flow: column nowrap;
    gap: 2rem;
    height: fit-content;

    &__shareBar {
        display: flex;
        align-items: center;
    }

    > * {
        
    height: fit-content;
    }
}
</style>