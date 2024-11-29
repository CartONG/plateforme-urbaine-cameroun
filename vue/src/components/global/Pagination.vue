<template>
    <v-pagination class="Pagination" v-model="page" :length="totalPages" :total-visible="5"></v-pagination>
</template>

<script setup lang="ts">
import { computed, onMounted, ref, watch, type ModelRef, type PropType } from 'vue';

const props = withDefaults(defineProps<{
    items: any[]
    itemsPerPage?: number
}>(), {
    itemsPerPage: 20
})
const page = ref(1);

const paginatedItems = defineModel()
const emit = defineEmits(['update:page'])
onMounted(() => updatePaginatedItems())
watch(page, () => updatePaginatedItems())
watch(() => props.items, () => {
    page.value = 1
    updatePaginatedItems()
})

const itemsCount = computed(() => props.items.length)
const totalPages = computed(() => Math.ceil(itemsCount.value / props.itemsPerPage));

const updatePaginatedItems = () => {
    const start = (page.value - 1) * props.itemsPerPage;
    const end = start + props.itemsPerPage;
    paginatedItems.value = props.items.slice(start, end);
}
</script>

<style lang="scss">
.Pagination {
    padding-top: 1rem;
    width: 100%;
    // position: sticky;
    // bottom: 0;
    // background: linear-gradient(to bottom, transparent 0%, rgba(white, 60%) 50%);
}
</style>