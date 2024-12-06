<template>
  <div class="SheetContactActions">
    <BasicCard icon="mdi-open-in-new" v-if="website">
      <a :href="website" target="_blank" class="ml-2">{{ $t('content.website') }}</a>
    </BasicCard>
    <BasicCard icon="mdi-email-plus-outline" v-if="email">
      <a :href="`mailto:${email}`" class="ml-2">{{ $t('content.mail') }}</a>
    </BasicCard>
    <BasicCard icon="mdi-phone" v-if="phone">
      <span class="ml-2">{{ phone }}</span>
    </BasicCard>
  </div>
</template>

<script setup lang="ts">
import BasicCard from '@/components/global/BasicCard.vue'

defineProps<{
  email: string
  phone: string
  website: string
}>()
</script>

<style lang="scss">
.SheetContactActions {
  width: 100%;
  display: flex;
  flex-flow: row nowrap;
  gap: 1rem;

  & > * {
    flex: 1 1 220px;
  }
}

@media (max-width: $bp-md) {
  .SheetContactActions {
    flex-flow: column nowrap;
  }
}
</style>
