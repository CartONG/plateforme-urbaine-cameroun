<template>
  <div class="AdminTable" ref="el">
    <div
      class="AdminTable__row"
      v-for="item in paginatedItems"
      :key="item.id"
      :item-id="item.id"
      :class="{
        'AdminTable__row--overlay': isOverlayShownFunction ? isOverlayShownFunction(item) : false
      }"
      :style="{ gridTemplateColumns: columnWidths.join(' ') }"
    >
      <div v-if="!!$slots.adminTableItemFirst" class="AdminTable__item AdminTable__item--first">
        <slot name="adminTableItemFirst" :item="item"></slot>
      </div>
      <div class="AdminTable__item" v-for="(tableKey, index) in tableKeys" :key="index">
        <img
          loading="lazy"
          v-if="index === 0 && withLogo && getNestedObjectValue(item, logoField)"
          :src="getNestedObjectValue(item, logoField)"
          class="AdminTable__item__logo"
        />
        <template v-if="isStringDate(getNestedObjectValue(item, tableKey))">
          <v-tooltip :text="new Date(getNestedObjectValue(item, tableKey)).toLocaleDateString()">
            <template v-slot:activator="{ props }">
              <span v-bind="props">{{
                new Date(getNestedObjectValue(item, tableKey)).toLocaleDateString()
              }}</span>
            </template>
          </v-tooltip>
        </template>
        <template v-else>
          <v-tooltip :text="getNestedObjectValue(item, tableKey)">
            <template v-slot:activator="{ props }">
              <span v-bind="props">{{ reduceText(getNestedObjectValue(item, tableKey), 25) }}</span>
            </template>
          </v-tooltip>
        </template>
      </div>
      <div v-if="!!$slots.editContentCell" class="AdminTable__item AdminTable__item--last">
        <slot name="editContentCell" :item="item"></slot>
      </div>
    </div>
    <Pagination :items="items" v-model="paginatedItems" />
  </div>
</template>

<script setup lang="ts">
import Pagination from '@/components/global/Pagination.vue'
import type { Actor } from '@/models/interfaces/Actor'
import type { User } from '@/models/interfaces/auth/User'
import type { AppComment } from '@/models/interfaces/Comment'
import type { HighlightedItem } from '@/models/interfaces/HighlightedItem'
import type { Project } from '@/models/interfaces/Project'
import type { QgisMap } from '@/models/interfaces/QgisMap'
import type { Resource } from '@/models/interfaces/Resource'
import { getNestedObjectValue, reduceText } from '@/services/utils/UtilsService'
import type { SortableEvent } from 'sortablejs'
import { onMounted, ref, watch, type Ref } from 'vue'
import { useDraggable } from 'vue-draggable-plus'

type Item = Actor | User | Project | Resource | HighlightedItem | QgisMap | AppComment

const props = defineProps<{
  items: Item[]
  tableKeys: string[]
  columnWidths?: string[]
  plainText?: boolean
  isDraggable?: boolean
  withLogo?: boolean
  logoField?: string
  isOverlayShownFunction?: (item: Item) => boolean
}>()
const defaultColumnWidths = ['15%', '40%', '25%', '20%']
const columnWidths = props.columnWidths || defaultColumnWidths
const paginatedItems: Ref<Item[]> = ref([])

function isStringDate(candidate: string): boolean {
  const date = new Date(candidate)
  return !isNaN(date.getTime())
}

const el = ref<HTMLElement | null>(null)
onMounted(() => {
  initDraggable()
})

watch(
  () => paginatedItems.value,
  () => {
    initDraggable()
  }
)

const emits = defineEmits(['update:order'])

const initDraggable = () => {
  if (props.isDraggable) {
    useDraggable(el, paginatedItems, {
      animation: 150,
      onUpdate(e: SortableEvent) {
        emits('update:order', {
          id: e.item.getAttribute('item-id'),
          oldIndex: e.oldIndex,
          newIndex: e.newIndex
        })
      }
    })
  }
}
</script>

<style lang="scss">
.AdminTable {
  display: flex;
  flex-direction: column;
  width: 100%;
  margin-top: 30px;
}
.AdminTable__row {
  display: grid;
  flex-direction: row;
  height: 3.5rem;
  align-items: center;
  padding-left: 10px;
  border-bottom: 1px solid rgb(var(--v-theme-main-grey));
  &--overlay {
    background-color: rgb(var(--v-theme-light-yellow));
  }
  .AdminTable__item {
    display: flex;
    align-items: center;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    &--last {
      justify-content: flex-end;
      padding-right: 10px;
    }
  }
}
.AdminTable__item__logo {
  width: 2rem;
  height: 2rem;
  object-fit: cover;
  margin-right: 10px;
}
</style>
