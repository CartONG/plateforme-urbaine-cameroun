<template>
  <v-btn
    class="PrintButton"
    @click="print"
    variant="text"
    prepend-icon="$printer"
    icon-color="main-blue"
    >{{ $t('buttons.print') }}</v-btn
  >
</template>

<script setup lang="ts">
const print = () => window.print()
</script>

<style lang="scss">
.PrintButton {
  font-weight: 400 !important;
}

@media print {
  * {
    print-color-adjust: exact;
  }

  .Header,
  .Breadcrumbs,
  .fixed-btn,
  .PrintButton {
    display: none;
  }
}

@page {
  size: auto;
  margin: 0.375cm;
}
</style>
