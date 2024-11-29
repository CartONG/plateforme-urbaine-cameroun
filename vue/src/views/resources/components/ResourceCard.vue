<template>
    <GenericInfoCard
        :id="resource.id"
        :title="resource.name"
        :description="resource.description"
        :type="ItemType.RESOURCE"
        :type-label="$t('resources.resourceType.' + resource.type)"
        :action-icon="icon"
        :is-editable="true"
        :edit-function="editResource"
        >
        <template #footer-right>
            <v-icon class="InfoCard__actionIcon" :icon="icon" color="light-blue"></v-icon>
        </template>
    </GenericInfoCard>
</template>

<script setup lang="ts">
import type { Resource } from '@/models/interfaces/Resource';
import { ItemType } from '@/models/enums/app/ItemType';
import GenericInfoCard from '@/components/global/GenericInfoCard.vue';
import { ResourceType } from '@/models/enums/contents/ResourceType';
import { computed } from 'vue';
import { useResourceStore } from '@/stores/resourceStore';
const resourceStore = useResourceStore()
const props = defineProps<{
  resource: Resource;
}>();

const icon = computed(() => {
  switch (props.resource.type) {
    case ResourceType.VIDEO:
    case ResourceType.WEB:
      return 'mdi-open-in-new'
    case ResourceType.XLSX:
    case ResourceType.PDF:
      return 'mdi-download'
    case ResourceType.IMAGE:
      return 'mdi-arrow-right'
  }
})

const editResource = () => {
    resourceStore.isResourceFormShown = true;
    resourceStore.editedResourceId = props.resource.id;
}
</script>