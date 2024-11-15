<template>
<div class="AdminTopBar">
    <div class="AdminTopBar--left">
        <SectionTitle :title="title" />
        <v-icon icon="mdi mdi-magnify" class="ml-5" color="main-blue"></v-icon>
        <v-text-field
            clearable
            density="compact"
            :label="$t('admin.search')"
            variant="solo"
            hide-details
            @update:modelValue="searchQuery = $event"
        ></v-text-field>

    </div>
    <div class="AdminTopBar--right">
        <v-btn color="white" class="mr-3">
            <span>{{ $t('placeholders.sortBy') }}</span><v-icon icon="mdi mdi-arrow-down-drop-circle-outline" class="ml-2"></v-icon>
            <v-menu activator="parent">
                <v-list>
                    <v-list-item 
                        v-for="item in sortingListItems" 
                        @click="sortingKey = item.sortingKey"
                    >
                        {{ item.text }}
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-btn>
        <v-btn @click="createFunction()" color="main-red">{{ $t("admin.add")}}</v-btn>
    </div>
</div>
</template>

<script setup lang="ts">
import { i18n } from '@/assets/plugins/i18n';
import type { Actor } from '@/models/interfaces/Actor';
import type { User } from '@sentry/vue';
import { computed, ref, watch } from 'vue';
import SectionTitle from '@/components/text-elements/SectionTitle.vue';

type AdminPages = 'Actors' | 'Projects' | 'Members'
const props = defineProps<{
    page: AdminPages,
    items: Actor[] | User[],
    sortingListItems: {sortingKey: string, text: string}[],
    searchKey: string,
    createFunction: () => void
}>()
const emit = defineEmits(['updateSortingKey', 'updateSearchQuery'])
const sortingKey = ref("isValidated")
watch(() => sortingKey.value, () => {
    emit('updateSortingKey', sortingKey.value)
})
const title = computed(() => {
        if (props.page === 'Members') {
            return `${props.items.length.toString()} ${i18n.t('admin.member', props.items.length)}`
        }
        if (props.page === 'Actors') {
            return `${props.items.length.toString()} ${i18n.t('actors.actor', props.items.length)}`
        }
        return `${props.items.length.toString()} ${i18n.t('actors.actor', props.items.length)}`
    }
)

const searchQuery = ref("")
watch(() => searchQuery.value, () => {
    emit('updateSearchQuery', searchQuery.value)
})

</script>

<style lang="scss">
.AdminTopBar {
    display: flex;
    justify-content: space-between;
    width: 100%;
    flex-direction: row;
    height: 48px;
    align-items: center;

    &--left {
        display: flex;
        align-items: center;
        flex-grow: 1;
        margin-right: 30px;
    }
}
</style>