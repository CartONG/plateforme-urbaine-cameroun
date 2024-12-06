<template>
  <v-btn class="ListSortBy" color="white">
    <span>{{ $t('placeholders.sortBy') }}</span
    ><v-icon icon="mdi mdi-menu-down" class="ml-2"></v-icon>
    <v-menu activator="parent">
      <v-list>
        <slot></slot>
      </v-list>
    </v-menu>
  </v-btn>
</template>
