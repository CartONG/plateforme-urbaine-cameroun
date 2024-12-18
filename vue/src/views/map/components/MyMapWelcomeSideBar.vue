<template>
  <div class="MyMapWelcomeSideBar">
    <MyMapWelcomeBlock />
  </div>
</template>

<script setup lang="ts">
import MyMapWelcomeBlock from '@/views/map/components/MyMapWelcomeBlock.vue'
</script>

<style lang="scss">
.MyMapWelcomeSideBar {
  display: flex;
  flex-flow: column nowrap;
  width: 21rem;
  flex: 1 0 auto;
  background: #fff;
  padding: 1rem;
}
</style>
