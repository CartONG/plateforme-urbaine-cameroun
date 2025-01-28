<template>
  <v-btn
    @click.prevent="highlight"
    v-if="userStore.userIsAdmin()"
    variant="text"
    density="comfortable"
    :icon="'mdi mdi-star' + (!highlightedItem?.isHighlighted ? '-outline' : '')"
    color="main-blue"
  ></v-btn>
</template>

<script setup lang="ts">
import type { HighlightedItem } from '@/models/interfaces/HighlightedItem'
import { HighlightedItemService } from '@/services/highlight/HighlightedItemService'
import { useHighlightStore } from '@/stores/highlightStore'
import { useUserStore } from '@/stores/userStore'
import { computed, onMounted } from 'vue'

const highlightStore = useHighlightStore()
const userStore = useUserStore()
const emits = defineEmits(['update:highlight'])
const props = defineProps<{
  itemId: string
}>()

onMounted(async () => {
  if (userStore.userIsAdmin()) {
    await highlightStore.getAll()
  }
})

const highlightedItem = computed(() =>
  highlightStore.highlights.find((item) => item.itemId === props.itemId)
)

const highlight = async () => {
  const highlightedItemSubmission: Partial<HighlightedItem> = {
    itemId: props.itemId,
    isHighlighted: !highlightedItem.value?.isHighlighted
  }
  const highlightedItemExists = highlightStore.highlights.some(
    (item) => item.itemId === props.itemId
  )

  ;(highlightedItemExists
    ? HighlightedItemService.patch(highlightedItemSubmission)
    : HighlightedItemService.post(highlightedItemSubmission)
  ).then((item) => {
    emits('update:highlight', highlightedItemSubmission)
    highlightStore.updateHighlightedItem(item)
  })
}
</script>
