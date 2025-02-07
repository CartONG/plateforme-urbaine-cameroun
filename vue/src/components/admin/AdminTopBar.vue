<template>
  <div class="AdminTopBar">
    <div class="AdminTopBar--left">
      <SectionTitle :title="title" v-if="title" />
      <v-text-field
        v-if="searchKey"
        clearable
        density="compact"
        :label="$t('admin.search')"
        variant="solo"
        hide-details
        @update:modelValue="searchQuery = $event"
      >
        <template v-slot:prepend-inner>
          <v-icon icon="mdi-magnify" color="main-blue"></v-icon>
        </template>
      </v-text-field>
    </div>
    <div class="AdminTopBar--right">
      <v-btn color="white" class="mr-3" v-if="sortingListItems">
        <span class="font-weight-regular">{{ $t('placeholders.sortBy') }}</span>
        <v-icon icon="mdi mdi-menu-down" class="ml-1"></v-icon>
        <v-menu activator="parent">
          <v-list>
            <v-list-item
              v-for="(item, index) in sortingListItems"
              @click="sortingKey = item.sortingKey"
              :key="index"
            >
              {{ item.text }}
            </v-list-item>
          </v-list>
        </v-menu>
      </v-btn>
      <v-btn v-if="createFunction" @click="createFunction()" color="main-red">{{
        $t('admin.add')
      }}</v-btn>
    </div>
  </div>
</template>

<script setup lang="ts">
import { i18n } from '@/plugins/i18n'
import type { Actor } from '@/models/interfaces/Actor'
import type { User } from '@sentry/vue'
import { computed, ref, watch } from 'vue'
import SectionTitle from '@/components/text-elements/SectionTitle.vue'
import { AtlasGroup } from '@/models/enums/geo/AtlasGroup'

type AdminPages =
  | 'Actors'
  | 'Projects'
  | 'Members'
  | 'Resources'
  | 'Highlights'
  | AtlasGroup.PREDEFINED_MAP
  | AtlasGroup.THEMATIC_DATA
  | 'QgisMaps'
const props = defineProps<{
  page: AdminPages
  items: Actor[] | User[]
  sortingListItems?: { sortingKey: string; text: string }[]
  searchKey?: string
  createFunction?: () => void
}>()
const emit = defineEmits(['updateSortingKey', 'updateSearchQuery'])
const sortingKey = ref('isValidated')
watch(
  () => sortingKey.value,
  () => {
    emit('updateSortingKey', sortingKey.value)
  }
)
const title = computed(() => {
  switch (props.page) {
    case 'Projects':
      return `${props.items.length} ${i18n.t('projects.projects', props.items.length)}`
    case 'Members':
      return `${props.items.length} ${i18n.t('admin.member', props.items.length)}`
    case 'Resources':
      return `${props.items.length} ${i18n.t('resources.resources', props.items.length)}`
    case AtlasGroup.PREDEFINED_MAP:
      return `${props.items.length} ${i18n.t('atlas.leftMaps', props.items.length)}`
    case AtlasGroup.THEMATIC_DATA:
      return `${props.items.length} ${i18n.t('atlas.rightMaps', props.items.length)}`
    case 'QgisMaps':
      return `${props.items.length} ${i18n.t('qgisMap.qgisMaps', props.items.length)}`
    case 'Actors':
      return `${props.items.length} ${i18n.t('actors.actors', props.items.length)}`
    default:
      return null
  }
})

const searchQuery = ref('')
watch(
  () => searchQuery.value,
  () => {
    console.log('bloup')
    emit('updateSearchQuery', searchQuery.value)
  }
)
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
