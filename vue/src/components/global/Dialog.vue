<template>
  <v-dialog v-model="showDialog" @click:outside="closeOutside && closeDialog()" width="auto">
    <div class="Dialog">
      <div class="Dialog__header">
        <h2 class="Dialog__title"><slot name="title"></slot></h2>
        <div class="Dialog__subtitle"><slot name="subtitle"></slot></div>
      </div>
      <div class="Dialog__content">
        <slot name="content"></slot>
      </div>
      <div class="Dialog__bottomContent">
        <slot name="bottom-content"></slot>
      </div>
    </div>
  </v-dialog>
</template>

<script setup lang="ts">
import { useApplicationStore } from '@/stores/applicationStore'
import { computed } from 'vue'
import { useRouter } from 'vue-router'

interface Props {
  closeOutside?: boolean
}

withDefaults(defineProps<Props>(), {
  closeOutside: true
})

const emit = defineEmits<{
  close: []
}>()

const router = useRouter()
const applicationStore = useApplicationStore()
const showDialog = computed(() => applicationStore.activeDialog != null)
const closeDialog = () => {
  router.replace({ query: { dialog: undefined } })
  emit('close')
}
</script>

<style lang="scss">
.Dialog {
  --dialog-padding-x: 2.5rem;
  --dialog-padding-top: 5.5rem;
  $dim-dialog-h: calc(100vh - 4rem);

  background: #fff url(@/assets/images/Frise.svg) no-repeat top center;
  background-attachment: local;
  padding: var(--dialog-padding-top) var(--dialog-padding-x) 3rem var(--dialog-padding-x);
  width: $dim-dialog-w;
  min-height: min(42rem, $dim-dialog-h);
  max-height: calc(100vh - $dim-dialog-h);
  max-width: 100%;
  overflow-y: auto;
  display: flex;
  flex-flow: column nowrap;
  align-items: center;
  border-radius: 3px;
  box-shadow: $mixin-shadow;
  white-space: pre-line;

  .Dialog__header {
    display: flex;
    flex-flow: column nowrap;
    align-items: center;
    gap: 0.125rem;
    margin-bottom: 1.5rem;
    text-align: center;

    .Dialog__title {
      color: rgb(var(--v-theme-main-red));
    }

    .Dialog__subtitle {
      text-align: center;
    }
  }

  .Dialog__content {
    width: 100%;
    margin-bottom: 2rem;
    display: flex;
    flex-flow: column nowrap;
    align-items: center;
    justify-content: center;
    text-align: justify;

    .Link--withoutUnderline {
      width: fit-content;
    }
  }

  .Dialog__bottomContent {
    display: flex;
    flex-flow: row wrap;
    text-align: center;
    justify-content: center;
    gap: 0.5rem;
  }
}

@media (max-width: $bp-md) {
  .Dialog {
    --dialog-padding-x: 1rem;
    --dialog-padding-top: 3.5rem;
  }
}
</style>
