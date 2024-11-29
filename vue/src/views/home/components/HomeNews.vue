<template>
    <div class="HomeNews">
        <GenericInfoCard
            v-for="item in homeStore.news"
            :id="item.id"
            :title="item.name"
            :description="item.description"
            :image="item.image"
            :type="item.type"
            :type-label="$t('itemType.' + item.type)"
            :slug="item.slug"
            :key="item.id" />
    </div>
</template>

<script setup lang="ts">
import GenericInfoCard from '@/components/global/GenericInfoCard.vue';
import { useHomeStore } from '@/stores/homeStore';
import { onBeforeMount } from 'vue';

const homeStore = useHomeStore()
onBeforeMount(async () => await homeStore.getNews())
</script>

<style lang="scss">
.HomeNews {
    display: flex;
    flex-flow: row nowrap;
    max-width: 100%;
    gap: 2rem;
    & > * {
        flex: 1 0 20rem
    }
}
@media (max-width: $bp-lg) {
    .HomeNews {
        flex-flow: column nowrap;
    }
}
</style>