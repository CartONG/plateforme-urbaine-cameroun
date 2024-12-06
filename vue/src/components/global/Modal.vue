<template>
  <v-dialog v-model="$props.show">
    <div class="Modal">
      <div class="Modal__header">
        <span class="Modal__title">{{ title }}</span>
        <div class="Modal__closeBtn">
          <v-icon icon="mdi mdi-close" @click="$emit('close')"></v-icon>
        </div>
      </div>
      <div class="Modal__content">
        <slot name="content"></slot>
      </div>
      <div class="Modal__footer">
        <div class="Modal__footerBlock Modal__footerBlock--left">
          <slot name="footer-left"></slot>
        </div>
        <div class="Modal__footerBlock Modal__footerBlock--right">
          <slot name="footer-right"></slot>
        </div>
      </div>
    </div>
  </v-dialog>
</template>

<script setup lang="ts">
defineProps<{
  title: string
  show?: boolean
}>()
</script>

<style lang="scss">
.Modal {
  $dim-modal-h: calc(100vh - 4rem);
  $dim-modal-w: 30rem;
  margin: auto;
  background: white;
  width: $dim-modal-w;
  max-height: 100vh;
  max-width: 100%;
  overflow-y: auto;
  display: flex;
  flex-flow: column nowrap;
  border-radius: $dim-radius;
  box-shadow: $mixin-shadow;
  white-space: pre-line;

  > * {
    padding: 0.675rem 1.25rem;
  }

  .Modal__header {
    display: flex;
    flex-flow: row nowrap;
    width: 100%;
    justify-content: space-between;
    align-items: center;
  }

  .Modal__content {
    display: flex;
    flex-flow: column nowrap;
    border: solid #cfcfcf;
    border-width: 1px 0 1px 0;
    gap: 1rem;
    padding: 1.25rem;
    overflow-y: auto;

    .Modal__block {
      display: flex;
      flex-flow: column nowrap;
      gap: 0.5rem;
    }
    .Modal__chipGroup {
      .v-chip {
        border-color: rgba(black, 0.4);

        &.v-chip--selected {
          background-color: rgb(var(--v-theme-main-blue));
          border-color: rgb(var(--v-theme-main-blue));

          .v-chip__content {
            color: white;
          }
        }
      }
    }
    .Modal__label {
      font-weight: bold;
      color: rgb(var(--v-theme-main-blue));
    }
  }

  .Modal__footer {
    display: flex;
    flex-flow: row nowrap;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
  }
}
</style>
