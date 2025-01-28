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
        {{ getNestedObjectValue(item, tableKey) }}
      </div>
      <div v-if="!!$slots.editContentCell" class="AdminTable__item AdminTable__item--last">
        <slot name="editContentCell" :item="item"></slot>
      </div>
    </div>
    <Pagination :items="items" v-model="paginatedItems" />
  </div>
</template>

<script setup lang="ts">
import type { Actor } from '@/models/interfaces/Actor'
import { getNestedObjectValue } from '@/services/utils/UtilsService'
import { onMounted, ref, watch, type Ref } from 'vue'
import Pagination from '@/components/global/Pagination.vue'
import type { Project } from '@/models/interfaces/Project'
import type { User } from '@/models/interfaces/auth/User'
import type { Resource } from '@/models/interfaces/Resource'
import type { HighlightedItem } from '@/models/interfaces/HighlightedItem'
import { useDraggable } from 'vue-draggable-plus'
import type { SortableEvent } from 'sortablejs'

type Item = Actor | User | Project | Resource | HighlightedItem

const props = defineProps<{
  items: Item[]
  tableKeys: string[]
  columnWidths?: string[]
  plainText?: boolean
  isDraggable?: boolean
  isOverlayShownFunction?: (item: Item) => boolean
}>()
const defaultColumnWidths = ['15%', '40%', '25%', '20%']
const columnWidths = props.columnWidths || defaultColumnWidths
const paginatedItems: Ref<Item[]> = ref([])

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
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    &--last {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      padding-right: 10px;
    }
  }
}
</style>
