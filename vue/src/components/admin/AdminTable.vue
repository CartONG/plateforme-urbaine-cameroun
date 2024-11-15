<template>
    <div class="AdminTable">
        <div 
            class="AdminTableItem"
            :class="{ 'AdminTableItem--overlay': !item.isValidated }"
            v-for="item in items" :key="item.id"
            :style="{ gridTemplateColumns: columnWidths.join(' ') }"
        >
            <div class="AdminTableItem">
                {{ plainText ? item[tableKeys[0] as keyof typeof item] : reduceText(item[tableKeys[0] as keyof typeof item], 5) }}
            </div>
            <div class="AdminTableItem">
                {{ plainText ? item[tableKeys[1] as keyof typeof item] : reduceText(item[tableKeys[1] as keyof typeof item], 25) }}
            </div>
            <div class="AdminTableItem">
                {{ plainText ? item[tableKeys[2] as keyof typeof item] : reduceText(item[tableKeys[2] as keyof typeof item], 20) }}
            </div>
            <div class="AdminTableItem--last">
                <slot name="editContentCell" :item="item"></slot>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { Actor } from '@/models/interfaces/Actor';
import { reduceText } from '@/services/utils/UtilsService';
import type { User } from '@sentry/vue';

const props = defineProps<{
  items: Actor[] | User[];
  tableKeys: (keyof Actor | keyof User)[];
  columnWidths?: string[];
  plainText?: boolean;
}>()
const defaultColumnWidths = ['15%', '40%', '25%', '20%'];
const columnWidths = props.columnWidths || defaultColumnWidths;
</script>

<style lang="scss">
.AdminTable {
    display: flex;
    flex-direction: column;
    width: 100%;
    margin-top: 30px;
}
.AdminTableItem {
    display: grid;
    flex-direction: row;
    height: 3.5rem;
    align-items: center;
    padding-left: 10px;
    border-bottom: 1px solid rgb(var(--v-theme-main-grey));
    &--overlay {
        background-color: rgb(var(--v-theme-light-yellow));
    }
    &--last {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding-right: 10px;
    }
}
</style>