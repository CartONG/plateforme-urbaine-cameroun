<template>
  <AtlasesList :atlasesPanel="AtlasGroup.THEMATIC_DATA" />
</template>
<script setup lang="ts">
import { AtlasGroup } from '@/models/enums/geo/AtlasGroup'
import AtlasesList from '@/views/admin/components/admin-maps/AtlasesList.vue'
</script>
