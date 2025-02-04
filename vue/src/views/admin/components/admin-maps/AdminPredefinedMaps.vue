<template>
  <AtlasesList :atlasesPanel="AtlasGroup.PREDEFINED_MAP" />
</template>
<script setup lang="ts">
import { AtlasGroup } from '@/models/enums/geo/AtlasGroup'
import AtlasesList from '@/views/admin/components/admin-maps/AtlasesList.vue'
</script>
