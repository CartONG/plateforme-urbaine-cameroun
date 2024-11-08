<template>
    <InfoCard class="GenericInfoCard" :to="to">
        <template #content>
            <div class="GenericInfoCard__imgCtn">
                <img class="GenericInfoCard__img" :src="image">
            </div>
            <div class="GenericInfoCard__infoCtn">
                <span class="InfoCard__title">{{ title }}</span>
                <span class="InfoCard__description" >{{ description }}</span>
            </div>
        </template>
        <template #footer-left>
            <v-chip class="mr-2">{{ $t('itemType.' + type) }}</v-chip>
            <ShareButton />
            <LikeButton :id="id" />
        </template>
        <template #footer-right>
            <v-icon class="InfoCard__actionIcon" icon="mdi mdi-open-in-new" color="light-blue"></v-icon>
        </template>
    </InfoCard>
</template>

<script setup lang="ts">
import InfoCard from '@/components/global/InfoCard.vue';
import LikeButton from '@/components/global/LikeButton.vue';
import ShareButton from '@/components/global/ShareButton.vue';
import { ItemType } from '@/models/enums/app/ItemType';
import { computed } from 'vue';

const props = defineProps<{
    id: string,
    title: string,
    description: string,
    image?: string,
    type: ItemType,
    slug: string,
}>();

const to = computed(() => {
    switch (props.type) {
        case ItemType.PROJECT:
            return { name: 'projectPage', params: { slug: props.slug } };
        case ItemType.ACTOR:
            return { name: 'actorProfile', params: { slug: props.slug } };
    }
})
</script>

<style lang="scss">
.GenericInfoCard {
    // padding: 1rem 1.25rem !important;
    padding: 0 !important;
    $dim-img-h: 11rem;
    $dim-text-max-h: 3rem;
    $dim-card-h: 23rem;
    $card-transition: all .15s ease-in;
    height: $dim-card-h;
    max-height: $dim-card-h;
    min-height: $dim-card-h;
    justify-content: space-between;
    
    &:hover {
        .GenericInfoCard__imgCtn {
            height: 0;
            min-height: 0;
            opacity: 0;
        }
        .GenericInfoCard__infoCtn {
            margin-top: 0;
            // max-height: calc($dim-card-h);
            // height: calc($dim-text-max-h + $dim-img-h);
            // min-height: calc($dim-text-max-h + $dim-img-h);

            .InfoCard__description  {
                
                max-height: calc($dim-text-max-h + $dim-img-h);
                // min-height: calc($dim-text-max-h + $dim-img-h);
            }
        }
    }
    
    .GenericInfoCard__imgCtn {
        position: absolute;
        transition: $card-transition;
        height: $dim-img-h;
        background: #ddd;
        inset: 0;
        min-height: $dim-img-h;
        width: 100%;
        // z-index: 1;

        .GenericInfoCard__img {            
            width: 100%;
            object-fit: cover;
        }
    }
    .InfoCard__footer {
        margin: 1rem 1.25rem !important;
    }
    .GenericInfoCard__infoCtn {
        transition: margin-top .15s ease-in;
        padding: 1rem 1.25rem !important;
        margin-top: $dim-img-h;
        display: flex;
        flex-flow: column nowrap;
        gap: .5rem;
        background: white;
        overflow-y: hidden;
        position: relative;
        
        .InfoCard__title {
            font-size: $font-size-h5 !important;
        }
        .InfoCard__description {
            max-height: $dim-text-max-h;
            font-size: $font-size-sm;
            transition: $card-transition;
        }
        
        &::after {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: linear-gradient(to bottom, transparent calc(100% - 2rem), white 100%);
        }
    }
}
</style>