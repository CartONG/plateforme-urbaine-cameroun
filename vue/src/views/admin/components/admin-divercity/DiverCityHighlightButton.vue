<template>
  <v-btn
    v-if="canManage"
    variant="text"
    density="comfortable"
    :icon="'$star' + (!isHighlighted ? 'Outline' : '')"
    color="main-blue"
    @click.stop.prevent="toggleHighlight"
  ></v-btn>
</template>

<script setup lang="ts">
import { DiverCityHighlightedResourceService } from '@/services/divercity/DiverCityHighlightedResourceService'
import { useDiverCityHighlightStore } from '@/stores/divercity/divercityHighlightStore'
import { useUserStore } from '@/stores/userStore'
import { computed, onMounted } from 'vue'

const highlightStore = useDiverCityHighlightStore()
const userStore = useUserStore()

const emits = defineEmits(['update:highlight'])
const props = defineProps<{
  resourceId: string
}>()

const canManage = computed(() => userStore.userIsAdmin() || userStore.userIsDiverCitySpaceAdmin())

const existingHighlight = computed(() =>
  highlightStore.highlights.find((item) => item.resourceId === props.resourceId)
)

const isHighlighted = computed(() => !!existingHighlight.value?.isHighlighted)

onMounted(async () => {
  if (canManage.value && highlightStore.highlights.length === 0) {
    await highlightStore.getAll()
  }
})

const toggleHighlight = async () => {
  const targetState = !isHighlighted.value
  const payload = {
    resourceId: props.resourceId,
    isHighlighted: targetState
  }

  // Si l'élément existe déjà en base, on utilise PATCH, sinon POST (exactement comme PDC)
  const request = existingHighlight.value
    ? DiverCityHighlightedResourceService.patch(payload)
    : DiverCityHighlightedResourceService.post(payload)

  const updatedItem = await request
  highlightStore.updateHighlightedResource(updatedItem)
  emits('update:highlight', updatedItem)
}
</script>